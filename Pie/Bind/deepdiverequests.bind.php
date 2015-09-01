<?php

class DeepdiveRequests Extends DB
{

	protected $connection = 'statuspeople_deepdive';

	public function GetDives()
	{
		$query = "SELECT *
					FROM spdv_dives
					WHERE live = 1 AND finished = 0";
		
		$result = $this->SelectRecords($query);
		
		return $result;
	}
	
	public function GetMyDives($id)
	{
		$query = "SELECT *
					FROM spdv_dives
					WHERE userid = :userid 
					AND live = 1 
					AND finished = 0";
		
		$params = array('userid'=>array($id,'INT',0));

		$result = $this->SelectRecords($query,$params);
		
		return $result;
	}

	public function GetDivers()
	{
		$query = "SELECT DISTINCT(userid)
					FROM spdv_dives";	
		
		$result = $this->SelectRecords($query);
		
		return $result;
	}
	
	public function GetTopDive($userid)
	{
		$query = "SELECT *
					FROM spdv_dives
					WHERE userid = :userid AND live = 1
					ORDER BY created ASC
					LIMIT 0,1";
		
		$params = array('userid'=>array($userid,'INT',0));
		
		$result = $this->SelectRecord($query,$params);
		
		return $result;
	}
	
	public function GetFinishedDives()
	{
		$query = "SELECT *
					FROM spdv_dives
					WHERE live = 1 AND finished = 1";
		
		$result = $this->SelectRecords($query);
		
		return $result;
	}
	
	public function AddDive($userid, $twitterid, $screenname, $followers, $created)
	{
		$query = "INSERT INTO spdv_dives (userid, twitterid, screen_name, followers, created)
					VALUES (:userid, :twitterid, :screenname, :followers, :created)";
		
		$params = array('userid'=>array($userid,'INT',0),
					   'twitterid'=>array($twitterid,'INT',0),
						'screenname'=>array($screenname,'STR',150),
					   'followers'=>array($followers,'INT',0),
					   'created'=>array($created,'INT',0));
		
		$result = $this->InsertRecord($query,$params);
		
		return $result;
	}
	
	public function UpdateCursor($userid,$twitterid,$cursor)
	{
		$query = "UPDATE spdv_dives
					SET twittercursor = :cursor
					WHERE userid = :userid AND twitterid = :twitterid";

		$params = array('userid'=>array($userid,'INT',0),
					   'twitterid'=>array($twitterid,'INT',0),
					   'cursor'=>array($cursor,'STR',50));
		
		//Errors::PrintArray($params);
		
		$result = $this->UpdateRecord($query,$params);
		
		return $result;
		
	}
	
	public function TurnOffDive($id)
	{
		$query = "UPDATE spdv_dives
					SET live = 0
					WHERE id = :id";
		
		$params = array('id'=>array($id,'INT',0));
		
		$result = $this->UpdateRecord($query,$params);
		
		return $result;
	}
	
	public function UpdateFinishedStatus($id)
	{
		$query = "UPDATE spdv_dives
					SET finished = 1 AND twittercursor = 0
					WHERE id = :id";
		
		$params = array('id'=>array($id,'INT',0));
		
		$result = $this->UpdateRecord($query,$params);
		
		return $result;
	}
	
	public function GetFollowerIDs($twitterid)
	{
		$query = "SELECT *
					FROM spdv_follower_ids
					WHERE twitterid = :twitterid AND checked = 0 AND live = 1
					ORDER BY created ASC 
					LIMIT 0,1";
		
		$params = array('twitterid'=>array($twitterid,'INT',0));
		
		$result = $this->SelectRecord($query,$params);
		
		return $result;
	}
	
	public function CountFollowerIDs($twitterid)
	{
		$query = "SELECT COUNT(*)
					FROM spdv_follower_ids
					WHERE twitterid = :twitterid AND checked = 0 AND live = 1";
		
		$params = array('twitterid'=>array($twitterid,'INT',0));
		
		$result = $this->SelectCount($query,$params);
		
		return $result;
	}
	
	public function CountCheckedFollowers($twitterid)
	{
		$query = "SELECT COUNT(*)
					FROM spdv_follower_ids
					WHERE twitterid = :twitterid AND checked = 1 AND live = 1";
		
		$params = array('twitterid'=>array($twitterid,'INT',0));
		
		$result = $this->SelectCount($query,$params);
		
		return $result;
	}
	
