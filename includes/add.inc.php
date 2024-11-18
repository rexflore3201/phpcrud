<?php

if(isset($_POST["submit2"])){

    $name = $_POST["name"];
    $company = $_POST["company"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];

    require_once 'dbh.inc.php';
    require_once 'functions.inc.php';

    if (emptyInputSignup($name, $email, $company, $phone) !== false) {
        header("location: ../index.php?error=emptyinput");
        exit();
    }
    if (invalidEmail($email) !== false) {
        header("location: ../index.php?error=invalidemail");
        exit();
    }
    
    createUser2($conn, $name, $email, $company, $phone);

}
else{
    header("location: ../index.php");
    exit();
}