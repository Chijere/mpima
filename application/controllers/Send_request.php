<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Send_request extends CI_Controller {

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

          $this->load->model('Type_model');
          $this->load->model('Category_model');
          $this->load->model('Location_model');
        
		/*########################################
          1. Get Data from Database using Models
        #######################################*/		    
   		
		$type =$this->Type_model->getType(); 
		$category =$this->Category_model->getCategory(); 
		$location =$this->Location_model->getLocation(); 


		$data['page_data']= array();
		$data['page_data']['type']= $type;	
		$data['page_data']['category']= $category;	
		$data['page_data']['location']= $location;	


		//echo "<pre>";
		//print_r($location);
		//return;
		$this->load->view('send_request/send_request',$data);

	}

	public function send_request_form($pdata=array())
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
							                'field' => 'email',
							                'label' => 'Email',
							                'rules' => 'valid_email',
							                'errors' => array(
									                        'required' => 'Email is required',
									                        'valid_email' => 'Provide a valid email',
							                				),		                
							        ),
							        array(
							                'field' => 'phone',
							                'label' => 'required|Phone number',
							                'rules' => 'callback_valid_phone[5,15]',
							                'errors' => array(
									                        'valid_phone' => 'Provide a valid phone number',
							                				),			                
							        ),
						        array(
						                'field' => 'price',
						                'label' => 'Price',
						                'rules' => 'numeric',
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

       		$pass_data = array(	'name' => $this->input->post('name',true),
       							'user_id' => 001,
       							'price' => $this->input->post('price',true),
       							'phone' => $this->input->post('phone',true),
       							'email' => $this->input->post('email',true),
       							'category_id' => $this->input->post('category',true),
       							'location_id' => $this->input->post('location',true),
       							'type_id' => $this->input->post('type',true),
       							'description' => $this->input->post('description',true),
       						);
       		$pass_data['addPic']=array();
       		for ($i=1; $i <13 ; $i++) { 
       		 	if($this->input->post('input'.$i,true)!='')
       			array_push($pass_data['addPic'],$this->input->post('input'.$i));
       		}

		   	$this->load->model('Item_request_model');
       		$model_data=$this->Item_request_model->addItem($pass_data);

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

	public function contact()
	{

		$data['page_data']= array();	
		$this->load->view('about/contact',$data);

	}


///////////////////////// --- validation callbacks --- ///////////////////////////
public  function inlude_alpha($str) {          
         $this->form_validation->set_message('inlude_alpha', 'The %s must include letters.');
         if(preg_match('/^[a-zA-Z\d]+$/', $str)) { // do your validations
                return TRUE;
          } else {
              return FALSE;
          }
       }

public  function inlude_numeric($str) {          
         $this->form_validation->set_message('inlude_numeric', 'The %s must include numbers.');
         if(preg_match('@[0-9]@', $str)) { // do your validations
                return TRUE;
          } else {
              return FALSE;
          }
       }

public  function inlude_special_char($str) {          
         $this->form_validation->set_message('inlude_special_char', 'The %s must include special characters.');
         if(preg_match('/[!@#$%^&*()\-_=+{};:,<.>]/', $str)) { // do your validations
                return TRUE;
          } else {
              return FALSE;
          }
       }

public  function both_alpha_numeric($str) {          
         $this->form_validation->set_message('both_alpha_numeric', 'The %s must include both letters and numbers.');
         if(preg_match('#[0-9]#', $str) && preg_match('#[a-zA-Z]#', $str)) { // do your validations
                return TRUE;
          } else {
              return FALSE;
          }
       }

public  function valid_phone($str,$range='7,10') {          
         $this->form_validation->set_message('valid_phone', 'Provide a valid %s ');
         $str = preg_replace('/[^0-9,]|,[0-9]*$/','',$str);
         $regex='/^[\d]{'.$range.'}$/';
         if(preg_match($regex, $str) || empty($str)) { // do your validations
              return TRUE;
          } else {
              return FALSE;
          }
       }

}
	
