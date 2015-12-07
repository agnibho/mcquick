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
<!-- User Log -->
<script>
 var user=new User();
 <?php echo (isSet($_SESSION["user"])?'user.load('.$_SESSION["user"].', "'.$_SESSION["name"].'");':'');?>
</script>

<!-- Navigation -->
<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container-fluid">
	<div class="navbar-header">
	    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navigation-menu">
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
	    </button>
	</div>
	
	<div class="collapse navbar-collapse" id="navigation-menu">
	    <ul class="nav navbar-nav">
		<li title="Solve MCQ papers"><a href="index.php">Home</a></li>
		<li title="Create new paper"><a href="editor.php">Editor</a></li>
		<li title="Browse all papers"><a href="paper.php">Papers</a></li>
		<li title="View your profile"><a href="profile.php">Profile</a></li>
		<li title="Read FAQ or Report a Problem"><a href="help.php">Help</a></li>
	    </ul>
	    <ul class="nav navbar-nav navbar-right">
		<button class="btn btn-success navbar-btn" id="log_in">Log in</button>
		<p class="navbar-text" id="user_log">You are signed in as <span id="user_name"></span></p>
		<a href="login.php?logout=true" class="btn btn-default navbar-btn" id="log_out">Log out</a>
	    </ul>
	</div>
    </div>
</nav>

<!-- Page Top -->
<div class="container">
    <div class="row">
	<div class="text-primary">
	    <div class="jumbotron">
		<h1>MCQuick</h1>
		<h2>Multiple Choice Question Solver...</h2>
	    </div>
	</div>
	
	<!-- JavaScript Check -->
	<div id="js-check">
	    <div class="alert alert-danger">
		<h3>MCQuick requires Javascript</h3>
	    </div>
	</div>
	<script>
	 $(document).ready(function(){
	     $("#js-check").hide();
	 });
	</script>
	
	<!-- Login Script -->
	<script>
	 var user=new User();
	 <?php
	 if(isSet($_SESSION["user"])){
	     echo "user.load(".$_SESSION['user'].", '".$_SESSION['name']."');";
	 }
	 ?>
	 $(document).ready(function(){
	     show_login();
	     $("#login-modal").click(function(){
		 $.post("ajax.php", {email:$("#email-modal").val(),password:$("#password-modal").val()}, function(data, status){
		     u=JSON.parse(data);
		     if(u.user){
			 user.load(u.user, u.name);
		     }
		     show_login();
		     $("#log_in_providers").modal("hide");
		 });
	     });
	 });
	 function show_login(){
	     if(user.is_logged()){
		 $("#user_log").show();
		 $("#log_out").show();
		 $("#log_in").hide();
		 $("#user_name").text(user.get_name());
	     }
	     else{
		 $("#user_log").hide();
		 $("#log_out").hide();
		 $("#log_in").show();
	     }
	     $("#log_in").click(function(){
		 $("#log_in_providers").modal("show");
	     });
	 }
	</script>
