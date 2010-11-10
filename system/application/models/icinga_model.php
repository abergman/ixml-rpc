<?php
class Icinga_model extends Model {

    
    function Icinga_model()
    {
        // Call the Model constructor
        
parent::Model();
    }
	
	function write_host($filename,$filedata){
		 if(!write_file($filename,$filedata)){
			return '0';		
		}
		else{ 			
			return '1';	
		}	
	
	
	}		
}
?>
