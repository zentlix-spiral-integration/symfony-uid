<?php

declare(strict_types=1);

namespace Tests\Functional\Driver\Common;

use Cycle\Annotated\Entities;
use Cycle\Annotated\MergeColumns;
use Cycle\Annotated\MergeIndexes;
use Cycle\Database\Config\DatabaseConfig;
use Cycle\Database\Database;
use Cycle\Database\DatabaseInterface;
use Cycle\Database\DatabaseManager;
use Cycle\Database\Driver\DriverInterface;
use Cycle\Database\Driver\Handler;
use Cycle\ORM\Collection\ArrayCollectionFactory;
use Cycle\ORM\Config\RelationConfig;
use Cycle\ORM\Entity\Behavior\EventDrivenCommandGenerator;
use Cycle\ORM\EntityManager;
use Cycle\ORM\Factory;
use Cycle\ORM\SchemaInterface;
use Cycle\ORM\ORM;
use Cycle\Schema\Compiler;
use Cycle\Schema\Generator;
use Cycle\Schema\Registry;
use PHPUnit\Framework\TestCase;
use Spiral\Attributes\AttributeReader;
use Spiral\Tokenizer\Tokenizer;
use Tests\Utils\SimpleContainer;

abstract class BaseTest extends TestCase
{
    public const DRIVER = null;

    public static array $config;
    protected ?DatabaseManager $dbal = null;
    protected ?ORM $orm = null;
    private static array $driverCache = [];

    protected function setUp(): void
    {
        $this->dbal = new DatabaseManager(new DatabaseConfig());
        $this->dbal->addDatabase(
            new Database(
                'default',
                '',
                $this->getDriver()
            )
        );
    }

    protected function tearDown(): void
    {
        $this->dropDatabase($this->dbal->database('default'));

        $this->orm = null;
        $this->dbal = null;

        if (\function_exists('gc_collect_cycles')) {
            \gc_collect_cycles();
        }
    }

    public function getDriver(): DriverInterface
    {
        if (isset(static::$driverCache[static::DRIVER])) {
            return static::$driverCache[static::DRIVER];
        }

        $config = self::$config[static::DRIVER];
        if (!isset($this->driver)) {
            $this->driver = $config->driver::create($config);
        }

        return static::$driverCache[static::DRIVER] = $this->driver;
    }

    protected function dropDatabase(DatabaseInterface $database = null): void
    {
        if ($database === null) {
            return;
        }

        foreach ($database->getTables() as $table) {
            $schema = $table->getSchema();

            foreach ($schema->getForeignKeys() as $foreign) {
                $schema->dropForeignKey($foreign->getColumns());
            }

            $schema->save(Handler::DROP_FOREIGN_KEYS);
        }

        foreach ($database->getTables() as $table) {
            $schema = $table->getSchema();
            $schema->declareDropped();
            $schema->save();
        }
    }

    protected function getDatabase(): Database
    {
        return $this->dbal->database('default');
    }

    public function withSchema(SchemaInterface $schema): ORM
    {
        $this->orm = new ORM(
            new Factory(
                $this->dbal,
                RelationConfig::getDefault(),
                null,
                new ArrayCollectionFactory()
            ),
            $schema,
            new EventDrivenCommandGenerator($schema, new SimpleContainer())
        );

        return $this->orm;
    }

    public function compileWithTokenizer(Tokenizer $tokenizer): void
    {
        $reader = new AttributeReader();

        (new Compiler())->compile($this->registry = new Registry($this->dbal), [
            new Entities($tokenizer->classLocator(), $reader),
            new Generator\ResetTables(),
            new MergeColumns($reader),
            new MergeIndexes($reader),
            new Generator\GenerateRelations(),
            new Generator\GenerateModifiers(),
            new Generator\ValidateEntities(),
            new Generator\RenderTables(),
            new Generator\RenderRelations(),
            new Generator\RenderModifiers(),
            new Generator\GenerateTypecast(),
        ]);
    }

    protected function save(object ...$entities): void
    {
        $tr = new EntityManager($this->orm);
        foreach ($entities as $entity) {
            $tr->persist($entity);
        }
        $tr->run();
    }
}
