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
require_once "lib/php/Paper.php";
require_once "lib/php/Record.php";
require_once "lib/php/User.php";
if(isSet($_POST["email"]) && isSet($_POST["password"])){
    if($_POST["email"]!=""&&$_POST["password"]!=""){
	$user=new User();
	if(!$user->load_user($_POST["email"], $_POST["password"])){
	    exit(json_encode(-1));
	}
	else{
	    $_SESSION["user"]=$user->get_id();
	    $_SESSION["name"]=$user->get_name();
	    exit(json_encode($_SESSION));
	}
    }
    else{
	$error="E-mail or password can't be empty.";
    }
}
else if(isSet($_POST["save"])){
    if(isSet($_SESSION["user"])){
	$paper=new Paper();
	if($paper->store($_SESSION["user"], $_POST["save"])){
	    if(file_exists("draft/".$_SESSION["user"])){
		unlink("draft/".$_SESSION["user"]);
	    }
	    exit(json_encode(true));
	}
	else{
	    exit(json_encode(false));
	}
    }
    else{
	exit(json_encode(false));
    }
}
else if(isSet($_POST["update"]) && isSet($_POST["data"])){
    if(isSet($_SESSION["user"])){
	if($_SESSION["user"]==Paper::get_owner($_POST["update"])){
	    $paper=new Paper();
	    if($paper->change($_SESSION["user"], $_POST["data"], $_POST["update"])){
		exit(json_encode(true));
	    }
	    else{
		exit(json_encode(false));
	    }
	}
	else{
	    exit(json_encode(false));
	}
    }
    else{
	exit(json_encode(false));
    }
}
else if(isSet($_POST["draft"])){
    if(isSet($_SESSION["user"])){
	file_put_contents("draft/".$_SESSION["user"], $_POST["draft"]);
	exit(json_encode(true));
    }
    else{
	exit(json_encode(false));
    }
}
else if(isSet($_POST["stat"]) && isSet($_POST["paper"])){
    if(isSet($_SESSION["user"])){
	$rec=new Record();
	if($rec->set($_SESSION["user"], $_POST["stat"], $_POST["paper"])){
	    exit(json_encode(true));
	}
	else{
	    exit(json_encode(false));
	}
    }
    else{
	exit(json_encode(false));
    }
}

else if(isSet($_POST["del"])){
    if(isSet($_SESSION["user"])){
	if($_SESSION["user"]==Paper::get_owner($_POST["del"])){
	    $paper=new Paper();
	    if($paper->delete($_POST["del"])){
		exit(json_encode(true));
	    }
	    else{
		exit(json_encode(false));
	    }
	}
	else{
	    exit(json_encode(false));
	}
    }
    else{
	exit(json_encode(false));
    }
}
else if(isSet($_POST["email"])&&isSet($_POST["msg"])){
    if(isSet($_SESSION["user"])){
	file_put_contents("report/".time(), $_POST["email"]."\t".$_SESSION["user"]."\n\n".$_POST["msg"]);
	exit(json_encode(true));
    }
    else{
	file_put_contents("report/".time(), $_POST["email"]."\t".$_SERVER["REMOTE_ADDR"]."\n\n".$_POST["msg"]);
	exit(json_encode(true));
    }
}

if(isSet($_GET["paper"])){
    $data=false;
    $paper=new Paper();
    if($paper->retrieve($_GET["paper"])){
	$data=$paper->get();
    }
    echo json_encode($data);
}
else if(isSet($_GET["draft"])){
    if(isSet($_SESSION["user"])){
	if($_GET["draft"]=="check"){
	    exit(json_encode(file_exists("draft/".$_SESSION["user"])));
	}
	else if($_GET["draft"]=="get"){
	    exit(file_get_contents("draft/".$_SESSION["user"]));
	}
	else if($_GET["draft"]=="del"){
	    exit(json_encode(unlink("draft/".$_SESSION["user"])));
	}
    }
    else{
	exit(json_encode(false));
    }
}
else if(isSet($_GET["list"])){
    if(isSet($_GET["search"]) && isSet($_GET["term"])){
	$list=Paper::get_list($_GET["list"], $_GET["search"], $_GET["term"]);
	echo json_encode($list);
    }
    else{
	$list=Paper::get_list($_GET["list"]);
	echo json_encode($list);
    }
}
else if(isSet($_GET["profile"])){
    if(isSet($_SESSION["user"])){
	$paper=Paper::get_list_by_user($_SESSION["user"]);
	$stat=Record::get_list($_SESSION["user"]);
	echo json_encode(array("paper"=>$paper, "stat"=>$stat));
    }
    else{
	echo json_encode(false);
    }
}
else if(isSet($_GET["name"])){
    $name=User::get_user_name($_GET["name"]);
    echo json_encode($name);
}
else if(isSet($_GET["logout"])){
    session_destroy();
}
else{
    echo json_encode(false);
}
?> 
