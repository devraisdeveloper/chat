<html>
<head>
    <title>Login for chat !!!</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="styling.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>

<body>

<?php
$error_message = "";
if(isset($_GET['fail']) && $_GET['fail'] == 1){
    $error_message = 'Failed to login. Please try again';
}
if(isset($_GET['fail']) && $_GET['fail'] == 2){
    $error_message = 'Attempted to open invalid user. Relogin, please !!!';
}

?>

<div>
    <div class="container" >
        <div class="col-md-10" >
            <div id="logbox"  >
                <form id="signup" method="post" action="backend.php" >
                    <h1>Log to Chat</h1>
                    <input id="id-login-username" name="login_user[username]" type="text" placeholder="Username" class="input pass"/>
                    <input id="id-login-password" name="login_user[password]" type="password" placeholder="Write password" class="input pass"/>
                    <input id="id-login-user" type="submit" value="Log me in!" class="inputButton"/>
                </form>
                <form action="signup.php">
                    <input type="submit" class="inputButton" value="Go to Sign up" />
                </form>
                <span id="id-login-error" class="error"><?= $error_message ?></span>
            </div>
        </div>
    </div>
</div>

</body>
</html>

<script type="text/javascript">

   $(window).on('unload', function () {
       $("#id-login-error").html("");
   });
</script>