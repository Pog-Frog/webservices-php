<?php

namespace App\Controllers;

use App\Models\Product;
use App\Models\MySQLHandler;

class RequestController
{
    private $method;
    private $route;
    private $resource;
    private $resource_id;
    private $body;

    public function __construct(protected MySQLHandler $mysql_handler)
    {
        header("Content-Type: application/json");
    }

    public function handle_request()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->route = $_SERVER['REQUEST_URI'];

        $this->resource = explode("/", trim($this->route, '/'))[0];
        $this->resource_id = explode("/",trim($this->route, '/'))[1];

        if ($this->method === 'POST' || $this->method === 'PUT') {
            $input = file_get_contents('php://input');
            $this->body = json_decode($input, true);
        }

        if (!$this->validate()) {
            echo "validation failed";
            return;
        }

        switch ($this->method) {
            case 'GET':
                if ($this->resource_id !== null) {
                    $this->handleGetSingle();
                } else {
                    $this->handleGetAll();
                }
                break;
            case 'POST':
                $this->handlePost();
                break;
            case 'PUT':
                $this->handlePut();
                break;
            case 'DELETE':
                $this->handleDelete();
                break;
            default:
                http_response_code(405);
                echo "Invalid mthod";
                break;
        }
    }

    private function handleGetSingle()
    {
        $result = $this->mysql_handler->get_record_by_id($this->resource_id);
        if ($result === false) {
            http_response_code(500);
            echo "Database error";
        } elseif (empty($result)) {
            http_response_code(404);
            echo "Item not found";
        } else {
            http_response_code(200);
            echo json_encode($result);
        }
    }

    private function handleGetAll()
    {
        $result = $this->mysql_handler->get_data();
        if ($result === false) {
            http_response_code(500);
            echo json_encode(['error' => 'Database error']);
        } else {
            http_response_code(200);
            echo json_encode($result);
        }
    }

    private function handlePost()
    {
        $success = $this->mysql_handler->save($this->body);
        if ($success) {
            http_response_code(201);
            echo json_encode(['message' => 'Item created successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to create item']);
        }
    }

    private function handlePut()
    {
        $success = $this->mysql_handler->update($this->body, $this->resource_id);
        if ($success) {
            http_response_code(200);
            echo "Item updated successfully";
        } else {
            http_response_code(404); 
            echo "Failed to update item";
        }
    }

    private function handleDelete()
    {
        $success = $this->mysql_handler->delete($this->resource_id);
        if ($success) {
            http_response_code(200);
            echo "Item deleted successfully";
        } else {
            http_response_code(404);
            echo "Failed to delete item or item not found";
        }
    }


    public function validate()
    {
        if (is_null($this->resource) || $this->resource !== "products") {
            http_response_code(404);
            echo "Resource not found";
            return false;
        }

        if (in_array($this->method, ['PUT', 'DELETE']) && empty($this->resource_id)) {
            http_response_code(400);
            echo "Resource ID is required";
            return false;
        }

        if (($this->method == "POST" || $this->method == "PUT") && (empty($this->body) || !is_array($this->body))) {
            http_response_code(400);
            echo "Invalid or empty request body";
            return false;
        }

        return true;
    }
}
