    <?php

    $active = '';

    session_start();

    if (!isset($_SESSION['login'])) {
        header("Location: index.php");
    }

    if (isset($_POST['log_out_btn'])) {
        echo ' elo';
        $_SESSION['logged_out'] = true;
        header("Location: index.php");
    }


    ?>


    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Home Page | YourTasksList</title>
        <link rel="stylesheet" href="styles/home_page/home_page.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">
        <script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>
    </head>

    <body>
        <h1 class="page_title">Hello <?php echo $_SESSION['login']; ?>!</h1>

        <form action="home_page.php" method='POST' class="log_out_button_form">
            <input type="submit" class="log_out_button" value='Log out' name='log_out_btn'>
        </form>

        <form action="home_page.php" method="POST" class="add_task_page_btn_form">
            <button class="mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored add_task_page_btn" type="submit" name="add_task">
                <i class="material-icons add_task_button_cross">add</i>
            </button>
        </form>

        <main>

            <?php

            $login = $_SESSION['login'];


            // adding task 
            if (isset($_POST['add_task_btn'])) {
                $new_title = $_POST['task_title'];
                $new_description = $_POST['task_description'];

                if ($new_title && $new_description) {

                    $link = mysqli_connect('localhost', 'root', '', 'main');

                    $sql5 = "INSERT INTO $login (task_title, task_description) VALUES ('$new_title', '$new_description')";
                    mysqli_query($link, $sql5);

                    unset($_POST);
                    unset($_REQUEST);
                    header('Location: ?');

                    mysqli_close($link);
                } else {
                    $_POST['add_task'] = true;
                }
            }


            //deleting task
            if (isset($_POST['delete_task'])) {
                $link = mysqli_connect('localhost', 'root', '', 'main');

                $id = $_POST['delete_task'];
                $sql4 = "DELETE FROM $login WHERE id = $id";

                mysqli_query($link, $sql4);
                mysqli_close($link);
            }

            $link = mysqli_connect('localhost', 'root', '', 'main');


            //task display
            $sql2 = "SELECT * FROM $login";
            if ($result = mysqli_query($link, $sql2)) {
                for ($i = 0; $i < mysqli_num_rows($result); $i++) {


                    $row = mysqli_fetch_array($result);

                    $id = $row['id'];
                    $title = $row['task_title'];
                    $description = $row['task_description'];

                    echo "
                    
                    <div class='task'>
                        <h1 class='task_title'>$title</h1>
                        
                        <p class='task_description'> $description</p>

                        <form action='home_page.php' method='POST'>
                            <input type='submit' value=$id name='delete_task'>
                        </form>
                    </div>
                    
                    ";
                }
            }


            ?>

            <!-- add task button (show add task WINDOW) control -->
            <?php if (isset($_POST['add_task'])) { ?>
                <script>
                    document.getElementsByTagName('body')[0].style.overflow = 'hidden';
                </script>

                <div class="shadow"></div>
                <div class='add_task_window'>

                    <!-- closing add-task window -->
                    <form action="home_page.php" method="POST" class="close_add_task_window_form">
                        <input type="submit" value="" class="close_add_task_window_input" name='close_add_task_window'>
                    </form>

                    <?php
                    if (isset($_POST['close_add_task_window'])) {
                        unset($_POST['add_task']);
                    }
                    ?>


                    <form action="home_page.php" method="POST" class='add_task_form'>

                        <h1 class='add_task_form_title'>Add task</h1>

                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label add_task_form_title_div">
                            <input class="mdl-textfield__input add_task_form_title_input" type="text" id="sample3" name='task_title'>
                            <label class="mdl-textfield__label add_task_form_title_label" for="sample3">Title</label>
                        </div>


                        <div class="mdl-textfield mdl-js-textfield add_task_form_description_div">
                            <textarea class="mdl-textfield__input add_task_form_description_input" type="text" rows="5" id="sample5" name='task_description'></textarea>
                            <label class="mdl-textfield__label add_task_form_description_label" for="sample5">Description</label>
                        </div>

                        <input type="submit" value="ADD" class="add_task_btn" name='add_task_btn' onclick=add_task()>

                    </form>

                </div>
        </main>


    <?php } ?>

    <footer>
        <p>Made by Wleciał Brothers. Icons made by <a href="https://www.flaticon.com/authors/freepik" new_title="Freepik">Freepik</a> from <a href="https://www.flaticon.com/" new_title="Flaticon"> www.flaticon.com</a>.</p>
    </footer>


    </body>


    </html>