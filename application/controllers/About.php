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
		/*########################################
          0. load classes
        #######################################*/		    

        $this->load->model('Team_members_model');
		$this->load->library('general_functions');

		
		/*########################################
          1. Get Data from Database using Models
        #######################################*/		   
        
   		$pass_data = array(	
   						   );

   		$model_data=$this->Team_members_model->getItem($pass_data);

   		$href=base_url();
   		$addition_info=$model_data['addition_info'];
   		$status=$model_data['status'];


   		//do some formating to the results
   		foreach ($model_data['data']['records'] as $key => $value) {

			$model_data['data']['records'][$key]['date']=date( "j M Y", strtotime($value['date']));
			foreach ($value['item_pic'] as $key2 => $value2) {
				# calculate image dimensions
				$width=600;$height =600;
				if (file_exists($value2['path'].'.jpg')) 
					list($width, $height, $type, $attr) = getimagesize($value2['path'].'.jpg');	
				else
					$model_data['data']['records'][$key]['item_pic'][$key2]['path'] = 'media/default/images/no_image';
					$model_data['data']['records'][$key]['item_pic'][$key2]['dimension'] = $width.'x'.$height;
			}
			#limit length 				
			if(strlen($value['summary'])>25)
				$model_data['data']['records'][$key]['summary']=$this->general_functions->wordTrimmer($value['summary'],25,'&hellip;');

   		}

		/*########################################
          2. Send data to view
        #######################################*/		    

        $data['page_data']= array();	
        $data['page_data']['item']= $model_data;

        //echo "<pre>";
		//print_r($model_data);
		//return;
         //check if is ajax call
         // We added an ajax call because the item images got called by jquery after the page is already loaded
       if($this->input->is_ajax_request())
       {
        $data['info']=array();         
        $data['info']['item']= $model_data;        
        $data['print_as']='json';         
        $this->load->view('ajaxCall/ajaxCall',$data);  
       }
       else
       { 
		$this->load->view('about/about',$data);
       }

	}

  public function service()
  {

    $data['page_data']= array();  
    $this->load->view('about/service',$data);

  }

  public function download_service_brochure()
  {
    /*########################################
          0. load classes
        #######################################*/       

      $this->load->model('Team_members_model');
      $this->load->library('general_functions');

    
    /*########################################
          1. Get Data from Database using Models
        #######################################*/      
        
      $pass_data = array( 
                 );

      $model_data=$this->Team_members_model->getItem($pass_data);

      $href=base_url();
      $addition_info=$model_data['addition_info'];
      $status=$model_data['status'];


      //do some formating to the results
      foreach ($model_data['data']['records'] as $key => $value) {

      $model_data['data']['records'][$key]['date']=date( "j M Y", strtotime($value['date']));
      foreach ($value['item_pic'] as $key2 => $value2) {
        # calculate image dimensions
        $width=600;$height =600;
        if (file_exists($value2['path'].'.jpg')) 
          list($width, $height, $type, $attr) = getimagesize($value2['path'].'.jpg'); 
        else
          $model_data['data']['records'][$key]['item_pic'][$key2]['path'] = 'media/default/images/no_image';
          $model_data['data']['records'][$key]['item_pic'][$key2]['dimension'] = $width.'x'.$height;
      }
      
      #limit length         
      if(strlen($value['summary'])>25)
        $model_data['data']['records'][$key]['summary']=$this->general_functions->wordTrimmer($value['summary'],25,'&hellip;');

      }

    /*########################################
          2. Send data to view
        #######################################*/       

        $data['page_data']= array();  
        $data['page_data']['item']= $model_data;

        //echo "<pre>";
        //print_r($model_data);
        //return;
        //check if is ajax call
        // We added an ajax call because the item images got called by jquery after the page is already loaded
       if($this->input->is_ajax_request())
       {
        $data['info']=array();         
        $data['info']['item']= $model_data;        
        $data['print_as']='json';         
        $this->load->view('ajaxCall/ajaxCall',$data);  
       }
       else
       { 
    $this->load->view('about/about',$data);
       }

  }

  public function service_single()
  {

    $data['page_data']= array();  
    $this->load->view('about/service_single',$data);

  }

	public function contact()
	{

		$data['page_data']= array();	
		$this->load->view('about/contact',$data);

	}

	

}
