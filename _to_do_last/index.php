<?php
require_once 'Controller/taskController.php';
require_once 'Controller/userController.php';

$request = isset($_SERVER['PATH_INFO']) ? explode('/', trim($_SERVER['PATH_INFO'], '/')) : [""];

session_start();
$taskController = new TaskController();
$userController = new UserController();
error_log("Request: " . $request[0] . ";");
// error_log("user_id: " . $_SESSION["user_id"] . ";");

switch ($_SERVER['REQUEST_METHOD']) {
    // r-me: GET
    case 'GET':
        if ($request[0] === '' && isset($_SESSION['user_id']) && !isset($_GET["search_term"]))
            include_once 'View/list.php';
        elseif ($request[0] === '' && !isset($_SESSION['user_id']) && !isset($_GET["search_term"]))
            include_once 'View/login.php';
        elseif ($request[0] == 'logout' && isset($_SESSION['user_id']))
            $userController->logout();
        elseif ($request[0] === 'tasks' && isset($_GET["search_term"]))
            $taskController->searchTask($_GET['search_term']);
        elseif ($request[0] === 'tasks')
            $taskController->getTask();
        else
            TaskController::sendResponse(404, "Endpoint not found");
        break;

    // r-me: POST
    case 'POST':
        if ($request[0] === 'tasks')
            $taskController->InsertTask();
        elseif ($request[0] === 'login')
            $userController->login();
        elseif ($request[0] === 'register')
            $userController->register();
        else
            TaskController::sendResponse(404, "Endpoint not found");
        break;

    // r-me: PUT
    case 'PUT':
        if ($request[0] === 'edit-task')
            $taskController->editTask();
        else
            TaskController::sendResponse(404, "Endpoint not found");
        break;

    // r-me: DELETE
    case 'DELETE':
        if ($request[0] === 'delete-task')
            $taskController->deleteTask($request[1]);
        else
            TaskController::sendResponse(404, "Endpoint not found");
        break;

    // r-me: Default
    default:
        TaskController::sendResponse(405, "Method Not Allowed");
        break;
}
?>