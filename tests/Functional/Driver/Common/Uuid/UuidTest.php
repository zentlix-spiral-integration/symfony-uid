<?php

declare(strict_types=1);

namespace Tests\Functional\Driver\Common\Uuid;

use Cycle\ORM\Heap\Heap;
use Cycle\ORM\Schema;
use Cycle\ORM\Select;
use Cycle\Schema\Registry;
use Spiral\Tokenizer\Config\TokenizerConfig;
use Spiral\Tokenizer\Tokenizer;
use Symfony\Component\Uid;
use Tests\Fixtures\Post;
use Tests\Fixtures\User;
use Tests\Functional\Driver\Common\BaseTest;
use Tests\Traits\TableTrait;

abstract class UuidTest extends BaseTest
{
    use TableTrait;

    protected Registry $registry;

    protected function setUp(): void
    {
        parent::setUp();

        $this->makeTable(
            'users',
            [
                'uuid' => 'string',
                'uuid3' => 'string',
                'uuid4' => 'string',
                'camel_case_uuid5' => 'string',
                'custom_column' => 'string',
            ]
        );

        $this->withSchema(new Schema($this->compileWithTokenizer(new Tokenizer(new TokenizerConfig([
            'directories' => [dirname(__DIR__, 4) . '/Fixtures'],
            'exclude' => [],
        ])))));
    }

    public function testColumnExist(): void
    {
        $fields = $this->registry->getEntity(User::class)->getFields();

        $this->assertTrue($fields->has('uuid'));
        $this->assertTrue($fields->hasColumn('uuid'));
        $this->assertSame('uuid', $fields->get('uuid')->getType());
        $this->assertSame([Uid\Uuid::class, 'fromString'], $fields->get('uuid')->getTypecast());
        $this->assertSame(5, $fields->count());
    }

    public function testAddColumn(): void
    {
        $fields = $this->registry->getEntity(Post::class)->getFields();

        $this->assertTrue($fields->has('customUuid'));
        $this->assertTrue($fields->hasColumn('custom_uuid'));
        $this->assertSame('uuid', $fields->get('customUuid')->getType());
        $this->assertSame([Uid\Uuid::class, 'fromString'], $fields->get('customUuid')->getTypecast());
    }

    public function testUuid1(): void
    {
        $user = new User();
        $this->save($user);

        $select = new Select($this->orm->with(heap: new Heap()), User::class);
        $data = $select->fetchOne();

        $this->assertInstanceOf(Uid\UuidV1::class, $data->uuid);
        $this->assertIsString($data->uuid->toRfc4122());
    }

    public function testUuid3(): void
    {
        $user = new User();
        $this->save($user);

        $select = new Select($this->orm->with(heap: new Heap()), User::class);
        $data = $select->fetchOne();

        $this->assertInstanceOf(Uid\UuidV3::class, $data->uuid3);
        $this->assertIsString($data->uuid3->toRfc4122());
    }

    public function testUuid4(): void
    {
        $user = new User();
        $this->save($user);

        $select = new Select($this->orm->with(heap: new Heap()), User::class);
        $data = $select->fetchOne();

        $this->assertInstanceOf(Uid\UuidV4::class, $data->uuid4);
        $this->assertIsString($data->uuid4->toRfc4122());
    }

    public function testUuid5(): void
    {
        $user = new User();
        $this->save($user);

        $select = new Select($this->orm->with(heap: new Heap()), User::class);
        $data = $select->fetchOne();

        $this->assertInstanceOf(Uid\UuidV5::class, $data->camelCaseUuid5);
        $this->assertIsString($data->camelCaseUuid5->toRfc4122());
    }

    public function testUuid6(): void
    {
        $user = new User();
        $this->save($user);

        $select = new Select($this->orm->with(heap: new Heap()), User::class);
        $data = $select->fetchOne();

        $this->assertInstanceOf(Uid\UuidV6::class, $data->uuid6);
        $this->assertIsString($data->uuid6->toRfc4122());
    }
}
