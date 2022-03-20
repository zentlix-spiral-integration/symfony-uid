<?php

declare(strict_types=1);

namespace SpiralCMS\SymfonyUID\Listener;

use Cycle\ORM\Entity\Behavior\Attribute\Listen;
use Cycle\ORM\Entity\Behavior\Event\Mapper\Command\OnCreate;
use Symfony\Component\Uid\Uuid;

final class Uuid4
{
    public function __construct(
        private readonly string $field = 'uuid'
    ) {
    }

    #[Listen(OnCreate::class)]
    public function __invoke(OnCreate $event): void
    {
        if (!isset($event->state->getData()[$this->field])) {
            $event->state->register($this->field, Uuid::v4());
        }
    }
}
