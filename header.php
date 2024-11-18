<?php
    session_start();
    ini_set('display_errors', 'off');

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css"> 
        <title>My Last Program</title>
        <link href="">
        <link rel="stylesheet" href="css/reset.css">
        <link rel="stylesheet" href="css/style.css">
    </head>
    <style>
  .index-intro {
    width: 1200px;
    margin: 0 auto;
  }
</style>
    <body>
        <!--Navbar-->
        <nav class="navbar navbar-expand-lg bg-dark navbar-dark py-3 fixed-top">
            <div class="container">
                <a href="#" class="navbar-brand fw-bold">My <span class="text-warning">Fav Work </span></a>
                <button
                class="button navbar-toggler" 
                type="button" 
                data-bs-toggle="collapse" 
                data-bs-target="#navmenu"
                >
                    <span class="navbar-toggler-icon"></span>
                </button>
                    
                <div class="collapse navbar-collapse" id="navmenu">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a href="index.php" class="nav-link"><span class="text-warning homefav">Home
                        </span></a>
                        </li>
                    </ul>
                </div>
                <ul>
                <?php
                        if(isset($_SESSION["username"])){
                            echo '<li style="color: blue; list-style:none;">Contact</li>';
                            // echo "<li><a href='profile.php'>Contact</a></li>";
                            echo '<li><a href="includes/logout.inc.php">Log out</a></li>';
                        }
                        else{
                            echo '<li><a href="signup.php">Sign up</a></li>';
                            echo '<li><a href="login.php">Log in</a></li>';
                        }
                    ?>
                    
                </ul>
                </div>
            </div>
        </nav>
        
        <div class="wrapper">