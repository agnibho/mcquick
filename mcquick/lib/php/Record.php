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
class Record{
    private $id;
    private $user_id;
    private $paper_id;
    private $content;
    private $time;
    
    public function set($u, $c, $p){
	$this->user_id=$u;
	$this->content=$c;
	$this->paper_id=$p;
	$this->time=time();
	$sql=DB::get_sql();
	$stmt=$sql->prepare("INSERT INTO record (user_id, paper_id, content, time) VALUES (?, ?, ?, ?)");
	$stmt->bind_param("iisi", $this->user_id, $this->paper_id, json_encode($this->content), $this->time);
	$stmt->execute();
	$stmt->close();
	$sql->close();
	return true;
    }
    
    public function get($n){
	$this->num=$n;
	
	$sql=DB::get_sql();
	$stmt=$sql->prepare("SELECT user_id, paper_id, content, time FROM record WHERE id=?");
	$stmt->bind_param("i", $this->id);
	$stmt->execute();
	$stmt->bind_result($this->user_id, $this->paper_id, $this->content, $this->time);
	$stmt->fetch();
	$stmt->close();
	$sql->close();
	
	return array($this->user_id, $this->paper_id, $this->content, $this->time);
    }
    
    public static function get_list($user){
	$rec=array();
	$sql=DB::get_sql();
	$stmt=$sql->prepare("SELECT time, paper_id, content FROM record WHERE user_id=?");
	$stmt->bind_param("i", $user);
	$stmt->execute();
	$stmt->bind_result($time, $paper_id, $content);
	while($stmt->fetch()){
	    $rec[]=array("time"=>$time, "paper"=>$paper_id, "stat"=>$content);
	}
	$stmt->close();
	$sql->close();
	
	return $rec;
    }
}
?> 
