<?php

class DBRequests extends DB
{
	
		public function CountUsers($twitterid)
        {
            
            $query = "SELECT COUNT(*)
                        FROM spsp_users
                        WHERE twitterid = :twitterid AND live = 1";
            
            $params = array('twitterid'=>array($twitterid));
            
            $result = $this->SelectCount($query, $params);
            
            return $result;
            
        }
        
        public function GetTwitterDetails($twitterid)
        {
            
            $query = "SELECT *
                        FROM spsp_users
                        WHERE twitterid = :twitterid AND live = 1";
            
            $params = array('twitterid'=>array($twitterid));
            
            $result = $this->SelectRecord($query, $params);
            
            return $result;
            
        }
        
        public function AddTwitterDetails($twitterid,$token,$secret,$created)
        {            
            $query = "INSERT INTO spsp_users (twitterid,token,secret,created)
                        VALUES (:twitterid,:token,:secret,:created)";
            
            $params = array('twitterid'=>array($twitterid,'INT',0),
                            'token'=>array($token,'STR',64),
                            'secret'=>array($secret,'STR',64),
                            'created'=>array($created,'INT',0));
            
            $result = $this->InsertRecord($query,$params);
            
            return $result;   
        }
        
        public function ResetTwitterDetails($twitterid)
        {
            $query = "UPDATE spsp_users
                        SET live = 0
                        WHERE twitterid = :twitterid";
            
            $params = array('twitterid'=>array($twitterid,'INT',0));
            
            $result = $this->UpdateRecord($query,$params);
            
            return $result;
        }


        public function GetLatestSpamRecords($limit)
        {
            
            $query = "SELECT ui.screen_name, ui.avatar, sc.spam, sc.potential, sc.checks, sc.followers, ui.twitterid
                        FROM spsp_spam_scores AS sc 
                        JOIN spsp_user_info AS ui ON sc.twitterid = ui.twitterid
                        ORDER BY (sc.spam/sc.checks) DESC, sc.spam DESC
                        LIMIT 0,:limit";
            
            $params = array('limit'=>array($limit,'INT',0));
            
            $result = $this->SelectRecords($query,$params);
            
            return $result;
            
        }
        
        public function CountSpamRecords($twitterid)
        {
            $query = "SELECT COUNT(*)
                        FROM spsp_spam_scores
                        WHERE twitterid = :twitterid AND live = 1";
            
            $params = array('twitterid'=>array($twitterid));
            
            $result = $this->SelectCount($query, $params);
            
            return $result;
        }
        
		public function GetSearches($twid)
		{
			$query = "SELECT searches
						FROM spsp_users
						WHERE twitterid = :twid AND live = 1";
			
			$params = array('twid'=>array($twid,'INT',0));
			
			$result = $this->SelectRecord($query,$params);
			
			return $result;
		}
		
		public function UpdateSearches($twid,$searches)
		{
			$query = "UPDATE spsp_users
						SET searches = :searches
						WHERE twitterid = :twid AND live = 1";
			
			$params = array('twid'=>array($twid,'INT',0),
						   	'searches'=>array($searches,'INT',0));
			
			$result = $this->UpdateRecord($query,$params);
			
			return $result;
		}
	
        public function GetSpamDetails($twitterid)
        {
            $query = "SELECT *
                        FROM spsp_spam_scores
                        WHERE twitterid = :twitterid AND live = 1";
            
            $params = array('twitterid'=>array($twitterid));
            
            $result = $this->SelectRecord($query, $params);
            
            return $result;
        }
        
        public function AddSpamDetails($twitterid,$spam,$potential,$checks,$followers,$updated,$created)
        {
            $query = "INSERT INTO spsp_spam_scores (twitterid,spam,potential,checks,followers,updated,created)
                        VALUES (:twitterid,:spam,:potential,:checks,:followers,:updated,:created)";
            
            $params = array('twitterid'=>array($twitterid,'INT',0),
                            'spam'=>array($spam,'INT',0),
                            'potential'=>array($potential,'INT',0),
                            'checks'=>array($checks,'INT',0),
                            'followers'=>array($followers,'INT',0),
                            'updated'=>array($updated,'INT',0),
                            'created'=>array($created,'INT',0));
            
            $result = $this->InsertRecord($query,$params);
            
            return $result;            
        }
        
