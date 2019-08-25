# Builder

A simple builder library intended to reduce duplication and headaches in your
tests.

For a detailed explanation on why this library exists, check the section *Why?*.

## Installation

Add it to your project dependencies with composer:

    composer require --dev xoubaman/builder

## Usage

Generate a builder for some class with the `builder` bin in vendor folder,
providing a fully qualified name of a the class:

    vendor/bin/builder build "Game\MetalSlugCharacter"

Now we have the `Game\MetalSlugCharacterBuilder`.

If our class is:

```php
final class MetalSlugCharacter
{
    /** @var string */
    private $name;
    /** @var Weapon */
    private $weapon;
    /** @var int */
    private $bombs;

    public function __construct(string $name, Weapon $weapon, int $bombs)
    {
        $this->name   = $name;
        $this->weapon = $weapon;
        $this->bombs  = $bombs;
    }
}
```

With the builder we can do:

```php
    $builder = new MetalSlugCharacterBuilder();
    
    //A new instance with default values
    $character = $builder->build();
    
    //A new instance with a different setup
    $character = $builder
                    ->withName('Eri Kasamoto')
                    ->withWeapon(new BigMachineGun())
                    ->build();
    
    //A new instance with the same setup as the last one created
    $character = $builder->cloneLast();
```

Each time you call the build() method the current setup will be wiped out
and calling build() again will return an instance with default values.

The `builder` bin will generate a default value for each class constructor
parameter using reflection. It is not specially smart so it is very likely the
generated builder will need some tweaking.

The generated builder will look like the following:

```php
final class MetalSlugCharacterBuilder extends Builder
{
    protected const CLASS_TO_BUILD = MetalSlugCharacter::class;

    public function __construct()
    {
        //Here the command generates default values and probably you will have to change them
        $this->base = [
            'name'   => 'Marco Rossi',
            'weapon' => new RocketLauncher(),
            'bombs'  => 10,
        ];
    }

    //Methods build() and cloneLast() are override for type hinting
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
        $this->addToCurrent('name', $name);

        return $this;
    }

    public function withWeapon(Weapon $weapon): self
    {
        $this->addToCurrent('weapon', $weapon);

        return $this;
    }

    public function withBombs(int $bombs): self
    {
        $this->addToCurrent('bombs', $bombs);

        return $this;
    }
}
```

## Adding more swag

For builder setups used over and over again, encapsulate that setup in a static
factory method to remove duplication and improve readability. This is a design
pattern called [Object Mother](https://www.martinfowler.com/bliki/ObjectMother.html):

```php
//Instead of doing all the time
$character = $builder
                ->withWeapon(new BigMachineGun())
                ->withBombs(99)
                ->build();

//Encapuslate the setup in a static factory method
public static function fullyLoadedWithMachineGun(): MetalSlugCharacter
{
    return (new self())
                ->withWeapon(new BigMachineGun())
                ->withBombs(99)
                ->build();
}

//And use the more readable and meaninguful one liner
$character = MetalSlugCharacterBuilder::fullyLoadedWithMachineGun();
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

## License

This library is licensed under MIT License. More details in `LICENSE` file.
