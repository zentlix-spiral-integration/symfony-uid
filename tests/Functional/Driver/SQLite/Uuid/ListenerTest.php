<?php

declare(strict_types=1);

namespace Tests\Functional\Driver\SQLite\Uuid;

// phpcs:ignore
use Tests\Functional\Driver\Common\Uuid\ListenerTest as CommonClass;

/**
 * @group driver
 * @group driver-sqlite
 */
class ListenerTest extends CommonClass
{
    public const DRIVER = 'sqlite';
}
