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
if(!isSet($_SESSION["user"])){
    header("Location: login.php");
}
?>
<!DOCTYPE html>
<html>
    <head>
	<title>Profile | MCQuick</title>
	<?php include("template/head.php"); ?>
    </head>
    <body>
	<?php include("template/heading.php"); ?>
	<div class="row">
	    <div class="text-primary">
		<div class="jumbotron">
		    <h2 id="name">Your Profile</h2>
		</div>
	    </div>
	    <div class="col-sm-8">
		<div class="panel panel-success">
		    <div class="panel-heading">
			Papers
		    </div>
		    <div class="panel-body">
			<table class="table" id="paper">
			    <tr><th>Title</th><th>Date</th><th>Subject</th><th>Action</th></tr>
			</table>
		    </div>
		</div>
	    </div>
	    <div class="col-sm-4">
		<div class="panel panel-success">
		    <div class="panel-heading">
			Statistics
		    </div>
		    <div class="panel-body">
			<table class="table" id="stat">
			    <tr><th>Date</th><th>Paper ID</th><th>Marks</th></tr>
			</table>
		    </div>
		</div>
	    </div>
	    
	    <div class="modal fade" id="link_box" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
		    <div class="modal-content">
			<div class="modal-header">
			    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			    <h4>Direct link for the selected paper:</h4>
			</div>
			<div class="modal-body">
			    <form>
				<input class="form-control" type="text" id="direct_link" readonly>
			    </form>
			</div>
		    </div>
		</div>
	    </div>
	    <div class="modal fade" id="confirm" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
		    <div class="modal-content">
			<div class="modal-header">
			    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			    <h4>Are you sure you want to delete the paper?</h4>
			</div>
			<div class="modal-footer">
			    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
			    <button type="button" class="btn btn-danger" data-dismiss="modal" id="delete-confirm">Delete</button>
			</div>
		    </div>
		</div>
	    </div>
	</div>
	
	<script>
	 var del=false;
	 $(document).ready(function(){
	     $("#paper").on("click", ".link", function(){
		 $("#direct_link").val("<?php echo URL; ?>/index.php?paper="+$(this).data("id"));
		 $("#link_box").modal("show");
	     });
	     
	     $("#paper").on("click", ".del", function(){
		 del=$(this).data("id");
		 $("#confirm").modal("show");
	     });
	     $("#delete-confirm").click(function(){
		 del_paper(del);
	     });
	 });
	 function load(id, name){
	     $("#name").text(name);
	     
	     $("#block-wait").modal("show");
	     $.get("ajax.php?profile="+id, function(data, status){
		 $("#block-wait").modal("hide");
		 if(status=="success"){
		     obj=JSON.parse(data);
		     for(i=0; i<obj['paper'].length; i++){
			 date=new Date(0);
			 date.setSeconds(obj['paper'][i]['time']);
			 $("#paper").append("<tr><td>"+esc(obj['paper'][i]['title'])+"</td><td>"+date.toLocaleDateString()+"</td><td>"+esc(obj['paper'][i]['subject'])+"</td><td><a href='editor.php?paper="+obj['paper'][i]['id']+"' title='Edit'><span class='glyphicon glyphicon-pencil'></span></a> <a href='#' title='Get Link' data-id='"+obj['paper'][i]['id']+"' class='link'><span class='glyphicon glyphicon-link'></span></a> <a href='#' data-id='"+obj['paper'][i]['id']+"' class='del' title='Delete'><span class='glyphicon glyphicon-remove'></span></a></td></tr>");
		     }
		     for(i=0; i<obj['stat'].length; i++){
			 date=new Date(0);
			 date.setSeconds(obj['stat'][i]['time']);
			 $("#stat").append("<tr><td>"+date.toLocaleDateString()+"</th><td>"+obj['stat'][i]['paper']+"</td><td>"+JSON.parse(obj['stat'][i]['stat']).marks+"</td></tr>");
		     }
		 }
		 else{
		     $("#connect-fail").modal("show");
		 }
	     });
	 }
	 function del_paper(id){
	     $("#block-wait").modal("show");
	     $.post("ajax.php", {del: id}, function(data, status){
		 $("#block-wait").modal("hide");
		 if(status=="success"){
		     alert("The paper has been deleted.");
		     load(user.get_id(), user.get_name());
		 }
		 else{
		     $("#connect-fail").modal("show");
		 }
	     });
	 }
	 
	 function esc(input){
	     return $("<div/>").text(input).html();
	 }
	 load();
	</script>
	<hr>
	<?php
	include("template/modals.php");
	include("template/footer.php");
	?>
	</div>
    </body>
</html>
