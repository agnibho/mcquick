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
var lock=true;

var paper=new Paper();
var questions=new Array();

var paper_id=-1;

var edit=-1;
var opt=4;
var multi=false;

$(document).ready(function(){
    $("#initial").show();
    $(".multi-true").hide();
    
    $("#allow_multiple").change(function(){
	if($(this).is(":checked")){
	    multi=true;
	    $(".multi-false").hide();
	    $(".multi-true").show();
	}
	else{
	    multi=false;
	    $(".multi-true").hide();
	    $(".multi-false").show();
	}
    });
    
    $("#time_hour").change(function(){
	if(i=parseInt($("#time_hour").val())){
	    if(i<=10){
		$("#time_hour").val(i);
	    }
	    else{
		alert("Time limit too long");
		$("#time_hour").val("");
	    }
	}
	else{
	    alert("Please enter a number as hour");
	    $("#time_hour").val("");
	}
    });
    $("#time_min").change(function(){
	if(i=parseInt($("#time_min").val())){
	    if(i<59){
		$("#time_min").val(i);
	    }
	    else{
		alert("Minute can not be more than 59");
		$("#time_min").val("");
	    }
	}
	else{
	    alert("Please enter a number as minute");
	    $("#time_min").val("");
	}
    });
    
    $("#choose-opt-4").click(function(){
	opt=4;
	$("#choose-opt-num").html("4 Options <span class='caret'></span>");
	$("#opt-5").hide();
	if(questions.length>0){
	    if(questions[0].options.length==5){
		for(i=0; i<questions.length; i++){
		    questions[i].options.pop();
		}
	    }
	}
	refresh();
	check();
    });
    $("#choose-opt-5").click(function(){
	opt=5;
	$("#choose-opt-num").html("5 Options <span class='caret'></span>");
	$("#opt-5").show();
	if(questions.length>0){
	    if(questions[0].options.length==4){
		for(i=0; i<questions.length; i++){
		    questions[i].options.push("");
		}
	    }
	}
	refresh();
	check();
    });
    
    $("#save_changes").click(function(){
	new_q=new Question();
	stem=$("#new_stem").val();
	options=[$("#new_a").val(), $("#new_b").val(), $("#new_c").val(), $("#new_d").val()];
	if(opt==5){
	    options.push($("#new_e").val());
	}
	if(multi){
	    correct=$("#new_options :checkbox:checked").map(function(){
		return $(this).data("val");
	    }).get();
	}
	else{
	    correct=$("#new_options :radio:checked").data("val");
	}
	comment=$("#new_comment").val();
	if(correct==undefined || correct.length==0){
	    alert("Please choose a correct answer");
	    return;
	}
	new_q.create(stem, options, correct, comment);
	if(edit>=0){
	    edit_question(new_q, edit);
	}
	else{
	    add_question(new_q);
	}
	$("#close_box").click();
    });
    
    $("#next_question").click(function(){
	$("#save_changes").click();
	$("#edit_box").one("hidden.bs.modal", function(){
	    $("#edit_box").modal("show");
	});
    });
    
    $("#edit_box").on("shown.bs.modal", function(e){
	edit=$(e.relatedTarget).data("idx");
	if(edit>=0){
	    $("#new_options :radio").prop("checked", false);
	    $("#new_options :checkbox").prop("checked", false);
	    $("#new_stem").val(questions[edit].get_stem());
	    $("#new_a").val(questions[edit].get_options()[0]);
	    $("#new_b").val(questions[edit].get_options()[1]);
	    $("#new_c").val(questions[edit].get_options()[2]);
	    $("#new_d").val(questions[edit].get_options()[3]);
	    if(opt==5){
		$("#new_e").val(questions[edit].get_options()[4]);
	    }
	    if(multi){
		ans=questions[edit].get_correct();
		for(i=0; i<ans.length; i++){
		    $("#check_"+ans.charAt(i)+"_2").prop("checked", true);
		}
	    }
	    else{
		ans=questions[edit].get_correct();
		if(ans.length==1){
		    $("#check_"+ans).prop("checked", true);
		}
	    }
	    $("#new_comment").val(questions[edit].get_comment());
	}
	else{
	    $("#new_options :radio").prop("checked", false);
	    $("#new_options :checkbox").prop("checked", false);
	    $("#new_stem").val("");
	    $("#new_a").val("");
	    $("#new_b").val("");
	    $("#new_c").val("");
	    $("#new_d").val("");
	    $("#new_e").val("");
	    $("#new_comment").val("");			
	}
    });
    
    $("#question_box").on("click", ".delete_question", function(e){
	e.preventDefault();
	delete_question($(e.target).data("idx"));
    });
    
    $("#question_box").on("click", ".move_up", function(e){
	e.preventDefault();
	idx=$(e.target).data("idx");
	if(idx>0){
	    switch_question(idx, idx-1);
	}
    });
    
    $("#question_box").on("click", ".move_down", function(e){
	e.preventDefault();
	idx=$(e.target).data("idx");
	if(idx<(questions.length-1)){
	    switch_question(idx, idx+1);
	}
    });
    
    $("#save_paper").click(function(){
	if(check()){
	    prepare();
	    if(paper.get_title().length<4){
		alert("Paper Title must be more than 4 letters");
	    }
	    else if(paper.get_time_ms()<60000){
		alert("Time limit must be more than 1 minute");
	    }
	    else{
		if(questions.length>0){
		    if(user.is_logged()){
			$("#block-wait").modal("show");
			if(paper_id>0){
			    $.post("ajax.php",
				   {
				       update:paper_id,
				       data:JSON.stringify(paper)
				   },
				   function(data, status){
				       $("#block-wait").modal("hide");
				       if(status=="success"){
					   if(JSON.parse(data)){
					       alert("The paper has been saved");
					       lock=false;
					       document.location="profile.php";
					   }
					   else{
					       alert("Failed to save");
					   }
				       }
				       else{
					   $("#connect-fail").modal("show");
				       }
				   });
			}
			else{
			    $.post("ajax.php",
				   {
				       save:JSON.stringify(paper)
				   },
				   function(data, status){
				       $("#block-wait").modal("hide");
				       if(status=="success"){
					   if(JSON.parse(data)){
					       alert("The paper has been saved");
					       lock=false;
					       document.location="profile.php";
					   }
					   else{
					       alert("Failed to save");
					   }
				       }
				       else{
					   $("#connect-fail").modal("show");
				       }
				   });
			}
		    }
		    else{
			alert("Please log in before saving paper");
			$("#log_in").click();
		    }
		}
		else{
		    alert("The paper is empty");
		}
	    }
	}
	else{
	    alert("The paper has some inconsistencies. Please check again.")
	}
    });
    
    $("#save_draft").click(function(){
	console.log(user);
	if(check()){
	    prepare();
	    if(questions.length>0){
		if(user.is_logged()){
		    $("#block-wait").modal("show");
		    $.post("ajax.php",
			   {
			       draft:JSON.stringify(paper)
			   },
			   function(data, status){
			       $("#block-wait").modal("hide");
			       if(status=="success"){
				   if(JSON.parse(data)){
				       alert("The draft has been saved");
				       lock=false;
				       document.location="profile.php";
				   }
				   else{
				       alert("Failed to save");
				   }
			       }
			       else{
				   $("#connect-fail").modal("show");
			       }
			   });
		}
		else{
		    alert("Please log in before saving draft");
		    $("#log_in").click();
		}
	    }
	    else{
		alert("Please add one or more questions");
	    }
	}
	else{
	    alert("The paper has some inconsistencies. Please check again.")
	}
    });
    
    $("#code_box").on("shown.bs.modal", function(){
	prepare();
	$("#show_code").val(JSON.stringify(paper));
    });
    
    $("#load_draft").click(function(){
	$("#block-wait").modal("show");
	$.get("ajax.php?draft=get", function(data, state){
	    $("#block-wait").modal("hide");
	    if(state=="success"){
		obj=JSON.parse(data);
		if(obj){
		    load_paper(obj);
		}
		else{
		    $("#not-found").modal("show");
		}
	    }
	    else{
		$("#connect-fail").modal("show");
	    }
	});
    });
    $("#del_draft").click(function(){
	$("#block-wait").modal("show");
	$.get("ajax.php?draft=del", function(data, state){
	    $("#block-wait").modal("hide");
	    if(state=="success"){
		if(JSON.parse(data)){
		    alert("Draft deleted");
		}
		else{
		    $("#not-found").modal("show");
		}
	    }
	    else{
		$("#connect-fail").modal("show");
	    }
	});
    });
    
    $("#select_file").on("change", function(e){
	open_file(e);
    });
    $("#open_file").click(function(){
	$("#select_file").click();
    });
    
    $(window).on("beforeunload", function(){
	if(lock){
	    return "You have not saved the paper. Are you sure you want to leave the page?";
	}
    });
    
});

