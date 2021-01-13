# wp-plugin-framework-core
*Foundation for advanced WordPress plugin development by Christoph Ehlers & Kevin Wellmann* 
## Developer notes

### Install dependencies
Using composer to install plugin dependencies:
`composer install`

### Test and code-coverage
There are several scripts to helps You testing Your code or check code-style:

|Scriptname|Example|Description|
|---|---|---|
|build:cc               | composer run build:cc                 |Generates the coverage files|
|build:cs&#x2011;diff   | composer run build:cs&#x2011;diff     |Generates a file that contains difference between Your and a fixed version of code|
|check:cs               | composer run check:cs                 |Check code-style|
|fix:cs                 | composer run fix:cs                   |Fix code-style|
|patch:cs&#x2011;diff   | composer run patch:cs&#x2011;diff     |Using generated .diff-file to fix code-style|
|start&#x2011;server:cc | composer run start&#x2011;server:cc   |Run local webserver to display generated coverage files|
|test                   | composer run test                     |Run unit-tests|

## Setup

Create a new WordPress plugin with this package as its dependency. Make sure to include the composer autoloader and the follwing lines to your main plugin file to load the framework:

```
$frameworkLoader = new \CEKW\WpPluginFramework\Core\Loader(__FILE__);
$frameworkLoader
    ->loadModules('src/', __NAMESPACE__)
    ->init();
```

### Optional methods

#### loadPackage(PackageInterface $package)

Include additonal classes to extend the framework.
Optional Package included in the core library:  

* \CEKW\WpPluginFramework\Core\RestRouting\RestRoutingPackage

External Packages:

* https://github.com/Ce-Kw/wp-plugin-framework-assets
* https://github.com/Ce-Kw/wp-plugin-framework-routing

Packages can be loaded by passing an instance of the package class to the loadPackage method.

## Plugin structure

By default the framework expects the different modules to be found inside the src directory in its own directory named after its main class.

```
src/
  Module1/
    Module1.php
  Module2/
    Module2.php
```

Additional classes like Events, PostTypes, Shortcodes or Widgtes should be put alongside the main class or in subdirectories to better group them.

```
src/
  Module1/
    Module1.php
    Module1PostType.php
```

```
src/
  Module1/
    Module1.php
    PostType/
      Module1PAnotherostType.php
      Module1PostType.php
```

## Module main class

At the bare minimum the class is expected to have an init method where most of the functionality is bootstapped.    
To better seperate concerns you may add an admin method which will only be called if `is_admin()` returns `true`.

The following methods are available and should be called from the init method:

* addPostType
* addShortcode
* addWidget

The following methods are available and should be called from the admin method:

* addListTableColumn
* addHelpTab
* addMenuItem

To add a WordPress hook that can't be added through other means use the `addHook` method which expects a class with an implementation of the `HookListenerInterface`. The class should be named after the hook in PascalCase with the suffix `Listener`.


Each module can also have its own activate and deactivate methods. Dependencies of those methods should be type-hinted and passed as parameters. The specified dependencies will be automatically injected. Usually you would want to inject `\CEKW\WpPluginFramework\Core\Event\Schedule` or the current global instance of the `wpdb` class.

## Events

The event class should implement the `EventInterface`. Dependencies of this class should be type-hinted and passed as parameters of the called method and will be automatically injected.