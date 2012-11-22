<?php

class PaymentRequests Extends DB
{
    
    public function CountUserDetails($twitterid)
    {
        $query = "SELECT COUNT(*)
                    FROM spsp_user_details
                    WHERE twitterid = :twitterid";
        
        $params = array('twitterid'=>array($twitterid,'INT',0));
        
        $result = $this->SelectCount($query, $params);
        
        return $result;
    }
    
    public function GetUserDetails($userid)
    {
        $query = "SELECT *
                    FROM spsp_user_details
                    WHERE twitterid = :twitterid";
        
        $params = array('twitterid'=>array($userid,'INT',0));
        
        $result = $this->SelectRecord($query, $params);
        
        return $result;
    }
    
    public function AddUserDetails($twitterid,$email,$title,$firstname,$surname,$created)
    {
        $query = "INSERT INTO spsp_user_details (twitterid,email,title,forename,surname,created)
                    VALUES (:twitterid,:email,:title,:forename,:surname,:created)";
        
        $params = array('twitterid'=>array($twitterid,'INT',0),
                        'email'=>array($email,'STR',255),
                        'title'=>array($title,'STR',150),
                        'forename'=>array($firstname,'STR',150),
                        'surname'=>array($surname,'STR',150),
                        'created'=>array($created,'INT',0));
        
//        Errors::DebugArray($params);
        
        $result = $this->InsertRecord($query,$params);
        
        return $result;
    }
    
    public function CreatePurchase($userid,$transactionid,$currency,$amount,$created)
    {

        $query = "INSERT INTO spsp_purchases (userid,transactionid,currency,amount,created)
                    VALUES (:userid,:transactionid,:currency,:amount,:created)";

        $params = array('userid'=>array($userid,'INT',0),
                        'transactionid'=>array($transactionid,'STR',64),
                        'currency'=>array($currency,'STR',3),
                        'amount'=>array($amount,'INT',0),
                        'created'=>array($created,'INT',0));
        
        $result = $this->InsertRecord($query,$params);

        return $result;

    }

    public function CountPurchases($transactionid)
    {
        $query = "SELECT COUNT(*)
                    FROM spsp_purchases
                    WHERE transactionid = :transactionid";
        
        $params = array('transactionid'=>array($transactionid,'INT',0));
        
        $result = $this->SelectCount($query,$params);
        
        return $result;
    }
    
    public function GetPurchaseDetails($transactionid)
    {
        $query = "SELECT *
                    FROM spsp_purchases
                    WHERE transactionid = :transactionid";
        
        $params = array('transactionid'=>array($transactionid,'INT',0));
        
        $result = $this->SelectRecord($query,$params);
        
        return $result;
    }
    
    public function CompletePurchase($transactionid)
    {
        $query = "UPDATE spsp_purchases 
                    SET complete = 1
                    WHERE transactionid = :transactionid";

        $params = array('transactionid'=>array($transactionid,'STR',64));

        $result = $this->InsertRecord($query,$params);

        return $result;
    }

    public function CountValidRecords($userid)
    {
        $query = "SELECT COUNT(*)
                    FROM spsp_valid
                    WHERE userid = :userid";

        $params = array('userid'=>array($userid,'INT',0));

        $result = $this->SelectCount($query,$params);

        return $result;
    }

    public function GetValidDate($userid)
    {
        $query = "SELECT valid
                    FROM spsp_valid
                    WHERE userid = :userid";

        $params = array('userid'=>array($userid,'INT',0));

        $result = $this->SelectRecord($query,$params);

        return $result;
    }

    public function CreateValidDate($purchaseid,$userid,$valid)
    {
        $query = "INSERT INTO spsp_valid (purchaseid,userid,valid)
                    VALUES (:purchaseid,:userid,:valid)";

        $params = array('purchaseid'=>array($purchaseid,'INT',0),
                        'userid'=>array($userid,'INT',0),
                        'valid'=>array($valid,'INT',0));

        $result = $this->InsertRecord($query,$params);

        return $result;
    }

    public function UpdateValidDate($purchaseid,$userid,$valid)
    {
        $query = "UPDATE spsp_valid
                    SET purchaseid = :purchaseid, valid = :valid
                    WHERE userid = :userid";

        $params = array('purchaseid'=>array($purchaseid,'INT',0),
                        'userid'=>array($userid,'INT',0),
                        'valid'=>array($valid,'INT',0));

        $result = $this->UpdateRecord($query,$params);

        return $result;
    }
    
}

?>