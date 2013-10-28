<?php

class DeepdiveRequests Extends Deepdive
{

	public function GetDives()
	{
		$query = "SELECT *
					FROM spdv_dives
					WHERE live = 1 AND finished = 0";
		
		$result = $this->SelectRecords($query);
		
		return $result;
	}
	
	public function AddDive($userid,$twitterid,$followers,$created)
	{
		$query = "INSERT INTO spdv_dives (userid,twitterid,followers,created)
					VALUES (:userid,:twitterid,:followers,:created)";
		
		$params = array('userid'=>array($userid,'INT',0),
					   'twitterid'=>array($twitterid,'INT',0),
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
	
	public function GetFollowerIDs($twitterid)
	{
		$query = "SELECT *
					FROM spdv_follower_ids
					WHERE twitterid = :twitterid AND checked = 0
					ORDER BY created ASC 
					LIMIT 0,1";
		
		$params = array('twitterid'=>array($twitterid,'INT',0));
		
		$result = $this->SelectRecord($query,$params);
		
		return $result;
	}
	
	public function AddFollowerIDs($twitterid,$followerids,$created)
	{
		$query = "INSERT INTO spdv_follower_ids (twitterid,followerids,created)
					VALUES (:twitterid,:followerids,:created)";
		
		$params = array('twitterid'=>array($twitterid,'INT',0),
					   'followerids'=>array($followerids,'STR',200000),
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
	
}

?>