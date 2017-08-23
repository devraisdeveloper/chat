<?php

require 'connection.php';

$connection = new Database();

if (isset($_POST['unload'])) {

    $query = "UPDATE chat_users SET user_status = 0 WHERE id=:id";

    $con = $connection->getConnection();
    $stmt = $con->prepare($query);
    $stmt->bindValue(":id", $_POST['unload']);
    $stmt->execute();
}

// LIST ALL chat messages from table
if (isset($_POST['list'])) {
    $query = "SELECT username, chat_message, chat_time FROM chat_messages";
    $stmt = $connection->getConnection()->prepare($query);
    $stmt->execute();
    $result['response'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($result);
}

// Add NEW MESSAGE to chat and table
if (isset($_POST['message'])) {

    $query = "INSERT INTO chat_messages SET chat_user_id=:chat_user_id, chat_message=:chat_message, chat_time=:chat_time, username=:username";
    $con = $connection->getConnection();

    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $con->beginTransaction();
    try {

        $stmt = $con->prepare($query);

        $stmt->bindValue(":chat_user_id", $_POST['message']['chat_user_id']);
        $stmt->bindParam(":chat_message", $_POST['message']['chat_message']);
        $stmt->bindParam(":chat_time", date("Y-m-d H:i:s"));
        $stmt->bindParam(":username", $_POST['message']['username']);
        $stmt->execute();

        $query = "SELECT username, chat_message, chat_time FROM chat_messages WHERE chat_id=" . $con->lastInsertId();
        $stmt = $con->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $response['response']['chat_message'] = $result['chat_message'];
        $response['response']['chat_time'] = $result['chat_time'];

        echo "User :" . $result['username'] . '  : [' . $result['chat_time'] . ']' . '  :  ' . $result['chat_message'];

        $con->commit();

    } catch (Exception $e) {

        $con->rollBack();
        echo $e->getMessage();
    }

}

// LIST ALL users that are registered in chat
if (isset($_POST['total_users'])) {
    $query = "SELECT id, username, user_status FROM chat_users";
    $stmt = $connection->getConnection()->prepare($query);
    $stmt->execute();
    //   $stmt->fetchAll(PDO::FETCH_ASSOC);
    $response['response'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($response);
}

// CREATE new user / Add new user who can use the chat

//if (isset($_POST['signup_user']) && isset($_POST['g-recaptcha-response']) && $_POST['g-recaptcha-response']) {
if (isset($_POST['signup_user'])) {

    /* $secret = "6LfzwC0UAAAAAHmiaW6esuPT9akRqUMij3oiuZhf";
     $ip = $_SERVER['REMOTE_ADDR'];
     $captcha = $_POST['g-recaptcha-response'];
     $rsp = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . $secret . "&response=" . $captcha . "&ip=" . $ip);
     $arr = json_decode($rsp, true);*/

    // if ($arr['success']) {
    $query = "INSERT INTO chat_users SET first_name=:first_name, second_name=:second_name, email=:email, password=:password, username=:username, user_status=:user_status";

    // $stmt = $connection->getConnection()->prepare($query);
    $con = $connection->getConnection();

    $stmt = $con->prepare($query);
    $stmt->bindParam(":first_name", $_POST['signup_user']['first_name']);
    $stmt->bindParam(":second_name", $_POST['signup_user']['second_name']);
    $stmt->bindParam(":email", $_POST['signup_user']['email']);
    $stmt->bindParam(":password", $_POST['signup_user']['password']);
    $stmt->bindParam(":username", $_POST['signup_user']['username']);
    $stmt->bindValue(":user_status", 1);

    $stmt->execute();

    session_start();
    $_SESSION[$_POST['signup_user']['username']] = [];
    $_SESSION[$_POST['signup_user']['username']][] = $_POST['signup_user']['username'];
    $_SESSION[$_POST['signup_user']['username']][] = $con->lastInsertId();
    header('Location: chat.php?user=' . $_POST['signup_user']['username'] . '&id=' . $con->lastInsertId());
}

// Existing users LOGS IN to the chat room
if (isset($_POST['login_user'])) {

    $query = "SELECT id, username FROM chat_users WHERE username=:username AND password=:password";

    $con = $connection->getConnection();
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $con->beginTransaction();

    try {

        $stmt = $con->prepare($query);

        $stmt->bindParam(":username", $_POST['login_user']['username']);
        $stmt->bindParam(":password", $_POST['login_user']['password']);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $query = "UPDATE chat_users SET user_status = 1 WHERE username=:username";
        $stmt = $con->prepare($query);
        $stmt->bindParam(":username", $_POST['login_user']['username']);
        $stmt->execute();
        $con->commit();

        session_start();
        $_SESSION[$result['username']] = [];
        $_SESSION[$result['username']][] = $result['username'];
        $_SESSION[$result['username']][] = $result['id'];
        header('Location: chat.php?user=' . $result['username'] . '&id=' . $result['id']);
        return true;

    } catch (Exception $e) {
        $con->rollBack();
        header('Location: form.php?fail=' . '1');
    }
}

if (isset($_GET['username']) && isset($_GET['id']) && $_GET['username'] && $_GET['id']) {
    session_start();

    $query = "UPDATE chat_users SET user_status = 0 WHERE username=:username";
    $stmt = $connection->getConnection()->prepare($query);
    $stmt->bindParam(":username", $_GET['username']);
    $stmt->execute();

    unset($_SESSION[$_GET['username']]);
    header('Location: form.php');
}

