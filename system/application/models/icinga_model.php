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
			
		}
		else{ 			

			return '1';	

		}	
	
	
	}		

	function write_from_template($template,$filename,$filedata){



	}
}
?>
