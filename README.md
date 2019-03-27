# Builder

A simple builder library intended to reduce duplication and headaches in your
tests.

## Installation

## Create your builders

Create your custom builder extending `Xoubaman\Builder\Builder`;

Define the class the builder will create in the class constant
`CLASS_TO_BUILD`.

Define a default data setup in the constructors or directly in the `$base`
property.

Add setters at your convenience to modify the data setup for the next instance,
using the protected method `addToCurrent()`.

Consider overriding `build()` and `cloneLast()` methods calling the parent ones
to take advantage of return type hints.

Consider adding static factory methods to encapsulate setups you end up doing
over and over again. This will end up

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


