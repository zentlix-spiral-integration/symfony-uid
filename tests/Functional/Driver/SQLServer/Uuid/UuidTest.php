<?php

declare(strict_types=1);

namespace Tests\Functional\Driver\SQLServer\Uuid;

// phpcs:ignore
use Tests\Functional\Driver\Common\Uuid\UuidTest as CommonClass;

/**
 * @group driver
 * @group driver-sqlserver
 */
class UuidTest extends CommonClass
{
    public const DRIVER = 'sqlserver';
}
