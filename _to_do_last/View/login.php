<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div class="login">
        <form action="index.php/login" method="POST" id="loginForm">
            <input id="username" type="text" name="username" placeholder="username">
            <input id="password" type="password" name="password" placeholder="password">
            <button type="submit">Login</button>
            <p style="padding-top: 10px;">Don't have an account? <a href="View/register.php">Register</a></p>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $('#loginForm').submit(function (event) {
            event.preventDefault();
            let username = $('#username').val();
            let password = $('#password').val();


            $.ajax({
                url: 'index.php/login',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ username: username, password: password }),
                success: function () {
                    alert(`Login successfully`);
                    window.location.href = 'index.php';
                },
                error: function (xhr, status, error) {
                    alert(`Failed to login!`);
                }
            })
        })
    </script>
</body>

</html>