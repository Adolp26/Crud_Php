<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseFactoryInterface;
use App\Models\Task;

class StatusController
{
    private $taskModel;
    private $responseFactory;

    public function __construct(Task $taskModel, ResponseFactoryInterface $responseFactory)
    {
        $this->taskModel = $taskModel;
        $this->responseFactory = $responseFactory;
    }

    public function readTrue(Request $request, Response $response)
    {
        $tasks = $this->taskModel->readStatusTrue($request);
        return $this->createResponseWithJson($response, $tasks);
    }

     public function readFalse(Request $request, Response $response)
    {
        $tasks = $this->taskModel->readStatusFalse($request);
        return $this->createResponseWithJson($response, $tasks);
    }

    public function updateStatus(Request $request, Response $response, $args)
    {
        $id = $args['id'];

        if ($this->taskModel->toggleStatus($id)) {
            $message = ['message' => 'Status atualizada com sucesso'];
            return $this->createResponseWithJson($response, $message);
        } else {
            $error = ['error' => 'Anotação não encontrada'];
            return $this->createResponseWithJson($response, $error, 404);
        }
    }

    private function createResponseWithJson(Response $response, $data, $statusCode = 200)
    {
        $response->getBody()->write(json_encode($data));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($statusCode);
    }
}
