<?php

//Autoloader callback function

function autoLoader($class)
{
	
	/*echo '<p>'.$class.'</p>';*/
	
	// Array of potential folders your classes can exist in
	
	$directories = array(
		'/Pie/',
		'/Pie/Jelly/',
		'/Pie/Pork/',
		'/Pie/Bind/',
        '/Pie/Chutney/'
	);
	
	// Array of class filename types that exist within the framework
	
	$filenames = array(
		'%s.php',
		'%s.jelly.php',
		'%s.pork.php',
		'%s.bind.php',
        '%s.chutney.php'
	);
	
	// Loop through the directories and filenames to find the relevant class file
	
        //print_r($directories);
	require_once('vendor/autoload.php');

    foreach ($directories as $directory)
	{
		foreach ($filenames as $file)
		{
			
			$path = __SITE_PATH.$directory.sprintf($file, strtolower($class));
			$path2 = __SITE_PATH.$directory.sprintf($file,str_replace('\\','/',$class));
                        //echo $path.'<br/>';
                        
			/*echo '<p>'.$path.'</p>';*/
			
			if (file_exists($path))
			{
				//echo $path.'<br/>';
				require_once($path);	
				
			}
			else if (file_exists($path2))
			{
				require_once($path2);
			}

			
		}
		
	}
	
}

// Sets the autoloader callback function

spl_autoload_register('autoLoader');

?>