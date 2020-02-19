# Builder

A simple builder library intended to reduce headaches in your tests.

Builders allow to centralize where fixture data and objects are created, reducing duplication and some other fancy benefits.

Imagine we are testing some behaviour of our `Order` class when the deadline delivery date has been surpassed. Instead of fully instantiating the `Order` like:

```php
$order = new Order(
    Uuid::create(),
    [
        new Item('Speck of dust', 1000, 2, 'GOLD'),
        new Item('Higgins bosom', 100, 1, 'GOLD'),
    ],
    new Address('Rincewind', 'Mage Tower', 'Ankh-Morpork', '0000', 'Discworld'),
    new DateTime('yesterday')
);
```

With a builder, you focus only in what is relevant for the current test:

```php
$order = (new OrderBuilder)
    ->withDeliveryDeadline(new DateTime('yesterday'))
    ->build();
```

More readable, explicit, and resilient to changes in the `Order` class. You can read more in detail about the benefits in the *Why you should use builders?* section of this document.

## Installation

Add it to your project dependencies with composer:

    composer require --dev xoubaman/builder

## Creating builders

Builders can be created manually or using a generator that will create them for you.

Create one manually following these steps:
 
* Create a builder extending `Xoubaman\Builder\Builder`.
* Set the name of the class you want to build in the `CLASS_TO_BUILD` constant, or leave it empty for the builder to return arrays instead of objects.
* Define the default values used for the build in the `$base` property as an array with the format `fieldName => value`. A good place for that may be the builder constructor.
* Add methods to modify the next build setup at your convenience.

There are two built-in public methods to build instances:

* `build()` will use the current data setup to do a build. Once called, the setup is wiped out and the next call will use the default values. Note the actual class constructor is called, so any side effect there will be triggered as well.
* `cloneLast()` will return the same build as the last time `build()` was called. Handy to get multiple builds of the same setup.

To modify the data setup there are two non-public methods:

* `addToCurrent(string $field, $value)` will set the value of `$field` in the current data setup.
* `removeFromCurrent(string $field)` will remove `$field` from the current data setup. Note this will probably cause errors when building class instances.
* `currentSetup()` will return the whole current data setup, useful to apply more complex operations than adding or removing stuff.
* `replaceCurrentSetup(array $newSetup)` will replace the whole current setup, handy to set data transformed after a `currentSetup()`. 

The following is an example of a builder, quite similar to what the generator will create:

```php
//Class to build
final class MetalSlugCharacter {
    private string $name;
    private Weapon $weapon;
    private int $bombs;

    public function __construct(string $name, Weapon $weapon, int $bombs) {...}
}

//The builder
final class MetalSlugCharacterBuilder extends Builder {
    //Leave empty to return an array
    protected const CLASS_TO_BUILD = MetalSlugCharacter::class;

    public function __construct() {
        //This data setup will be used by default
        $this->base = [
            'name'   => 'Marco Rossi',
            'weapon' => new RocketLauncher(),
            'bombs'  => 10,
        ];
    }

    //Methods build() and cloneLast() are override for type hinting
    public function build(): MetalSlugCharacter {
        return parent::build();
    }

    public function cloneLast(): MetalSlugCharacter {
        return parent::cloneLast();
    }

    //The fluent withXXX() methods are used to change the next build setup 
    public function withName(string $name): self {
        return $this->addToCurrent('name', $name);
    }

    public function withWeapon(Weapon $weapon): self {
        return $this->addToCurrent('weapon', $weapon);
    }
}
```

Now, in our tests:

```php
$builder = new MetalSlugCharacterBuilder();

//A new instance with default values
$character = $builder->build(); //Returns Marco with a Rocket Launcher

//A new instance with a different setup
$character = $builder
                ->withName('Eri Kasamoto')
                ->withWeapon(new HeavyMachineGun())
                ->build();

//A new instance with the same setup as the last one created
$character = $builder->cloneLast(); //Returns Eri with the Heavy Machine Gun again
```

## Generating builders

Since builders boilerplate is a repetitive and non-sexy thing to write, a generator script is provided to automatically create builders for a provided class.

The script requires the fully qualified name of the class as a parameter:

    vendor/bin/builder build "Game\MetalSlugCharacter"

Will create the `Game\MetalSlugCharacterBuilder`.

It is possible to use a custom template for the builders instead of the one provided. For that a converter must be passed as a parameter:

    vendor/bin/builder build "Game\MetalSlugCharacter" "Path\To\My\Converter"

A converter is a class implementing the `Converter` interface. It will be invoked by the script passing `Xoubaman\Builder\Generator\ClassMetadata\ClassMetadata` as parameter.

## Adding more swag

For builder setups used over and over again, encapsulate that setup in a static
factory method to remove duplication and improve readability. This is a design
pattern called [Object Mother](https://www.martinfowler.com/bliki/ObjectMother.html):

```php
//Instead of doing all the time
$character = $builder
                ->withWeapon(new HeavyMachineGun())
                ->withBombs(99)
                ->build();

//Encapuslate the setup in a static factory method
public static function fullyLoadedWithMachineGun(): MetalSlugCharacter
{
    return (new self())
                ->withWeapon(new HeavyMachineGun())
                ->withBombs(99)
                ->build();
}

//And use the more readable and meaninguful one liner
$character = MetalSlugCharacterBuilder::fullyLoadedWithMachineGun();
```

## Why you should use builders?

Using builders in your tests will reduce duplication and save you time when it comes the time to refactor. For instance, imagine you have these tests:

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

Looks good. However, now you need to add a new parameter to `Robot` constructor and you realise you have to change the class constructor *plus* the two tests you are instantiating a `Robot`.

If instead you use a builder you will only need to update a single place -the builder- for each change in the constructor.

For a more detailed explanation about how builders rock, check this [post](https://dev.to/xoubaman/about-mothers-builders-and-how-to-reduce-duplication-in-your-tests-25gg).

## License

This library is licensed under MIT License. More details in `LICENSE` file.
