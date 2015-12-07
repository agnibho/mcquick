<?php
/**********************************************************************
 * Title: MCQuick
 * Description: Application for creating and solving MCQ papers
 * Author: Agnibho Mondal
 * Website: http://code.agnibho.com
 **********************************************************************
   Copyright (c) 2014-2015 Agnibho Mondal
   All rights reserved
 **********************************************************************
   This file is part of MCQuick.
   
   MCQuick is free software: you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation, either version 3 of the License, or
   (at your option) any later version.
   
   MCQuick is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.
   
   You should have received a copy of the GNU General Public License
   along with MCQuick.  If not, see <http://www.gnu.org/licenses/>.
 **********************************************************************/
?>
<?php
session_start();
require_once "lib/php/DB.php";
require_once "lib/php/User.php";
$user=new User();
$email="";
$send=false;
$code="";
$error="";
$info="";

/*GET*/
//Reset password
if(isSet($_GET["email"])&&isSet($_GET["code"])){
    $email=$_GET["email"];
    $code=$_GET["code"];
}
//Send reset code
if(isSet($_GET["send"])){
    $send=true;
}
//Log out
if(isSet($_GET["logout"])){
    unset($_SESSION["user"]);
    unset($_SESSION["name"]);
}

/*POST*/
//Set password
if(isSet($_POST["email"])&&isSet($_POST["code"])&&isSet($_POST["pass"])&&isSet($_POST["again"])){
    if(isSet($_POST["code"])!=""&&$_POST["code"]!=""&&$_POST["pass"]!=""&&$_POST["pass"]==$_POST["again"]){
	if($user->set_password($_POST["email"], $_POST["pass"], $_POST["code"])){
	    $info="Password changed.";
	    $code="";
	}
	else{
	    $error="Failed to chamge password. Please recheck the confirmation code.";
	}
    }
    else{
	$error="E-mail, password or confirmation code can't be empty.";
    }
}
//Log in
else if(isSet($_POST["email"])&&isSet($_POST["password"])){
    if($_POST["email"]!=""&&$_POST["password"]!=""){
	if(!$user->load_user($_POST["email"], $_POST["password"])){
	    $error="Login failed. E-mail id and password do not match.";
	}
	else{
	    $info="Successfully logged in.";
	    $_SESSION["user"]=$user->get_id();
	    $_SESSION["name"]=$user->get_name();
	    header("Location: index.php");
	}
    }
    else{
	$error="E-mail or password can't be empty.";
    }
}
//Register
else if(isSet($_POST["name"])&&isSet($_POST["email"])){
    if($_POST["name"]!=""&&$_POST["email"]!=""){
	if($user->add_user($_POST["name"], $_POST["email"])){
	    $info="Account created. Please confirm e-mail id to set password.";
	}
	else{
	    $error="Accound creation failed. E-mail id may be invalid or already in use.";
	}
    }
}
//Send reset code
else if(isSet($_POST["email"])){
    if($_POST["email"]!=""){
	if($user->resend_code($_POST["email"])){
	    $info="Password reset code sent to your e-mail id. Please follow the link in the e-mail.";
	    $send=false;
	    $code=true;
	}
	else{
	    $error="Password reset failed.";
	}
    }
}

?>
<!DOCTYPE html>
<html>
    <head>
	<title>MCQuick Login</title>
	<?php include("template/head.php"); ?>
    </head>
    <body>
	<?php include("template/heading.php"); ?>
	<div class="alert alert-danger" <?php echo ($error)?"":"style='display:none'"; ?>>
	    <p><?php echo $error; ?></p>
	</div>
	<div class="alert alert-info" <?php echo ($info)?"":"style='display:none'"; ?>>
	    <p><?php echo $info; ?></p>
	</div>
	<div class="row" id="login" <?php echo ($code or $send)?"style='display:none'":""; ?>>
	    <div class="col-sm-6">
		<div class="panel panel-success">
		    <div class="panel-heading">
			<h4>Log-in if you already have an account</h4>
		    </div>
		    <div class="panel-body">
			<form method="post">
			    <div class="form-group">
				<label for="email-1">E-mail:</label>
				<input type="text" name="email" id="email-1" class="form-control" placeholder="Enter your registered e-mail id">
			    </div>
			    <div class="form-group">
				<label for="password">Password:</label>
				<input type="password" name="password" id="password" class="form-control" placeholder="Enter your MCQuick password">
			    </div>
			    <button class="btn btn-success" type="submit">Log in</button>
			    <a href="#" id="reset-password" class="pull-right">Forgot your password?</a>
			</form>
		    </div>
		</div>
	    </div>
	    <div class="col-sm-6">
		<div class="panel panel-primary">
		    <div class="panel-heading">
			<h4>Create a new account with your e-mail id</h4>
		    </div>
		    <div class="panel-body">
			<form method="post">
			    <div class="form-group">
				<label for="name">Name:</label>
				<input type="text" name="name" id="name" class="form-control" placeholder="Please enter your full name">
			    </div>
			    <div class="form-group">
				<label for="email-2">E-mail:</label>
				<input type="text" name="email" id="email-2" class="form-control" placeholder="Enter your valid e-mail id">
			    </div>
			    <button class="btn btn-success" type="submit">Register</button>
			    <a href="#" id="confirm-code" class="pull-right">Confirm your e-mail id</a>
			</form>
		    </div>
		</div>
	    </div>
	</div>
	<div class="row" id="sendcode" <?php echo ($send)?"":"style='display:none'"; ?>>
	    <div class="col-sm-6 col-sm-offset-3">
		<div class="panel panel-default">
		    <div class="panel-body">
			<form method="post">
			    <div class="form-group">
				<label for="code-email">E-mail:</label>
				<input type="text" name="email" id="send-email" class="form-control" placeholder="Enter your e-mail id">
			    </div>
			    <button class="btn btn-success" type="submit">Send Password Reset Code</button>
			</form>
		    </div>
		</div>
	    </div>
	</div>
	<div class="row" id="confirm" <?php echo ($code)?"":"style='display:none'"; ?>>
	    <div class="col-sm-6 col-sm-offset-3">
		<div class="panel panel-default">
		    <div class="panel-body">
			<form method="post">
			    <div class="form-group">
				<label for="code-email">E-mail:</label>
				<input type="text" name="email" id="code-email" class="form-control" placeholder="Enter your e-mail id" <?php echo ($email)?"value='$email'":""; ?>>
			    </div>
			    <div class="form-group">
				<label for="code">Code:</label>
				<input type="text" name="code" id="code" class="form-control" placeholder="Enter the code sent by e-mail" <?php echo ($code && $code!==true)?"value='$code'":""; ?>>
			    </div>
			    <div class="form-group">
				<label for="pass">New Password:</label>
				<input type="password" name="pass" id="pass" class="form-control" placeholder="Enter your new password">
			    </div>
			    <div class="form-group">
				<label for="again">Confirm Password:</label>
				<input type="password" name="again" id="again" class="form-control" placeholder="Enter your password again">
			    </div>
			    <button class="btn btn-success" type="submit">Set Password</button>
			</form>
		    </div>
		</div>
	    </div>
	</div>
	<hr>
	<?php
	include("template/modals.php");
	include("template/footer.php");
	?>
	</div>
	<script>
	 $(document).ready(function(){
	     $("#reset-password").click(function(){
		 $("#login").hide();
		 $("#confirm").show();
	     });
	     $("#confirm-code").click(function(){
		 $("#login").hide();
		 $("#confirm").show();
	     });
	 });
	</script>
    </body>
</html>
