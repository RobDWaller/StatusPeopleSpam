<?php

class DBRequests extends DB
{
	protected $connection = 'statuspeople_spam';

        public function CountUsers($twitterid)
        {
            
            $query = "SELECT COUNT(*)
                        FROM spsp_users
                        WHERE twitterid = :twitterid AND live = 1";
            
            $params = array('twitterid'=>array($twitterid,'INT',0));
            
            $result = $this->SelectCount($query, $params);
            
            return $result;
            
        }
        
        public function GetTwitterDetails($twitterid)
        {
            
            $query = "SELECT *
                        FROM spsp_users
                        WHERE twitterid = :twitterid AND live = 1";
            
            $params = array('twitterid'=>array($twitterid,'INT',0));
            
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

		public function GetUserInfo($twitterid)
		{
			$query = "SELECT *
						FROM spsp_user_info
						WHERE twitterid = :twitterid AND live = 1";

			$params = array('twitterid'=>array($twitterid,'INT',0));

			$result = $this->SelectRecord($query,$params);

			return $result;			
		}

        public function GetUserInfoByScreenName($screenname)
        {
            $query = "SELECT *
                        FROM spsp_user_info
                        WHERE screen_name = :screen_name AND live = 1";

            $params = array('screen_name'=>array($screenname,'INT',0));

            $result = $this->SelectRecord($query,$params);

            return $result;         
        }

        public function GetLatestSpamRecords($limit)
        {
		
			$time = strtotime('-4 weeks');
            
            $query = "SELECT ui.screen_name, ui.avatar, sc.spam, sc.potential, sc.checks, sc.followers, ui.twitterid
                        FROM spsp_spam_scores AS sc 
                        JOIN spsp_user_info AS ui ON sc.twitterid = ui.twitterid
						WHERE sc.updated > ".$time."
						AND sc.followers > 250
						ORDER BY (sc.spam/sc.checks) DESC, sc.spam DESC, sc.updated DESC
                        LIMIT 0,:limit";
            
            $params = array('limit'=>array($limit,'INT',0));
            
            $result = $this->SelectRecords($query,$params);
            
            return $result;
            
        }
		
		public function GetFakersWall($limit)
		{
			$query = "SELECT sc.screen_name, sc.avatar, sc.spam, sc.potential, sc.checks, sc.followers, sc.twitterid
                        FROM spsp_fakers_wall AS sc 
						GROUP BY sc.twitterid
                        ORDER BY (sc.spam/sc.checks) DESC, sc.spam DESC, sc.updated DESC
						LIMIT 0,:limit";
            
            $params = array('limit'=>array($limit,'INT',0));
            
            $result = $this->SelectRecords($query,$params);
            
            return $result;
		}
	
		public function GetSpamScoreDetails()
		{
			$query = "SELECT ui.screen_name, ui.avatar, sc.spam, sc.potential, sc.checks, sc.followers, ui.twitterid, sc.updated
                        FROM spsp_spam_scores AS sc 
                        JOIN spsp_user_info AS ui ON sc.twitterid = ui.twitterid
                        ORDER BY sc.apicheck ASC
                        LIMIT 0,1000";
            
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
        
		public function UpdateAPICheck($twitterid,$apicheck)
		{
			$query = "UPDATE spsp_spam_scores
						SET apicheck = :apicheck
						WHERE twitterid = :twitterid";
			
			$params = array('twitterid'=>array($twitterid,'INT',0),
                            'apicheck'=>array($apicheck,'INT',0));
			
			$result = $this->UpdateRecord($query,$params);
			
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
        
		public function UpdateUserInfo($twitterid,$screenname,$avatar)
        {
            
            $query = "UPDATE spsp_user_info
						SET screen_name = :screen_name,
						avatar = :avatar
						WHERE twitterid = :twitterid AND live = 1";
            
            $params = array('twitterid'=>array($twitterid,'INT',0),
                            'screen_name'=>array($screenname,'STR',140),
                            'avatar'=>array($avatar,'STR',255));
            
            $result = $this->UpdateRecord($query, $params);
            
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
	
		public function GetUserToCheck($userid)
		{
			$query = "SELECT *
						FROM spsp_checks
						WHERE accounttype = 1 AND live = 1 AND userid = :userid AND lastcheck < (UNIX_TIMESTAMP()-(3600*12))
						ORDER BY lastcheck ASC
						LIMIT 0,1";
			
			$params = array('userid'=>array($userid,'INT',0));
			
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
	
		public function CountBlocked($userid)
		{
			$query = "SELECT COUNT(*)
                        FROM spsp_fakes
                        WHERE userid = :userid AND live = 0 AND notspam = 0";
            
            $params = array('userid'=>array($userid,'INT',0));
            
            $result = $this->SelectCount($query, $params);
            
            return $result;
		}
	
		public function FindFake($userid,$string)
		{
			$query = "SELECT * 
						FROM spsp_fakes
						WHERE live = 0 AND notspam = 0 AND userid = :userid AND screen_name LIKE :string
						LIMIT 0,10";
			
			$params = array('userid'=>array($userid,'INT',0),
						   'string'=>array('%'.$string.'%','INT',0));
			
			$result = $this->SelectRecords($query,$params);
			
			return $result;
		}
	
        public function GetCompetitors($userid)
        {
            $query = "SELECT c.userid, cs.twitterid, cs.screen_name, c.avatar, cs.spam, cs.potential, cs.checks, cs.followers, cs.created, c.updated, c.lastcheck, c.live
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
            $query = "SELECT c0.spam, c0.potential, c0.checks, DATE_FORMAT(FROM_UNIXTIME(created),'%b %d') as date, c0.created, c0.id 
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
        
		public function UpdateFakerCheck($twitterid,$screen_name,$avatar)
		{
			$query = "UPDATE spsp_checks 
						SET screen_name = :screen_name,
						avatar = :avatar
						WHERE twitterid = :twitterid AND live = 1";
			
			
			$params = array('twitterid'=>array($twitterid,'INT',0),
                            'screen_name'=>array($screen_name,'STR',140),
                            'avatar'=>array($avatar,'STR',255));
            
            $result = $this->UpdateRecord($query,$params);
            
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
						   'twitterid'=>array($userid,'INT',0));
			
			$result = $this->SelectCount($query,$params);
			
			return $result;
		}
	
        public function GetAutoSpamUsers()
        {
			$time = time();

			$query = "SELECT c.userid
                    FROM spsp_checks AS c
                    JOIN spsp_valid AS v on c.userid = v.userid
                    JOIN spsp_purchases AS p on v.purchaseid = p.id
                    WHERE c.autoremove = 1 
                    AND c.accounttype = 1
                    AND v.valid >= $time
                    AND p.type >= 2
                    GROUP BY c.userid
                    ORDER BY c.autocheck ASC
                    LIMIT 0,10";
            
            $result = $this->SelectRecords($query);
            
            return $result;
        }
        
		public function GetAutoRemoveAccounts($limit,$time)
		{
            $time = time();

			$query = "SELECT c.*
                    FROM spsp_checks AS c
                    JOIN spsp_valid AS v on c.userid = v.userid
                    JOIN spsp_purchases AS p on v.purchaseid = p.id
                    WHERE c.autoremove = 1
					AND lastcheck < :time
                    AND v.valid >= $time
                    AND p.type >= 2
                    GROUP BY c.userid
                    ORDER BY c.lastcheck ASC
                    LIMIT 0,:limit";
            
			$params = array('time'=>array($time,'INT',0),
                            'limit'=>array($limit,'INT',0));
			
            $result = $this->SelectRecords($query,$params);
            
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
	
		public function AddLogin($twitterid,$ip,$created)
		{
			$query = "INSERT INTO spsp_logins (twitterid,ipaddress,created)
						VALUES (:twitterid,:ipaddress,:created)";
			
			$params = array('twitterid'=>array($twitterid,'INT',0),
							'ipaddress'=>array($ip,'STR',32),
						   	'created'=>array($created,'INT',0));
			
			$result = $this->InsertRecord($query,$params);
			
			return $result;
		}

        public function GetLogins($start,$limit)
        {
            $query = "SELECT * 
                        FROM spsp_logins
                        ORDER BY created DESC
                        LIMIT :start,:limit";

            $params = array('start'=>array($start,'INT',0),
                            'limit'=>array($limit,'INT',0));

            $result = $this->SelectRecords($query,$params);

            return $result;
        }

        public function GetPurchaseLogins($start,$limit)
        {
            $query = "select l.*,p.created,from_unixtime(p.created) 
                        from spsp_purchases as p 
                        join spsp_logins as l 
                        on p.userid = l.twitterid 
                        group by l.ipaddress 
                        order by p.created desc 
                        limit :start,:limit";

            $params = array('start'=>array($start,'INT',0),
                            'limit'=>array($limit,'INT',0));

            $result = $this->SelectRecords($query,$params);

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
        
		public function GetCacheDate($userid)
		{
			$query = "SELECT created
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
	
		public function DeleteCache()
		{
			$query = "DELETE FROM spsp_cache
						WHERE created < ".strtotime('-1 Month');
						
			$result = $this->DeleteRecords($query);
			
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
	
		public function GetCheckersFromChecks()
		{
			$query = "SELECT userid
						FROM spsp_checks
						WHERE userid = twitterid AND accounttype = 1
						AND live = 1";
			
			$result = $this->SelectRecords($query);
			
			return $result;
		}
	
		public function GetCheckers()
		{
			$query = "SELECT sc.userid,FROM_UNIXTIME(sv.valid),FROM_UNIXTIME(sc.updated)
						FROM spsp_checkers AS sc
						JOIN spsp_valid AS sv ON sc.userid = sv.userid
						WHERE sv.valid > (UNIX_TIMESTAMP()-(3600*24*30))
						ORDER BY sc.updated ASC
						LIMIT 0,20";
			
			// $query = "SELECT sc.userid,FROM_UNIXTIME(sv.valid),FROM_UNIXTIME(sc.updated)
						// FROM spsp_checkers AS sc
						// JOIN spsp_valid AS sv ON sc.userid = sv.userid
						// WHERE sv.valid > (UNIX_TIMESTAMP()-(3600*24*30))
						// AND sc.userid = 198192466";
			
			$result = $this->SelectRecords($query);
			
			return $result;
		}
	
		public function AddChecker($userid,$updated,$created)
		{
			$query = "INSERT INTO spsp_checkers (userid,updated,created)
						VALUES (:userid,:updated,:created)";
			
			$params = array('userid'=>array($userid,'INT',0),
						   'updated'=>array($updated,'INT',0),
						   'created'=>array($created,'INT',0));
			
			$result = $this->InsertRecord($query,$params);
			
			return $result;
		}
	
		public function UpdateCheckerTime($userid,$updated)
		{
			$query = "UPDATE spsp_checkers
						SET updated = :updated
						WHERE userid = :userid";
			
			$params = array('userid'=>array($userid,'INT',0),
						   'updated'=>array($updated,'INT',0));
			
			//Errors::DebugArray($params);
			
			$result = $this->UpdateRecord($query,$params);
			
			return $result;
		}
	
		public function GetSites()
		{
			$query = "SELECT * 
					FROM spsp_suggested_sites
					WHERE live = 1 AND added = 0";
			
			$result = $this->SelectRecords($query);
			
			return $result;
		}
	
		public function GetSite($url)
		{
			$query = "SELECT * 
						FROM spsp_suggested_sites
						WHERE url = :url AND live = 1";
			
			$params = array('url'=>array($url,'STR',255));
			
			$result = $this->SelectRecord($query,$params);
			
			return $result;
		}
		
		public function CheckForSite($url)
		{
			$query = "SELECT COUNT(*)
						FROM spsp_suggested_sites
						WHERE live = 1 AND url = :url";
			
			$params = array('url'=>array($url,'STR',255));
			
			$result = $this->SelectCount($query,$params);
			
			return $result;
		}
	
		public function CheckIPCount($ip,$created)
		{
			$query = "SELECT COUNT(*)
						FROM spsp_suggested_sites
						WHERE ipaddress = :ip AND created > :created";
			
			$params = array('ip'=>array($ip,'STR',20),
						   'created'=>array($created,'INT',0));
			
			$result = $this->SelectCount($query,$params);
			
			return $result;
		}
	
		public function GetValidatedSites()
		{
			$query = "SELECT *
						FROM spsp_valid_sites
						WHERE live = 1";
			
			$result = $this->SelectRecords($query);
			
			return $result;
		}
	
		public function CheckForValidSite($url)
		{
			$query = "SELECT COUNT(*)
						FROM spsp_valid_sites
						WHERE live = 1 AND url = :url";
			
			$params = array('url'=>array($url,'STR',255));
			
			$result = $this->SelectCount($query,$params);
			
			return $result;
		}
	
		public function AddSite($url,$title,$ipaddress,$created)
		{
			$query = "INSERT INTO spsp_suggested_sites (url,title,ipaddress,created)
						VALUES (:url,:title,:ipaddress,:created)";
			
			$params = array('url'=>array($url,'STR',255),
						   'title'=>array($title,'STR',255),
							'ipaddress'=>array($ipaddress,'STR',20),
						   'created'=>array($created,'INT',0));
			
			$result = $this->InsertRecord($query,$params);
			
			return $result;
		}
	
		public function UpdateSiteCount($id,$ipaddress,$suggestions)
		{
			$query = "UPDATE spsp_suggested_sites
						SET ipaddress = :ipaddress,
						suggestions = :suggestions
						WHERE id = :id";
			
			$params = array('id'=>array($id,'STR',255),
							'ipaddress'=>array($ipaddress,'STR',20),
						   	'suggestions'=>array($suggestions,'INT',0));
			
			$result = $this->UpdateRecord($query,$params);
			
			return $result;
		}
			
		public function AddValidSite($suggestedid,$url,$title,$image,$description,$created)
		{
			$query = "INSERT INTO spsp_valid_sites (suggestedid,url,title,image,description,created)
						VALUES (:suggestedid,:url,:title,:image,:description,:created)";
			
			$params = array('suggestedid'=>array($suggestedid,'INT',0),
						   'url'=>array($url,'STR',255),
						   'title'=>array($title,'STR',255),
						   'image'=>array($image,'STR',255),
						   'description'=>array($description,'STR',255),
						   'created'=>array($created,'INT',0));
			
			$result = $this->InsertRecord($query,$params);
			
			return $result;
		}
	
		public function DeleteValidSite($id)
		{
			$query = "UPDATE spsp_valid_sites 
						SET live = 0
						WHERE id = :id";
			
			$params = array('id'=>array($id,'INT',0));
			
			$result = $this->UpdateRecord($query,$params);
			
			return $result;
		}
	
		public function AddMarketingEmail($email,$forename,$surname,$created)
		{
			$query = "INSERT INTO spsp_marketing (email,forename,surname,created)
						VALUES (:email,:forename,:surname,:created)";
			
			$params = array('email'=>array($email,'STR',255),
						   	'forename'=>array($forename,'STR',50),
						   	'surname'=>array($surname,'STR',50),
						   	'created'=>array($created,'INT',0));
			
			//Errors::PrintArray($params);
			
			$result = $this->InsertRecord($query,$params);
			
			return $result;
		}
	
		public function AddMarketingEmails($string)
		{
			$query = "INSERT IGNORE INTO spsp_marketing (email,forename,surname,created)
						VALUES ".$string;
			
			$result = $this->InsertRecord($query);
			
			return $result;
		}
	
		public function GetMarketingEmails()
		{
			$query = "SELECT * 
						FROM spsp_marketing
						WHERE live = 1";
			
			$result = $this->SelectRecords($query);
			
			return $result;
		}
	
		public function CheckMarketingEmail($email)
		{
			$query = "SELECT COUNT(*)
						FROM spsp_marketing
						WHERE email = :email AND live = 1";
			
			$params = array('email'=>array($email,'STR',255));
			
			$result = $this->SelectCount($query,$params);
			
			return $result;
		}
	
		public function UnsubscribeEmail($email)
		{
			$query = "UPDATE spsp_marketing
						SET live = 0
						WHERE email = :email";
			
			$params = array('email'=>array($email,'STR',255));
			
			$result = $this->UpdateRecord($query,$params);
			
			return $result;
		}

		public function CheckForParent($parentid,$userid)
		{
			$query = "SELECT COUNT(*)
						FROM spsp_parents
						WHERE parentid = :parentid AND userid = :userid AND live = 1";
			
			$params = array('parentid'=>array($parentid,'INT',0),
						   'userid'=>array($userid,'INT',0));
			
			$result = $this->SelectCount($query,$params);
			
			return $result;
		}
	
		public function YourParents($userid)
		{
			$query = "SELECT ui.*
						FROM spsp_parents AS p
						JOIN spsp_user_info AS ui ON p.parentid = ui.twitterid
						WHERE p.userid = :userid AND p.live = 1 AND ui.live = 1";
			
			$params = array('userid'=>array($userid,'INT',0));
			
			$result = $this->SelectRecords($query,$params);
			
			return $result;
		}
	
		public function YourChildren($parentid)
		{
			$query = "SELECT ui.*
						FROM spsp_parents AS p
						JOIN spsp_user_info AS ui ON p.userid = ui.twitterid
						WHERE p.parentid = :parentid AND p.live = 1 AND ui.live = 1";
			
			$params = array('parentid'=>array($parentid,'INT',0));
			
			$result = $this->SelectRecords($query,$params);
			
			return $result;
		}
	
		public function GetAllChildren()
		{
			$query = "SELECT userid 
						FROM spsp_parents
						WHERE live = 1";
			
			$result = $this->SelectRecords($query);
			
			return $result;
		}
	
		public function AddParent($parentid,$userid,$created)
		{
			$query = "INSERT INTO spsp_parents (parentid,userid,created)
						VALUES (:parentid,:userid,:created)";
			
			$params = array('parentid'=>array($parentid,'INT',0),
						   'userid'=>array($userid,'INT',0),
						   'created'=>array($created,'INT',0));
			
			$result = $this->InsertRecord($query,$params);
			
			return $result;
		}
	
		public function DeleteParentUserRelationship($parentid,$userid)
		{
			$query = "UPDATE spsp_parents
						SET live = 0
						WHERE parentid = :parentid AND userid = :userid";
			
			$params = array('parentid'=>array($parentid,'INT',0),
						   'userid'=>array($userid,'INT',0));
			
			$result = $this->UpdateRecord($query,$params);
			
			return $result;
		}
	
		public function GetProcessors()
		{
			$query = "SELECT * 
					FROM spsp_queue_processors
					ORDER BY last_use ASC
					LIMIT 0,5";
					
			$result = $this->SelectRecords($query);

			return $result;
		}
		
		public function GetUserInQueue()
		{
			$query = "SELECT * 
					FROM spsp_queue
					WHERE live = 1
					ORDER BY created DESC
					LIMIT 0,1";
					
			$result = $this->SelectRecords($query);
			
			return $result;
		}
		
		public function UpdateProcessorTime($twitterid,$time)
		{
			$query = "UPDATE spsp_queue_processors
					SET last_use = :time
					WHERE twitterid = :twitterid";
					
			$params = array('time'=>array($time,'INT',0),
							'twitterid'=>array($twitterid,'INT',0));
							
			$result = $this->UpdateRecord($query,$params);
			
			return $result;
		}
		
		public function UpdateUserQueue($twitterid)
		{
			$query = "UPDATE spsp_queue
						SET live = 0
						WHERE twitterid = :twitterid";
						
			$params = array('twitterid'=>array($twitterid,'INT',0));
							
			$result = $this->UpdateRecord($query,$params);
			
			return $result;			
		}
}

?>