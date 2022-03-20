<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use SpiralCMS\SymfonyUID\Uuid4;

#[Entity]
#[Uuid4(field: 'customUuid', column: 'custom_uuid')]
class Post
{
    #[Column(type: 'primary')]
    public int $id;
}
