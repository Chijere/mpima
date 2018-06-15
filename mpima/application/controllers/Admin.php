<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

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
        
		//refuse unlogged in users
		$this->user_account->header_control(array('redirect_unlogged_url' =>base_url().'sign_in' , ));		
    }	
 

	public function index()
	{


		$pass_data = array(	'user_id' => $_SESSION['user_id']
   						   );

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

		$data['page_data']= array();	
		$data['page_data']['item']= $model_data;	
		$this->load->view('admin/browse_content',$data);

	}

	public function add_property()
	{

		$data['page_data']= array();	
		$this->load->view('admin/add_property',$data);

	}

	public function add_property_form($pdata=array())
	{



		//default valiables	
		$status=false;
		$fail_result=false;
		$addition_info='';
		$result_info="";
		$item_id = '';
		$result_array=array();
		$href='';
		
		//echo "string";
		//include libraries
		$this->load->library('form_validation');

		//validation configurations
		$validationRules['rule1']=array(
						        array(
						                'field' => 'name',
						                'label' => 'Name',
						                'rules' => 'required|max_length[30]',
						                'errors' => array(
								                        'required' => 'Name is required',
								                        'max_length' => 'Must not exceed 30 Characters',
						                				),	                
						        	),
						        array(
						                'field' => 'price',
						                'label' => 'Price',
						                'rules' => 'required|numeric',
						                'errors' => array(
						                				'required' => 'Price is required',
								                        'numeric' => 'Provide number only',
						                				),
						            ),
						        array(
						                'field' => 'description',
						                'label' => 'Description',
						                'rules' => 'max_length[2000]',
						                'errors' => array(
								                        'max_length' => 'Description must not be less than 2000 characters',
						                				),	 		                
						        	),
						        array(
						                'field' => 'summary',
						                'label' => 'Summary',
						                'rules' => 'max_length[500]',
						                'errors' => array(
								                        'max_length' => 'Summary must not be less than 500 characters',
						                				),	 		                
						        	),
						        array(
						                'field' => 'category',
						                'label' => 'Category',
						                'rules' => 'required',
						                'errors' => array(
						                				'required' => 'system_error',
						                				),	 		                
						        	),
						        array(
						                'field' => 'location',
						                'label' => 'Location',
						                'rules' => 'required',
						                'errors' => array(
						                				'required' => 'system_error',
						                				),	 		                
						        	),
						        array(
						                'field' => 'type',
						                'label' => 'Type',
						                'rules' => 'required',
						                'errors' => array(
						                				'required' => 'system_error',
						                				),	 		                
						        	),
						        array(
						                'field' => 'input1',
						                'label' => 'Image',
						                'rules' => 'exact_length[20]|alpha_numeric|required',
							            'errors' => array(
									                        'exact_length' => 'system_error',
									                        'alpha_numeric' => 'system_error', 
									                        'required' => 'Provide a feature image',		                
						        	),
								),
						    );


		for ($i=2; $i <13 ; $i++) { 
 			array_push($validationRules['rule1'],array(
							                'field' => 'input'.$i,
							                'label' => 'Photo',
							                'rules' => 'exact_length[20]|alpha_numeric',
							                'errors' => array(
									                        'exact_length' => 'system_error',
									                        'alpha_numeric' => 'system_error',
							            ),		                
							        	));
 		}	        			
		
   							                   


 		//validate
        $this->form_validation->set_rules($validationRules['rule1']);
  
	    if (!$fail_result && $this->form_validation->run() == FALSE)
        {
        	$fail_result=true;
   			$addition_info='validation error';
   			$result_info="Sorry An error Occurred";
   			// control the error messsages
   			$result_array= $this->form_validation->error_array(); 
			
   			//correct common errors first
			$commonErrors=false;
			foreach ($result_array as $key => $value) {
				if('system_error'!= $value)
					$commonErrors=true;
			}

			/*
			the logic is treat common errors first then for systems error prompt user to refresh the page 
			 */
			// control the error messsages
   			$errors = $this->form_validation->error_array();
   			// define them
   			if(!isset($errors['type']))$errors['type']='';
   			if(!isset($errors['location']))$errors['location']='';
   			if(!isset($errors['category']))$errors['category']='';

   			if(($errors['type']=='system_error' || $errors['location']=='system_error' || $errors['category']=='system_error') && !$commonErrors)
   			{
   				$addition_info='system error';	
   			}
   			
			for ($i=1; $i <5 ; $i++) { 
       			
       			if(isset($error['input'.$i]))
       			if($errors['input'.$i]=='system_error' && !$commonErrors)
       			{
       				$addition_info='system error';	
       			}					
	 		}     			
        }	  

	   // flesh load	
       if(!$fail_result)
       {

       		$pass_data = array(	'user_id' => $_SESSION['user_id'],
       							'name' => $this->input->post('name',true),
       							'price' => $this->input->post('price',true),
       							'category_id' => $this->input->post('category',true),
       							'location_id' => $this->input->post('location',true),
       							'type_id' => $this->input->post('type',true),
       							'front_pic' => $this->input->post('input1',true),
       							'description' => $this->input->post('description',true),
       							'summary' => $this->input->post('summary',true),
       						);
       		$pass_data['addPic']=array();
       		for ($i=1; $i <13 ; $i++) { 
       		 	if($this->input->post('input'.$i,true)!='')
       			array_push($pass_data['addPic'],$this->input->post('input'.$i));
       		}

		   	$this->load->model('Item_model');
       		$model_data=$this->Item_model->addItem($pass_data);

       		$href=base_url();
       		$addition_info=$model_data['addition_info'];
       		$status=$model_data['status'];
       		$result_info=$model_data['data']['result_info'];
       		$item_id=$model_data['data']['item_id'];
       }
       

       //check if is ajax call
       if($this->input->is_ajax_request())
       {
	       	$data['info']['status']=$status;
	       	$data['info']['data']=array(
	       								 'href'=>$href,
	       								 'addition_info'=>$addition_info,	
	       								 'result_array'=>$result_array,	
	       								 'result_info'=>$result_info,
	       								 'item_id'=>$item_id	
	       								);
	       	$data['print_as']='json';         
	        $this->load->view('ajaxCall/ajaxCall',$data);
       }
       elseif(1==0)
       {}
	}	

}
