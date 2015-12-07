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
function Question(){
    this.stem="";
    this.options=new Array();
    this.correct="";
    this.comment="";
    
    this.create=function(stem, options, correct, comment){
	this.stem=stem;
	this.options=options;
	this.correct=correct;
	this.comment=comment;
    }
    
    this.get_stem=function(){
	return this.stem;
    }
    this.get_options=function(){
	return this.options;
    }
    this.get_correct=function(){
	return this.correct;
    }
    this.get_comment=function(){
	return this.comment;
    }
} 