        public function UpdateSpamDetails($twitterid,$spam,$potential,$checks,$followers,$updated)
        {
            $query = "UPDATE spsp_spam_scores
                        SET spam = :spam, potential = :potential, checks = :checks, followers = :followers, updated = :updated
                        WHERE twitterid = :twitterid";
            
            $params = array('twitterid'=>array($twitterid,'INT',0),
                            'spam'=>array($spam,'INT',0),
                            'potential'=>array($potential,'INT',0),
                            'checks'=>array($checks,'INT',0),
                            'followers'=>array($followers,'INT',0),
                            'updated'=>array($updated,'INT',0));
            
            $result = $this->UpdateRecord($query, $params);
            
            return $result;
        }
        
        public function CountUserInfoRecords($twitterid)
        {
            
            $query = "SELECT COUNT(*) 
                        FROM spsp_user_info
                        WHERE twitterid = :twitterid";
            
            $params = array('twitterid'=>array($twitterid,'INT',0));

            $result = $this->SelectCount($query, $params);
            
            return $result;
            
        }
        
        public function AddUserInfo($twitterid,$screenname,$avatar,$updated,$created)
        {
            
            $query = "INSERT INTO spsp_user_info (twitterid,screen_name,avatar,updated,created)
                        VALUES (:twitterid,:screen_name,:avatar,:updated,:created)";
            
            $params = array('twitterid'=>array($twitterid,'INT',0),
                            'screen_name'=>array($screenname,'STR',140),
                            'avatar'=>array($avatar,'STR',255),
                            'updated'=>array($updated,'INT',0),
                            'created'=>array($created,'INT',0));
            
            $result = $this->InsertRecord($query, $params);
            
            return $result;
            
        }
        
        public function GetUsersToCheck($limit)
        {
            $query = "SELECT *
                        FROM spsp_checks AS sc
						JOIN spsp_valid AS sv ON sc.userid = sv.userid
                        WHERE accounttype = 1 AND live = 1 AND DATE_FORMAT(FROM_UNIXTIME(sc.updated),'%d/%m/%y') != DATE_FORMAT(CURDATE(),'%d/%m/%y') AND sv.valid < (UNIX_TIMESTAMP()+(3600*24*30))
                        GROUP BY sc.screen_name
                        ORDER BY sc.lastcheck ASC
                        LIMIT 0,:limit";
            
            $params = array('limit'=>array($limit,'INT',0));
            
            $result = $this->SelectRecords($query,$params);
            
            return $result;            
        }
        
        public function UpdateUsersToCheckTime($twitterid,$screen_name,$time)
        {
            $query = "UPDATE spsp_checks
                        SET updated = :time
                        WHERE twitterid = :twitterid AND screen_name = :screen_name";
            
            $params = array('time'=>array($time,'INT',0),
                            'screen_name'=>array($screen_name,'STR',140),
                            'twitterid'=>array($twitterid,'INT',0));
            
            $result = $this->UpdateRecord($query, $params);
            
            return $result;
        }
        
        public function UpdateLastCheckTime($twitterid,$screen_name,$time)
        {
            $query = "UPDATE spsp_checks
                        SET lastcheck = :time
                        WHERE twitterid = :twitterid AND screen_name = :screen_name";
            
            $params = array('time'=>array($time,'INT',0),
                            'screen_name'=>array($screen_name,'STR',140),
                            'twitterid'=>array($twitterid,'INT',0));
            
            $result = $this->UpdateRecord($query, $params);
            
            return $result;   
        }
        
        public function AddCheckScore($twitterid,$screen_name,$spam,$potential,$checks,$followers,$created)
        {
            
            $query = "INSERT INTO spsp_check_scores (twitterid,screen_name,spam,potential,checks,followers,created)
                        VALUES (:twitterid,:screen_name,:spam,:potential,:checks,:followers,:created)";
            
            $params = array('twitterid'=>array($twitterid,'INT',0),
                            'screen_name'=>array($screen_name,'STR',150),
                            'spam'=>array($spam,'INT',0),
                            'potential'=>array($potential,'INT',0),
                            'checks'=>array($checks,'INT',0),
                            'followers'=>array($followers,'INT',0),
                            'created'=>array($created,'INT',0));
            
            $result = $this->InsertRecord($query, $params);
            
            return $result;            
        }
        
