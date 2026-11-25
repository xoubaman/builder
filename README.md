# Builder

[![CI](https://github.com/xoubaman/builder/actions/workflows/ci.yml/badge.svg)](https://github.com/xoubaman/builder/actions/workflows/ci.yml)
[![Latest version](https://img.shields.io/packagist/v/xoubaman/builder)](https://packagist.org/packages/xoubaman/builder)
[![PHP version](https://img.shields.io/packagist/dependency-v/xoubaman/builder/php)](https://packagist.org/packages/xoubaman/builder)
[![License](https://img.shields.io/packagist/l/xoubaman/builder)](LICENSE)

A small PHP library to write reusable builders with very little boilerplate, plus a CLI to generate them.

Builders are classes that know how to create other objects. They are most useful in tests, where they:

* Give meaning to object creation: `$order = $builder->withExpiredDeadline()->build()`.
* Hide the clutter of instantiating large objects, so tests show only the data that matters.
* Keep constructor calls in a single place, so changing a constructor does not mean touching dozens of tests.

There is a [post](https://dev.to/xoubaman/about-mothers-builders-and-how-to-reduce-duplication-in-your-tests-25gg) explaining these benefits in more detail.

## Installation

```bash
composer require --dev xoubaman/builder
```

Tests are the most common place for builders. If you use the library outside tests, drop the `--dev` flag.

### Supported versions

| Version | PHP      | Status                                       |
|---------|----------|----------------------------------------------|
| 3.x     | 8.3–8.5  | Maintained                                   |
| 2.x     | ≥ 7.2    | Unmaintained, no bug fixes or security fixes |
| 1.x     | ≥ 7.2    | Unmaintained, no bug fixes or security fixes |

Upgrading from 2.x? See the [changelog](CHANGELOG.md#300) for the breaking changes.

## Quick example

Given a class:

```php
final class MetalSlugCharacter
{
    public function __construct(
        private string $name,
        private Weapon $weapon,
        private int $bombs,
    ) {}
}
```

A builder declares which class it builds and the default values for its constructor:

```php
use Xoubaman\Builder\Builder;

final class MetalSlugCharacterBuilder extends Builder
{
    protected const CLASS_TO_BUILD = MetalSlugCharacter::class;

    public function __construct()
    {
        // Default setup: same number and order as the constructor parameters
        $this->base = [
            'name'   => 'Marco Rossi',
            'weapon' => new RocketLauncher(),
            'bombs'  => 10,
        ];
    }

    // Override build() and cloneLast() only to narrow the return type
    public function build(): MetalSlugCharacter
    {
        return parent::build();
    }

    public function cloneLast(): MetalSlugCharacter
    {
        return parent::cloneLast();
    }

    public function withName(string $name): self
    {
        return $this->addToCurrent('name', $name);
    }

    public function withWeapon(Weapon $weapon): self
    {
        return $this->addToCurrent('weapon', $weapon);
    }

    // Name methods after what they mean in your domain
    public function withoutWeapon(): self
    {
        return $this->addToCurrent('weapon', new BareHands());
    }
}
```

And in your tests:

```php
$builder = new MetalSlugCharacterBuilder();

$marco = $builder->build();                     // Marco with the rocket launcher

$eri = $builder
    ->withName('Eri Kasamoto')
    ->withWeapon(new HeavyMachineGun())
    ->build();                                  // Eri with the heavy machine gun

$anotherEri = $builder->cloneLast();            // Same setup as the last build

$eriWithFewBombs = $builder
    ->repeatLastSetup()                         // Start again from Eri's setup...
    ->withBombs(5)                              // ...and change one field (see "Magic setters")
    ->build();
```

## Generating builders with the CLI

Builder boilerplate is repetitive, so the package ships a generator. Pass it the fully qualified class name:

```bash
vendor/bin/builder build "Game\MetalSlugCharacter"
```

It creates `MetalSlugCharacterBuilder.php` next to the original class, with a constant per field, a `$base` setup with placeholder values, typed `build()` and `cloneLast()` overrides and a `with*` method per constructor parameter. The command fails if the builder file already exists.

To use your own template, pass a converter as the second argument:

```bash
vendor/bin/builder build "Game\MetalSlugCharacter" "App\Testing\MyConverter"
```

A converter implements `Xoubaman\Builder\Generator\Converter`. It receives a `Xoubaman\Builder\Generator\ClassMetadata\ClassMetadata` and returns the builder source code as a string.

## Writing builders by hand

Extend `Xoubaman\Builder\Builder` and configure it with these constants, all optional:

| Constant           | Purpose                                                                                                               | Default                              |
|--------------------|-----------------------------------------------------------------------------------------------------------------------|--------------------------------------|
| `CLASS_TO_BUILD`   | FQN of the class to instantiate.                                                                                      | `''`: `build()` returns an array     |
| `USE_CONSTRUCTOR`  | Name of a static factory method to call instead of `new`.                                                             | `''`: uses `__construct()`           |
| `AFTER_BUILD_CALL` | Method to call on the new instance right after building it. First element is the method name, the rest its arguments. | `[]`: nothing is called              |

Then set the default values in `$base`, usually in the constructor, as `fieldName => value`. **Values are passed positionally**, so `$base` must have as many elements as the constructor (or factory method) needs, in the same order. Keys are only names you use to change the setup.

```php
final class MetalSlugCharacterBuilder extends Builder
{
    protected const CLASS_TO_BUILD   = MetalSlugCharacter::class;
    protected const USE_CONSTRUCTOR  = 'create';              // MetalSlugCharacter::create(...)
    protected const AFTER_BUILD_CALL = ['loadBombs', 100];    // $character->loadBombs(100)

    // ...
}
```

### Public API

* `build()` builds using the current setup. Afterwards the setup goes back to `$base`, so every build starts clean.
* `cloneLast()` builds again with the setup of the last `build()` call.
* `repeatLastSetup()` makes the setup of the last `build()` the current one, so you can tweak it before building again.

### Protected helpers for custom methods

* `addToCurrent(string $field, mixed $value)` sets one field in the current setup.
* `removeFromCurrent(string $field)` removes a field. This is mostly useful for array builders: for objects it removes a constructor argument.
* `currentSetup()` returns the whole current setup, for changes more complex than setting a field.
* `replaceCurrentSetup(array $setup)` replaces the whole current setup.

All of them except `currentSetup()` return the builder, so they can end a fluent method with `return $this->addToCurrent(...)`.

### Magic setters

Calling any undefined `withSomething($value)` method sets the `something` field of the current setup. It throws an `OutOfBoundsException` if the field does not exist. Add `@method` annotations to keep autocompletion and static analysis happy:

```php
/**
 * @method self withBombs(int $bombs)
 */
final class MetalSlugCharacterBuilder extends Builder
```

### Lazy and generated values

Any value in the setup that is a callable gets called at build time, and its result is used instead. This is handy for values that should differ on each build, like ids:

```php
$this->base = [
    'id'    => static fn(): string => Uuid::v4()->toRaw(),
    'name'  => 'Marco Rossi',
];
```

> [!NOTE]
> This uses `is_callable()`, so strings that happen to be function names (like `'date'` or `'max'`) and `[object, 'method']` arrays are called as well. To pass such a value literally, wrap it: `static fn() => 'date'`.

### Array builders

Leave `CLASS_TO_BUILD` empty and `build()` returns the setup as an array, keys included. This is useful for request payloads, rows or configuration:

```php
final class OrderPayloadBuilder extends Builder
{
    public function __construct()
    {
        $this->base = ['id' => 1, 'items' => [], 'coupon' => null];
    }
}
```

## Builders and Object Mothers

For setups you use again and again, wrap them in named static factories. This is the [Object Mother](https://martinfowler.com/bliki/ObjectMother.html) pattern, and builders make it cheap:

```php
final class MetalSlugCharacterBuilder extends Builder
{
    // ...

    public static function fullyLoaded(): MetalSlugCharacter
    {
        return (new self())
            ->withWeapon(new HeavyMachineGun())
            ->withBombs(99)
            ->build();
    }

    public static function emptyHanded(): MetalSlugCharacter
    {
        return (new self())
            ->withoutWeapon()
            ->withBombs(0)
            ->build();
    }
}

// Tests now read like the scenario they describe
$character = MetalSlugCharacterBuilder::fullyLoaded();
```

If a builder plus mother grows too large, split them into two classes, with the mother using the builder internally.

## Contributing

Issues and pull requests are welcome. Run the whole test and quality suite with:

```bash
composer all-checks
```

It runs the [Mago](https://mago.carthage.software/) linter and formatter check, then PHPUnit. `composer fix-style` formats the code.

## License

MIT. See [LICENSE](LICENSE).
