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
var paper=new Paper();
var questions=new Array();
var empty={};
var is_empty=false;
var stat={};

var paper_id=-1;

var time={};
time.limit=0;
time.exceed=false;

$(document).ready(function(){
    $("#initial").show();
    
    $("#select_file").on("change", function(e){
	open_file(e);
	$("#initial").hide();
	$("#display").show();
    });
    $("#open_file").click(function(){
	$("#select_file").click();
    });
    
    $("#create_empty").click(function(){
	is_empty=true;
	empty.total=$("#empty_total").val();
	empty.opt=$("#empty_options").val();
	empty.multi=$("#empty_multiple").is(":checked");
	empty.neg=$("#empty_negative").val();
	
	for(i=0; i<empty.total; i++){
	    $("#show_question").append(display_empty(i+1));
	}
	$("#initial").hide();
	$("#display").show();
	time.start=new Date();
	time.clock=setInterval(display_clock, 100);
    });
    
    $("#submit_paper").click(function(){
	time.end=new Date();
	time.duration=new Date(time.end-time.start);
	clearInterval(time.clock);
	if(is_empty){
	    len=empty.total;
	    for(i=0; i<len; i++){
		$("#correct_sheet").append(display_empty((i+1), len));
	    }
	    $("#correct_box").modal("show");
	}
	else{
	    ans=new Array();
	    len=questions.length;
	    if(paper.get_multiple()){
		for(i=0; i<len; i++){
		    ans[i]=$("#q"+i+" :checkbox:checked").map(function(){
			return $(this).data("val");
		    }).get();
		}
	    }
	    else{
		for(i=0; i<len; i++){
		    ans[i]=$("#q"+i+" :radio:checked").data("val");
		}
	    }
	    stat=paper.evaluate(ans);
	    $("#result_total").text(stat.total);
	    $("#result_right").text(stat.right);
	    $("#result_wrong").text(stat.wrong);
	    $("#result_skipped").text(stat.skip);
	    $("#result_percent").text(stat.marks+"%");
	    if(time.exceed){
		$("#result_time").text("Exceeded");
	    }
	    else{
		$("#result_time").text(time.duration.getUTCHours()+":"+time.duration.getUTCMinutes()+":"+time.duration.getUTCSeconds());
	    }
	    
	    $("#display").hide();
	    $("#final").show();
	    $("#save_stat").show();
	    $("#view_answers").show();
	}
    });
    
    $("#check_empty").click(function(){
	$("#correct_box").modal("hide");
	ans=new Array();
	chk=new Array();
	len=empty.total;
	for(i=0; i<len; i++){
	    if(!empty.multi){
		ans[i]=$("#q"+i+" :radio:checked").data("val");
	    }
	    else{
		ans[i]=$("#q"+i+" :checkbox:checked").map(function(){
		    return $(this).data("val");
		}).get();
	    }
	}
	for(i=0; i<len; i++){
	    if(!empty.multi){
		chk[i]=$("#q"+(parseInt(i)+parseInt(len))+" :radio:checked").data("val");
	    }
	    else{
		chk[i]=$("#q"+(parseInt(i)+parseInt(len))+" :checkbox:checked").map(function(){
		    return $(this).data("val");
		}).get();
	    }
	}
	total=0;
	right=0;
	wrong=0;
	skip=0;
	marks=0;
	if(empty.multi){
	    for(i=0; i<chk.length; i++){
		total+=chk[i].length;
		for(j=0; j<ans[i].length; j++){
		    if(chk[i].indexOf(ans[i][j])>-1){
			right++;
			marks+=100;
		    }
		    else{
			wrong++;
			marks-=empty.neg;
		    }
		}
	    }
	    skip=total-right;
	    marks=marks/total;
	}
	else{
	    total=empty.total;
	    for(i=0; i<chk.length; i++){
		if(ans[i]){
		    if(ans[i]==chk[i]){
			right++;
			marks+=100;
		    }
		    else{
			wrong++;
			marks-=empty.neg;
		    }
		}
		else{
		    skip++;
		}
	    }
	    marks=marks/total;
	}
	$("#result_total").text(total);
	$("#result_right").text(right);
	$("#result_wrong").text(wrong);
	$("#result_skipped").text(skip);
	$("#result_percent").text(marks);
	if(time.exceed){
	    $("#result_time").text("Exceeded");
	}
	else{
	    $("#result_time").text(time.duration.getUTCHours()+":"+time.duration.getUTCMinutes()+":"+time.duration.getUTCSeconds());
	}
	
	$("#display").hide();
	$("#final").show();
	$("#save_stat").hide();
	$("#view_answers").hide();
    });
    
    $("#save_stat").click(function(){
	if(user.is_logged()){
	    $("#block-wait").modal("show");
	    $.post("ajax.php",
		   {
		       user:user.get_id(),
		       stat:stat,
		       paper:paper_id
		   },
		   function(data, status){
		       $("#block-wait").modal("hide");
		       if(status=="success"){
			   if(JSON.parse(data)){
			       alert("Saved to profile");
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
	    alert("Please log in before saving stat");
	    $("#log_in_providers").modal("show");
	}
    });
    
    $("#solution_box").on("shown.bs.modal", function(){
	$("#solved_answers").text("");
	for(i=0; i<questions.length; i++){
	    $("#solved_answers").append(display_solution(questions[i], i+1));
	}
    });
    
    $("#toggle_clock").click(function(){
	$("#text_clock").toggle();
	if($("#toggle_clock").text()=="Show Clock"){
	    $("#toggle_clock").text("Hide Clock");
	}
	else{
	    $("#toggle_clock").text("Show Clock");
	}
    });
    
    $("#time_up_submit").click(function(){
	$("#submit_paper").click();
    });
    
    $("#time_up_continue").click(function(){
	time.exceed=true;
    });
});

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
    $("#show_title").text(paper.get_title());
    $("#show_author").text(paper.get_author());
    $("#show_subject").text(paper.get_subject());
    $("#show_info").text(paper.get_info());
    for(i=0; i<questions.length; i++){
	$("#show_question").append(display_question(questions[i], i+1));
    }
    time.limit=paper.get_time_ms();
    time.start=new Date();
    time.end=new Date(time.start.getTime()+paper.get_time_ms());
    time.clock=setInterval(display_clock, 100);
}

function display_question(q, i){
    if(paper.get_multiple()){
	html='<div class="col-xs-1" style="margin:0px;padding:0px">'
	    +'<h2 class="text-primary">'+i+'</h2>'
	    +'</div>'
	    +'<div class="col-xs-11" style="margin:0px;padding:0px">'
	    +'<div class="panel panel-success">'
	    +'<div class="panel-heading">'
	    +'<p class="lead">'+esc(q.get_stem())+'</p>'
	    +'</div>'
	    +'<div class="panel-body">'
	    +'<table class="table table-hover" id="q'+(i-1)+'">'
	    +'<tr><td><input type="checkbox" name="q'+i+'" data-val="a"/> <strong>a.</strong> </td><td>'+esc(q.get_options()[0])+'</td></tr>'
	    +'<tr><td><input type="checkbox" name="q'+i+'" data-val="b"/> <strong>b.</strong> </td><td>'+esc(q.get_options()[1])+'</td></tr>'
	    +'<tr><td><input type="checkbox" name="q'+i+'" data-val="c"/> <strong>c.</strong> </td><td>'+esc(q.get_options()[2])+'</td></tr>'
	    +'<tr><td><input type="checkbox" name="q'+i+'" data-val="d"/> <strong>d.</strong> </td><td>'+esc(q.get_options()[3])+'</td></tr>';
	if(paper.get_options()==5){
	    html=html+'<tr><td><input type="checkbox" name="q'+i+'" data-val="e"/> <strong>e.</strong> </td><td>'+esc(q.get_options()[4])+'</td></tr>';
	}
	html=html+'</table>'
	    +'</div>'
	    +'</div>'
	    +'</div>';
    }
    else{
	html='<div class="col-xs-1" style="margin:0px;padding:0px">'
	    +'<h2 class="text-primary">'+i+'</h2>'
	    +'</div>'
	    +'<div class="col-xs-11" style="margin:0px;padding:0px">'
	    +'<div class="panel panel-success">'
	    +'<div class="panel-heading">'
	    +'<p class="lead">'+esc(q.get_stem())+'</p>'
	    +'</div>'
	    +'<div class="panel-body">'
	    +'<table class="table table-hover" id="q'+(i-1)+'">'
	    +'<tr><td><input type="radio" name="q'+i+'" data-val="a"/> <strong>a.</strong> </td><td>'+esc(q.get_options()[0])+'</td></tr>'
	    +'<tr><td><input type="radio" name="q'+i+'" data-val="b"/> <strong>b.</strong> </td><td>'+esc(q.get_options()[1])+'</td></tr>'
	    +'<tr><td><input type="radio" name="q'+i+'" data-val="c"/> <strong>c.</strong> </td><td>'+esc(q.get_options()[2])+'</td></tr>'
	    +'<tr><td><input type="radio" name="q'+i+'" data-val="d"/> <strong>d.</strong> </td><td>'+esc(q.get_options()[3])+'</td></tr>';
	if(paper.get_options()==5){
	    html=html+'<tr><td><input type="radio" name="q'+i+'" data-val="e"/> <strong>e.</strong> </td><td>'+esc(q.get_options()[4])+'</td></tr>';
	}
	html=html+'</table>'
	    +'</div>'
	    +'</div>'
	    +'</div>';
    }
    return html;
}

function display_solution(q, i){
    html='<div>'
	+'<div class="col-xs-1" style="margin:0px;padding:0px">'
	+'<div class="col-xs-6" style="margin:0px;padding:0px">'
	+'<h3 class="text-primary">'+i+'</h3>'
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
    if(q.get_options().length==5){
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

function display_empty(i, l){
    i=parseInt(i);
    l=typeof l!=="undefined"?parseInt(l):0;
    if(empty.multi){
	html='<div class="col-xs-12" style="margin:0px;padding:0px">'
	    +'<div class="panel panel-success">'
	    +'<table class="table" id="q'+(i+l-1)+'">'
	    +'<tr>'
	    +'<th>'+i+'</th>'
	    +'<td><input type="checkbox" name="q'+(i+l)+'" data-val="a"/> <strong>a.</strong></td>'
	    +'<td><input type="checkbox" name="q'+(i+l)+'" data-val="b"/> <strong>b.</strong></td>'
	    +'<td><input type="checkbox" name="q'+(i+l)+'" data-val="c"/> <strong>c.</strong></td>'
	    +'<td><input type="checkbox" name="q'+(i+l)+'" data-val="d"/> <strong>d.</strong></td>';
	if(empty.opt==5){
	    html=html+'<td><input type="checkbox" name="q'+(i+l)+'" data-val="e"/> <strong>e.</strong></td>';
	}
	html=html+'</tr>'
	    +'</table>'
	    +'</div>'
	    +'</div>'
	    +'</div>';
	return html;
    }
    else{
	html='<div class="col-xs-12" style="margin:0px;padding:0px">'
	    +'<div class="panel panel-success">'
	    +'<table class="table" id="q'+(i+l-1)+'">'
	    +'<tr>'
	    +'<th>'+i+'</th>'
	    +'<td><input type="radio" name="q'+(i+l)+'" data-val="a"/> <strong>a.</strong></td>'
	    +'<td><input type="radio" name="q'+(i+l)+'" data-val="b"/> <strong>b.</strong></td>'
	    +'<td><input type="radio" name="q'+(i+l)+'" data-val="c"/> <strong>c.</strong></td>'
	    +'<td><input type="radio" name="q'+(i+l)+'" data-val="d"/> <strong>d.</strong></td>';
	if(empty.opt==5){
	    html=html+'<td><input type="radio" name="q'+(i+l)+'" data-val="e"/> <strong>e.</strong></td>';
	}
	html=html+'</tr>'
	    +'</table>'
	    +'</div>'
	    +'</div>'
	    +'</div>';
	return html;
    }
}

display_clock=function(){
    curr=new Date();
    if(time.limit>0){
	ms=time.end-curr;
	if(ms>0){
	    dt=new Date(ms);
	    $("#text_clock").html("<p class='lead'>"+dt.getUTCHours()+":"+dt.getUTCMinutes()+":"+dt.getUTCSeconds()+"</p>");
	}
	else{
	    clearInterval(time.clock);
	    $("#time_up").modal("show");
	}
    }
    else{
	ms=curr-time.start;
	dt=new Date(ms);
	$("#text_clock").html("<p class='lead'>"+dt.getUTCHours()+":"+dt.getUTCMinutes()+":"+dt.getUTCSeconds()+"</p>");
    }
}

function esc(input){
    return $("<div/>").text(input).html();
}
