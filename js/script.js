/*
    MCQuick- Solve MCQ
    Copyright (C) 2014  Agnibho Mondal
    
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
*/
function showAlert(msg){
	$("#alert").html("<div class='alert alert-danger alert-dismissable'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>"+msg+"</div>");
	$("#alert").focus();
}
//Class Sheet
function Sheet(tot, opt, multi, idx, neg, mcq){
	var total=parseInt(tot);
	var index=parseInt(idx);
	var option=parseInt(opt);
	var multiple=multi;
	var negative=parseInt(neg);
	var questions=new Array();
	if(mcq!=undefined){
		for(var i=0; i<total; i++){
			questions[i]=new Question(i+1, option, multiple, mcq[i]);
		}
	}
	else{
		for(var i=0; i<total; i++){
			questions[i]=new Question(i+1, option, multiple);
		}
	}
	
	this.getAnswerSheet=function(){	
		if(mcq!=undefined){
			var html="<ul class='list-group'>";
			for(var i=0; i<total; i++){
				html=html+"<li class='list-group-item list-group-item-success'>"+questions[i].getAnswerHTML()+"</li>";
			}
			html=html+"</ul><button type='button' id='sub' class='btn btn-lg btn-warning col-sm-offset-5'>Submit</button>";
		}
		else{
			var html="<table class='table table-hover'>";
			for(var i=0; i<total; i++){
				html=html+"<tr>"+questions[i].getAnswerHTML()+"</tr>";
			}
			html=html+"</table><button type='button' id='check' class='btn btn-lg btn-warning col-sm-offset-5'>Check</button>";
		}
		return html;
	}
	
	this.collectMarked=function(){
		for(var i=0; i<total; i++){
			questions[i].recordMarked();
		}
	}
	
	this.getCheckSheet=function(){
		var html="<table class='table'>";
		for(var i=0; i<total; i++){
			html=html+questions[i].getCheckHTML();
		}
		html=html+"</table><button type='button' id='eval' class='btn btn-lg btn-info col-sm-offset-5'>Evaluate</button>";
		return html;
	}
	
	this.collectChecked=function(){
		for(var i=0; i<total; i++){
			questions[i].recordChecked();
		}
	}
	
	this.getResult=function(){
		var result={total:0, right:0, wrong:0, unattempted:0, incomplete:0, unknown:0, marks:0, percent:0};
		result.total=total;
		var number=0;
		for(var i=0; i<total; i++){
			eval=questions[i].evaluate();
			if(eval.status=="right"){
				result.right++;
			}
			else if(eval.status=="wrong"){
				result.wrong++;
			}
			else if(eval.status=="unattempted"){
				result.unattempted++;
			}
			else if(eval.status=="incomplete"){
				result.incomplete++;
			}
			else if(eval.status=="unknown"){
				result.unknown++;
			}
			if(eval.status!="unknown"){
				number=number+eval.number;
			}
			result.marks=result.marks+eval.right-eval.wrong*negative/100;
		}
		result.percent=result.marks*100/number;
		return result;
	}
	
	this.getAllAnswers=function(){
		if(mcq!=undefined){
			var html="<div class='panel panel-info'><div class='panel-heading'><h2>Correct Answers</h2></div><div class='panel-body'>";
			for(var i=0; i<total; i++){
				html=html+questions[i].getCorrectAnswer();
			}
			html=html+"</div></div>";
			return html;
		}
		else{
			return false;
		}
	}
	
	//Class Question
	function Question(number, option, multiple, mcq){
		var marked="";
		var checked="";
		this.getAnswerHTML=function(){
			var html="";
			if(mcq!=undefined){
				html="<strong>"+number+". "+mcq.question+"</strong><ul class='list-group'>"
				if(multiple){
					html=html+
						"<li class='list-group-item'><input type='checkbox' id='"+number+"a'/> a. "+mcq.a+"</li>"+
						"<li class='list-group-item'><input type='checkbox' id='"+number+"b'/> b. "+mcq.b+"</li>"+
						"<li class='list-group-item'><input type='checkbox' id='"+number+"c'/> c. "+mcq.c+"</li>"+
						"<li class='list-group-item'><input type='checkbox' id='"+number+"d'/> d. "+mcq.d+"</li>";
					if(option==5){
						html=html+"<li class='list-group-item'><input type='checkbox' id='"+number+"e'/> e. "+mcq.e+"</li>";
					}
					html=html+"</ul>";
				}
				else{
					html=html+
						"<li class='list-group-item'><input type='radio' id='"+number+"a'/> a. "+mcq.a+"</li>"+
						"<li class='list-group-item'><input type='radio' id='"+number+"b'/> b. "+mcq.b+"</li>"+
						"<li class='list-group-item'><input type='radio' id='"+number+"c'/> c. "+mcq.c+"</li>"+
						"<li class='list-group-item'><input type='radio' id='"+number+"d'/> d. "+mcq.d+"</li>";
					if(option==5){
						html=html+"<li class='list-group-item'><input type='radio' id='"+number+"e'/> e. "+mcq.e+"</li>";
					}
					html=html+"</ul>";
				}
			}
			else{
				if(multiple){
					html="<th class='active'>"+number+"</th>"+
						"<td><input type='checkbox' id='"+number+"a'/> a</td>"+
						"<td><input type='checkbox' id='"+number+"b'/> b</td>"+
						"<td><input type='checkbox' id='"+number+"c'/> c</td>"+
						"<td><input type='checkbox' id='"+number+"d'/> d</td>";
					if(option==5){
						html=html+"<td><input type='checkbox' id='"+number+"e'/> e</td>";
					}
				}
				else{
					html="<th class='active'>"+number+"</th>"+
						"<td><input type='radio' id='"+number+"a'/>a</td>"+
						"<td><input type='radio' id='"+number+"b'/>b</td>"+
						"<td><input type='radio' id='"+number+"c'/>c</td>"+
						"<td><input type='radio' id='"+number+"d'/>d</td>";
					if(option==5){
						html=html+"<td><input type='radio' id='"+number+"e'/>e</td>";
					}
				}
			}
			return html;
		}
		this.recordMarked=function(){
			marked=record(number);
		}
		this.getCheckHTML=function(){
			var html="<tr style='background:bisque;'>"+
					"<td colspan='2' style='border:none;text-align:left;'>Question "+number+"</td>"+
					"<td colspan='"+(option-2)+"' style='border:none;text-align:right;'>Selected Answer: "+marked+"</td>"+
					"</tr>"+
					"<tr><td colspan='"+option+"' style='border:none;'>Select Correct Answer:</td></tr>"+
					"<tr>";
			if(multiple){
				html=html+
					"<td><input type='checkbox' id='"+number+"a'/> a</td>"+
					"<td><input type='checkbox' id='"+number+"b'/> b</td>"+
					"<td><input type='checkbox' id='"+number+"c'/> c</td>"+
					"<td><input type='checkbox' id='"+number+"d'/> d</td>";
				if(option==5){
					html=html+"<td><input type='checkbox' id='"+number+"e'/> e</td>";
				}
			}
			else{
				html=html+
					"<td><input type='radio' id='"+number+"a'/>a</td>"+
					"<td><input type='radio' id='"+number+"b'/>b</td>"+
					"<td><input type='radio' id='"+number+"c'/>c</td>"+
					"<td><input type='radio' id='"+number+"d'/>d</td>";
				if(option==5){
					html=html+"<td><input type='radio' id='"+number+"e'/>e</td>";
				}
			}
			html=html+"</tr>";
			return html;
		}
		this.recordChecked=function(){
			if(mcq!=undefined){
				checked=mcq.correct;
			}
			else{
				checked=record(number);
			}
		}
		this.evaluate=function(){
			var eval={right:0,wrong:0};
			if(checked!="blank"){
				eval.number=checked.length;
				if(marked!="blank"){
					if(multiple){
						if(marked==checked){
							eval.status="right";
							eval.right=marked.length;
						}
						else{
							for(var i=0; i<marked.length; i++){
								if(checked.indexOf(marked.charAt(i))!=-1){
									eval.right++;
								}
								else{
									eval.wrong++;
								}
							}
							if(eval.wrong>0){
								eval.status="wrong";
							}
							else{
								eval.status="incomplete";
							}
						}
					}
					else{
						if(marked==checked){
							eval.status="right";
							eval.right=1;
						}
						else{
							eval.status="wrong";
							eval.wrong=1;
						}
					}
				}
				else{
					eval.status="unattempted";
				}
			}
			else{
				eval.status="unknown";
			}
			return eval;
		}
		
		this.getCorrectAnswer=function(){
			var ans;
			if(mcq.correct=="a"){
				ans=mcq.a;
			}
			else if(mcq.correct=="b"){
				ans=mcq.b;
			}
			else if(mcq.correct=="c"){
				ans=mcq.c;
			}
			else if(mcq.correct=="d"){
				ans=mcq.d;
			}
			else if(mcq.correct=="e"){
				ans=mcq.e;
			}
			return "<div class='panel panel-success'><div class='panel-heading'>"+number+". "+mcq.question+"</div><div class='panel-body'>"+ans+"</div></div>";
		}
		
		function record(num){
			var chs=["a","b","c","d","e"];
			var mark="";
			for(var i=0; i<option; i++){
				if($("#"+num+chs[i]).is(":checked")){
					mark=mark+chs[i];
				}
			}
			if(mark==""){
				mark="blank";
			}
			return mark;
		}
	}
}

