<?php

require_once 'Model/Task.php';

class taskController
{

    private $taskdb;

    public function __construct()
    {
        $this->taskdb = new Task();
    }
    //Phuong thuc gui phan hoi ve client
    public static function sendResponse($status, $message, $data = [])
    {
        http_response_code($status);
        $response = [
            'status' => $status === 200 || $status === 201 ? 'success' : 'error',
            'message' => $message,
            'data' => $data,
            "backBtn" => "<a style='display:block;text-align:center;border:1px solid black;text-decoration:none;color:black;font-weight:bold;margin-top:16px;padding:8px;'href='javascript:history.back()'>Back</a>"
        ];

        echo json_encode($response, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        exit;
    }
    public function InsertTask()
    {

        if (!isset($_SESSION['user_id'])) {
            self::sendResponse(401, "Missing user_id");
            return;
        }

        $task_title = $_POST['task_title'];
        $task_content = $_POST['task_content'];
        $task_status = $_POST['task_status'];
        $user_id = $_SESSION['user_id'];

        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        if ($data !== null) {
            $task_title = $data["task_title"];
            $task_content = $data["task_content"];
            $task_status = $data["task_status"];
        }

        $task = new Task();
        $task->title = $task_title;
        $task->content = $task_content;
        $task->status = $task_status;
        $task->user_id = $user_id;

        if ($task->create())
            self::sendResponse(201, "Task created successfully");
        else
            self::sendResponse(500, "Failed to create task");
    }
    public function getTask()
    {

        if (!isset($_SESSION['user_id'])) {
            self::sendResponse(401, "Missing user_id");
            return;
        }
        // error_log($_SESSION['user_id']);

        $tasks = $this->taskdb->getTask();
        self::sendResponse(200, 'Success to get all Tasks of current user', $tasks);
    }
    public function searchTask($searchTerm)
    {
        $tasks = $this->taskdb->searchTask($searchTerm);
        self::sendResponse(200, 'Success to get all Tasks of current user', $tasks);

    }
    public function deleteTask($id = "")
    {
        if (!isset($_SESSION["user_id"])) {
            self::sendResponse(401, "Unauthorized!");
            return;
        } elseif (empty($id)) {
            self::sendResponse(400, "Task ID is required");
            return;
        }

        if (Task::find($id)->delete())
            self::sendResponse(200, "Task deleted successfully");
        else
            self::sendResponse(500, "Failed to delete task!");
    }
    public function editTask($id = "")
    {
        if (!isset($_SESSION["user_id"])) {
            self::sendResponse(401, "Unauthorized!");
            return;
        }
        $putData = json_decode(file_get_contents("php://input"), true);

        $id = $putData['task_id'] ?? null;
        $task_title = $putData['task_title'] ?? null;
        $task_content = $putData['task_content'] ?? null;
        $task_status = $putData['task_status'] ?? null;
        $user_id = $_SESSION["user_id"] ?? null;

        $task = Task::find($id);
        $task->title = $task_title;
        $task->content = $task_content;
        $task->status = $task_status;
        $task->user_id = $user_id;
        if ($task->update())
            self::sendResponse(200, "Task updated successfully");
        else
            self::sendResponse(500, "Failed to update task!");
    }

}