<?php

function emptyInputSignup($name, $email, $pwd, $pwdRepeat) {
    $result;
    if(empty($name) || empty($email) || empty($pwd) || empty($pwdRepeat)) {
        $result = true;
    }
    else{
        $result = false;
    }
    return $result;
}
// function invalidUid($username) {
//     $result;
//     if(!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
//         $result = true;
//     }
//     else{
//         $result = false;
//     }
//     return $result;
// }
function invalidEmail($email) {
    $result;
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $result = true;
    }
    else{
        $result = false;
    }
    return $result;
}
function pwdMatch($pwd, $pwdRepeat) {
    $result;
    if($pwd !== $pwdRepeat) {
        $result = true;
    }
    else{
        $result = false;
    }
    return $result;
}
function uidExists($conn, $email) {
    $sql = "SELECT * FROM users WHERE usersEmail = ?;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$sql)){  
        header("location: ../signup.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt,"s", $email);
    mysqli_stmt_execute($stmt);
    
    $resultData = mysqli_stmt_get_result($stmt);

    if($row = mysqli_fetch_assoc($resultData)){
        return $row;
    }
    else{
        $result = false;
        return $result;
    }

    mysqli_stmt_close($stmt);
}
function createUser($conn, $name, $email, $pwd) {
    $sql = "INSERT INTO users (usersName, usersEmail, usersPwd) VALUES (?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$sql)){  
        header("location: ../signup.php?error=stmtfailed");
        exit();
    }

    $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT); 
    if ($hashedPwd === false) {
        die("Password hashing failed.");
    }

    mysqli_stmt_bind_param($stmt,"sss", $name, $email, $hashedPwd);
    // mysqli_stmt_bind_param($stmt,"sss", $name, $email, $pwd);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("location: ../signup.php?error=none");
    exit(); 
}
function createUser2($conn, $name, $email, $company, $phone) {
    $sql = "INSERT INTO userss (userssName, userssEmail, userssCompany, userssPhone) VALUES (?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$sql)){  
        header("location: ../index.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt,"ssss", $name, $email, $company, $phone);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("location: ../index.php?error=none");
    exit(); 
}
function emptyInputLogin($username, $pwd) {
    $result;
    if(empty($username) || empty($pwd)) {
        $result = true;
    }
    else{
        $result = false;
    }
    return $result;
}

function loginUser($conn, $username, $pwd) {
    $uidExists = uidExists($conn, $username);

    if($uidExists === false){
        header("location: ../login.php?error=wronglogin");
        exit();
    }

    $pwdHashed = $uidExists["usersPwd"];
    $checkPwd = password_verify($pwd, $pwdHashed); 

    if($checkPwd === false){
        header("location: ../login.php?error=wronglogin");
        exit();
    }
    else if($checkPwd === true) {
        session_start();
        $_SESSION["username"] = $uidExists["usersName"];
        $_SESSION["userid"] = $uidExists["usersId"];
        // $_SESSION["useruid"] = $uidExists["usersUid"];
        $_SESSION["status"] = $uidExists["status"];
        header("location: ../index.php");
        exit();
    }
}