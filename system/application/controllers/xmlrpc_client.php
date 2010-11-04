<?php

class Xmlrpc_client extends Controller {
	
	function index()
	{	
		$this->load->helper('url');
		$server_url = site_url('xmlrpc_server');
	
		$this->load->library('xmlrpc');
		$this->xmlrpc->set_debug(TRUE);		
		$this->xmlrpc->server($server_url, 80);
		$this->xmlrpc->method('AddHostFreeform');
		
		$request = array(array(array('apikey' => 'test1'),'struct'),array(array('host'=>'Host01','adress'=>'host01.test.se'),'struct'));
		$this->xmlrpc->request($request);	
		
		if ( ! $this->xmlrpc->send_request())
		{
			echo $this->xmlrpc->display_error();
		}
		else
		{
			echo '<pre>';
			print_r($this->xmlrpc->display_response());
			
			echo '</pre>';
			
		}
	}
}
?>
