<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div class="register">
        <form id="register-form" action="index.php/register" method="POST" id="registerForm">
            <input id="username" type="text" name="username" placeholder="username">
            <input id="password" type="password" name="password" placeholder="password">
            <button type="submit">Register</button>
            <p style="padding-top: 10px;">Already have an account? <a href="javascript:history.back()">Login</a></p>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $('#register-form').submit(function (event) {
            event.preventDefault();
            let username = $('#username').val();
            let password = $('#password').val();

            $.ajax({
                url: '../index.php/register',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ username: username, password: password }),
                success: function (response) {
                    alert('Register successfully! ');
                    window.location.href = 'javascript:history.back()';
                },
                error: function (xhr, status, error) {
                    alert(`Failed to register! response: ${xhr.responseText}`);
                }
            });
        });
    </script>
</body>

</html>