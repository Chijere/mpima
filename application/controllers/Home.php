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

        //$this->load->library('Mobile_redirect');
        
        //$this->mobile_redirect->mobile_redirect();
        
        $this->load->library('user_account');
        
        $this->load->model('user_info_model');

        $this->load->model('item_model');
        
		//load header global vars
		$this->user_account->header_control(array('redirect_unlogged'=>false));		
    }	
 

	public function index() 
	{

		/*########################################
          0. load classes
        #######################################*/		    

        $this->load->model('Banner_model');
		$this->load->library('general_functions');

		/*##################################
		 1.	Define General Variables
		##################################*/
		 	$page_data=array();

		
        /*########################################
          2. Get Data from Database using Models
         #######################################*/  
   		$pass_data['from']=0;
   		$pass_data['take']=4; 
   		$pass_data['get_total_records']=true;
   		$pass_data['category_id']=2;


   		/*####Get Properties#####*/
		$model_data_property=$this->item_model->getItem($pass_data);

		/*####Get Banners######*/
		$pass_data=array(); // refresh pass_data
   		$model_data_banner=$this->Banner_model->getItem($pass_data);

		  

		 #do some formating to the results
   		foreach ($model_data_property['data']['records'] as $key => $value) {

			$model_data_property['data']['records'][$key]['price']='MK'.$value['price'];
			$model_data_property['data']['records'][$key]['date']=date( "j M Y", strtotime($value['date']));
			foreach ($value['item_pic'] as $key2 => $value2) {
				# calculate image dimensions
				$width=600;$height =600;
				if (file_exists($value2['path'].'.jpg')) 
					list($width, $height, $type, $attr) = getimagesize($value2['path'].'.jpg');	
				else
					$model_data_property['data']['records'][$key]['item_pic'][$key2]['path'] = 'media/default/images/no_image';
					$model_data_property['data']['records'][$key]['item_pic'][$key2]['dimension'] = $width.'x'.$height;
			}
			#limit length 				
			if(strlen($value['item_name'])>23)
				$model_data_property['data']['records'][$key]['item_name']=$this->general_functions->wordTrimmer($value['item_name'],23,'&hellip;');

			if(strlen($value['item_description'])>108)
				$model_data_property['data']['records'][$key]['item_description']=$this->general_functions->wordTrimmer($value['item_description'],108,'&hellip;');

   		}

   		#do some formating to the results
   		foreach ($model_data_banner['data']['records'] as $key => $value) {

			$model_data_banner['data']['records'][$key]['date']=date( "j M Y", strtotime($value['date']));
			foreach ($value['item_pic'] as $key2 => $value2) {
				# calculate image dimensions
				$width=600;$height =600;
				if (file_exists($value2['path'].'.jpg')) 
					list($width, $height, $type, $attr) = getimagesize($value2['path'].'.jpg');	
				else
					$model_data_banner['data']['records'][$key]['item_pic'][$key2]['path'] = 'media/default/images/no_image';
					$model_data_banner['data']['records'][$key]['item_pic'][$key2]['dimension'] = $width.'x'.$height;
			}
			#limit length 				
			if(strlen($value['summary'])>25)
				$model_data_banner['data']['records'][$key]['summary']=$this->general_functions->wordTrimmer($value['summary'],25,'&hellip;');

   		}

		$data['page_data']['item']= $model_data_property;	
		$data['page_data']['banner']= $model_data_banner;	
		$this->load->view('home/home',$data);

	}
 

}
