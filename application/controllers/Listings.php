<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Listings extends CI_Controller {

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
 

	public function index() #this function handle propety for sell/rent directs
	{
		/*##################################
		 0.	Define General Variables
		##################################*/
		 	$page_data=array();

		/*##################################
		 1.	For Pagination -part1
		 	- define necessary variables
		##################################*/
	    $records_per_page=6;
	    $current_page=$this->input->get('pg',true);
	    if($current_page<1)
	      $current_page=1;
	    #caculate amount to get
	    $take_from=ceil($records_per_page*($current_page-1));
	   	
		/*#####################################
          2. Construct URL Parameters
	    #####################################*/
	    
	    #Define them
	    $priceUrlPart="";$paginationUrlPart="";$locationUrlPart="";$typeUrlPart="";$Url_value=array();

		if(!empty($this->input->get('lct',true)))						    
   		{
   			$Url_value['lct'] = $this->input->get('lct',true);
   			$pass_data['location_id']=$this->input->get('lct',true);
   			$locationUrlPart = '&lct='.urlencode ($this->input->get('lct',true));
   		}		

		if(!empty($this->input->get('typ',true)))						    
   		{
   			$Url_value['typ'] = $this->input->get('typ',true);
   			$pass_data['type_id']=$this->input->get('typ',true);
   			$typeUrlPart = '&typ='.urlencode ($this->input->get('typ',true));
   		}		

		if(!empty($this->input->get('prc',true)))						    
   		{
   			$price = explode('-', $this->input->get('prc',true));
   			$priceFrom=preg_replace('/\D/','', $price[0]);
   			if(!empty($priceFrom))
   			{
   				$pass_data['price_from']=$priceFrom;
   				$priceUrlPart = '&prf='.urlencode ($priceFrom);
   			}
   			
   			$priceTo=preg_replace('/\D/','', $price[1]);	  			
   			if(!empty($priceTo))
   			{
   				$pass_data['price_to']=$priceTo;
   				$priceUrlPart .= '&prt='.urlencode ($priceTo);
   			}

   			$Url_value['prc'] = $priceFrom.'-'.$priceTo;	
   		}

		if(!empty($this->input->get('pg',true)))						    
   		{

   			$Url_value['pg'] = $this->input->get('pg',true);
   			$paginationUrlPart = '&pg='.urlencode ($this->input->get('pg',true));
   		}		

		
   		#make uri to be returned for filter 
		$url_parameters=array();
		$url_parameters['url'] = base_url().'listings/?ty=eer'.$priceUrlPart.$paginationUrlPart.$locationUrlPart.$typeUrlPart; 
		$url_parameters['params'] = $Url_value; 

		/*########################################
          3. load classes
        #######################################*/		    

          $this->load->model('Type_model');
          $this->load->model('Category_model');
          $this->load->model('Location_model');

        /*########################################
          4. Get Data from Database using Models
         #######################################*/  

		$type =$this->Type_model->getType(); 
		$category =$this->Category_model->getCategory(); 
		$location =$this->Location_model->getLocation(); 

   		$pass_data['from']=$take_from;
   		$pass_data['take']=$records_per_page;
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

		/*
		###############################
		 5. Send data to Views
		############################### */

   		$data['page_data']= array();			
		$data['page_data']['type']= $type;	
		$data['page_data']['category']= $category;	
		$data['page_data']['location']= $location;	

			/*
			###############################
			 1.1 For Pagination -part2
			###############################
		    */
			if(!isset($model_data['data']['total_records']))$model_data['data']['total_records']=1;
			$total_pages=ceil($model_data['data']['total_records']/$records_per_page);
			
			if($current_page>1)$prev_page=$current_page-1;else$prev_page=1;
			if($current_page>$total_pages)$next_page=$current_page+1;else$next_page=$total_pages;

			$data['page_data']['pagination']=array(
														'href'=>$url_parameters['url'],
														'current_page'=>$current_page,
														'total_pages'=>$total_pages,
													);
		   		/*
			###############################
			 	End For Pagination 
			###############################
		    */

		$data['page_data']['item']= $model_data;	
		$data['page_data']['url_parameters']= $url_parameters;	
		$this->load->view('listings/listings_property',$data);

	}
 

	public function land()
	{
		/*##################################
		 0.	Define General Variables
		##################################*/
		 	$page_data=array();

		/*##################################
		 1.	For Pagination -part1
		 	- define necessary variables
		##################################*/
	    $records_per_page=6;
	    $current_page=$this->input->get('pg',true);
	    if($current_page<1)
	      $current_page=1;
	    #caculate amount to get
	    $take_from=ceil($records_per_page*($current_page-1));
	   	
		/*#####################################
          2. Construct URL Parameters
	    #####################################*/
	    
	    #Define them
	    $priceUrlPart="";$paginationUrlPart="";$locationUrlPart="";$typeUrlPart="";$Url_value=array();

		if(!empty($this->input->get('lct',true)))						    
   		{
   			$Url_value['lct'] = $this->input->get('lct',true);
   			$pass_data['location_id']=$this->input->get('lct',true);
   			$locationUrlPart = '&lct='.urlencode ($this->input->get('lct',true));
   		}		

		if(!empty($this->input->get('typ',true)))						    
   		{
   			$Url_value['typ'] = $this->input->get('typ',true);
   			$pass_data['type_id']=$this->input->get('typ',true);
   			$typeUrlPart = '&typ='.urlencode ($this->input->get('typ',true));
   		}		

		if(!empty($this->input->get('prc',true)))						    
   		{
   			$price = explode('-', $this->input->get('prc',true));
   			$priceFrom=preg_replace('/\D/','', $price[0]);
   			if(!empty($priceFrom))
   			{
   				$pass_data['price_from']=$priceFrom;
   				$priceUrlPart = '&prf='.urlencode ($priceFrom);
   			}
   			
   			$priceTo=preg_replace('/\D/','', $price[1]);	  			
   			if(!empty($priceTo))
   			{
   				$pass_data['price_to']=$priceTo;
   				$priceUrlPart .= '&prt='.urlencode ($priceTo);
   			}

   			$Url_value['prc'] = $priceFrom.'-'.$priceTo;	
   		}

		if(!empty($this->input->get('pg',true)))						    
   		{

   			$Url_value['pg'] = $this->input->get('pg',true);
   			$paginationUrlPart = '&pg='.urlencode ($this->input->get('pg',true));
   		}		

		
   		#make uri to be returned for filter 
		$url_parameters=array();
		$url_parameters['url'] = base_url().'listings/?ty=eer'.$priceUrlPart.$paginationUrlPart.$locationUrlPart.$typeUrlPart; 
		$url_parameters['params'] = $Url_value; 


		/*########################################
          3. load classes
        #######################################*/		    

          $this->load->model('Type_model');
          $this->load->model('Category_model');
          $this->load->model('Location_model');

        /*########################################
          4. Get Data from Database using Models
         #######################################*/  

		$type =$this->Type_model->getType(); 
		$category =$this->Category_model->getCategory(); 
		$location =$this->Location_model->getLocation(); 

   		$pass_data['from']=$take_from;
   		$pass_data['take']=$records_per_page;
   		$pass_data['get_total_records']=true;
   		$pass_data['category_id']=4;


		$model_data=$this->item_model->getItem($pass_data);
		   		//do some formating to the results
   		foreach ($model_data['data']['records'] as $key => $value) {

			$model_data['data']['records'][$key]['price']='MK'.$value['price'];
			$model_data['data']['records'][$key]['date']=date( "j M Y", strtotime($value['date']));

			#limit length 				
			if(strlen($value['item_name'])>23)
				$model_data['data']['records'][$key]['item_name']=$this->general_functions->wordTrimmer($value['item_name'],23,'&hellip;');

			if(strlen($value['item_description'])>108)
				$model_data['data']['records'][$key]['item_description']=$this->general_functions->wordTrimmer($value['item_description'],108,'&hellip;');

   		}

		/*
		###############################
		 5. Send data to Views
		############################### */

   		$data['page_data']= array();			
		$data['page_data']['type']= $type;	
		$data['page_data']['category']= $category;	
		$data['page_data']['location']= $location;	

			/*
			###############################
			 1.1 For Pagination -part2
			###############################
		    */
			if(!isset($model_data['data']['total_records']))$model_data['data']['total_records']=1;
			$total_pages=ceil($model_data['data']['total_records']/$records_per_page);
			
			if($current_page>1)$prev_page=$current_page-1;else$prev_page=1;
			if($current_page>$total_pages)$next_page=$current_page+1;else$next_page=$total_pages;

			$data['page_data']['pagination']=array(
														'href'=>$url_parameters['url'],
														'current_page'=>$current_page,
														'total_pages'=>$total_pages,
													);
		   		/*
			###############################
			 	End For Pagination 
			###############################
		    */

		$data['page_data']['item']= $model_data;	
		$data['page_data']['url_parameters']= $url_parameters;	
		$this->load->view('listings/listings_land',$data);

	}

	public function listings_single()
	{

		/*########################################
          0. load classes
        #######################################*/		    

        $this->load->model('item_model');
		$this->load->library('general_functions');
		
		/*########################################
          1. Get Data from Database using Models
        #######################################*/		    
   		$pass_data = array(	'item_id' => $this->uri->segment(2)
   						   );

   		$model_data=$this->item_model->getItem($pass_data);

   		$href=base_url();
   		$addition_info=$model_data['addition_info'];
   		$status=$model_data['status'];

   		//do some formating to the results
   		foreach ($model_data['data']['records'] as $key => $value) {

			$model_data['data']['records'][$key]['price']='MK'.$value['price'];
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
			if(strlen($value['item_name'])>23)
				$model_data['data']['records'][$key]['item_name']=$this->general_functions->wordTrimmer($value['item_name'],23,'&hellip;');

   		}

		/*########################################
          2. Send data to view
        #######################################*/		    

		$data['page_data']= array();	
		$data['page_data']['item']= $model_data;	
		$this->load->view('listings/listings_single',$data);

	}

	

}
