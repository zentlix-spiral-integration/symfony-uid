<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use SpiralCMS\SymfonyUID\Uuid1;
use Symfony\Component\Uid\Uuid;

#[Entity]
#[Uuid1]
class User
{
    #[Column(type: 'uuid', primary: true)]
    public Uuid $uuid;
}
