# Builder

A PHP library to simplify the implementation of builders.

The builder pattern is a design pattern where a class know how to build another one. Using builders provides a number of benefits:

* Adds semantics to object instantiation
* Reduces the clutter when instantiating large objects
* Reduces the number of places where a class is instantiated, reducing the number of places to change when refactoring the class constructor.

Here you have a [post](https://dev.to/xoubaman/about-mothers-builders-and-how-to-reduce-duplication-in-your-tests-25gg) outlining these benefits more in detail.

# Installation

Require the dependency in your project using composer:

    composer require --dev xoubaman/builder
    
Tests are the most common place for using builders. If you intend to use the library outside tests, do not include the `--dev` flag.

## Using a builder

Builders define a "base" setup in the property `$base` that will be used as a starting point for each build. This initial setup can be modified to build customized instances.

Once you have a builder prepared it will provide the following public methods:

* `build()` will use the current data setup to do a build. Once called, the setup is wiped out and the next call will use the default values.
* `cloneLast()` will return the same build as the last time `build()` was called. Handy to get multiple builds of the same setup.
* `repeatLastSetup()` will change the current setup to the same as in the last build. Handy to avoid repeating large setups with small variations between them.

## Creating builders with the CLI generator

Since builders boilerplate is a repetitive and non-sexy thing to write, a generator script is provided to automatically create builders for a provided class.

The script requires the fully qualified name of the class as a parameter:

    vendor/bin/builder build "Game\MetalSlugCharacter"

Will create the `Game\MetalSlugCharacterBuilder`.

It is possible to use a custom template for the builders instead of the one provided. For that a converter must be passed as a parameter:

    vendor/bin/builder build "Game\MetalSlugCharacter" "Path\To\My\Converter"

A converter is a class implementing the `Converter` interface. It will be invoked by the script passing `Xoubaman\Builder\Generator\ClassMetadata\ClassMetadata` as parameter.

# Creating builders manually

* Create a builder extending `Xoubaman\Builder\Builder`.
* Declare any of the following constants to suit the builder needs:
    * `CLASS_TO_BUILD`: the FQN of the class to instantiate. Omit or set to empty for the builder to return an array.
    * `USE_CONSTRUCTOR`: the constructor method to call when instantiating the class. Omit or set to empty to use the regular constructor.
    * `AFTER_BUILD_CALL`: a method to call in a new instance right after building it. It is an array where first element is the method name and the others will be used as parameters to the call.
* Define the default values used for the build in the `$base` property as an array with the format `fieldName => value`. A good place for that may be the builder constructor. This property is an array that must have as many elements and in the same order as the constructor being used to instantiate the class.
* Add methods to modify the build setup at your convenience.

## Tips and tricks

The following protected methods are available to create custom methods to modify the build setup:

* `addToCurrent(string $field, $value)` will set the value of `$field` in the current data setup.
* `removeFromCurrent(string $field)` will remove `$field` from the current data setup. Note this will probably cause errors when building class instances, because it will remove a parameter from the constructor call.
* `currentSetup()` will return the whole current data setup, useful to apply more complex operations than adding or removing stuff.
* `replaceCurrentSetup(array $newSetup)` will replace the whole current setup. 

The magic method `__call` is implemented in a way that calling a `withParameter` method will attempt to replace they key `parameter` in the current setup. For instance, `withFooBar('baz')` will set the key `fooBar` in the current setup with the value `'baz'`.

For better typing it is a good idea to override the `build` method in the child builder setting the return type to the class being instantiated.

Any value in `$base` property (or the current setup) that is a callable will be called when building. For instance, with `$base['foo' => function() { return 'bar'; }]`, the value used for parameter foo will be `'bar'`. This is useful to yield different values on each build, like some autoincremental id.

## Example
The following is an example of a builder, quite similar to what the generator will create:

```php
//Class to build
final class MetalSlugCharacter {
    private string $name;
    private Weapon $weapon;
    private int $bombs;

    public function __construct(string $name, Weapon $weapon, int $bombs) {...}
    public static function create(): self {}
    public function loadBombs(int $howMany): void {}
}
```

```php
//The builder
final class MetalSlugCharacterBuilder extends Builder {
    //Class to instantiate, leave empty to return an array
    protected const CLASS_TO_BUILD = MetalSlugCharacter::class;
    //Constructor to use, leave empty to use the default __construct()
    protected const USE_CONSTRUCTOR = 'create';
    //Call this method after instantiation. First element is the method name, the other will be used as parameters to the call 
    protected const AFTER_BUILD_CALL = ['loadBombs', 100];

    public function __construct() {
        //This data setup will be used by default
        //It must hold enough parameters and in the same order as needed by the constructor being used
        $this->base = [
            'name'   => 'Marco Rossi',
            'weapon' => new RocketLauncher(),
            'bombs'  => fn() => rand(10, 50), //Callables will be invoked to get the value
        ];
    }

    //Tip: override build() for type hinting
    public function build(): MetalSlugCharacter {
        return parent::build();
    }

    //We declare methods to modify the build setup 
    public function withName(string $name): self {
        return $this->addToCurrent('name', $name);
    }

    public function withWeapon(Weapon $weapon): self {
        return $this->addToCurrent('weapon', $weapon);
    }
    
    //Be creative with method names to express intention and improve readability
    public function withoutWeapon(): self  {
        return $this->addToCurrent('weapon', new BareHands);
    }
}
```

Now, in our tests:

```php
$builder = new MetalSlugCharacterBuilder();

//A new instance with default values
$character = $builder->build(); //Returns the default Marco + Rocket Launcher

//A new instance with a different setup
$character = $builder
                ->withName('Eri Kasamoto')
                ->withWeapon(new HeavyMachineGun())
                ->build();

//A new instance with the same setup as the last one created
$character = $builder->cloneLast(); //Returns Eri with the Heavy Machine Gun again

$character = $builder->repeatLastSetup() //Repeats the Eri + Heavy Machine Gun setup
    ->withBombs(5) //Methods starting with "with" change the current setup using __call 
    ->build();
```

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

//Encapsulate concrete setups in a static factory methods
public static function fullyLoadedWithMachineGun(): MetalSlugCharacter
{
    return (new self())
                ->withWeapon(new HeavyMachineGun())
                ->withBombs(99)
                ->build();
}

public static function emptyHanded(): MetalSlugCharacter
{
    return (new self())
                ->withWeapon(new BareHands())
                ->withBombs(0)
                ->build();
}


//And start to use meaningful one liners that do not create distraction in the tests
$character = MetalSlugCharacterBuilder::fullyLoadedWithMachineGun();
$character = MetalSlugCharacterBuilder::emptyHanded();
```
If your builder + mother became too cluttered consider separating them, using the builder internally inside the mother.

## License

This library is licensed under MIT License. More details in `LICENSE` file.
