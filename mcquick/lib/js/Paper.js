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
function Paper(){
    this.title="";
    this.info="";
    this.author="";
    this.subject="";
    this.options=4;
    this.multiple=false;
    this.negative=0;
    this.time=[0, 0];
    this.questions=new Array();
    
    this.create=function(questions, title, info, author, subject, options, multiple, negative, time){
	this.title=title;
	this.info=info;
	this.author=author;
	this.subject=subject;
	this.options=options;
	this.multiple=multiple;
	this.negative=negative;
	this.time=time;
	this.questions=questions;
    }
    
    this.load=function(obj){
	q_arr=new Array();
	for(i=0; i<obj.questions.length; i++){
	    q=new Question();
	    q.create(obj.questions[i].stem, obj.questions[i].options, obj.questions[i].correct, obj.questions[i].comment);
	    q_arr.push(q);
	}
	this.create(q_arr, obj.title, obj.info, obj.author, obj.subject, obj.options, obj.multiple, obj.negative, obj.time);
    }
    
    this.get_questions=function(){
	return this.questions;
    }
    this.get_title=function(){
	return this.title;
    }
    this.get_info=function(){
	return this.info;
    }
    this.get_author=function(){
	return this.author;
    }
    this.get_subject=function(){
	return this.subject;
    }
    this.get_options=function(){
	return this.options;
    }
    this.get_multiple=function(){
	return this.multiple;
    }
    this.get_negative=function(){
	return this.negative;
    }
    this.get_time=function(){
	return this.time;
    }
    this.get_time_ms=function(){
	return (this.time[0]*3600000+this.time[1]*60000);
    }
    
    this.evaluate=function(ans){
	ret={};
	ret.total=0;
	ret.right=0;
	ret.wrong=0;
	ret.skip=0;
	ret.marks=0;
	if(ans.length==this.questions.length){
	    if(this.multiple){
		for(i=0; i<this.questions.length; i++){
		    ret.total+=this.questions[i].get_correct().length;
		    for(j=0; j<ans[i].length; j++){
			if(this.questions[i].get_correct().indexOf(ans[i][j])>-1){
			    ret.right++;
			    ret.marks+=100;
			}
			else{
			    ret.wrong++;
			    ret.marks-=this.negative;
			}
		    }
		}
		ret.skip=ret.total-ret.right;
		ret.marks=ret.marks/ret.total;
	    }
	    else{
		ret.total=this.questions.length;
		for(i=0; i<this.questions.length; i++){
		    if(ans[i]){
			if(ans[i]==this.questions[i].get_correct()){
			    ret.right++;
			    ret.marks+=100;
			}
			else{
			    ret.wrong++;
			    ret.marks-=this.negative;
			}
		    }
		    else{
			ret.skip++;
		    }
		}
	    }
	    ret.marks=ret.marks/ret.total;
	    return ret;
	}
	return false;
    }
    
    function check_equal(ans, chk){
	if(ans.length!=chk.length){
	    return false;
	}
	if(ans instanceof Array){
	    ans.sort();
	}
	if(chk instanceof Array){
	    chk.sort();
	}
	flag=true;
	for(j=0; j<ans.length; j++){
	    if(ans[j]!=chk[j]){
		flag=false;
		break;
	    }
	}
	return flag;
    }
    
    function check_partial(ans, chk){
	if(ans.length>chk.length){
	    return false;
	}
	flag=true;
	for(j=0; j<ans.length; j++){
	    if(chk.indexOf(ans[j])==-1){
		flag=false;
		break;
	    }
	}
	return flag;
    }
} 
