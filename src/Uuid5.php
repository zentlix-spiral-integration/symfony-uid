<?php

declare(strict_types=1);

namespace SpiralCMS\SymfonyUID;

use SpiralCMS\SymfonyUID\Listener\Uuid5 as Listener;
use Doctrine\Common\Annotations\Annotation\NamedArgumentConstructor;
use Doctrine\Common\Annotations\Annotation\Target;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\Uid\Uuid as SymfonyUuid;

/**
 * Uses a version 5 (name-based) UUID based on the SHA-1 hash of a
 * namespace ID and a name
 *
 * @Annotation
 * @NamedArgumentConstructor()
 * @Target({"CLASS"})
 */
#[\Attribute(\Attribute::TARGET_CLASS), NamedArgumentConstructor]
final class Uuid5 extends Uuid
{
    /**
     * @param SymfonyUuid $namespace The namespace (must be a valid UUID, for example, UUID v4)
     * @param non-empty-string $name The name to use for creating a UUID
     * @param non-empty-string $field Uuid property name
     * @param non-empty-string|null $column Uuid column name
     *
     * @see SymfonyUuid::v5()
     */
    public function __construct(
        private SymfonyUuid $namespace,
        private string $name,
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

    #[ArrayShape(['field' => 'string', 'namespace' => SymfonyUuid::class, 'name' => 'string'])]
    protected function getListenerArgs(): array
    {
        return [
            'field' => $this->field,
            'namespace' => $this->namespace,
            'name' => $this->name
        ];
    }
}
