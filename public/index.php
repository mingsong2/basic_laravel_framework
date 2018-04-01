<?php
date_default_timezone_set("PRC");
use Illuminate\Database\Capsule\Manager;
use Illuminate\Support\Fluent;

// 调用自动加载文件，添加自动加载文件函数
require __DIR__.'/../vendor/autoload.php';

// 实例化服务容器，注册事件，路由服务提供者
$app = new Illuminate\Container\Container;
Illuminate\Container\Container::setInstance($app);   // 调用服务容器的setInstance()方法，这样就可以在任何位置获取服务容器的实例，例如在controller 里面 user一下，然后直接$app = Container::getInstance获取服务容器实例

// 这里的with在 illuminate/support/helpers.php中定义，返回一个对象
// 下面两句话等于
// $eventProvider = new Illuminate\Events\EventServiceProvider($app);
// $eventProvider->register();
// $routingProvider = new Illuminate\Routing\RoutingServiceProvider($app);
// $routingProvider->register();
with(new Illuminate\Events\EventServiceProvider($app))->register();
with(new Illuminate\Routing\RoutingServiceProvider($app))->register();

//启动 Eloquent ORM模块并进行相关配置
$manager = new Manager();
$manager->addConnection(require '../config/database.php');
$manager->bootEloquent();

$app->instance('config',new Fluent);
$app['config']['view.compiled'] = "/Volumes/work_1/learn/laravel_book/storage/framework/views";
$app['config']['view.paths'] = ['/Volumes/work_1/learn/laravel_book/resources/views'];
with(new Illuminate\View\ViewServiceProvider($app))->register();
with(new Illuminate\Filesystem\FilesystemServiceProvider($app))->register();

// 加载路由
require __DIR__.'/../app/Http/routes.php';

// 实例化请求并分发处理请求
$request = Illuminate\Http\Request::createFromGlobals();
$reponse = $app['router']->dispatch($request);

// 返回请求相应
$reponse->send();