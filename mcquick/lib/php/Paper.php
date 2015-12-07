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
class Paper{
    private $paper_id;
    private $user_id;
    private $timestamp;
    private $title;
    private $info;
    private $author;
    private $subject;
    private $options;
    private $multiple;
    private $negative;
    private $time;
    private $content;
    
    private $retrieve=false;
    
    public function store($user, $data){
	$obj=json_decode($data);
	$this->paper_id=false;
	
	$this->user_id=$user;
	$this->timestamp=time();
	$this->title=$obj->title;
	$this->info=$obj->info;
	$this->author=$obj->author;
	$this->subject=$obj->subject;
	$this->options=$obj->options;
	$this->multiple=$obj->multiple;
	$this->negative=$obj->negative;
	$this->time=$obj->time;
	$this->content=$obj->questions;
	
	$sql=DB::get_sql();
	$stmt=$sql->prepare("INSERT INTO papers (user_id, title, timestamp, info, author, subject, options, multiple, negative, time, content) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
	$stmt->bind_param("isisssiiiss", $this->user_id, $this->title, $this->timestamp, $this->info, $this->author, $this->subject, $this->options, $this->multiple, $this->negative, json_encode($this->time), json_encode($this->content));
	$stmt->execute();
	$stmt->close();
	$stmt=$sql->prepare("SELECT paper_id FROM papers WHERE user_id=? AND title=? AND timestamp=?");
	$stmt->bind_param("iss", $this->user_id, $this->title, $this->timestamp);
	$stmt->execute();
	$stmt->bind_result($this->paper_id);
	$stmt->fetch();
	$stmt->close();
	$sql->close();
	
	return $this->paper_id;
    }
    
    public function change($user, $data, $id){
	$obj=json_decode($data);
	$this->paper_id=$id;
	
	$this->user_id=$user;
	$this->timestamp=time();
	$this->title=$obj->title;
	$this->info=$obj->info;
	$this->author=$obj->author;
	$this->subject=$obj->subject;
	$this->options=$obj->options;
	$this->multiple=$obj->multiple;
	$this->negative=$obj->negative;
	$this->time=$obj->time;
	$this->content=$obj->questions;
	
	$sql=DB::get_sql();
	$stmt=$sql->prepare("UPDATE papers SET user_id=?, title=?, timestamp=?, info=?, author=?, subject=?, options=?, multiple=?, negative=?, time=?, content=? WHERE paper_id=?");
	$stmt->bind_param("isisssiiissi", $this->user_id, $this->title, $this->timestamp, $this->info, $this->author, $this->subject, $this->options, $this->multiple, $this->negative, json_encode($this->time), json_encode($this->content), $id);
	$stmt->execute();
	$stmt->close();
	$sql->close();
	
	return $this->paper_id;
    }
    
    public function retrieve($id){
	$this->paper_id=$id;
	
	$sql=DB::get_sql();
	$stmt=$sql->prepare("SELECT user_id, title, timestamp, info, author, subject, options, multiple, negative, time, content FROM papers WHERE paper_id=?");
	$stmt->bind_param("i", $this->paper_id);
	$stmt->execute();
	$stmt->bind_result($this->user_id, $this->title, $this->timestamp, $this->info, $this->author, $this->subject, $this->options, $this->multiple, $this->negative, $this->time, $this->content);
	if($stmt->fetch()){
	    $stmt->close();
	    $sql->close();
	    $this->time=json_decode($this->time);
	    $this->content=json_decode($this->content);
	    return true;
	}
	else{
	    return false;
	}
    }
    
    public function get_id(){
	return $this->paper_id;
    }
    
    public function get(){
	$obj=array();
	$obj["title"]=$this->title;
	$obj["info"]=$this->info;
	$obj["author"]=$this->author;
	$obj["subject"]=$this->subject;
	$obj["options"]=$this->options;
	$obj["multiple"]=$this->multiple;
	$obj["negative"]=$this->negative;
	$obj["time"]=$this->time;
	$obj["questions"]=$this->content;
	return $obj;
    }
    
    public function delete($id){
	$this->paper_id=$id;
	$this->retrieve($this->get_id());
	file_put_contents("trash", $this->get_id()."\t".time()."\n".json_encode($this->get())."\n\n", FILE_APPEND);
	$sql=DB::get_sql();
	$stmt=$sql->prepare("DELETE FROM papers WHERE paper_id=?");
	$stmt->bind_param("i", $this->paper_id);
	$stmt->execute();
	$stmt->close();
	$sql->close();
	return true;
    }
    
    public static function get_owner($id){
	$user=false;
	$sql=DB::get_sql();
	$stmt=$sql->prepare("SELECT user_id FROM papers WHERE paper_id=?");
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$stmt->bind_result($user);
	$stmt->fetch();
	$stmt->close();
	$sql->close();
	return $user;
    }
    
    public static function get_list($page=1, $search="", $term=""){
	$num=$page*10;
	$off=$num-10;
	$flag=false;
	switch($search){
	    case "title";
		$search="papers.title";
		$flag=true;
		break;
	    case "name";
		$search="user_account.name";
		$flag=true;
		break;
	    case "subject";
		$search="papers.subject";
		$flag=true;
		break;
	}
	$sql=DB::get_sql();
	if($flag){
	    $stmt=$sql->prepare("SELECT papers.paper_id, papers.title, user_account.name, papers.subject, papers.timestamp FROM papers INNER JOIN user_account ON papers.user_id=user_account.user_id WHERE ".$search." LIKE '%".$term."%' LIMIT ".$off.", ".$num);
	}
	else{
	    $stmt=$sql->prepare("SELECT papers.paper_id, papers.title, user_account.name, papers.subject, papers.timestamp FROM papers INNER JOIN user_account ON papers.user_id=user_account.user_id LIMIT ".$off.", ".$num);
	}
	echo mysqli_error($sql);
	$stmt->execute();
	$stmt->bind_result($id, $title, $user, $subject, $time);
	$ret=array();
	while($stmt->fetch()){
	    $ret[]=array("id"=>$id, "title"=>$title, "user"=>$user, "subject"=>$subject, "time"=>$time);
	}
	$stmt->close();
	if($flag){
	    $stmt=$sql->prepare("SELECT COUNT(papers.paper_id) FROM papers INNER JOIN user_account ON papers.user_id=user_account.user_id WHERE ".$search." LIKE '%".$term."%'");
	}
	else{
	    $stmt=$sql->prepare("SELECT COUNT(paper_id) FROM papers");
	}
	$stmt->execute();
	$stmt->bind_result($num);
	$stmt->fetch();
	$stmt->close();
	$sql->close();
	return array($ret, $num);
    }
    
    public static function get_list_by_user($u){
	$sql=DB::get_sql();
	$stmt=$sql->prepare("SELECT paper_id, title, subject, timestamp FROM papers WHERE user_id=?");
	$stmt->bind_param("i", $u);
	$stmt->execute();
	$stmt->bind_result($id, $title, $subject, $time);
	$ret=array();
	while($stmt->fetch()){
	    $ret[]=array("id"=>$id, "title"=>$title, "subject"=>$subject, "time"=>$time);
	}
	$stmt->close();
	$sql->close();
	return $ret;
    }
}
?> 
