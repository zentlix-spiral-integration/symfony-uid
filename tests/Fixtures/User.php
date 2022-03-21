<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use SpiralCMS\SymfonyUID;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

#[
    Entity,
    SymfonyUID\Uuid1,
    SymfonyUID\Uuid3(namespace: new UuidV4(), name: 'test', field: 'uuid3'),
    SymfonyUID\Uuid4(field: 'uuid4'),
    SymfonyUID\Uuid5(namespace: new UuidV4(), name: 'test', field: 'camelCaseUuid5'),
    SymfonyUID\Uuid6(field: 'uuid6', column: 'custom_column')
]
class User
{
    #[Column(type: 'uuid', primary: true)]
    public Uuid $uuid;

    public Uuid $uuid3;
    public Uuid $uuid4;
    public Uuid $camelCaseUuid5;
    public Uuid $uuid6;
}
