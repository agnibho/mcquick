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
define("URL", "http://app/mcquick/"); //URL to access MCQuick
define("HOST", "localhost"); //MYSQL host name
define("USER", "root"); //MYSQL username
define("PASS", "data"); //MYSQL password
define("DTBS", "mcquick"); //MYSQL database name
class DB{
    private static $db;
    private static $sql=false;
    
    public static function get_sql(){
	self::$sql=new mysqli(HOST, USER, PASS, DTBS);
	return self::$sql;
    }
    
    public static function close(){
	if(self::$sql!==false){
	    self::$sql->close();
	}
    }
}
?>