function check(){
    flag=true;
    for(i=0; i<questions.length; i++){
	if(!multi){
	    if(questions[i].get_correct().length>1){
		flag=false;
		$("[data-index="+i+"]").prop("class", "text-danger");
	    }
	}
	if(opt<5){
	    if(questions[i].get_correct().indexOf("e")!=-1){
		flag=false;
		$("[data-index="+i+"]").prop("class", "text-danger");
	    }
	}
    }
    return flag;
}

function prepare(){
    time=[$("#time_hour").val(), $("#time_min").val()];
    paper.create(questions, $("#title").val(), $("#addinfo").val(), user.get_name(), $("#subject").val(), opt, $("#allow_multiple").is(":checked"), $("#negative_marking").prop("value"), time);
}

function add_question(new_q){
    questions.push(new_q);
    $("#question_box").append(display_question(new_q, questions.length));
}

function edit_question(edit_q, idx){
    questions[idx]=edit_q;
    elem=$("[data-index='"+idx+"']");
    elem.after(display_question(edit_q, idx+1));
    elem.remove();
}

function delete_question(idx){
    questions.splice(idx, 1);
    refresh();
}

function switch_question(idx_1, idx_2){
    buff=questions[idx_1];
    questions[idx_1]=questions[idx_2];
    questions[idx_2]=buff;
    refresh();
}

