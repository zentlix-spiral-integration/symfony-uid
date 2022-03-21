<?php

declare(strict_types=1);

namespace SpiralCMS\SymfonyUID;

use Cycle\ORM\Entity\Behavior\Schema\BaseModifier;
use Cycle\ORM\Entity\Behavior\Schema\RegistryModifier;
use Cycle\Schema\Registry;
use Doctrine\Inflector\InflectorFactory;
use Symfony\Component\Uid\Uuid as SymfonyUuid;

abstract class Uuid extends BaseModifier
{
    private const TYPECAST_METHOD = 'fromString';

    protected ?string $column = null;
    protected string $field;

    public function compute(Registry $registry): void
    {
        $modifier = new RegistryModifier($registry, $this->role);
        $this->column = $modifier->findColumnName($this->field, $this->column);

        if ($this->column !== null) {
            $modifier->addUuidColumn($this->column, $this->field);
            $modifier->setTypecast(
                $registry->getEntity($this->role)->getFields()->get($this->field),
                [SymfonyUuid::class, self::TYPECAST_METHOD]
            );
        }
    }

    public function render(Registry $registry): void
    {
        $inflector = InflectorFactory::create()->build();

        $modifier = new RegistryModifier($registry, $this->role);
        $this->column = $modifier->findColumnName($this->field, $this->column) ?? $inflector->tableize($this->field);

        $modifier->addUuidColumn($this->column, $this->field);
        $modifier->setTypecast(
            $registry->getEntity($this->role)->getFields()->get($this->field),
            [SymfonyUuid::class, self::TYPECAST_METHOD]
        );
    }
}
