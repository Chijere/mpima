<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class About extends CI_Controller {

	//set defults
	private $ownerCheck = 0;
	private $userInfo = array();
    
    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();

        //$this->load->library('Mobile_redirect');
        
        //$this->mobile_redirect->mobile_redirect();
        
        $this->load->library('user_account');
        
        $this->load->model('user_info_model');
        
		//load header global vars
		$this->user_account->header_control(array('redirect_unlogged'=>false));		
    }	
 

	public function index()
	{

		$data['page_data']= array();	
		$this->load->view('about/about',$data);

	}

	public function contact()
	{

		$data['page_data']= array();	
		$this->load->view('about/contact',$data);

	}

	

}
