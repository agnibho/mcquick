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
function User(){
    this.id=false;
    this.name="Anonymous";
    this.email="";
    
    this.load=function(id, name){
	this.id=id;
	this.name=name;
    }
    
    this.is_logged=function(){
	if(this.id){
	    return true;
	}
	else{
	    return false;
	}
    }
    
    this.set_user=function(id, name, email){
	this.id=id;
	this.name=name;
	this.email=email;
    }
    
    this.log_out=function(){
	this.id=false;
	this.name="Anonymous";
	this.email="";
    }
    
    this.get_id=function(){
	return this.id;
    }
    
    this.get_name=function(){
	return this.name;
    }
} 
