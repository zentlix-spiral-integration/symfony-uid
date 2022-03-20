<?php

declare(strict_types=1);

namespace Tests\Functional\Driver\MySQL\Uuid;

// phpcs:ignore
use Tests\Functional\Driver\Common\Uuid\UuidTest as CommonClass;

/**
 * @group driver
 * @group driver-mysql
 */
class UuidTest extends CommonClass
{
    public const DRIVER = 'mysql';
}
