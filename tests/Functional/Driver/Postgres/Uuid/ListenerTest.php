<?php

declare(strict_types=1);

namespace Tests\Functional\Driver\Postgres\Uuid;

// phpcs:ignore
use Tests\Functional\Driver\Common\Uuid\ListenerTest as CommonClass;

/**
 * @group driver
 * @group driver-postgres
 */
class ListenerTest extends CommonClass
{
    public const DRIVER = 'postgres';
}
