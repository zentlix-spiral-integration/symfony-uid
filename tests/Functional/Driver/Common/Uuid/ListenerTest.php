<?php

declare(strict_types=1);

namespace Tests\Functional\Driver\Common\Uuid;

use Cycle\ORM\Heap\Heap;
use Cycle\ORM\Schema;
use Cycle\ORM\SchemaInterface;
use Cycle\ORM\Select;
use SpiralCMS\SymfonyUID\Listener;
use Symfony\Component\Uid;
use Tests\Fixtures\User;
use Tests\Functional\Driver\Common\BaseTest;
use Tests\Traits\TableTrait;

abstract class ListenerTest extends BaseTest
{
    use TableTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $this->makeTable(
            'users',
            [
                'uuid' => 'string',
            ]
        );
    }

    public function testAssignManually(): void
    {
        $this->withListeners(Listener\Uuid4::class);

        $user = new User();
        $user->uuid = Uid\Uuid::v4();
        $bytes = $user->uuid->toBinary();

        $this->save($user);

        $select = new Select($this->orm->with(heap: new Heap()), User::class);
        $data = $select->fetchOne();

        $this->assertSame($bytes, $data->uuid->toBinary());
    }

    public function testUuid1(): void
    {
        $this->withListeners([Listener\Uuid1::class]);

        $user = new User();
        $this->save($user);

        $select = new Select($this->orm->with(heap: new Heap()), User::class);
        $data = $select->fetchOne();

        $this->assertInstanceOf(Uid\UuidV1::class, $data->uuid);
        $this->assertIsString($data->uuid->toRfc4122());
    }

    public function testUuid3(): void
    {
        $this->withListeners([
            Listener\Uuid3::class,
            [
                'namespace' => Uid\UuidV4::v4(),
                'name' => 'https://example.com/foo'
            ]
        ]);

        $user = new User();
        $this->save($user);

        $select = new Select($this->orm->with(heap: new Heap()), User::class);
        $data = $select->fetchOne();

        $this->assertInstanceOf(Uid\UuidV3::class, $data->uuid);
        $this->assertIsString($data->uuid->toRfc4122());
    }

    public function testUuid4(): void
    {
        $this->withListeners(Listener\Uuid4::class);

        $user = new User();
        $this->save($user);

        $select = new Select($this->orm->with(heap: new Heap()), User::class);
        $data = $select->fetchOne();

        $this->assertInstanceOf(Uid\UuidV4::class, $data->uuid);
        $this->assertIsString($data->uuid->toRfc4122());
    }

    public function testUuid5(): void
    {
        $this->withListeners([
            Listener\Uuid5::class,
            ['namespace' => Uid\UuidV4::v4(), 'name' => 'https://example.com/foo']
        ]);

        $user = new User();
        $this->save($user);

        $select = new Select($this->orm->with(heap: new Heap()), User::class);
        $data = $select->fetchOne();

        $this->assertInstanceOf(Uid\UuidV5::class, $data->uuid);
        $this->assertIsString($data->uuid->toRfc4122());
    }

    public function testUuid6(): void
    {
        $this->withListeners([Listener\Uuid6::class]);

        $user = new User();
        $this->save($user);

        $select = new Select($this->orm->with(heap: new Heap()), User::class);
        $data = $select->fetchOne();

        $this->assertInstanceOf(Uid\UuidV6::class, $data->uuid);
        $this->assertIsString($data->uuid->toRfc4122());
    }

    public function withListeners(array|string $listeners): void
    {
        $this->withSchema(new Schema([
            User::class => [
                SchemaInterface::ROLE => 'user',
                SchemaInterface::DATABASE => 'default',
                SchemaInterface::TABLE => 'users',
                SchemaInterface::PRIMARY_KEY => 'uuid',
                SchemaInterface::COLUMNS => ['uuid'],
                SchemaInterface::LISTENERS => [$listeners],
                SchemaInterface::SCHEMA => [],
                SchemaInterface::RELATIONS => [],
                SchemaInterface::TYPECAST => [
                    'uuid' => [Uid\Uuid::class, 'fromString']
                ]
            ]
        ]));
    }
}
