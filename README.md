# Shiblati

_**Sh**it **i** **b**ui**l**t **a** **t**h**i**ng_

---
## Table of Contents

* [Server Requirements](#system-requirements)
  * [Docker Setup](#docker-setup)
* [Application Structure](#application-structure)
* [Application Configuration](\configuration)
  * [Application Dispatch](#application-dispatch)
* [The Service Container](#the-service-container)
  * [Service Providers](#service-providers)
  * [Swapping Services](#swapping-services)
* [Services Provided](#services-provided)
  * [Logging Service](#log)
  * [Database Service](#database)
  * [Route Service](#route)
  * [Session Service](#session)
  * [View Service](#view)
* [Extensions](#extensions)
  * [Asset Extension](#asset-extension)
  * [Dotenv Extension](#dotenv-extension)
  * [Session Extension](#session-extension)
* [Controllers](#controllers)
  * [The Base Controller](#the-base-controller)
  * [Writing Controllers](#the-base-controller)
* [License](#license)

## Server Requirements

- MySQL 8 or MariaDB 10
- PHP 8.1+
- PHP mbstring extension
- PHP bcmath extension
- PHP curl extension
- PHP xml extension
- PHP zip extension

### Docker Setup

1. Clone the [docker](https://github.com/chrisrowles/docker-template) repository.
2. Follow the steps in the repository's [docs](https://github.com/chrisrowles/docker-template/README.md).


## Application Structure
```txt
.
├── config                             # Publishable application config files
│   ├── bootstrap.php                  # Application bootstrapping
│   ├── controllers.php                # Place to register application controllers
│   ├── routes.php                     # Place to register application routes
├── Framework                          # Framework source code
│   ├── Extensions                     # Framework extensions
│   │   └── Twig                       # Twig view extensions
│   ├── Handlers                       # Framework default event handlers
│   ├── Models                         # Framework default models
│   ├── Providers                      # Framework default service providers
│   ├── Validators                     # Framework default model/request validators
│   ├── Container.php                  # Framework service container
│   ├── Controller.php                 # Framework base controller
│   └── ServiceProviderInterface.php   # Framework service provider interface
├── vendor                             # Reserved for Composer (autoloader)
├── composer.json                      # Composer PHP dependencies
```

## Application Configuration

Shiblati is bootstrapped through `config/bootstrap.php`. When you start a shiblati project,
a standard "default" bootstrapping file will be published to your project's `config/`
directory.

This is a typical shiblati project bootstrapping file at published at `config/bootstrap.php`:

```php
<?php

// We start by auto-loading our framework classes 
require_once __DIR__.'/../vendor/autoload.php';

// Then, we load our application envirionment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// You must create a new container to bind your application services
// and make them accessible through dependency injection.
$app = new Shiblati\Framework\Container();

// here you can define your application service providers, you can either
// use the default ones provided, or you can use your own, you can also add
// as many custom services to the container as you like!
$app->register(new Shiblati\Framework\Providers\LogServiceProvider());
$app->register(new Shiblati\Framework\Providers\DatabaseServiceProvider());
$app->register(new Shiblati\Framework\Providers\RouteServiceProvider());
$app->register(new Shiblati\Framework\Providers\SessionServiceProvider());
$app->register(new Shiblati\Framework\Providers\ViewServiceProvider());

// This is a global dependency resolver function provided with the
// default bootstrapping file, using this function, you can retrieve
// any application services that are defined in your container, from
// anywhere across the application, for example, to retrieve the
// logging service: app('log')->debug('..')
function app($dependency = null): mixed
{
    global $app;

    return $app->offsetExists($dependency)
        ? $app->offsetGet($dependency)
        : false;
}

// We could define our controllers and routes entirely in this file,
// but let's define them separately and include them for improved
// readability.
require_once __DIR__.'/../config/controllers.php';
require_once __DIR__.'/../config/routes.php';

// Shiblati also comes with a default execption handler, however,
// you are free to replace it and/or add your own.
new Shiblati\Framework\Handlers\ExceptionHandler($app);

```

### Application Dispatch

Shiblati applications are instantiated through `public/index.php`.

First, it retrieves the bootstrapping file which contains our bootstrapped application,
then, it retrieves our application router from the container and dispatch the listener
for requests.

```php
require_once __DIR__.'/../config/bootstrap.php';

/** @var \Shiblati\Framework\Container $app */
$app['router']->dispatch();
```

## The Service Container

Shiblati uses a [service container](https://github.com/silexphp/Pimple) to manage all of its dependencies. A service
container is simply just an array (or object) that contains other objects and sets default behaviours, it's great for
defining all of our services in one place, and even better for providing access to those services across the whole
application.

If you open your application's `config/bootstrap.php`, you should see the following:

```php
/*----------------------------------------
 | Register service providers             |
 ----------------------------------------*/
$app = new \Shiblati\Framework\Container();

$app->register(new Shiblati\Framework\Providers\LogServiceProvider());
$app->register(new Shiblati\Framework\Providers\DatabaseServiceProvider());
$app->register(new Shiblati\Framework\Providers\RouteServiceProvider());
$app->register(new Shiblati\Framework\Providers\SessionServiceProvider());
$app->register(new Shiblati\Framework\Providers\ViewServiceProvider());
```

### Service Providers
Shiblati's [service providers](https://github.com/silexphp/Pimple#extending-a-container) are responsible for defining
the underlying service implementations bound to the container.

At a minimum, the following is needed to register an application service to the container.
```php
public function register(\Shiblati\Framework\Container $container): Container
{
    $container['foo'] = new Foo();
    return $container;
}
```
In the example above, we define a new instance of our class `Foo`, and then register it to the container. This allows
us to fetch and inject our `Foo` service across the application wherever it's needed.

Sometimes services require more configuration before being registered, this is entirely possible and can be done
within the service provider's `register` method.

For example, shiblati's default view engine is [Twig](#); in the default view service provider, shiblati creates the
new file  system loader, passing the path to the application's views, it then registers environment settings, global
variables  available throughout the templates, and finally, template extensions, before binding it to the container.

```php
public function register(\Shiblati\Framework\Container $container): Container
{
    $loader = new FilesystemLoader($this->viewPath());
    $container['view'] = new Environment($loader, [
        'cache' => getenv('APP_CACHE') ? $this->resolveCachePath() : false,
        'debug' => getenv('APP_DEBUG'),
    ]);
    $container['view']->addGlobal('session', $_SESSION);
    $container['view']->addGlobal('request', $_REQUEST);

    $container['view']->addExtension(new DebugExtension());
    $container['view']->addExtension(new DotenvExtension());
    $container['view']->addExtension(new AssetExtension());
    $container['view']->addExtension(new SessionExtension());
    $container['view']->addExtension(new UrlExtension());

    return $container;
}
```

### Swapping Services

It's entirely possible to swap existing "default" implementations and use custom providers. For example, you may
decide that you don't want to use Twig for frontend views, instead you may want to use
[Mustache](https://github.com/bobthecow/mustache.php) instead. In which case, all you need to do is write your own
view service provider and register in in `config/bootstrap.php`.

**MyApp\Providers\ViewServiceProvider.php:**
```php
<?php

namespace MyApp\Providers;

use Mustache\Engine;
use Shiblati\Framework\Container;
use Shiblati\Framework\ServiceProviderInterface;

class ViewServiceProvider implements ServiceProviderInterface
{
    /**
     * Register mustache view service provider instead.
     *
     * @param Container $container
     * @return Container
     */
    public function register(Container $container): Container
    {
        $container['view'] = Engine([...]);
        // ... custom setup e.g. extensions
        return $container;
    }
}
```

**config/boostrap.php:**
```php
// $app->register(new Shiblati\Framework\Providers\ViewServiceProvider());
$app->register(new MyApp\Providers\ViewServiceProvider());
```

You can even remove service providers entirely, for example, if you don't need a database, or want to use a frontend
framework such as [Vue](#) and build your backend as an API, you can simply remove the relevant service provider
registrations in `config/bootstrap.php`.

```php
// $app->register(new Shiblati\Framework\Providers\DatabaseServiceProvider());
// $app->register(new Shiblati\Framework\Providers\SessionServiceProvider());
// $app->register(new Shiblati\Framework\Providers\ViewServiceProvider());
```

## Services Provided

Shiblati provides the following default services.

### Log

Shiblati uses [Monolog](https://github.com/Seldaek/monolog) for logging.

### Database

Shiblati's default database service is rather straight-forward, it's essentially just a
[PDO](https://www.php.net/manual/en/book.pdo.php) wrapper that provides a simple layer of abstraction over the
communication between our application models, and the database entities they represent.

Please note that it will only work with MySQL or MariaDB, if you want to use a different database, you can write your
own service provider for it to swap out the implementation.

You can view the default implementation [here](https://github.com/chrisrowles/rowles.ch/blob/v2/src/Database.php).

### Route

Shiblati's uses a forked version of [klein](https://github.com/klein/klein.php) for routing. It has been forked and
updated to be compatible with PHP 8.

You can view the [fork](https://github.com/chrisrowles/klein.php) here.

### Session

Shiblati's session service is database-driven, it tracks user sessions by a unique id and session name, which are
never passed to the client. The session object also contains logged in user profile information, such as name and
email address, which is passed to the client.

The service also provides a timeout mechanism that expires the session after 1 hour and cleans up the related
record in the database. You can override this mechanism easily to either shorten or extend the timeout limit,
or persist session records in the database. You can learn how to do this in the [Controllers](#) section.

You can view the default implementation [here](https://github.com/chrisrowles/rowles.ch/blob/v2/src/Models/Session.php).

### View

Shiblati's default view templating engine is [Twig](#). The framework also comes with some handy extensions, which
you can learn more about [here](#).

## Extensions

Shiblati ships with a few extensions that integrate with its default view service provider for Twig. If you don't want
to use Twig, then you don't need to worry about this section.

### Asset Extension

**Shiblati\Framework\Extensions\Twig\AssetExtension**

This is an asset path resolver extension that handles resolving paths to frontend assets. It can be used in Twig
templates like so:

For CSS
```php
<link rel="stylesheet" href="{{ asset({file: 'rowles.bundle.css'}) }}">
```

For Images
```php
<img src="{{ asset({file: 'hero.svg'}) }}"/>
```

For scripts
```php
<script defer src="{{ asset({file: 'rowles.bundle.js'}) }}"></script>
```

Note that in the example above there is no file path, only a file name. Shiblati will automatically resolve different
extensions based on the following convention:

- scripts: `public/js`
- styles: `public/css`
- images `public/images`

If your assets folder is structured differently, you can simply just pass the file path relative to your document
root.

### Dotenv Extension

**Shiblati\Framework\Extensions\Twig\DotEnvExtension**

This extension provides easy access to environment variables in your twig templates.

For example:

```php
<h1>{{ env.app_name }}</h1>
```

### Session Extension

**Shiblati\Framework\Extensions\Twig\DotEnvExtension**

This extension provides easy access to user-accessible session data in your twig templates.

For example:

```php
<h1>Welcome back, {{ session.user.name }}</h1>
```

## Controllers

Shiblati follows the [MVC](https://developer.mozilla.org/en-US/docs/Glossary/MVC) pattern of development.

The application controllers contain the logic that updates our [models](#) in response to input from the users of the
application.

### The Base Controller

The base controller `Shiblati\Framework\Controller.php` contains abstract logic that is inherited by controllers that
extend it.

```php
public function __construct(Shiblati\Framework\Container $container)
{
    $this->log = $container['log'];
    $this->db = $container['db'];
    $this->router = $container['router'];
    $this->view = $container['view'];
    $this->session = $container['session'];

    $this->data['title'] = env("APP_NAME");

    $this->time();
}
```

The base controller retrieves application services from the container and assigns them for use. It then sets the
default view data array's title which is used for changing the document title in views, before finally calling
`time()`, which is a method used for session expiry.

```php
public function time() {
    $session = $this->session->get();
    if ($session && isset($session->id)) {
        $time = time() - strtotime($session->created_at);
        if ($time > Session::TTL) {
            $this->session->destroy();
            $session = null;
        }
    }
}
```

#### Modifying Default Behaviour

As you can see from the constructor snippet above, Shiblati's default constructor registers the following default
services:

- log
- db
- router
- view
- session

In addition to registering the session timeout watcher - `time()`.

You can modify this behaviour by writing your own base controller. Simply extend your custom base controller from
Shiblati's base controller, and write your own constructor, passing in only the services you want or need.

```php
class Controller extends \Shiblati\Framework\Controller
{
    /** @var MyApp\Custom\Foo $foo Inject our custom service */
    protected MyApp\Custom\Foo $foo;
    
    public function __construct(Shiblati\Framework\Container $container)
    {
    
       // We don't want these, so we can remove them.
       // $this->db = $container['db'];
       // $this->session = $container['session'];
    
        $this->log = $container['log'];
        $this->router = $container['router'];
        $this->view = $container['view'];
        
        $this->foo = $container['foo'] // Fetch our custom service implementation from the container!
    
        $this->data['title'] = 'I wanted to change this, and I can!';
    
        // $this->time();
    }
}
```

As you can see from the example above, you can add or remove services that you do or don't need, it's all quite
configurable.

In addition to fetching service implementations for use throughout all controllers, you can fine-tune which services
you need by registering them in extending classes only instead. You can learn more about this in the section [below](#).

Finally, the base controller provides three methods for managing view template rendering, again, all of these methods
can be overridden or disregarded completely to better suit your needs:

**render(string $template)**

This method fetches the view template, automatically resolving the path relative to the root views directory which is
defined in the default view service provider. If your backend is structured as an API, then you don't need to worry
about this method. If you're using a custom view service provider, simply override this method to call the method
defined in your view service implementation. For example:

```php
  protected function render(string $template): mixed
  {
      $template = $this->getTemplate($template);

      return $this->view
        ->display($template, $this->data); // Note our custom service's method is "display"
  }
```

**getTemplate(string $template)**

This method is used by the method above to resolve the view file and return it to `render`. If your backend is
structured  as an API, then you don't need to worry about this method. If you're using a custom view service provider,
simply override  this method to resolve your custom view extensions. For example, if you wanted to use Vue instead:

```php
public function getTemplate(string $template): string
{
    if (!str_contains($template, '.vue')) {
        return $template.'.vue';
    }

    return $template;
}
```

**setViewData(array $data = [])**

This method sets the data to be used in the view for each request. By default, it simply contains the document title.
You can add whatever you like to the view data array and return it for use in your views. For example, if we were
fetching posts for a blog:

```php
  public function home(array $data = []): mixed
  {
      $data['posts'] = $this->blog->getPosts();

      return $this->setViewData($data)
        ->render('blog/home');
  }
```

### Writing Controllers

A standard controller may look like this:

```php
<?php

namespace MyApp\Controllers;

use MyApp\Models\Blog;use Shiblati\Framework\Container;use Shiblati\Framework\Http\Request;use Shiblati\Framework\Response;

class BlogController extends Controller
{
    protected Blog $blog;

    public function __construct(Container $container)
    {
        $this->blog = $container['blog']
        parent::__construct($container);
    }
    
    public function view(int $id, array $data = []): mixed
    {
        $data['post'] = $this->blog->getPost($id);
        $data['title'] = $data['post']['title'];

        return $this->setViewData($data)
            ->render(static::$views[__FUNCTION__]);
    }

    public function submit(Request $request, Response $response): Response
    {
        $this->blog->setAttributes($request->params())
            ->save()
        
        return $response->json($return);
    }
}
```

# License

[MIT License](https://github.com/chrisrowles/shiblati-framework/blob/main/LICENSE).
