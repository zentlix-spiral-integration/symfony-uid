<?php

declare(strict_types=1);

namespace Tests\Functional\Driver\SQLite\Uuid;

// phpcs:ignore
use Tests\Functional\Driver\Common\Uuid\UuidTest as CommonClass;

/**
 * @group driver
 * @group driver-sqlite
 */
class UuidTest extends CommonClass
{
    public const DRIVER = 'sqlite';
}
