<?php

declare(strict_types=1);

namespace SpiralCMS\SymfonyUID;

use SpiralCMS\SymfonyUID\Listener\Uuid6 as Listener;
use Doctrine\Common\Annotations\Annotation\NamedArgumentConstructor;
use Doctrine\Common\Annotations\Annotation\Target;
use JetBrains\PhpStorm\ArrayShape;

/**
 * UUID type 6 is not part of the UUID standard. It's lexicographically sortable
 * (like ULIDs) and contains a 60-bit timestamp and 63 extra unique bits.
 *
 * @Annotation
 * @NamedArgumentConstructor()
 * @Target({"CLASS"})
 */
#[\Attribute(\Attribute::TARGET_CLASS), NamedArgumentConstructor]
final class Uuid6 extends Uuid
{
    /**
     * @param non-empty-string $field Uuid property name
     * @param non-empty-string|null $column Uuid column name
     *
     * @see \Symfony\Component\Uid\Uuid::v6()
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
