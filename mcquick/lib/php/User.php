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
class User{
    private $user_id;
    private $name;
    private $email;
    
    public function load_user($e, $p){
	$this->email=$e;
	$pass=$p;
	
	$sql=DB::get_sql();
	$stmt=$sql->prepare("SELECT user_id, name, pass FROM user_account WHERE email=?");
	$stmt->bind_param("s", $this->email);
	$stmt->execute();
	$stmt->bind_result($this->user_id, $this->name, $hash);
	if($stmt->fetch()){
	    if(!password_verify($pass, $hash)){
		$this->user_id=false;
	    }
	}
	else{
	    $this->user_id=false;
	}
	$stmt->close();
	$sql->close();
	return $this->user_id;
    }
    
    public function add_user($n, $e){
	$this->name=$n;
	$this->email=$e;
	$time=time();
	$code=bin2hex(openssl_random_pseudo_bytes(8));
	$hash=password_hash($code, PASSWORD_DEFAULT);
	
	$sql=DB::get_sql();
	$stmt=$sql->prepare("SELECT user_id FROM user_account WHERE email=?");
	$stmt->bind_param("s", $this->email);
	$stmt->execute();
	$stmt->bind_result($this->user_id);
	if(!$stmt->fetch()){
	    if(filter_var($this->email, FILTER_VALIDATE_EMAIL)){
		$stmt->close();
		$stmt=$sql->prepare("INSERT INTO user_account (name, email, time, code) VALUES (?, ?, ?, ?)");
		$stmt->bind_param("ssis", $this->name, $this->email, $time, $hash);
		$stmt->execute();
		$stmt->close();
		$this->send_mail($this->email, $code);
		$stmt=$sql->prepare("SELECT user_id FROM user_account WHERE email=?");
		$stmt->bind_param("s", $this->email);
		$stmt->execute();
		$stmt->bind_result($this->user_id);
		$stmt->fetch();
	    }
	    else{
		$this->user_id=false;
	    }
	}
	else{
	    $this->user_id=false;
	}
	$stmt->close();
	$sql->close();
	
	return $this->user_id;
    }

    public function set_password($e, $p, $c){
	$this->email=$e;
	$hash=password_hash($p, PASSWORD_DEFAULT);
	$code=$c;
	
	$sql=DB::get_sql();
	$stmt=$sql->prepare("SELECT user_id, code FROM user_account WHERE email=?");
	$stmt->bind_param("s", $this->email);
	$stmt->execute();
	$stmt->bind_result($this->user_id, $check);
	if($stmt->fetch()){
	    if(password_verify($code, $check)){
		$stmt->close();
		$stmt=$sql->prepare("UPDATE user_account SET pass=?, code='' WHERE user_id=?");
		$stmt->bind_param("si", $hash, $this->user_id);
		$stmt->execute();
	    }
	    else{
		$this->user_id=false;
	    }
	}
	else{
	    $this->user_id=false;
	}
	$stmt->close();
	$sql->close();
	
	return $this->user_id;
    }

    public function resend_code($e){
	$this->email=$e;
	$code=bin2hex(openssl_random_pseudo_bytes(8));
	$hash=password_hash($code, PASSWORD_DEFAULT);
	
	$sql=DB::get_sql();
	$stmt=$sql->prepare("SELECT user_id FROM user_account WHERE email=?");
	$stmt->bind_param("s", $this->email);
	$stmt->execute();
	$stmt->bind_result($this->user_id);
	if($stmt->fetch()){
	    $stmt->close();
	    $stmt=$sql->prepare("UPDATE user_account SET code=? WHERE user_id=?");
	    $stmt->bind_param("si", $hash, $this->user_id);
	    $stmt->execute();
	    $this->send_mail($this->email, $code);
	}
	$stmt->close();
	$sql->close();
	
	return $this->user_id;
    }
    
    public function get_id(){
	return $this->user_id;
    }
    public function get_name(){
	return $this->name;
    }
    
    public static function get_user_name($i){
	$sql=DB::get_sql();
	$stmt=$sql->prepare("SELECT name FROM user_account WHERE user_id=?");
	$stmt->bind_param("i", $i);
	$stmt->execute();
	$stmt->bind_result($name);
	$stmt->fetch();
	$stmt->close();
	$sql->close();
	
	return $name;
    }

    private function send_mail($to, $content=""){
	$sub="MCQuick Password";
	$body="Please follow this link to set your MCQuick password:\n".URL."/login.php?email=".$this->email."&code=".$content."\n\nAltrnatively you can use the following confirmation code:\n".$content;
	mail($to, $sub, $body);
    }
}
?> 
