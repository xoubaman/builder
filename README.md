# Builder

A simple builder library intended to reduce duplication and headaches in your
tests.

For a detailed explanation on why this library exists, check the section *Why?*.

## Installation

Add it to your project dependencies with composer:

    composer require --dev xoubaman/builder

## Create your builders

Create your custom builder extending `Xoubaman\Builder\Builder`;

Define the class the builder will create in the class constant
`CLASS_TO_BUILD`.

Define a default data setup in the constructors or directly in the `$base`
property.

Add setters at your convenience to modify the data setup for the next instance,
using the protected method `addToCurrent()`.

Example:

```php
//The class we want to build
final class MetalSlugCharacter
{
    /** @var string */
    private $name;
    /** @var Weapon */
    private $weapon;

    public function __construct(string $name, Weapon $weapon)
    {
        $this->name   = $name;
        $this->weapon = $weapon;
    }
}

final class MetalSlugCharacterBuilder extends Builder
{
    //Define here the name of the clase being built
    protected const CLASS_TO_BUILD = MetalSlugCharacter::class;

    public function __construct()
    {
        $this->base = [
            'name'    => 'Marco Rossi',
            'weapon'  => new RocketLauncher(),
        ];
    }

    // Override parent method to add return type hint
    public function build(): MetalSlugCharacter
    {
        return parent::build();
    }

    public function cloneLast(): MetalSlugCharacter
    {
        return parent::cloneLast();
    }

    //Change the current setup with setters like this one
    public function withName(string $name): self
    {
        $this->addToCurrent('name', $name);

        return $this;
    }

    public function withWeapon(Weapon $weapon): self
    {
        $this->addToCurrent('weapon', $weapon);

        return $this;
    }
}

```

## Usage

The parent `Builder` class exposes two public methods by default.

The `build()` method returns an instance with the current data setup. Initial data
setup is the one defined in the `$base` Each time the method is called the data
setup is wiped, so the next instance built will start over from the initial
setup.

The `cloneLast()` method returns a new instance with the same setup as the last
one built.

## Add more swag

Consider overriding `build()` and `cloneLast()` methods calling the parent ones
to take advantage of return type hints.

Consider adding static factory methods to encapsulate setups you end up doing
over and over again. For instance, in the above example we could have:

```php
public static function marcoWithMachineGun(): MetalSlugCharacter
{
    return (new self())->withWeapon(new BigMachineGun())->build();
}
```

## Why?

Using builders in your tests will reduce duplication and save you time when it
comes the time to refactor. For instance, imagine you have these tests:

```php

public function testMarvinIsDepressed(): void
{
    $marvin = new Robot('Marvin');
    self::assertTrue($marvin->hasInfiniteDepressionLevel());
}

public function testMarvinIsNotHappy(): void
{
    $marvin = new Robot('Marvin');
    self::assertFalse($marvin->isHappy());
}
```

Looks good. However, now you need to add a new parameter to `Robot` constructor
and you realise you have to change the class constructor *plus* the two tests
you are instantiating a `Robot`. Some time later you decide to change the type
of the robot name from string to a `RobotName` value object. Again, you will
have to change all the tests instantiating a `Robot`.

If instead you use a builder you will only need to update a single place -the
builder- for each change in the constructor.

Not mentioning the increase in readability and meaning provided by using
something like `$marvin = RobotBuilder::marvin()`;


## License

This library is licensed under MIT License. More details in `LICENSE` file.

