<?php

/* BuildChutney is a non-essential part of PorkPiePHP and offers some methods for
generating html elements. Suggest you add all non essential model code to this class. */

//namespace PorkPie\Chutney;

class Build
{
	
	// Pass and array of error messages to ErrorMessages to generate an HTML error message. 
	
	public function ErrorMessages($message)
	{
		
		if (isset($message))
		{
			
			$output = '<div class="holder bgorange" id="error">';
			$output .= '<div class="row column">';
                        $output .= '<div class="one">';
                        $output .= '<div class="e1 a"><span class="sp1 bree white">Error!!</span></div>';
                        $output .= '<div class="e2 a">';
                        
			foreach ($message as $mess)
			{
				$output .= '<ul class="normal">';
				$output .= '<li>'.$mess.'</li>';
				$output .= '</ul>';	
			}
                        
			$output .= '</div>';
                        $output .= '<div class="e3 normal" id="errorclose">X</div>';
                        $output .= '</div>';
                        $output .= '</div>';
			$output .= '</div>';
			
			return $output;
				
		}
		
	}
	
	
	
        public function PersistantErrorMessages($message)
	{
		
		if (isset($message))
		{
			
			$output = '<div class="row one bgorange">';
			
			foreach ($message as $mess)
			{
				$output .= '<ul class="normal white">';
				$output .= '<li>'.$mess.'</li>';
				$output .= '</ul>';	
			}
			
			$output .= '</div>';	
		
			return $output;
		
		}
		
	}
        
	// Pass and array of success messages to SuccessMessages to generate an HTML success message. 
	
	public function SuccessMessages($message,$success = true)
	{
		
		if (isset($message))
		{
			
			$output = '<div class="holder bgdblue" id="'.($success?'success':'info').'">';
			$output .= '<div class="row column">';
                        $output .= '<div class="one">';
                        $output .= '<div class="e1 a"><span class="sp1 bree white">'.($success?'Success!!':'Info').'</span></div>';
                        $output .= '<div class="e2 a">';
                        
			foreach ($message as $mess)
			{
				$output .= '<ul class="normal">';
				$output .= '<li>'.$mess.'</li>';
				$output .= '</ul>';	
			}
                        
			$output .= '</div>';
                        $output .= '<div class="e3 normal" id="'.($success?'successclose':'infoclose').'">X</div>';
                        $output .= '</div>';
                        $output .= '</div>';
			$output .= '</div>';
			
			return $output;
		
		}
		
	}
        
        public function PersistantSuccessMessages($message)
	{
		
		if (isset($message))
		{
			
			$output = '<div class="row one bgdblue">';
			
			foreach ($message as $mess)
			{
				$output .= '<ul class="normal white">';
				$output .= '<li>'.$mess.'</li>';
				$output .= '</ul>';	
			}
			
			$output .= '</div>';	
		
			return $output;
		
		}
		
	}
	
	// Pass an array of links to Menu to generate an HTML menu.
	
	public function Menu($menu)
	{
	
		$output = '<ul class="menu">';
	
		foreach ($menu as $key => $obj)
		{
		
			$output .= '<li class="menu">';
			$output .= '<a href="';
			$output .= $obj;
			$output .= '" id="';
			$output .= $key;
			$output .= '" class="menu" >';
			$output .= $key;
			$output .= '</a>';
			$output .= '</li>';	
			
		}	
		
		$output .= '</ul>';
		
		return $output;
		
	}
	
	// Pass an array of links to SubMenu to generate an HTML submenu.
	
	public function SubMenu($menu)
	{
		
		$output = '<ul class="nav normal">';
		
		foreach ($menu as $key => $obj)
		{
			
			$output .= '<li class="submenu">';
			$output .= '<a href="';
			$output .= $obj;
			$output .= '" id="';
			$output .= $key;
			$output .= '" class="submenu" >';
			$output .= $key;
			$output .= '</a>';
			$output .= '</li>';	
			
		}	
		
		$output .= '</ul>';
		
		return $output;
		
	}
        
        public function SubSubMenu($menu)
	{
		
		$output = '<ul class="subsubmenu">';
		
		foreach ($menu as $key => $obj)
		{
			
			$output .= '<li class="subsubmenu">';
			$output .= '<a href="';
			$output .= $obj;
			$output .= '" id="';
			$output .= $key;
			$output .= '" class="subsubmenu" >';
			$output .= $key;
			$output .= '</a>';
			$output .= '</li>';	
			
		}	
		
		$output .= '</ul>';
		
		return $output;
		
	}
	
