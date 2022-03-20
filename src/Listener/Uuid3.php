<?php

declare(strict_types=1);

namespace SpiralCMS\SymfonyUID\Listener;

use Cycle\ORM\Entity\Behavior\Attribute\Listen;
use Cycle\ORM\Entity\Behavior\Event\Mapper\Command\OnCreate;
use Symfony\Component\Uid\Uuid;

final class Uuid3
{
    public function __construct(
        private readonly Uuid $namespace,
        private readonly string $name,
        private readonly string $field = 'uuid'
    ) {
    }

    #[Listen(OnCreate::class)]
    public function __invoke(OnCreate $event): void
    {
        if (!isset($event->state->getData()[$this->field])) {
            $event->state->register($this->field, Uuid::v3($this->namespace, $this->name));
        }
    }
}
