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
?>
<!DOCTYPE html>
<html>
    <head>
	<title>Help | MCQuick</title>
	<?php include("template/head.php"); ?>
    </head>
    <body>
	<?php include("template/heading.php"); ?>
	<div class="row">
	    <div class="text-primary">
		<div class="jumbotron">
		    <h2>MCQuick Help</h2>
		</div>
		<div class="row">
		    <div class="col-sm-8">
			<div class="panel panel-info">
			    <div class="panel-heading">
				<h4>Frequently Asked Questions</h4>
			    </div>
			    <div class="panel-body">
				<div class="panel panel-success">
				    <div class="panel-heading">What is MCQuick?</div>
				    <div class="panel-body">MCQuick is a Web Application for solving or building MCQ papers.</div>
				</div>
				<div class="panel panel-success">
				    <div class="panel-heading">How do I find a paper to solve?</div>
				    <div class="panel-body">Please visit the <a href="paper.php">paper.php</a> page. You will see a list of already prepared prapers. You can open any of those papers by clicking on them.</div>
				</div>
				<div class="panel panel-success">
				    <div class="panel-heading">How do I create a paper?</div>
				    <div class="panel-body">To create a paper, please visit <a href="editor.php">editor.php</a>. Fill out the Paper info form. Add questuions by clicking on the <em class="text-warning">Add_Question</em> button. When finished save the paper by clicking on the <em class="text-warning">Save_Paper</em> button.</div>
				</div>
				<div class="panel panel-success">
				    <div class="panel-heading">How do I get a direct link to a paper I created?</div>
				    <div class="panel-body">Please visit your <a href="profile.php">profile.php</a> page. You will see a list of all the papers you have created. Click on the <em class="text-warning"><span class="glyphicon glyphicon-link"></span></em> icon beside the paper you intend to share. A dialog box will open with the <em>direct link to the paper which you can share.</em></div>
				</div>
				<div class="panel panel-success">
				    <div class="panel-heading">How do I log in?</div>
				    <div class="panel-body"><p>You do not need to register to use MCQuick. MCQuick accepts log in via <em>Google</em> and <em>Facebook</em>.</p><p>To log in please click on  <em class="text-warning">Log in</em> on the top right corner of the page => Choose your log in provider (Google or Facebook) => You will be redirected to the respective providers website => When asked if you want to share your profile information please click yes (MCQuick only requires your User ID, Name and E-Mail ID).</p><p>You only need to log in when <em>Creating a paper</em> or when <em>saving your result</em> after solving a mcq paper.</p></div>
				</div>
			    </div>
			</div>
		    </div>
		    <div class="col-sm-4">
			<div class="panel panel-default">
			    <div class="panel-heading">
				<h4>Info</h4>
			    </div>
			    <div class="panel-body">
				<dl class="dl-horizontal">
				    <dt>MCQuick</dt>
				    <dd>Version 4.0</dd>
				    <dt>Developed by</dt>
				    <dd><a href="http://www.agnibho.com" style="text-decoration:none">Agnibho Mondal</a></dd>
				</dl>
			    </div>
			</div>
		    </div>
		</div>
	    </div>
	    <hr>
	</div>
	<hr>
	<?php
	include("template/modals.php");
	include("template/footer.php");
	?>
	</div>
    </body>
</html>

