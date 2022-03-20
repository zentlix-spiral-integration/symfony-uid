<?php

declare(strict_types=1);

namespace Tests\Functional\Driver\SQLServer\Uuid;

// phpcs:ignore
use Tests\Functional\Driver\Common\Uuid\ListenerTest as CommonClass;

/**
 * @group driver
 * @group driver-sqlserver
 */
class ListenerTest extends CommonClass
{
    public const DRIVER = 'sqlserver';
}
