<html>
<head>
    <title>This is a test !!!</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="styling.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body>

<?php
session_start();

if (isset($_GET['user']) && isset($_GET['id']) && isset($_SESSION[$_GET['user']]) && ($_SESSION[$_GET['user']][0] == $_GET['user']) && ($_SESSION[$_GET['user']][1] == $_GET['id'])) {
    echo '<p class="session_username">' . $_SESSION[$_GET['user']][0] . "</p>";
    echo '<a id=' . $_SESSION[$_GET['user']][1] . ' class="session_user" href="backend.php?username=' . $_SESSION[$_GET['user']][0] . '&id=' . $_SESSION[$_GET['user']][1] . '">Logout</a>';

} else {
    echo 'error';
    unset($_SESSION[$_GET['user']][1]);
    header("Location :form.php?fail=2");
}
?>


<div id="chatbox">

    <div id="chatarea">
    </div>

    <div id="loginperson">
    </div>

    <div id="textbox">
        <form>
            <textarea rows="3" cols="150" id="text"></textarea>
            <button id="btn-submit" type="submit" class="btn btn-success" onclick="getText()">Submit</button>
        </form>
    </div>
    </center>
</div>

</body>

</html>

<script type="text/javascript">

    // LIST ALL MESSAGES IN CHAT
    function getAllMessages() {
        $.ajax({
            type: "POST",
            url: "backend.php",
            data: {
                list: true
            }
        }).done(function (data) {
            var obj = jQuery.parseJSON(data);
            $("#chatarea").html('');
            $.each(obj.response, function (i, item) {
                $("#chatarea").append('<p>' + 'User :' + item.username + ' :  [' + item.chat_time + '] : ' + item.chat_message + '</p>');
            });

        });
    }

    // LIST ALL CHAT USERS
    function getAllUsers() {
        $.ajax({
            type: "POST",
            url: 'backend.php',
            data: {
                total_users: true
            }
        }).done(function (data) {
            var obj = jQuery.parseJSON(data);
            $("#loginperson").html('');
            $.each(obj.response, function (i, item) {
                $("#loginperson").append('<p class=user_status_' + item.user_status + '>' + item.username + '</p>');
            });

            $(".user_status_0").each(function (i, item) {
                $(this).css('color', 'red');
            });

            $(".user_status_1").each(function (i, item) {
                $(this).css('color', 'green');
            });
        });
    }

    // SUBMIT MESSAGE TO CHAT FROM ACTIVE USER
    $(document).ready(function () {
        $("#btn-submit").click(function (event) {
            event.preventDefault();
            $.ajax({
                type: "POST",
                url: "backend.php",
                data: {
                    message: {
                        chat_message: $("#text").val(),
                        chat_user_id: $('.session_user').attr('id'),
                        username: $('.session_username').html()
                    }
                }
            }).done(function (data) {
                $("#chatarea").append('<p>' + data + '</p>');
            });
        });
    });

    // Set ajax post for every 3 seconds
    $(document).ready(function () {
        setInterval(function () {
            getAllMessages()
        }, 3000);
        setInterval(function () {
            getAllUsers()
        }, 3000);

    });

    // Disable current user if he kills the application
 /*   $(window).on('unload', function () {
        $.ajax({
            type: "POST",
            url: "backend.php",
            data: {
                unload: $('.session_user').attr('id')
            }
        })
    })*/

</script>

<?php