        public function AddFakes($insertstring)
        {
            $query = "INSERT IGNORE INTO spsp_fakes (userid,twitterid,screen_name,avatar,created)
                        VALUES $insertstring";
            
            $result = $this->InsertRecord($query);
            
            return $result;
        }
        
        public function GetFakes($userid,$type,$limit)
        {
            $query = "SELECT *
                        FROM spsp_fakes
                        WHERE userid = :userid AND live = :type AND notspam = 0
                        ORDER BY created DESC
                        LIMIT 0,:limit";
            
            $params = array('userid'=>array($userid,'INT',0),
							'type'=>array($type,'INT',0),
                            'limit'=>array($limit,'INT',0));
            
            $result = $this->SelectRecords($query, $params);
            
            return $result;
        }
	
        public function GetCompetitors($userid)
        {
            $query = "SELECT c.userid, cs.twitterid, cs.screen_name, c.avatar, cs.spam, cs.potential, cs.checks, cs.followers, cs.created
                     	FROM spsp_checks AS c
                        JOIN spsp_check_scores AS cs ON c.twitterid = cs.twitterid
			JOIN(	
				SELECT cs1.twitterid, MAX(cs1.created) AS date
                     		FROM spsp_checks AS c1
                        	JOIN spsp_check_scores AS cs1 ON c1.twitterid = cs1.twitterid
				WHERE c1.userid = :userid AND c1.live = 1
                        	GROUP BY cs1.twitterid
                        	ORDER BY cs1.screen_name ASC
				) cs2
			ON cs.created = cs2.date
                        WHERE c.userid = :userid AND c.live = 1
                        GROUP BY cs.twitterid
                        ORDER BY cs.screen_name ASC";
            
            $params = array('userid'=>array($userid,'INT',0));
            
            $result = $this->SelectRecords($query,$params);
            
            return $result;
        }
        
        public function GetCompetitorCount($userid)
        {
            $query = "SELECT COUNT(*) 
                        FROM spsp_checks 
                        WHERE userid = :userid AND live = 1";
            
            $params = array('userid'=>array($userid,'INT',0));
            
            $result = $this->SelectCount($query,$params);
            
            return $result;
        }
        
        public function GetScoresOverTime($twitterid,$limit)
        {
            $query = "SELECT c0.spam, c0.potential, c0.checks, DATE_FORMAT(FROM_UNIXTIME(created),'%b %d') as date, c0.created 
                        FROM spsp_check_scores c0
			JOIN (SELECT MAX(checks) AS chks
				FROM spsp_check_scores
				WHERE twitterid = :twitterid
				GROUP BY DATE_FORMAT(FROM_UNIXTIME(created),'%y/%m/%d')) c1
			ON c0.checks = c1.chks
			WHERE twitterid = :twitterid
			GROUP BY DATE_FORMAT(FROM_UNIXTIME(created),'%y/%m/%d')
			ORDER BY created DESC
                        LIMIT 0,:limit";
            
            $params = array('twitterid'=>array($twitterid,'INT',0),
                            'limit'=>array($limit,'INT',0));
            
            $result = $this->SelectRecords($query,$params);
            
            return $result;
        }
        
        public function Get500kClub()
        {
            $query = "SELECT i.screen_name, i.avatar, s.spam, s.potential, s.checks, s.followers
                        FROM spsp_user_info AS i
                        JOIN spsp_spam_scores AS s ON i.twitterid = s.twitterid
                        WHERE s.followers > 500000 AND ((s.spam/s.checks)*100)<10 AND s.checks > 500
                        ORDER BY s.followers DESC";
            
            $result = $this->SelectRecords($query);
            
            return $result;
        }
        
        public function CheckForFakerCheck($userid,$twitterid)
        {
            $query = "SELECT COUNT(*)
                        FROM spsp_checks
                        WHERE userid = :userid AND twitterid = :twitterid AND live = 1";
            
            $params = array('userid'=>array($userid,'INT',0),
                               'twitterid'=>array($twitterid,'INT',0));
            
            $result = $this->SelectCount($query,$params);
            
            return $result;
        }
        
