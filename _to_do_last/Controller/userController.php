<?php

require_once 'Model/Users.php';


class userController
{
    public function __construct()
    {
        $this->userdb = new Users();
    }

    public function sendResponse($status, $message, $data = [])
    {
        http_response_code($status);
        $response = [
            'status' => $status === 200 || $status === 201 ? 'success' : 'error',
            'message' => $message,
            'data' => $data,
        ];

        echo json_encode($response, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function login()
    {
        $username = $_POST['username'] ?? "";
        $password = $_POST['password'] ?? "";

        $inputData = json_decode(file_get_contents('php://input'), true);

        if (isset($inputData['username']) && isset($inputData['password'])) {
            $username = $inputData['username'];
            $password = $inputData['password'];
        }

        error_log($username);
        $user = new Users();
        $user->username = $username;
        $user->pass = $password;

        $userCheck = $user->getUser();
        if (is_array($userCheck) && count($userCheck) > 0)
            if ($userCheck[0]->password == $password) {
                $_SESSION['user_id'] = $userCheck[0]->id;
                error_log($$userCheck[0]->id);

                self::sendResponse(200, "Login successfully {$userCheck[0]->id}");
            } else
                self::sendResponse(401, "Wrong password!");
        else
            self::sendResponse(404, "User not found!");
    }
    public function register()
    {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        if ($data !== null) {
            $username = $data['username'];
            $password = $data['password'];
        }

        $user = new Users();
        $user->username = $username;
        $user->pass = $password;

        if (count($user->getUserByUsername()) > 0)
            self::sendResponse(409, "Username already exists!");

        if ($user->insert())
            self::sendResponse(201, "user created successfully");
        else
            self::sendResponse(500, "Failed to create user!");
    }

    public function logout()
    {
        unset($_SESSION['user_id']);
        self::sendResponse(200, "Logout successfully");
    }

}