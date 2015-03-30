<?php

//namespace PorkPie\Glaze;

class Glaze {

	public function view($file,$data = null)
	{
	
		if (is_array($data))
		{
			extract($data);
		}
		
		require_once('Crust/' . $file);
		
		die();
	}	
	
}

?>