function open_file(e){
    reader=new FileReader();
    reader.readAsText(e.target.files[0]);
    reader.onload=function(f){
	paper_id=-1;
	load_paper(JSON.parse(f.target.result));
    }
}

function load_paper(data){
    paper.load(data);
    questions=paper.get_questions();
    if(paper.get_options()==5){
	$("#choose-opt-5").click();
    }
    $("#title").val(paper.get_title());
    $("#addinfo").val(paper.get_info());
    $("#subject").val(paper.get_subject());
    if(paper.get_multiple()){
	$("#allow_multiple").click();
    }
    $("#negative_marking").val(paper.get_negative());
    $("#time_hour").val(paper.get_time()[0]);
    $("#time_min").val(paper.get_time()[1]);
    refresh();
}

function refresh(){
    $("#question_box").html("");
    for(i=0; i<questions.length; i++){
	$("#question_box").append(display_question(questions[i], i+1));
    }
}

function display_question(q, i){
    html='<div data-index="'+(i-1)+'">'
	+'<div class="col-xs-1" style="margin:0px;padding:0px">'
	+'<div class="col-xs-6" style="margin:0px;padding:0px">'
	+'<h3 class="text-primary">'+i+'</h3>'
	+'</div>'
	+'<div class="col-xs-6" style="margin:0px;padding:0px">'
	+'<div>'
	+'<a href="#" title="Move Up" class="move_up text-success" data-idx="'+(i-1)+'"><span class="glyphicon glyphicon-arrow-up" data-idx="'+(i-1)+'"></span></a>'
	+'</div>'
	+'<div>'
	+'<a href="#" title="Delete" class="delete_question text-danger" data-idx="'+(i-1)+'"><span class="glyphicon glyphicon-remove" data-idx="'+(i-1)+'"></span></a>'
	+'</div>'
	+'<div>'
	+'<a href="#" title="Edit" class="text-warning" data-toggle="modal" data-target="#edit_box" data-idx="'+(i-1)+'"><span class="glyphicon glyphicon-pencil"></span></a>'
	+'</div>'
	+'<div>'
	+'<a href="#" title="Move Down" class="move_down text-success" data-idx="'+(i-1)+'"><span class="glyphicon glyphicon-arrow-down" data-idx="'+(i-1)+'"></span></a>'
	+'</div>'
	+'</div>'
	+'</div>'
	+'<div class="col-xs-11" style="margin:0px;padding:0px">'
	+'<div class="panel panel-info">'
	+'<div class="panel-heading">'
	+esc(q.get_stem())
	+'</div>'
	+'<div class="panel-body">'
	+'<table class="table">';
    if(q.correct.indexOf("a")!=-1){
	html=html+	'<tr class="success"><td>a.</td><td>'+esc(q.get_options()[0])+'</td></tr>';
    }
    else{
	html=html+	'<tr><td>a.</td><td>'+esc(q.get_options()[0])+'</td></tr>';
    }
    if(q.correct.indexOf("b")!=-1){
	html=html+	'<tr class="success"><td>b.</td><td>'+esc(q.get_options()[1])+'</td></tr>';
    }
    else{
	html=html+	'<tr><td>b.</td><td>'+esc(q.get_options()[1])+'</td></tr>';
    }
    if(q.correct.indexOf("c")!=-1){
	html=html+	'<tr class="success"><td>c.</td><td>'+esc(q.get_options()[2])+'</td></tr>';
    }
    else{
	html=html+	'<tr><td>c.</td><td>'+esc(q.get_options()[2])+'</td></tr>';
    }
    if(q.correct.indexOf("d")!=-1){
	html=html+	'<tr class="success"><td>d.</td><td>'+esc(q.get_options()[3])+'</td></tr>';
    }
    else{
	html=html+	'<tr><td>d.</td><td>'+esc(q.get_options()[3])+'</td></tr>';
    }
    if(opt==5){
	if(q.correct.indexOf("e")!=-1){
	    html=html+	'<tr class="success"><td>e.</td><td>'+esc(q.get_options()[4])+'</td></tr>';
	}
	else{
	    html=html+	'<tr><td>e.</td><td>'+esc(q.get_options()[4])+'</td></tr>';
	}
    }
    html=html+	'</table>'
	+'<div class="well" style="white-space:pre-wrap">'+esc(q.get_comment())+'</div>'
	+'</div>'
	+'</div>'
	+'</div>'
	+'</div>';
    return html;
}

function esc(input){
    return $("<div/>").text(input).html();
}