        public function NavigationMenu($tab,$submenu,$subtab = null)
        {

                if ($_SESSION['Access'] == 1 || $_SESSION['Access'] == 2 || $_SESSION['Access'] == 4 || $_SESSION['Access'] == 5)
                {
                    $menu['Dashboard'] = '/User/Home';
                    $menu['Social Hub'] = '/SocialMedia/Hub';
                }
                $menu['Reports'] = '/Reports';
                if ($_SESSION['Access'] == 1 || $_SESSION['Access'] == 2 || $_SESSION['Access'] == 4 || $_SESSION['Access'] == 5)
                {
                    $menu['Account'] = '/User/MyDetails';
                }
                $menu['Help'] = '/Help';
                
                $socialhub = array(
                    'Feeds'=>'/SocialMedia/Feeds',
                    'Campaigns'=>'/SocialMedia/Campaigns',
                    'Alerts'=>'/SocialMedia/Alerts',
                    'Image Cloud'=>'/Images'
                );
                
                $reports = array(
                    'Clicks'=>'/Reports/Clicks',
                    'Twitter'=>'/Reports/Twitter',
                    'Facebook'=>'/Reports/Facebook',
                    'Minions'=>'/Reports/Minions',
                    'Campaigns'=>'/Reports/Campaigns'
                );
                
                if ($_SESSION['Access'] == 1)
                {
                    $account['Accounts'] = '/User/Accounts';
                    $account['Users'] = '/User/AccountUsers';
                    $account['Connections'] = '/User/Authentication';
                    $account['API Keys'] = '/User/ApiKeys';
                    $account['Promote'] = '/User/Promote';
                    
                    $req = new DBRequests();
                    $parentid = $req->GetParentID($_SESSION['AccountID']);
                    
                    if ($parentid[1] == 0)
                    {
                        $account['Subscriptions'] = '/Products';
                    }
                }
                
                $help = array(
                    'Get Started'=>'/Help/GetStarted',
                    'FAQs'=>'/Help/FAQs',
                    'Feedback'=>'/Help/Feedback',
                    'Sitemap'=>'/Help/Sitemap',
                    'API'=>'/Help/API'
                );
                
                $submenus = array(
                    'Social Hub'=>$socialhub,
                    'Reports'=>$reports,
                    'Account'=>$account,
                    'Help'=>$help
                );

                $i = 1;
                
                foreach ($menu as $m=>$u)
                {
                    $output .= '<ul class="menu">';
                    $output .= '<li class="m1'.($m==$tab?' navtriangle':'').'"></li>';            
                    $output .= '<li class="m2 bree'.($m==$tab?' navdorange':' bglorange').'"><img src="/Pie/Crust/Template/img/'.str_replace(' ', '', $m).'.png" /><a href="'.Route::HREF($u,true).'" class="whitelink nounderline">'.$m.'</a></li>';      
                    if ($m==$tab&&$submenu)
                    {
                        foreach ($submenus as $s => $su)
                        {
                            
                            if ($s==$tab)
                            {
                                if (!empty($su))
                                {
                                    foreach ($su as $k=>$o)
                                    {
                                        $output .= '<ul class="submenu">';
                                        $output .= '<li class="sbm1'.($k==$subtab?' mininavtriangle':'').'"></li>';
                                        $output .= '<li class="sbm2"><a href="'.Route::HREF($o,true).'" class="whitelink nounderline">'.$k.'</a></li>';
                                        $output .= '</ul>';
                                    }
                                }
                            }
                        }
                    }
                    $output .= '</ul>';
                    $i++;
                }
                
                $output .= '<div class="bufferbottom bglorange"></div>';
                
                return $output;
        }
        
        public function PageMessage($type,$messages)
        {
            
            $output = '<div class="'.$type.'message bree" id="'.$type.'message">';
            $output .= '<div class="e1">';
            $output .= '<img src="/Pie/Crust/Template/img/'.$type.'_icon.png" />';
            $output .= '</div>';
            $output .= '<div class="e2 s0">';
            $output .= '<ul>';
            
            foreach ($messages as $m)
            {
                $output .= '<li>'.$m.'</li>';
            }
            
            $output .= '</ul>';
            $output .= '</div>';
            $output .= '<div class="e3 s0 '.$type.'close">';
            $output .= '<span id="'.$type.'close">X</span>';
            $output .= '</div>';
            $output .= '</div>';
            
            return $output;
        }
        
        public function SmallMessage($type,$messages)
        {
            
            $output = '<div class="row '.$type.'message bree" id="'.$type.'message">';
            $output .= '<div class="e1">';
            $output .= '<img src="/Pie/Crust/Template/img/'.$type.'_icon.png" />';
            $output .= '</div>';
            $output .= '<div class="e2 s0">';
            $output .= '<ul>';
            
            foreach ($messages as $m)
            {
                $output .= '<li>'.$m.'</li>';
            }
            
            $output .= '</ul>';
            $output .= '</div>';
            $output .= '<div class="e3 s0 '.$type.'close">';
            $output .= '<span id="'.$type.'close">X</span>';
            $output .= '</div>';
            $output .= '</div>';
            
            return $output;
        }
}

?>