<?php

session_start();

if (isset($_COOKIE['login']) && !$_SESSION['logged_out']) {

    $_SESSION['login'] = $_COOKIE['login'];

    header("Location: home_page.php");
}

$login = '';
$password = '';
$password_check = '';
$error = '';

function main()
{

    global $login;
    global $password;
    global $password_check;
    global $error;

    if (isset($_POST['submit'])) {

        if (isset($_POST['login'])) {
            $login = $_POST['login'];
        } else {
            $error = 'Login is required';
            return;
        }

        if (isset($_POST['password'])) {
            $password = $_POST['password'];
        } else {
            $error = 'Password is required';
            return;
        }

        if (isset($_POST['password_check'])) {
            $password_check = $_POST['password_check'];
        } else {
            $error = 'Please repeat password';
            return;
        }


        $link = mysqli_connect('localhost', 'root', '', 'main');

        $data = "SELECT * FROM users";
        $table = mysqli_query($link, $data);

        for ($i = 0; $i < mysqli_num_rows($table); $i++) {
            $row = mysqli_fetch_array($table);

            if ($login == $row['user_login']) {
                $error = 'Login already token';
                return;
            }
        }

        if (strlen($login) < 5) {
            $error = 'Login must be minimum 5 chars';
            return;
        }

        if ($password !== $password_check) {
            $error = 'Passwords are different!';
            return;
        }

        if (strlen($password) < 6) {
            $error = 'Password must be minimum 6 chars';
            return;
        }

        $sql = "INSERT INTO users (user_login, user_password) VALUES ('$login', '$password')";
        $sql2 = "CREATE TABLE $login (
            id INT NOT NULL PRIMARY KEY AUTO_INCREMENT UNIQUE,
            task_title VARCHAR(50) NOT NULL,
            task_description VARCHAR(250)
            )";


        mysqli_query($link, $sql);
        mysqli_query($link, $sql2);

        mysqli_close($link);


        session_start();
        $_SESSION['login'] = $login;

        header("Location: home_page.php");
    }
}

main();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up | YourTasksList</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">
    <script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>

    <link rel="stylesheet" href="styles/sign_up/sign_up.css">
</head>

<body>

    <form action="sign_up.php" method="POST">
        <h1>Sign up</h1>

        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label login_div">
            <input class="mdl-textfield__input login" type="text" id="sample3" name="login" value="<?php echo $login ?>">
            <label class="mdl-textfield__label" for="sample3">Login</label>
        </div>

        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label pass_div">
            <input class="mdl-textfield__input pass" type="password" id="sample3" name="password" value="<?php echo $password ?>">
            <label class="mdl-textfield__label" for="sample3">Password</label>
        </div>

        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label pass_div">
            <input class="mdl-textfield__input pass" type="password" id="sample3" name="password_check" value="<?php echo $password ?>">
            <label class="mdl-textfield__label" for="sample3">Repeat password</label>
        </div>

        <p class='error'><?php echo $error ?></p>
        <input class="submit_btn" type="submit" value="Sign up" name="submit">
        <p class="txt">Do you already have account? <a href="index.php">Sign in</a> now</p>
    </form>

    <script src="cookie_alert.js"></script>

</body>

</html>