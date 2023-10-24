<?php
use App\Controllers\TaskController;
use Slim\Factory\AppFactory;
use DI\Container;
use Slim\Exception\HttpNotFoundException;
use Slim\Psr7\Response;
use Slim\Psr7\Request;
use App\Models\Task;
use Slim\Routing\RouteCollectorProxy;
use Tuupola\Middleware\CorsMiddleware;
use Slim\Middleware\ErrorMiddleware;
use Slim\Middleware\BodyParsingMiddleware;

require __DIR__ . '/../vendor/autoload.php';

$container = new Container();
AppFactory::setContainer($container);

$app = AppFactory::create();
    $container->set('user', 'username'); // Substitua 'seu_usuario' pelo nome de usuário correto
    $container->set('pass', 'password'); // Substitua 'sua_senha' pela senha correta

$container->set('db', function ($container) {
    $host = "localhost";
    $dbname = "tasks";
    $user = $container->get('user'); // Obtém o nome de usuário a partir do contêiner
    $pass = $container->get('pass'); // Obtém a senha a partir do contêiner
   

    $db = new PDO("pgsql:host=$host;port=5432;dbname=$dbname", $user, $pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $db;
});

$container->set('App\Controllers\TaskController', function ($container) {
    return new \App\Controllers\TaskController($container);
});

$container->set('responseFactory', function () use ($app) {
    return $app->getResponseFactory();
});

$container->set('App\Controllers\StatusController', function ($container) {
    $responseFactory = $container->get('responseFactory');
    $taskModel = $container->get('App\Models\Task'); // Obtém uma instância da classe Task
    return new \App\Controllers\StatusController($taskModel, $responseFactory);
});

$container->set('App\Models\Task', function ($container) {
    $db = $container->get('db');
    return new \App\Models\Task($db);
});


// Configuração do middleware CORS
$app->add(new CorsMiddleware([
    'origin' => ['*'], // Substitua pela origem do seu aplicativo React
    'methods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
    'headers.allow' => ['Content-Type'],
]));


$app->add(BodyParsingMiddleware::class);
//$app->addRoutingMiddleware();
//$app->addErrorMiddleware(true, true, true);


// Define as rotas
$app->group('/', function (RouteCollectorProxy $group) {
    $group->get('tasks', 'App\Controllers\TaskController:readTasks');
    $group->post('tasks', 'App\Controllers\TaskController:createTask');
    $group->delete('tasks/{id}', 'App\Controllers\TaskController:deleteTask');
    $group->put('tasks/{id}', 'App\Controllers\TaskController:updateTask');
});

$app->group('/status', function (RouteCollectorProxy $group) {
    $group->get('/true', 'App\Controllers\StatusController:readTrue');
    $group->get('/false', 'App\Controllers\StatusController:readFalse');
    $group->put('/{id}', 'App\Controllers\StatusController:updateStatus');
});

$app->run();