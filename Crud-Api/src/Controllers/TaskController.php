<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use DI\Container;
use App\Models\Task;
use PDO;

class TaskController
{
    private $db;
    private $container;

    public function __construct(Container $container)
    {
        $this->db = $container->get('db');
        $this->container = $container;
    }

    public function readTasks(Request $request, Response $response)
    {
        $taskModel = new Task($this->db);
        $tasks = $taskModel->getAllTasks();

        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode($tasks));
        return $response;
    }

    public function createTask(Request $request, Response $response)
{
    $data = $request->getParsedBody();
    $title = $data['title'];
    $description = $data['description'];
    //$status = $data['status'];
    
    //var_dump($title, $description, $status);


    if ($title !== null && $description !== null) {
        $taskModel = new Task($this->db);
        $created = $taskModel->createTasks($title, $description,);

        if ($created) {
            $responseData = ['message' => 'Tarefa criada com sucesso'];
            return $this->respondWithJson($response, $responseData, 201);
        } else {
            $errorResponse = ['error' => 'Erro interno ao criar a tarefa'];
            return $this->respondWithJson($response, $errorResponse, 500);
        }
    } else {
        $errorResponse = ['error' => 'Parâmetros inválidos'];
        return $this->respondWithJson($response, $errorResponse, 400);
    }
}

    
   public function deleteTask(Request $request, Response $response, $args)
{
    $id = $args['id'];
    $taskModel = new Task($this->db);
    $deleted = $taskModel->deleteTasks($id);

    if ($deleted) {
        return $response->withStatus(204);
    } else {
        return $this->respondWithJson($response, ['error' => 'Tarefa não encontrada'], 404);
    }
}

    public function updateTask(Request $request, Response $response, $args){
    $id = $args['id'];
    $data = $request->getParsedBody();
    
    if (isset($data['description']) && $data['description'] !== null) {
        $description = $data['description'];
        $taskModel = new Task($this->db);
        $updated = $taskModel->updateTasks($id, $description);

        if ($updated) {
            return $this->respondWithJson($response, ['message' => 'Tarefa atualizada com sucesso']);
        } else {
            return $this->respondWithJson($response, ['error' => 'Tarefa não encontrada'], 404);
        }
    } else {
        // Campo 'description' não fornecido ou é nulo
        return $this->respondWithJson($response, ['error' => 'Campo "description" é obrigatório'], 400);
    }
}


public function respondWithJson(Response $response, $data, $status = 200){
    $response->getBody()->write(json_encode($data));
    return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
}

}