        public function AddFakerCheck($userid,$twitterid,$screen_name,$avatar,$accounttype,$updated,$created)
        {
            $query = "INSERT INTO spsp_checks (userid,twitterid,screen_name,avatar,accounttype,updated,created)
                        VALUES (:userid,:twitterid,:screen_name,:avatar,:accounttype,:updated,:created)";
            
            $params = array('userid'=>array($userid,'INT',0),
                            'twitterid'=>array($twitterid,'INT',0),
                            'screen_name'=>array($screen_name,'STR',140),
                            'avatar'=>array($avatar,'INT',0),
                            'accounttype'=>array($accounttype,'INT',0),
                            'updated'=>array($updated,'INT',0),
                            'created'=>array($created,'INT',0));
            
            $result = $this->InsertRecord($query,$params);
            
            return $result;
        }
        
        public function AddFakerCheckScore($twitterid,$screen_name,$spam,$potential,$checks,$followers,$created)
        {
            $query = "INSERT INTO spsp_check_scores (twitterid,screen_name,spam,potential,checks,followers,created)
                        VALUES (:twitterid,:screen_name,:spam,:potential,:checks,:followers,:created)";
            
            $params = array('twitterid'=>array($twitterid,'INT',0),
                            'screen_name'=>array($screen_name,'STR',140),
                            'spam'=>array($spam,'INT',0),
                            'potential'=>array($potential,'INT',0),
                            'checks'=>array($checks,'INT',0),
                            'followers'=>array($followers,'INT',0),
                            'created'=>array($created,'INT',0));
            
            $result = $this->InsertRecord($query,$params);
            
            return $result;
        }
        
        public function DeleteFakerCheck($userid,$twitterid)
        {
            $query = "UPDATE spsp_checks 
                        SET live = 0
                        WHERE userid = :userid AND twitterid = :twitterid";
            
            $params = array('userid'=>array($userid,'INT',0),
                            'twitterid'=>array($twitterid,'INT',0));
            
            $result = $this->UpdateRecord($query, $params);
            
            return $result;
        }
        
        public function BlockSpam($userid,$twitterid)
        {
            $query = "UPDATE spsp_fakes
                        SET live = 0
                        WHERE userid = :userid AND twitterid = :twitterid";
            
            $params = array('userid'=>array($userid,'INT',0),
                            'twitterid'=>array($twitterid,'INT',0));
            
            $result = $this->UpdateRecord($query, $params);
            
            return $result;
        }
        
        public function NotSpam($userid,$twitterid)
        {
            $query = "UPDATE spsp_fakes
                        SET live = 0, notspam = 1
                        WHERE userid = :userid AND twitterid = :twitterid";
            
            $params = array('userid'=>array($userid,'INT',0),
                            'twitterid'=>array($twitterid,'INT',0));
            
            $result = $this->UpdateRecord($query, $params);
            
            return $result;
        }
        
        public function AddSpamError($bio,$results,$type,$created)
        {
            $query = "INSERT INTO spsp_errors (bio,result,type,created)
                        VALUES (:bio,:results,:type,:created)";
                        
            $params = array('bio'=>array($bio,'STR',7000),
                            'results'=>array($results,'STR',7000),
                            'type'=>array($type,'INT',0),
                            'created'=>array($created,'INT',0));
                            
            $result = $this->InsertRecord($query,$params);
            
            return $result;
            
        }
        
		public function CountAutoRemoveRecords($userid)
		{
			$query = "SELECT COUNT(*)
						FROM spsp_checks
						WHERE userid = :userid AND twitterid = :twitterid AND autoremove = 1";
			
			$params = array('userid'=>array($userid,'INT',0),
						   'twitterid'=>array($twitterid,'INT',0));
			
			$result = $this->SelectCount($query,$params);
			
			return $results;
		}
	
        public function GetAutoSpamUsers()
        {
            $query = "SELECT c.userid
                    FROM spsp_checks AS c
                    JOIN (SELECT userid FROM spsp_fakes WHERE live = 1) AS f
                    ON c.userid = f.userid AND c.twitterid = f.userid
                    WHERE c.autoremove = 1 AND c.accounttype = 1
                    GROUP BY c.userid
                    ORDER BY c.autocheck ASC
                    LIMIT 0,3";
            
            $result = $this->SelectRecords($query);
            
            return $result;
        }
        
