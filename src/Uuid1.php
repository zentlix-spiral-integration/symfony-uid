<?php

declare(strict_types=1);

namespace SpiralCMS\SymfonyUID;

use Symfony\Component\Uid\Uuid as Listener;
use Doctrine\Common\Annotations\Annotation\NamedArgumentConstructor;
use Doctrine\Common\Annotations\Annotation\Target;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Uses a version 1 (time-based) UUID
 *
 * @Annotation
 * @NamedArgumentConstructor()
 * @Target({"CLASS"})
 */
#[\Attribute(\Attribute::TARGET_CLASS), NamedArgumentConstructor]
final class Uuid1 extends Uuid
{
    /**
     * @param non-empty-string $field Uuid property name
     * @param non-empty-string|null $column Uuid column name
     *
     * @see \Symfony\Component\Uid\Uuid::v1()
     */
    public function __construct(
        string $field = 'uuid',
        ?string $column = null
    ) {
        $this->field = $field;
        $this->column = $column;
    }

    protected function getListenerClass(): string
    {
        return Listener::class;
    }

    #[ArrayShape(['field' => 'string'])]
    protected function getListenerArgs(): array
    {
        return [
            'field' => $this->field
        ];
    }
}
