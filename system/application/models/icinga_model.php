<?php
class Icinga_model extends Model {

    
    function Icinga_model()
    {
        // Call the Model constructor
        
parent::Model();
    }
	
	function write_host($filename,$filedata){
		 $writedata = "define host{\n".$filedata."}\n";
		 
		 if(!write_file($filename,$writedata)){
			return '0';		
		}
		else{ 			

			return '1';	

		}	
	
	
	}		
}
?>