		public function GetAutoRemoveStatus($twid)
		{
			$query = "SELECT autoremove
						FROM spsp_checks AS c
						WHERE userid = :userid AND twitterid = :twitterid";
			
			$params = array('userid'=>array($twid,'INT',0),
						   	'twitterid'=>array($twid,'INT',0));
				
			$result = $this->SelectRecord($query,$params);
			
			return $result;
		}
	
        public function UpdateAutoRemove($userid,$twitterid,$time)
        {
            
            $query = "UPDATE spsp_checks 
                        SET autocheck = :time
                        WHERE userid = :userid AND twitterid = :twitterid";
                        
            $params = array('time'=>array($time,'INT',0),
                            'userid'=>array($userid,'INT',0),
                            'twitterid'=>array($twitterid,'INT',0));
                        
            $result = $this->UpdateRecord($query,$params);
            
            return $result;
        }
	
		public function UpdateAutoRemoveStatus($twid,$autoremove)
		{
			$query = "UPDATE spsp_checks
						SET autoremove = :autoremove
						WHERE userid = :userid AND twitterid = :twitterid";
			
			$params = array('autoremove'=>array($autoremove,'INT',0),
						   	'userid'=>array($twid,'INT',0),
						   	'twitterid'=>array($twid,'INT',0));
			
			$update = $this->UpdateRecord($query,$params);
			
			return $update;
		}
	
		public function AddLogin($twitterid,$created)
		{
			$query = "INSERT INTO spsp_logins (twitterid,created)
						VALUES (:twitterid,:created)";
			
			$params = array('twitterid'=>array($twitterid,'INT',0),
						   	'created'=>array($created,'INT',0));
			
			$result = $this->InsertRecord($query,$params);
			
			return $result;
		}
	
		public function CountCache($userid)
		{
			$query = "SELECT COUNT(*)
						FROM spsp_cache
						WHERE userid = :userid";
			
			$params = array('userid'=>array($userid,'INT',0));
			
			$result = $this->SelectCount($query,$params);
			
			return $result;
		}
	
		public function GetCache($userid)
		{
			$query = "SELECT *
						FROM spsp_cache
						WHERE userid = :userid";
			
			$params = array('userid'=>array($userid,'INT',0));
			
			$result = $this->SelectRecord($query,$params);
			
			return $result;
		}
        
		public function AddCache($userid,$lang,$averages,$spam,$created)
		{
			$query = "INSERT INTO spsp_cache (userid,lang,averages,fakers,created)
						VALUES (:userid,:lang,:averages,:spam,:created)";
			
			$params = array('userid'=>array($userid,'INT',0),
						 	'lang'=>array($lang,'STR',10000),
						 	'averages'=>array($averages,'STR',10000),
						 	'spam'=>array($spam,'STR',10000),
						 	'created'=>array($created,'INT',0));
			
			$result = $this->InsertRecord($query,$params);
			
			return $result;
			
		}
	
		public function UpdateCache($userid,$lang,$averages,$spam,$created)
		{
			$query = "UPDATE spsp_cache 
						SET lang = :lang, averages = :averages, fakers = :spam, created = :created
						WHERE userid = :userid";
			
			$params = array('userid'=>array($userid,'INT',0),
						 	'lang'=>array($lang,'STR',10000),
						 	'averages'=>array($averages,'STR',10000),
						 	'spam'=>array($spam,'STR',10000),
						 	'created'=>array($created,'INT',0));
			
			$result = $this->UpdateRecord($query,$params);
			
			return $result;
		}
	
		public function AddEmailSend($email,$subjectline,$created)
		{
			$query = "INSERT INTO spsp_emails_sent (email,subjectline,created)
						VALUES (:email,:subjectline,:created)";
			
			$params = array('email'=>array($email,'STR',255),
						   	'subjectline'=>array($subjectline,'STR',2000),
						   	'created'=>array($created,'INT',0));
			
			$result = $this->InsertRecord($query,$params);
			
			return $result;
		}
}

?>