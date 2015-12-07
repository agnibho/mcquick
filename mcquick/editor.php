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
	<title>Editor | MCQuick</title>
	<?php include("template/head.php"); ?>
	<script src="editor.js"></script>
    </head>
    <body>
	<?php include("template/heading.php"); ?>
	<script>console.log(user)</script>
	<div class="row">
	    <div class="col-sm-4">
		<div class="panel panel-info">
		    <div class="panel-body">
			<form role="form">
			    <div class="form-group">
				<label for="title">Paper Title:</label>
				<input class="form-control" type="text" id="title"/>
			    </div>
			    <div class="form-group">
				<label for="addinfo">Additional Information:</label>
				<textarea class="form-control" id="addinfo"></textarea>
			    </div>
			    <div class="form-group">
				<label for="subject">Subject:</label>
				<select class="form-control" id="subject">
				    <option>Mixed</option>
				    <option>Anatomy</option>
				    <option>Biochemistry</option>
				    <option>Community Medicine</option>
				    <option>Dermatology</option>
				    <option>Forensic Medicine</option>
				    <option>Medicine</option>
				    <option>Microbiology</option>
				    <option>Obstetrics/Gynaecology</option>
				    <option>Ophthalmology</option>
				    <option>Orthopaedics</option>
				    <option>Otorhinolaryngology</option>
				    <option>Paediatrics</option>
				    <option>Pathology</option>
				    <option>Pharmacology</option>
				    <option>Physiology</option>
				    <option>Surgery</option>
				</select>
			    </div>
			    <div class="checkbox">
				<label>
				    <input type="checkbox" id="allow_multiple"/> Allow Multiple Answers
				</label>
			    </div>
			    <div class="form-group">
				<label for="negative_marking">Negative Markings:</label>
				<select class="form-control" id="negative_marking">
				    <option value="0" selected>0%</option>
				    <option value="25">25%</option>
				    <option value="33">33%</option>
				    <option value="50">50%</option>
				    <option value="100">100%</option>
				</select>
			    </div>
			    <div class="form-group">
				<div class="col-sm-4">
				    <label class="pull-right">Time Limit:</label>
				</div>
				<div class="col-sm-4">
				    <input class="form-control" type="text" id="time_hour" placeholder="hour"/>
				</div>
				<div class="col-sm-4">
				    <input class="form-control" type="text" id="time_min" placeholder="minute"/>
				</div>
			    </div>
			</form>
		    </div>
		</div>
	    </div>
	    
	    <div class="col-sm-8">
		<div class="panel panel-info">
		    <div class="panel-body">
			<div id="question_box">
			    
			</div>
			
			<button class="btn btn-success" id="new_question" data-toggle="modal" data-target="#edit_box" type="button" data-edit="-1">Add a New Question</button>
			
			<span class="dropdown pull-right">
			    <button class="btn btn-warning dropdown-toggle" type="button" id="choose-opt-num" data-toggle="dropdown">4 Options <span class="caret"></span></button>
			    <ul class="dropdown-menu" role="menu" aria-labelledby="choose-opt-num">
				<li role="presentation" id="choose-opt-4"><a role="menuitem" tabindex="-1" href="#">4 options</a></li>
				<li role="presentation" id="choose-opt-5"><a role="menuitem" tabindex="-1" href="#">5 options</a></li>
			    </ul>
			</span>
			
			<div class="modal fade" id="edit_box" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
			    <div class="modal-dialog">
				<div class="modal-content">
				    <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4>Edit the question:</h4>
					<textarea class="form-control" placeholder="Enter the stem question" id="new_stem"></textarea>
				    </div>
				    <div class="modal-body" id="new_options">
					<h4>Enter the options:</h4>
					<div class="input-group">
					    <span class="input-group-addon">a</span>
					    <input type="text" class="form-control" id="new_a"/>
					    <span class="input-group-addon multi-false"><input type="radio" name="new_correct" id="check_a" data-val="a"/></span>
					    <span class="input-group-addon multi-true"><input type="checkbox" name="new_correct" id="check_a_2" data-val="a"/></span>
					</div>
					<div class="input-group">
					    <span class="input-group-addon">b</span>
					    <input type="text" class="form-control" id="new_b"/>
					    <span class="input-group-addon multi-false"><input type="radio" name="new_correct" id="check_b" data-val="b"/></span>
					    <span class="input-group-addon multi-true"><input type="checkbox" name="new_correct" id="check_b_2" data-val="b"/></span>
					</div>
					<div class="input-group">
					    <span class="input-group-addon">c</span>
					    <input type="text" class="form-control" id="new_c"/>
					    <span class="input-group-addon multi-false"><input type="radio" name="new_correct" id="check_c" data-val="c"/></span>
					    <span class="input-group-addon multi-true"><input type="checkbox" name="new_correct" id="check_c_2" data-val="c"/></span>
					</div>
					<div class="input-group">
					    <span class="input-group-addon">d</span>
					    <input type="text" class="form-control" id="new_d"/>
					    <span class="input-group-addon multi-false"><input type="radio" name="new_correct" id="check_d" data-val="d"/></span>
					    <span class="input-group-addon multi-true"><input type="checkbox" name="new_correct" id="check_d_2" data-val="d"/></span>
					</div>
					<div class="input-group" id="opt-5" style="display:none">
					    <span class="input-group-addon">e</span>
					    <input type="text" class="form-control" id="new_e"/>
					    <span class="input-group-addon multi-false"><input type="radio" name="new_correct" id="check_e" data-val="e"/></span>
					    <span class="input-group-addon multi-true"><input type="checkbox" name="new_correct" id="check_e_2" data-val="e"/></span>
					</div>
					<h4>Comment:</h4>
					<textarea class="form-control" placeholder="Subject/Chapter or Explanation" id="new_comment"></textarea>
				    </div>
				    <div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal" id="close_box">Close</button>
					<button type="button" class="btn btn-primary" id="save_changes">Save changes</button>
					<button type="button" class="btn btn-success" id="next_question">Next Question</button>
				    </div>
				</div>
			    </div>
			</div>
			
		    </div>
		</div>
	    </div>
	</div>
	
	<div id="menubar" class="btn-group" style="position:fixed;bottom:0;right:0">
	    <button class="btn btn-lg btn-primary" type="button" id="save_paper">Save Paper</button>
	    <button class="btn btn-lg btn-success" type="button" id="save_draft">Save Draft</button>
	    <button class="btn btn-lg btn-default" type="button" id="view_code" data-toggle="modal" data-target="#code_box">View Code</button>
	</div>
	
	<div class="modal fade" id="code_box" tabindex="-1" role="dialog" aria-hidden="true">
	    <div class="modal-dialog">
		<div class="modal-content">
		    <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			<h4 class="modal-title">JSON code for the Question Paper:</h4>
		    </div>
		    <div class="modal-body">
			<textarea class="form-control" rows="6" id="show_code" readonly></textarea>
		    </div>
		    <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		    </div>
		</div>
	    </div>
	</div>
	
	<div class="modal fade" id="draft_confirm" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	    <div class="modal-dialog">
		<div class="modal-content">
		    <div class="modal-header">
			<h4>You have a saved draft.</h4>
		    </div>
		    <div class="modal-footer">
			<button type="button" class="btn btn-danger" data-dismiss="modal" id="del_draft">Delete Draft</button>
			<button type="button" class="btn btn-primary" data-dismiss="modal" id="load_draft">Load Draft</button>
		    </div>
		</div>
	    </div>
	</div>
	
	<div style="display:none">
	    <input type="file" id="select_file"/>
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
	     else{
		 $("#block-wait").modal("show");
		 $.get("ajax.php?draft=check", function(data, state){
		     $("#block-wait").modal("hide");
		     if(state=="success"){
			 if(JSON.parse(data)){
			     $("#draft_confirm").modal("show");
			 }
		     }
		     else{
			 $("#connect-fail").modal("show");
		     }
		 });
	     }
	     //var user=new User();
	     <?php
	     /* if(isSet($_SESSION["user"])){
		echo "user.load(".$_SESSION['user'].", '".$_SESSION['name']."');";
		} */
	     ?>
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
