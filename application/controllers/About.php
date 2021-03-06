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

    /*########################################
          0. load classes
        #######################################*/       

        $this->load->model('Services_model');
    $this->load->library('general_functions');
    
    
    /*########################################
          1. Get Data from Database using Models
        #######################################*/      
        
      $pass_data = array( 
                 );

      $model_data=$this->Services_model->getItem($pass_data);

      $href=base_url();
      $addition_info=$model_data['addition_info'];
      $status=$model_data['status'];


      //do some formating to the results
      foreach ($model_data['data']['records'] as $key => $value) {

      $model_data['data']['records'][$key]['date']=date( "j M Y", strtotime($value['date']));

      #limit length         
      if(strlen($value['item_description'])>200)
        $model_data['data']['records'][$key]['item_description']=$this->general_functions->wordTrimmer($value['item_description'],200,'&hellip;');

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
         $this->load->view('about/service',$data);
       }

  }

  public function download_service_brochure()
  {
    /*########################################
          0. load classes
        #######################################*/       

    $this->load->model('Services_model');
    $this->load->model('Download_model');
    $this->load->library('general_functions');
    
    /*########################################
          1. Get Data from Database using Models
        #######################################*/       
      $pass_data = array( 'item_id' => $this->uri->segment(3)
                 );

      $model_data=$this->Services_model->getItem($pass_data);
      $href=base_url();
      $addition_info=$model_data['addition_info'];
      $status=$model_data['status'];
      
    if($status)
    {

      if(count($model_data['data']['records']) > 0 )
      {
        $pass_data = array(
                      'file_name' => 'Brochure - (MpimaInvestments.com)',
                      'file_path' => $model_data['data']['records'][0]['item_pic']['main']['path'],
                        );
        $downloadSong=$this->Download_model->force_download($pass_data);
        

            if(!$downloadSong['status'])
            { 
              $result_info=$downloadSong['data']['result_info'];  
              $addition_info = $downloadSong['addition_info'];  
              $status=false;
            }
      }else 
      {
              $result_info='File not found';    
              $status=false;
      }      
    } 

    /*########################################
          2. Send data to view
        #######################################*/       

        $data['info']=array();         
        $data['info']['item']= $model_data;  

        
      if($this->input->is_ajax_request())
       {
             
        $data['print_as']='json';         
        $this->load->view('ajaxCall/ajaxCall',$data);  
       }
       else
       { 
         $this->service();
       }

  }

  public function service_single()
  {
    /*########################################
          0. load classes
        #######################################*/       

        $this->load->model('Services_model');
    $this->load->library('general_functions');
    
    /*########################################
          1. Get Data from Database using Models
        #######################################*/       
      $pass_data = array( 'item_id' => $this->uri->segment(2)
                 );

      $model_data=$this->Services_model->getItem($pass_data);

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

      }

    /*########################################
          2. Send data to view
        #######################################*/       

    $data['page_data']= array();  
    $data['page_data']['item']= $model_data;  
    $this->load->view('about/service_single',$data);
  }

	public function contact()
	{

		$data['page_data']= array();	
		$this->load->view('about/contact',$data);

	}

	

}
