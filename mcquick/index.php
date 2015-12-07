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
	<title>MCQuick</title>
	<?php include("template/head.php"); ?>
	<script src="script.js"></script>
    </head>
    <body>
	<?php include("template/heading.php"); ?>
	<div id="initial" style="display:none">
	    <div class="col-sm-6">
		<div class="panel panel-info">
		    <div class="panel-heading">
			<h2>MCQuick Papers</h2>
		    </div>
		    <div class="panel-body">
			<div class="panel panel-success" style="margin-bottom:0px">
			    <div class="panel-heading">
				<h3>Create a New Paper</h3>
			    </div>
			    <div class="panel-body">
				<a href="editor.php" class="btn btn-primary btn-lg btn-block">Create Paper</a>
			    </div>
			</div>
			<div class="panel panel-success">
			    <div class="panel-heading">
				<h3>Solve Online Papers</h3>
			    </div>
			    <div class="panel-body">
				<a class="btn btn-success btn-lg btn-block" href="paper.php">Open Papers</a>
			    </div>
			</div>
			<div class="panel panel-info">
			    <div class="panel-heading">
				<h3>Open file from your computer</h3>
			    </div>
			    <div class="panel-body">
				<input type="file" id="file" style="display:none"/>
				<button class="btn btn-warning btn-lg btn-block" id="open_file">Load File</button>
				<input type="file" id="select_file" style="display:none"/>
			    </div>
			</div>
		    </div>
		</div>
	    </div>
	    <div class="col-sm-6">
		<div class="panel panel-success">
		    <div class="panel-heading">
			<h2>Create an empty sheet</h2>
		    </div>
		    <div class="panel-body">
			<form role="form">
			    <div class="form-group">
				<label for="number">Total Number of Questions:</label>
				<input class="form-control" type="text" id="empty_total" value="4"/>
			    </div>
			    <div class="form-group">
				<label for="first-index">First index of the questions:</label>
				<input class="form-control" type="text" id="empty_index" value="1"/>
			    </div>
			    <div class="form-group">
				<label for="option-number">Number of Options:</label>
				<select class="form-control" id="empty_options">
				    <option selected>4</option>
				    <option>5</option>
				</select>
			    </div>
			    <div class="checkbox">
				<label>
				    <input type="checkbox" id="empty_multiple"/> Allow Multiple Answers
				</label>
			    </div>
			    <div class="form-group">
				<label for="negative-marking">Negative Markings:</label>
				<select class="form-control" id="empty_negative">
				    <option value="0" selected>0%</option>
				    <option value="25">25%</option>
				    <option value="33">33%</option>
				    <option value="50">50%</option>
				    <option value="100">100%</option>
				</select>
			    </div>
			    <br>
			    <hr>
			    <button type="button" class="btn btn-success btn-lg btn-block" id="create_empty">Create Sheet</button>
			</form>
		    </div>
		</div>
	    </div>
	</div><!--initial-->
	
	<div id="display" style="display:none" class="col-xs-12">
	    <div class="panel panel-info">
		<div class="panel-heading">
		    <h4>Paper Info</h4>
		</div>
		<div class="panel-body">
		    <form class="form-horizontal" role="form">
			<div class="col-sm-6">
			    <div class="form-group">
				<label class="col-sm-4 control-label">Paper Title</label>
				<div class="col-sm-8">
				    <p class="form-control-static" id="show_title"></p>
				</div>
			    </div>
			    <div class="form-group">
				<label class="col-sm-4 control-label">Compiled by</label>
				<div class="col-sm-8">
				    <p class="form-control-static" id="show_author"></p>
				</div>
			    </div>
			    <div class="form-group">
				<label class="col-sm-4 control-label">Subject</label>
				<div class="col-sm-8">
				    <p class="form-control-static" id="show_subject"></p>
				</div>
			    </div>
			</div>
			<div class="col-sm-6">
			    <div class="form-group">
				<label class="control-label">Additional Info</label>
				<p class="form-control-static" id="show_info" style="white-space:pre-wrap"></p>
			    </div>
			</div>
		    </form>
		</div>
	    </div>
	    <span id="show_question"></span>
	    <div class="col-sm-offset-3 col-sm-6">
		<button class="btn btn-success btn-lg btn-block" id="submit_paper">Submit Paper</button>
	    </div>
	    <div class="col-xs-2" style="position:fixed;top:50px;right:0px">
		<div class="panel panel-info">
		    <div class="panel-heading" id="toggle_clock" style="cursor:pointer">
			Hide Clock
		    </div>
		    <div class="panel-body" id="text_clock">
			0:0:0
		    </div>
		</div>
	    </div>
	    <div class="modal fade" id="time_up" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog">
		    <div class="modal-content">
			<div class="modal-header">
			    <h4 class="modal-title">Your Time is Up</h4>
			</div>
			<div class="modal-footer">
			    <button type="button" class="btn btn-default" data-dismiss="modal" id="time_up_continue">Continue Solving</button>
			    <button type="button" class="btn btn-success" data-dismiss="modal" id="time_up_submit">Submit Paper</button>
			</div>
		    </div>
		</div>
	    </div>
	</div><!--display-->
	
	<div class="modal fade" id="correct_box" tabindex="-1" role="dialog" aria-hidden="true">
	    <div class="modal-dialog">
		<div class="modal-content">
		    <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			<h4>Mark the correct answers:</h4>
		    </div>
		    <div class="modal-body" id="correct_sheet">
		    </div>
		    <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal" id="close_box">Close</button>
			<button type="button" class="btn btn-primary" id="check_empty">Check Answers</button>
		    </div>
		</div>
	    </div>
	</div>
	
	<div id="final" style="display:none">
	    <div class="panel panel-primary">
		<div class="panel-heading">
		    <h4>Paper statistics</h4>
		</div>
		<div class="panel panel-body">
		    <table class="table table-striped table-bordered">
			<tr>
			    <th>Total Questions</th>
			    <td id="result_total"></td>
			</tr>
			<tr>
			    <th>Correct Answers</th>
			    <td id="result_right"></td>
			</tr>
			<tr>
			    <th>Wrong Answers</th>
			    <td id="result_wrong"></td>
			</tr>
			<tr>
			    <th>Unattempted Questions</th>
			    <td id="result_skipped"></td>
			</tr>
			<tr>
			    <th>Percentage</th>
			    <td id="result_percent"></td>
			</tr>
			<tr>
			    <th>Time Taken</th>
			    <td id="result_time"></td>
			</tr>
		    </table>
		    <br>
		    <hr>
		    <div class="col-sm-4">
			<button class="btn btn-success btn-lg btn-block" id="view_answers" data-toggle="modal" data-target="#solution_box">View Correct Answers</button>
		    </div>
		    <div class="col-sm-4">
			<a href="index.php" class="btn btn-warning btn-lg btn-block">Solve Another Paper</a>
		    </div>
		    <div class="col-sm-4">
			<button class="btn btn-primary btn-lg btn-block" id="save_stat">Save Statistics to Profile</button>
		    </div>
		</div>
	    </div>
	</div><!--final-->
	</div>
	
	<div class="modal fade" id="solution_box" tabindex="-1" role="dialog" aria-hidden="true">
	    <div class="modal-dialog">
		<div class="modal-content">
		    <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			<h4>Correct Answers:</h4>
		    </div>
		    <div class="modal-body" id="solved_answers">
		    </div>
		    <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		    </div>
		</div>
	    </div>
	</div>
	
	<script>
	 $(document).ready(function(){
	     <?php
	     if(isSet($_GET["paper"])){
		 echo 'paper_id='.$_GET["paper"].';';
	     }
	     ?>
	     if(paper_id!=-1){
		 $("#block-wait").modal("show");
		 $.get("ajax.php?paper="+paper_id, function(data, state){
		     $("#block-wait").modal("hide");
		     if(state=="success"){
			 obj=JSON.parse(data);
			 if(obj){
			     load_paper(obj);
			     $("#initial").hide();
			     $("#display").show();
			 }
			 else{
			     $("#not-found").modal("show");
			 }
		     }
		     else{
			 $("#connect-fail").modal("show");
		     }
		 });
	     }
	 });
	</script>
	<hr>
	<?php
	include("template/modals.php");
	include("template/footer.php");
	?>
	</div>
    </body>
</html>
