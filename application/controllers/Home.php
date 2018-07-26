<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	//set defults
	private $ownerCheck = 0;
	private $userInfo = array();
    
    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();

        $this->load->library('Mobile_redirect');
        
        $this->mobile_redirect->mobile_redirect();
        
        $this->load->library('user_account');
        
        $this->load->model('user_info_model');

        $this->load->model('item_model');
        
		//load header global vars
		$this->user_account->header_control(array('redirect_unlogged'=>false));		
    }	
 

	public function index() 
	{
		/*##################################
		 0.	Define General Variables
		##################################*/
		 	$page_data=array();

		
        /*########################################
          2. Get Data from Database using Models
         #######################################*/  
   		$pass_data['from']=0;
   		$pass_data['take']=4;
   		$pass_data['get_total_records']=true;
   		$pass_data['category_id']=2;


		$model_data=$this->item_model->getItem($pass_data);
		  

		 #do some formating to the results
   		foreach ($model_data['data']['records'] as $key => $value) {

			$model_data['data']['records'][$key]['price']='MK'.$value['price'];
			$model_data['data']['records'][$key]['date']=date( "j M Y", strtotime($value['date']));

			#limit length 				
			if(strlen($value['item_name'])>23)
				$model_data['data']['records'][$key]['item_name']=$this->general_functions->wordTrimmer($value['item_name'],23,'&hellip;');

			if(strlen($value['item_description'])>108)
				$model_data['data']['records'][$key]['item_description']=$this->general_functions->wordTrimmer($value['item_description'],108,'&hellip;');

   		}

		$data['page_data']['item']= $model_data;	
		$this->load->view('home/home',$data);

	}
 

}
