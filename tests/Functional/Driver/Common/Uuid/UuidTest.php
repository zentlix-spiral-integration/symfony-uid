<?php

declare(strict_types=1);

namespace Tests\Functional\Driver\Common\Uuid;

use Cycle\Schema\Registry;
use Spiral\Tokenizer\Config\TokenizerConfig;
use Spiral\Tokenizer\Tokenizer;
use Symfony\Component\Uid\Uuid;
use Tests\Fixtures\Post;
use Tests\Fixtures\User;
use Tests\Functional\Driver\Common\BaseTest;

abstract class UuidTest extends BaseTest
{
    protected Registry $registry;

    protected function setUp(): void
    {
        parent::setUp();

        $this->compileWithTokenizer(new Tokenizer(new TokenizerConfig([
            'directories' => [dirname(__DIR__, 4) . '/Fixtures'],
            'exclude' => [],
        ])));
    }

    public function testColumnExist(): void
    {
        $fields = $this->registry->getEntity(User::class)->getFields();

        $this->assertTrue($fields->has('uuid'));
        $this->assertTrue($fields->hasColumn('uuid'));
        $this->assertSame('uuid', $fields->get('uuid')->getType());
        $this->assertSame([Uuid::class, 'fromString'], $fields->get('uuid')->getTypecast());
        $this->assertSame(1, $fields->count());
    }

    public function testAddColumn(): void
    {
        $fields = $this->registry->getEntity(Post::class)->getFields();

        $this->assertTrue($fields->has('customUuid'));
        $this->assertTrue($fields->hasColumn('custom_uuid'));
        $this->assertSame('uuid', $fields->get('customUuid')->getType());
        $this->assertSame([Uuid::class, 'fromString'], $fields->get('customUuid')->getTypecast());
    }
}
