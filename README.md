# Cycle ORM Symfony UID
[![Latest Stable Version](https://poser.pugx.org/spiral-cms/symfony-uid/version)](https://packagist.org/packages/spiral-cms/symfony-uid)
[![Build Status](https://github.com/spiral-cms/symfony-uid/workflows/build/badge.svg)](https://github.com/spiral-cms/symfony-uid/actions)
[![Codecov](https://codecov.io/gh/spiral-cms/symfony-uid/graph/badge.svg)](https://codecov.io/gh/spiral-cms/symfony-uid)

The package provides an ability to use `symfony/uid` as a Cycle ORM entity column type.

## Installation

Install this package as a dependency using Composer.

```bash
composer require spiral-cms/symfony-uid
```

## Example

They are randomly-generated and do not contain any information about the time they are created or the machine that
generated them.

```php
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Symfony\Component\Uid\Uuid;
use SpiralCMS\SymfonyUID\Uuid4;

#[Uuid4]
#[Entity]
class User
{
    #[Column(field: 'uuid', type: 'uuid', primary: true)]
    private Uuid $uuid;
}
```

## License:

The MIT License (MIT). Please see [`LICENSE`](./LICENSE) for more information.

