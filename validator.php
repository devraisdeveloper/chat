<?php

require 'connection.php';

// define variables and set to empty values
$fname_error = $sname_error = $username_error = $email_error = $password_error = "";
$fname = $sname = $username = $email = $password = $password2 = "";

$errors = [];

function filterInput($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function validateName($name)
{
    if (empty($name)) {
        return "You have an empty field";
    }


    if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
        return "Only letters and white space allowed";
    }

    return true;
}

function validateUsername($username)
{
    if (empty($username)) {
        return "You have an empty field";
    }

    if (!preg_match('/^[a-z0-9]{3,15}$/', $username)) {
        return "Min 3 Max 15 characters";
    }

    $connection = new Database();
    $query = "SELECT username from chat_users WHERE username=:username";

    $stmt = $connection->getConnection()->prepare($query);
    $stmt->bindParam("username", $username);

    if (!$stmt->execute()) {
        // return "Failed to validate - submit again";
        return $stmt->execute();
    }

    if ($stmt->fetch(PDO::FETCH_ASSOC) != false) {
        return "Username is already in use !!!";
        // return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    return true;
}

function validateEmail($email)
{
    if (empty($email)) {
        return "You have an empty field";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Enter a valid email address";
    }

    $connection = new Database();
    $query = "SELECT email from chat_users WHERE email=:email";

    $stmt = $connection->getConnection()->prepare($query);
    $stmt->bindParam("email", $email);

    if (!$stmt->execute()) {
        return "Failed to validate - submit again";
        return $stmt->execute();
    }

    if ($stmt->fetch(PDO::FETCH_ASSOC) != false) {
        return "Email is already in use !!!";
        // return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    return true;
}

function validatePassword($password)
{
    if (empty($password)) {
        return "You have an empty field";
    }

    if (!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,12}$/', $password)) {
        return "At least one number, letter or one of the following: !@#$% and min 8 max 12 characters";
    }

    return true;
}

function validatePassword2($password, $password2)
{
    if ($password == $password2) {
        return true;
    }

    return "Second password does not match !";
}

function validateLogin($username, $password)
{

    $connection = new Database();

    $query = "SELECT id, username FROM chat_users WHERE username=:username AND password=:password";
    $stmt = $connection->getConnection()->prepare($query);
    $stmt->bindParam(":username", $username);
    $stmt->bindParam(":password", $password);

    if (!$stmt->execute()) {
        return "Failed to validate - try login again";
    }

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (count($result) > 0) {
        // session_start();

        $query = "UPDATE chat_users SET user_status = 1 WHERE username=:username";
        $stmt = $connection->getConnection()->prepare($query);
        $stmt->bindParam(":username", $_POST['login_user']['username']);

        if (!$stmt->execute()) {
            return "Failed to validate - try login again";
        }
        return 'success';
    } else {
        return 'Failed to login! Please try again';
    }
}

if (isset($_POST['validate']) && $_POST['validate']) {

    $failed = false;

    $fname = $_POST['validate']['fname'];
    $sname = $_POST['validate']['sname'];
    $username = $_POST['validate']['username'];
    $email = $_POST['validate']['email'];
    $password = $_POST['validate']['password'];
    $password2 = $_POST['validate']['password2'];

    $fname = filterInput($fname);
    $sname = filterInput($sname);
    $username = filterInput($username);
    $email = filterInput($email);

    $errors['errors']['fname'] = validateName($fname);
    $errors['errors']['sname'] = validateName($sname);
    $errors['errors']['username'] = validateUsername($username);
    $errors['errors']['email'] = validateEmail($email);
    $errors['errors']['password'] = validatePassword($password);
    $errors['errors']['password2'] = validatePassword2($password, $password2);

    /*var_dump($errors);
    die();*/

    foreach ($errors['errors'] as $key => $value) {
        if ($value !== true) {
            $failed = true;
            break;
        }
    }

    if ($failed) {
        echo json_encode($errors);
    } else {
        echo 'success';
    }
}

if (isset($_POST['login']) && $_POST['login']) {
    echo validateLogin($_POST['login']['username'], $_POST['login']['username']);
}