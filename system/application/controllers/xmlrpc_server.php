<?php

class Xmlrpc_server extends Controller {

	function index()
	{
		$this->load->library('xmlrpc');
		$this->load->library('xmlrpcs');
		$this->load->helper('file');				
		$config['functions']['AddHostFreeform'] = array('function' => 'Xmlrpc_server.AddHostFreeform');
		$config['functions']['AddHostFromTemplate'] = array('function' => 'Xmlrpc_server.AddHostFromTemplate');
		$this->load->model('Icinga_model');
		$this->xmlrpcs->initialize($config);
		$this->xmlrpcs->serve();
		
	}
	
function AddHostFreeform($request)
        {
		
                $parameters = $request->output_parameters();

                //Api key for client
		$apikey = $this->config->item('apikey');

		//get apikey from request
		$key = $parameters['0']['apikey'];
		$hostname = $parameters['1']['host'];
		$filename = $this->config->item('hostspath').$hostname.".cfg"; 
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
		       $response = array(array(

                                                        
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

function AddHostFromTemplate($request)
	 {
		$parameters = $request->output_parameters();
		$apikey = $this->config->item('apikey');	    
		$key = $parameters['0']['apikey'];
		$requestdata['template'] = "";
		if($key == $apikey){

		$template = $this->config->item('templatepath');
		
		//Get the template values
		foreach($parameters['1'] as $key=>$value){
		$requestdata[$key] = $value;
				
		}		   
		//Check if templatename is set
		if(!$requestdata['template']){
		return $this->xmlrpc->send_error_message('004', 'No template specified');
		}elseif(!$requestdata['hostname']){
		return $this->xmlrpc->send_error_message('006', 'No hostname specified');
		}elseif(!$requestdata['address']){
		return $this->xmlrpc->send_error_message('007', 'No hostadress specified')
		}
		$this->icinga_method->AddHostFromTemplate($requestdata['template']);
				

		//If the values is the request don't exist in the template as placeholders, send 005 Values out of bounds

		$response = array(

                     	                                  
        						                                               $templatedata,
                                                        'struct');

                                return $this->xmlrpc->send_response($response);
				}
		}	
		else{
			return $this->xmlrpc->send_error_message('001', 'Auth Failed');
		}
	
		 
	 }

}
?>
