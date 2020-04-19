<?php

require 'Connect/Connect.php';

session_start();

if (isset($_COOKIE['login']) && !$_SESSION['logged_out']) {

    $_SESSION['login'] = $_COOKIE['login'];

    header("Location: home_page.php");
}

if (isset($_SESSION['logged_out'])) {
    setcookie("login", '', time() - 1);
    session_destroy();
}

$login = '';
$password = '';

$error = '';

$link = mysqli_connect('localhost', 'root', '', 'main');

if ($link === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
};



$data = "SELECT * FROM users";
$table = mysqli_query($link, $data);

$url = parse_url(getenv("CLEARDB_DATABASE_URL"));
$server = $url["host"];


if (isset($_POST['submit'])) {

    $login = $_POST['login'];
    $password = $_POST['password'];


    if (!$login || !$password) {
        $error = 'Fill empty inputs!';
    } else {

        for ($rowCounter = 0; $rowCounter < mysqli_num_rows($table); $rowCounter++) {

            $row = mysqli_fetch_array($table);

            if ($row['user_login'] === $login && $row['user_password'] === $password) {

                if (isset($_POST['checkbox'])) {
                    setcookie("login", $login, time() + 86400 * 30);
                    $_COOKIE['login'] = $login;
                } else {
                    setcookie("login", $login, time() - 1);
                }

                session_start();
                $_SESSION['login'] = $login;

                header('Location: home_page.php');
            } else {
                $error = 'Incorrect login or password';
            }
        }
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in | YourTasksList</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">
    <script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>

    <link href="https://fonts.googleapis.com/css2?family=Montserrat+Alternates:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/index/index.css">
</head>

<body>

    <form action="index.php" method="POST">
        <h1>Sign in</h1>

        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label login_div">
            <input class="mdl-textfield__input login" type="text" id="sample3" name="login" value="<?php echo $login ?>">
            <label class="mdl-textfield__label" for="sample3">Login</label>
        </div>

        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label pass_div">
            <input class="mdl-textfield__input pass" type="password" id="sample3" name="password" value="<?php echo $password ?>">
            <label class="mdl-textfield__label" for="sample3">Password</label>
        </div>

        <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect checkbox_label" for="checkbox-2">
            <input type="checkbox" id="checkbox-2" class="mdl-checkbox__input checkbox" name="checkbox">
            <span class="mdl-checkbox__label checkbox_span">Don't log me out</span>
        </label>

        <p class='error'><?php echo $error ?></p>
        <input class='submit_btn' type="submit" value="Sign in" name="submit">
        <p class='txt'>Don't you have an account? <a href="sign_up.php">Sign up</a> now!</p>

    </form>

    <script src="cookie_alert.js"></script>
</body>

</html>