function check(total, option, index, negative){
	if(total.val()>0 && total.val()<=400){
		if(index.val()>0){
			if(option.val()==4 || option.val()==5){
				if(negative.val()==0||negative.val()==25||negative.val()==33){
					total.css("background", "");
					index.css("background", "");
					option.css("background", "");
					negative.css("background", "");
					return true;
				}
				else{
					nagative.css("background", "salmon");
					negative.focus();
					return false;
				}
			}
			else{
				option.css("background", "salmon");
				option.focus();
				return false;
			}
		}
		else{
			index.css("background", "salmon");
			index.focus();
			return false;
		}
	}
	else{
		total.css("background", "salmon");
		total.focus();
		return false;
	}
}

function openFile(file){
		var reader=new FileReader();
		var sheet;
		var result;
		reader.onload=function(event){
			data=event.target.result;
			try{
				paper=JSON.parse(data);
			}catch(e){
				showAlert("Sorry, the file you selected contains error. Details of the error: ["+e+"]");
				return;
			}
			if(tv4.validate(paper, schema)){
				if((paper.info.choices==4 || paper.info.choices==5) && (paper.info.negative==0 || paper.info.negative==25 || paper.info.negative==33)){
					sheet=new Sheet(paper.mcq.length, paper.info.choices, paper.info.multiple, 1, paper.info.negative, paper.mcq);
					$("#display").html(sheet.getAnswerSheet());
					$("#initial").hide(1000);
					$("#display").show(1000);
					time=new Watch(true);
					if(!time.setLimit(paper.info.time.hour, paper.info.time.min, paper.info.time.sec)){
						showAlert("Sorry, the Time Limit in the opened file is not valid. Time limit turned off.");
					}
					time.start();
				}
				else{
					showAlert("File could not be opened. The file contains invalid data.");
				}
			}
			else{
				showAlert("File format not supported. Please select a properly formatted file.");
			}
		}
		reader.readAsText(file.files[0]);
		$("#display").on("click", "#sub", function(){
			time.stop();
			$("#watch").hide(1000);
			$("#limit").hide(1000);
			$("#final").show(1000);
			sheet.collectMarked();
			sheet.collectChecked();
			result=sheet.getResult();
			$("#totalq").html(result.total);
			$("#right").html(result.right);
			$("#wrong").html(result.wrong);
			$("#unattempted").html(result.unattempted);
			$("#incomplete").html(result.incomplete);
			$("#unknown").html(result.unknown);
			$("#percent").html(result.percent+"%");
			$("#time").html(time.getTime());
			$("#display").html(sheet.getAllAnswers());
		});
	}