	public function AddFollowerIDs($diveid,$twitterid,$followerids,$cursor,$created)
	{
		$query = "INSERT INTO spdv_follower_ids (diveid,twitterid,followerids,twittercursor,created)
					VALUES (:diveid,:twitterid,:followerids,:twittercursor,:created)";
		
		$params = array('diveid'=>array($diveid,'INT',0),
						'twitterid'=>array($twitterid,'INT',0),
					   'followerids'=>array($followerids,'STR',200000),
						'twittercursor'=>array($cursor,'STR',50),
					   'created'=>array($created,'INT',0));
		
		$result = $this->InsertRecord($query,$params);
		
		return $result;
	}
	
	public function UpdateFollowerIDsStatus($id)
	{
		$query = "UPDATE spdv_follower_ids
					SET checked = 1
					WHERE id = :id";
		
		$params = array('id'=>array($id,'INT',0));
		
		$result = $this->UpdateRecord($query,$params);
		
		return $result;
	}
	
	public function GetFollowers($twitterid)
	{
		$query = "SELECT *
					FROM spdv_followers
					WHERE twitterid = :twitterid AND live = 1";
		
		$params = array('twitterid'=>array($twitterid,'INT',0));
		
		$result = $this->SelectRecords($query,$params);
		
		return $result;
	}
	
	public function AddFollowers($twitterid,$followeridsid,$followers,$created)
	{
		$query = "INSERT INTO spdv_followers (twitterid,followeridsid,followers,created)
					VALUES (:twitterid,:followeridsid,:followers,:created)";
		
		$params = array('twitterid'=>array($twitterid,'INT',0),
					 'followeridsid'=>array($followeridsid,'INT',0),
					 'followers'=>array($followers,'STR',5000000),
					 'created'=>array($created,'INT',0));
		
		$result = $this->InsertRecord($query,$params);
		
		return $result;
	}
	
	public function TurnOffFollowers($id)
	{
		$query = "UPDATE spdv_followers
					SET live = 0
					WHERE id = :id";
		
		$params = array('id'=>array($id,'INT',0));
		
		$result = $this->UpdateRecord($query,$params);
		
		return $result;
	}
	
	public function GetScores($twitterid)
	{
		$query = "SELECT *
					FROM spdv_scores
					WHERE twitterid = :twitterid";
			
		$params = array('twitterid'=>array($twitterid,'INT',0));
		
		$result = $this->SelectRecord($query,$params);
		
		return $result;
	}
	
	public function CountScores($twitterid)
	{
		$query = "SELECT COUNT(*)
					FROM spdv_scores
					WHERE twitterid = :twitterid";
			
		$params = array('twitterid'=>array($twitterid,'INT',0));
		
		$result = $this->SelectCount($query,$params);
		
		return $result;	
	}
	
	public function AddScore($twitterid,$spam,$potential,$checks,$created)
	{
		$query = "INSERT INTO spdv_scores (twitterid,spam,potential,checks,created)
					VALUES (:twitterid,:spam,:potential,:checks,:created)";
		
		$params = array('twitterid'=>array($twitterid,'INT',0),
					   	'spam'=>array($spam,'INT',0),
					   	'potential'=>array($potential,'INT',0),
					   	'checks'=>array($checks,'INT',0),
					   	'created'=>array($created,'INT',0));
			
		$result = $this->InsertRecord($query,$params);
		
		return $result;
	}
	
	public function UpdateScore($twitterid,$spam,$potential,$checks,$created)
	{
		$query = "UPDATE spdv_scores 
					SET spam = :spam,
					potential = :potential,
					checks = :checks,
					created = :created
					WHERE twitterid = :twitterid";
		
		$params = array('twitterid'=>array($twitterid,'INT',0),
					   	'spam'=>array($spam,'INT',0),
					   	'potential'=>array($potential,'INT',0),
					   	'checks'=>array($checks,'INT',0),
					   	'created'=>array($created,'INT',0));
			
		$result = $this->UpdateRecord($query,$params);
		
		return $result;
	}
	
	public function GetAllDiveScores()
	{
		$query = "SELECT s.twitterid,d.screen_name,s.potential,s.spam,s.checks,d.followers,s.created 
					FROM spdv_dives AS d
					LEFT JOIN spdv_scores AS s ON d.diveid = s.id";
		
		$result = $this->SelectRecords($query);
		
		return $result;
	}
}

?>