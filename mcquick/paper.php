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
	<title>Papers | MCQuick</title>
	<?php include("template/head.php"); ?>
    </head>
    <body>
	<?php include("template/heading.php"); ?>
	<div class="container">
	    <div class="text-primary">
		<div class="jumbotron">
		    <h2>MCQuick Question Papers</h2>
		</div>
	    </div>
	    <form class="form-inline col-sm-offset-4" role="search">
		<input type="text" class="form-control" placeholder="Search papers" id="text"/>
		<select class="form-control" id="by">
		    <option value="title">Paper Title</option>
		    <option value="name">Compiled by</option>
		    <option value="subject">Subject</option>
		</select>
		<button type="button" class="btn btn-success" id="search">Search</button>
	    </form>
	    <hr>
	    <table class="table table-hover" id="paper_list">
		<tr><th>Title</th><th>Compiled by</th><th>Subject</th><th>Date</th></tr>
	    </table>
	    <div class="text-center">
		<ul class="pagination" id="page">
		</ul>
	    </div>
	</div>
	
	<script>
	 var page=1;
	 var search="";
	 var term="";
	 $(document).ready(function(){
	     get_data();
	     $("#search").click(function(){
		 search=$("#by").val();
		 term=$("#text").val().trim();
		 get_data();
	     });
	     $("#paper_list").on("click", "tr.linked", function(){
		 document.location="index.php?paper="+$(this).data("id");
	     });
	     $("#page").on("click", ".nav-page", function(){
		 page=$(this).data("page");
		 get_data();
	     });
	 });
	 function get_data(){
	     query="list="+page;
	     if(term.length>0){
		 switch(search){
		     case "title":
			 query="search=title&term="+term+"&list="+page;
			 break;
		     case "name":
			 query="search=name&term="+term+"&list="+page;
			 break;
		     case "subject":
			 query="search=subject&term="+term+"&list="+page;
			 break;
		 }
	     }
	     $("#block-wait").modal("show");
	     $.get("ajax.php?"+query, function(data, status){
		 $("#block-wait").modal("hide");
		 if(status=="success"){
		     obj=JSON.parse(data);
		     papers=obj[0];
		     num=obj[1];
		     $("#paper_list").html('<tr><th>Paper Title</th><th>Compiled by</th><th>Subject</th><th>Date</th></tr>');
		     for(i=0; i<papers.length; i++){
			 date=new Date(0);
			 date.setSeconds(papers[i]['time']);
			 $("#paper_list").append("<tr class='linked' data-id='"+papers[i]['id']+"' style='cursor:pointer'><td>"+esc(papers[i]['title'])+"</th><td>"+papers[i]['user']+"</td><td>"+esc(papers[i]['subject'])+"</td><td>"+date.toLocaleDateString()+"</td></a></tr>");
		     }
		     $("#page").html("");
		     for(i=1; i<=num/10+1; i++){
			 if(i==page){
			     $("#page").append('<li class="active"><a href="#" class="nav-page" data-page="'+i+'">'+i+'</a></li>');
			 }
			 else{
			     $("#page").append('<li><a href="#" class="nav-page" data-page="'+i+'">'+i+'</a></li>');
			 }
		     }
		 }
		 else{
		     $("#connect-fail").modal("show");
		 }
	     });
	 }
	 
	 function esc(input){
	     return $("<div/>").text(input).html();
	 }
	</script>
	<hr>
	<?php
	include("template/modals.php");
	include("template/footer.php");
	?>
	</div>
    </body>
</html>