//Class Watch
function Watch(show){
	var startTime;
	var currTime;
	var endTime;
	var limit=false;
	var timer;
	var running=false;
	this.set=function(){
		startTime=new Date();
	}
	this.start=function(){
		if(show){
			$("#watch").fadeIn(1000);
		}
		else{
			$("#watch").css("display","none");
		}
		startTime=new Date();
		if(limit){
			endTime=new Date(startTime.getTime()+limit);
		}
		timer=setInterval(update, 1000);
		running=true;
	}
	this.stop=function(){
		if(running){
			clearInterval(timer);
			running=false;
			currTime=new Date();
			limit=false;
		}
		else{
			currTime=new Date();
		}
	}
	this.setLimit=function(h, m, s){
		if(h>=0 && m>=0 && m<60 && s>=0 && s<60){
			limit=(((h*60)+m)*60+s)*1000;
			$("#showLimit").html("Time Limit- "+h+":"+m+":"+s);
			$("#limit").fadeIn(1000);
			$("#removeLimit").click(function(){
				limit=false;
				$("#limit").fadeOut(1000);
			});
			return true;
		}
		else{
			return false;
		}
	}
	this.getTime=function(){
		return msToStr(currTime.getTime()-startTime.getTime());
	}
	function update(){
		currTime=new Date();
		$("#watch").html(msToStr(currTime.getTime()-startTime.getTime()));
		if(limit!=false){
			if(currTime>endTime){
				$("#check").click();
				$("#sub").click();
				limit=false;
			}
		}
	}
	function msToStr(ms){
		var h, m, s, str="";
		s=Math.floor(ms/1000);
		m=Math.floor(s/60);
		s=s%60;
		h=Math.floor(m/60);
		m=m%60;
		if(h<10){
			str="0"+h.toString();
		}
		else{
			str=h.toString();
		}
		if(m<10){
			str=str+":0"+m.toString();
		}
		else{
			srt=str+":"+m.toString();
		}
		if(s<10){
			str=str+":0"+s.toString();
		}
		else{
			str=str+":"+s.toString();
		}
		return str;
	}
}

