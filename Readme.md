# wp-plugin-framework-core
*Foundation for advanced WordPress plugin development by Christoph Ehlers & Kevin Wellmann* 

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

Additional classes like Events, PostTypes, Shortcodes or Widgtes should be put alongside the main class or in subdirectories to group them.

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
      Module1PAnotherPostType.php
      Module1PostType.php
```

## Module main class

At the bare minimum the class is expected to have an init method where most of the functionality is bootstapped.    
To better seperate concerns you may add an admin method which will only be called if `is_admin()` returns `true`.

### Hooks

The following helper methods should be called from the init method:

```
$this->addEvent(new FooImportEvent);

$this->addPostType(new FooPostType);

$this->addShortcode(new FooShortCode);

$this->addWidget(new FooWidget);
```

The following helper methods should be called from the admin method:

```
$this->addHelpTab('edit-post', 'Help', 'admin/post-help-tab.php');

// The current post ID is automatically passed to the template.
$this->addListTableColumn('post', 'Author image', 'admin/post-author-image.php');

$this->addMenuPage(new CustomMenuPage);

class CustomMenuPage extends \CEKW\WpPluginFramework\Core\Admin\AbstractMenuPage
{
  public function __construct()
  {
    $this->pageTitle = 'Custom Page';
    $this->capability = 'manage_options';
  }

  // Send a POST or GET request to the admin-post.php endpoint with the parameter action=myCustom
  public function myCustomAction()
  {

  }

  // Send a POST or GET request via JS to the admin-ajax.php endpoint with the parameter action=myCustom
  public function myCustomAjaxAction()
  {
    
  }

  public function render(): void
    {
        echo $this->renderTemplate(
            $this->templateDirPath . 'admin/custom-page.php',
            []
        );
    }
}
```

To add a simple WordPress hook callback that can't be added through other means use one of the following wrapper methods:

```
$this->addAction('foo', () => foo_function(), 10, 1);

$this->addFilter('foo', () => 'bar', 10, 1);
```

For a complex callback or to group related callbacks use the following helper method which expects a class which implements the `HookSubscriberInterface`.

```
$this->addHookSubscriber(new FooHookSubscriber());

class FooHookSubscriber implements \CEKW\WpPluginFramework\Core\Hook\HookSubscriberInterface
{
    public function getSubscribedHooks(): array
    {
        return [
            'foo' => ['foo'],
            'bar' => ['bar', 10, 2]
        ];
    }

    public function foo(): string
    {
        return 'foo';
    }

    public function bar($bar, $baz): string
    {
        if ($baz !== 'baz') {
          return $bar;
        }

        return 'bar';
    }
}
```

### Activation / deactivation hooks

Each module can also have its own activate and deactivate methods. Dependencies of those methods should be type-hinted and passed as parameters. The specified dependencies will be automatically injected. Usually you would want to inject `\CEKW\WpPluginFramework\Core\Event\Schedule` or the current global instance of the `wpdb` class.

The event class should implement the `EventInterface`. Dependencies of this class should be type-hinted and passed as parameters of the called method and will be automatically injected.

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