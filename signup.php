<html>
<head>
    <title>SignUp for chat !!!</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="styling.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>

<body>

<div>
    <div class="container" >
        <div class="col-md-10" >
            <div id="logbox"  >
                <form id="signup" method="post" action="backend.php" >
                    <h1>Create an Account</h1>
                    <input id="id-fname" name="signup_user[first_name]" type="text" placeholder="First Name" class="input pass"/>
                    <span id="id-fname-error" class="error"></span>
                    <input id="id-sname" name="signup_user[second_name]" type="text" placeholder="Second Name" class="input pass"/>
                    <span id="id-sname-error" class="error"></span>
                    <input id="id-username" name="signup_user[username]" type="text" placeholder="Username" class="input pass"/>
                    <span id="id-username-error" class="error"></span>
                    <input id="id-email" name="signup_user[email]" type="email" placeholder="Your active email" class="input pass"/>
                    <span id="id-email-error" class="error"></span>
                    <input id="id-password" name="signup_user[password]" type="password" placeholder="Enter password"  class="input pass"/>
                    <span id="id-password-error" class="error"></span>
                    <input id="id-password2" name="signup_user[password2]" type="password" placeholder="Confirm password"  class="input pass"/>
                    <span id="id-password2-error" class="error"></span>
                    <input id="id-submit-user" type="submit" value="Sign me up!" class="inputButton"/>
                </form>
                <form action="form.php">
                    <input type="submit" class="inputButton" value="Go to Login Page" />
                </form>
            </div>
        </div>
    </div>
</div>
        <!--col-md-6-->

</body>
</html>

<script type="text/javascript">

  $("#id-submit-user").click(function (event) {

      event.preventDefault();

      $("#id-fname-error").html("");
      $("#id-sname-error").html("");
      $("#id-username-error").html("");
      $("#id-email-error").html("");
      $("#id-password-error").html("");
      $("#id-password2-error").html("");

      $.ajax({
          type: "POST",
          url: "validator.php",
          async: false,
          cache: false,
          data: {
              validate :{
                  fname: $("#id-fname").val(),
                  sname: $("#id-sname").val(),
                  username: $("#id-username").val(),
                  email: $("#id-email").val(),
                  password: $("#id-password").val(),
                  password2: $("#id-password2").val()
              }
          }
      }).done(function (data) {
          if (data === 'success'){
              $("form").submit();
          }else {
              var obj = jQuery.parseJSON(data);
              if(obj.errors.fname !== true){
                  $("#id-fname-error").html(obj.errors.fname);
              }
              if(obj.errors.sname !== true){
                  $("#id-sname-error").html(obj.errors.sname);
              }
              if(obj.errors.username !== true){
                  $("#id-username-error").html(obj.errors.username);
              }
              if(obj.errors.email !== true){
                  $("#id-email-error").html(obj.errors.email);
              }
              if(obj.errors.password !== true){
                  $("#id-password-error").html(obj.errors.password);
              }
              if(obj.errors.password2 !== true){
                  $("#id-password2-error").html(obj.errors.password2);
              }
          }
      });
  });

</script>