$(document).ready(function(){
	var sheet;
	var time;
	$("#start").click(function(){
		if(check($("#total"), $("#option"), $("#index"), $("#neg"))){
			sheet=new Sheet($("#total").val(), $("#option").val(), $("#multi").is(":checked"), $("#index").val(), $("#neg").val());
			$("#display").html(sheet.getAnswerSheet());
			timeKeeping();
			$("#initial").hide(1000);
			$("#display").show(1000);
			$(window).scrollTop(0);
		}
	});
	$("#load").click(function(){
		file=new openFile($("#file")[0]);
	});
	$("#display").on("click", "#check", function(){
		sheet.collectMarked();
		time.stop();
		$("#watch").hide(1000);
		$("#limit").hide(1000);
		$("#display").hide();
		$("#display").html(sheet.getCheckSheet());
		$("#display").show(1000);
	});
	$("#display").on("click", "#eval", function(){
		sheet.collectChecked();
		$("#display").hide(1000);
		$("#final").show(1000);
		var result=sheet.getResult();
		$("#totalq").html(result.total);
		$("#right").html(result.right);
		$("#wrong").html(result.wrong);
		$("#unattempted").html(result.unattempted);
		$("#incomplete").html(result.incomplete);
		$("#unknown").html(result.unknown);
		$("#percent").html(result.percent+"%");
		$("#time").html(time.getTime());
	});
	$("#restart").click(function(){
		$("#final").hide(1000);
		$("#display").hide();
		$("#initial").show(1000);
	});
	
	function timeKeeping(){
		time=new Watch($("#timer").is(":checked"));
		if($("#limiter").is(":checked")){
			ret=time.setLimit($("#lim-h").val(), $("#lim-m").val(), $("#lim-s").val());
			if(!ret){
				showAlert("Sorry, the Time Limit you entered is not valid. Time limit turned off.");
			}
		}
		if($("#timer").is(":checked")||$("#limiter").is(":checked")){
			time.start();
		}
		else{
			time.set();
		}
	}
});