<?php
/**
*	@author : Ahsan Zahid Chowdhury
*	@owner : Exception Solutions
*	@email : azc.pavel@gmail.com
*	@phone : +880 1677 533818
*	@since : 2014-04-20
*	@version : 1.0.1
*	Models Database Loader
*/

Final class LoadDBClass
{	
	
	function __construct()
	{
		
	}

	function __call($mth_name,$mth_arg)
	{
		echo "Unknown Member Call $mth_name<br>You can get all details by calling get_class_details() method<br>".PHP_EOL;
	}

	function __get($porp_name){
		echo "Unknown Property Call $porp_name<br>You can get all details by calling get_class_details() method<br>".PHP_EOL;	
	}

	function get_class_details()
	{		
		echo '<pre>';
		echo "<br><b>Class Name</b><br>";
		echo "\t".get_class($this);

		echo "<br><br><b>List of Methods</b><br>";		
		foreach (get_class_methods($this) as $key => $value) {
			echo "\t".$value."()<br>";
		}		
		
		echo "<br><b>List of Properties</b><br>";
		if(count(get_object_vars($this)) > 0)
			print_r($this);			
		else
			echo "\t"."No Properties Exists";
		
		exit;
	}

	
	function database($name = 0)
	{
		require (APPLICATION.'/config/database.php');
		
		$db_get_all_config = $db_config;

		$model =& get_model_instance();
		
		if($name === 0)
			$name = 'default';

		if($db_get_all_config[$name]['db'] == '')
			exit("No database selected...!<br/>Please check config file.");

		$model->db = new dbClass($db_get_all_config[$name]['driver'],$db_get_all_config[$name]['host'],$db_get_all_config[$name]['user'],
			$db_get_all_config[$name]['pass'],$db_get_all_config[$name]['db'],$db_get_all_config[$name]['dbPrefix'],$db_get_all_config[$name]['port'],
			$db_get_all_config[$name]['service'],$db_get_all_config[$name]['protocol'],$db_get_all_config[$name]['server'],
			$db_get_all_config[$name]['uid'],$db_get_all_config[$name]['options'],$db_get_all_config[$name]['autocommit'],
			$db_get_all_config[$name]['preExecute'],$db_get_all_config[$name]['useDbEscape'],$db_get_all_config[$name]['charset'],
			$db_get_all_config[$name]['collation'],$db_get_all_config[$name]['engine']);

		return $model->db;
	}

	function library($load_libraries_name = '',$config = '')
	{
		$Model =& get_model_instance();

		$base_name = basename($load_libraries_name);

		if(file_exists(DOCUMENT_ROOT.BASEDIR.SYSTEM.'/libraries/'.$load_libraries_name.'.php'))
			{
				if(!class_exists($base_name))
					require(SYSTEM.'/libraries/'.$load_libraries_name.'.php');
				
				if(class_exists($base_name))
					{
						if($base_name === 'imgresize' || $base_name === 'zend' || $base_name == 'exqrcode' || $base_name == 'emoticons')
						{
							$Model->$base_name = new $base_name($config);
						}
						else
						{
							$Model->$base_name = new $base_name;

							if(is_array($config))
							foreach ($config as $key => $value) {
								
								if(property_exists($Model->$base_name, $key))
									$Model->$base_name->$key = $value;
							}

							unset($key);
							unset($value);
						}

					}
				else
					exit("Class $base_name not found in ".SYSTEM.'/libraries/'.$load_libraries_name.'.php');
			}
		else
			exit("Libraries not found in ".SYSTEM.'/libraries/'.$load_libraries_name.'.php');
	}

	function app_library($load_libraries_name = '', $config = '')
	{
		$Model =& get_model_instance();

		$base_name = basename($load_libraries_name);

		if(file_exists(APPLICATION.'/libraries/'.$load_libraries_name.'.php'))
			{
				if(!class_exists($base_name))
					require(APPLICATION.'/libraries/'.$load_libraries_name.'.php');
				
				if(class_exists($base_name))
					{
						
						$Model->$base_name = new $base_name;

						if(is_array($config))
						foreach ($config as $key => $value) {
							
							if(property_exists($Model->$base_name, $key))
								$Model->$base_name->$key = $value;
						}

						unset($key);
						unset($value);						

					}
				else
					exit("Class $base_name not found in your application".'/libraries/'.$load_libraries_name.'.php');
			}
		else
			exit("Libraries not found in your application".'/libraries/'.$load_libraries_name.'.php');
	}	
	
}
?>
