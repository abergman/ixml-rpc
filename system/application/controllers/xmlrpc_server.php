<?php

class Xmlrpc_server extends Controller {

	function index()
	{
		$this->load->library('xmlrpc');
		$this->load->library('xmlrpcs');
		$this->load->helper('file');		
		$config['functions']['AddHostFreeform'] = array('function' => 'Xmlrpc_server.AddHostFreeform');
		$this->load->model('Icinga_model');
		$this->xmlrpcs->initialize($config);
		$this->xmlrpcs->serve();
	}
	
function AddHostFreeform($request)
        {
		
                $parameters = $request->output_parameters();

                //Api key for client
		$key = "test1";

		//get apikey from request
		$apikey = $parameters['0']['apikey'];
		$hostname = $parameters['1']['host'];
		$filename = "/var/www/domains/".$hostname.".cfg"; 
		//Logic to fail if hostname-parameter is not sent		

		if($apikey == $key){
			   //If a file with suggested filename/hostname exist

			   if (file_exists($filename)) {
			      return $this->xmlrpc->send_error_message('002', 'Host Exist');
			      }
		
		else{

		//Create the file based on parameters sent
		$filedata = ""; 

		foreach($parameters['1'] as $key=>$value):
    		
		$data[$key] = $key;		
		$filedata .= $data[$key]." ". $value."\n"; 
							
    	 	 endforeach;		
		 
		 
		 //If file cannot be written
		 if(!$this->Icinga_model->write_host($filename, $filedata)){
		 return $this->xmlrpc->send_error_message('003', 'Write Failed');
		 }
		 //If everything seems ok
		 else{
		//Send response array
		       $response = array(

                                                        array(
                                                             $filedata),
                                                        'struct');

                		return $this->xmlrpc->send_response($response);
		}
		       }
		
		}
		
		//If the API-key is wrong
		else{
		return $this->xmlrpc->send_error_message('001', 'Auth Failed');
		} 
	}


}
?>
