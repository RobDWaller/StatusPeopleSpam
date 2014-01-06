<?php

class APIRequests Extends DBAPI
{

	public function CheckForScore($twitterid)
	{
		$query = "SELECT COUNT(*)
					FROM stpsa_scores
					WHERE twitterid = :twitterid AND live = 1";
		
		$params = array('twitterid'=>array($twitterid,'INT',0));
		
		$count = $this->SelectCount($query,$params);
		
		return $count;
	}
	
	public function CheckForScreenNameScore($screen_name)
	{
		$query = "SELECT COUNT(*)
					FROM stpsa_scores
					WHERE screen_name = :screen_name AND live = 1";
		
		$params = array('screen_name'=>array($screen_name,'STR',140));
		
		$count = $this->SelectCount($query,$params);
		
		return $count;
	}
	
	public function CheckScoreAndDate($twitterid,$score_date)
	{
		$query = "SELECT COUNT(*)
					FROM stpsa_scores
					WHERE twitterid = :twitterid AND score_date = :score_date AND live = 1";
		
		$params = array('twitterid'=>array($twitterid,'INT',0),
					   	'score_date'=>array($score_date,'INT',0));
		
		$count = $this->SelectCount($query,$params);
		
		return $count;
	}
	
	public function GetScore($screen_name)
	{
		$query = "SELECT *
					FROM stpsa_scores
					WHERE screen_name = :screen_name AND live = 1";
		
		$params = array('screen_name'=>array($screen_name,'STR',140));
		
		$result = $this->SelectRecord($query,$params);
		
		return $result;
	}
	
	public function AddScore($twitterid,$screen_name,$avatar,$good,$inactive,$fake,$checks,$followers,$type,$score_date,$created)
	{
		$query = "INSERT INTO stpsa_scores (twitterid,screen_name,avatar,good,inactive,fake,checks,followers,type,score_date,created)
					VALUES (:twitterid,:screen_name,:avatar,:good,:inactive,:fake,:checks,:followers,:type,:score_date,:created)";
		
		$params = array('twitterid'=>array($twitterid,'INT',0),
					   	'screen_name'=>array($screen_name,'STR',140),
						'avatar'=>array($avatar,'INT',255),
					   	'good'=>array($good,'INT',0),
					   	'inactive'=>array($inactive,'INT',0),
					   	'fake'=>array($fake,'INT',0),
						'checks'=>array($checks,'INT',0),
						'followers'=>array($followers,'INT',0),
						'type'=>array($type,'INT',0),
					   	'score_date'=>array($score_date,'INT',0),
					   	'created'=>array($created,'INT',0));
		
		$result = $this->InsertRecord($query,$params);
		
		return $result;
	}
	
	public function UpdateScore($twitterid,$screen_name,$avatar,$good,$inactive,$fake,$checks,$followers,$type,$score_date,$created)
	{
		$query = "UPDATE stpsa_scores
					SET screen_name = :screen_name,
					avatar = :avatar,
					good = :good,
					inactive = :inactive,
					fake = :fake,
					checks = :checks,
					followers = :followers,
					type = :type,
					score_date = :score_date,
					WHERE twitterid = :twitterid AND live = 1";
		
		$params = array('twitterid'=>array($twitterid,'INT',0),
						'screen_name'=>array($screen_name,'STR',140),
						'avatar'=>array($avatar,'STR',255),
					   	'good'=>array($good,'INT',0),
					   	'inactive'=>array($inactive,'INT',0),
					   	'fake'=>array($fake,'INT',0),
						'checks'=>array($checks,'INT',0),
						'followers'=>array($followers,'INT',0),
						'type'=>array($type,'INT',0),
					   	'score_date'=>array($score_date,'INT',0));
		
		$result = $this->InsertRecord($query,$params);
		
		return $result;
	}
	
	public function CheckForKey($key)
	{
		$query = "SELECT COUNT(*)
					FROM stpsa_keys
					WHERE apikey = :key AND live = 1";
		
		$params = array('key'=>array($key,'STR',64));
		
		$result = $this->SelectCount($query,$params);
		
		return $result;
	}
	
	public function CheckForUsersKey($userid)
	{
		$query = "SELECT COUNT(*)
					FROM stpsa_keys
					WHERE twitterid = :userid AND live = 1";
		
		$params = array('userid'=>array($userid,'INT',0));
		
		$result = $this->SelectCount($query,$params);
		
		return $result;
	}
	
	public function GetUsersKey($userid)
	{
		$query = "SELECT *
					FROM stpsa_keys
					WHERE twitterid = :userid AND live = 1";
		
		$params = array('userid'=>array($userid,'INT',0));
		
		$result = $this->SelectRecord($query,$params);
		
		return $result;
	}
	
	public function AddKey($twitterid,$key,$created)
	{
		$query = "INSERT INTO stpsa_keys (twitterid,apikey,created)
					VALUES (:twitterid,:key,:created)";
		
		$params = array('twitterid'=>array($twitterid,'INT',0),
					   'key'=>array($key,'STR',64),
					   'created'=>array($created,'INT',0));
		
		//Errors::PrintArray($params);
		
		$result = $this->InsertRecord($query,$params);
		
		return $result;
	}
	
	public function ResetKey($twitterid,$apikey)
	{
		$query = "UPDATE stpsa_keys
					SET apikey = :apikey
					WHERE twitterid = :twitterid";
		
		$params = array('twitterid'=>array($twitterid,'INT',0),
					   'apikey'=>array($apikey,'STR',64));
		
		$result = $this->UpdateRecord($query,$params);
		
		return $result;
	}

}

?>