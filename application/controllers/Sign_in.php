<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sign_in extends CI_Controller {

    public function __construct()
    {
      // Call the CI_Model constructor
      parent::__construct();

      // call password_crypt class 
      $this->load->library('user_account');
      $this->load->model('Credentials_model');
  } 


	public function index()
	{

      // redirect all login_users to home when trying to access this page
      $this->user_account->header_control(array('redirect_loggedIn'=>true,
                            'redirect_unlogged'=>false,));

		//default valiables	
		$status=false;
		$fail_result=false;
		$addition_info='';
		$result_info="";
		$href='';
		
		//include lilibraries
		$this->load->library('form_validation');
    
		//validation configurations
 		$validationRules = array(
			       'login'=> array( array(
							                'field' => 'password',
							                'label' => 'Password',
							                'rules' => 'required',
							                'errors' => array(
							                    	   		 'required' => 'required',
							               					 ),
							       		 ),
							        array(
							                'field' => 'email',
							                'label' => 'Email',
							                'rules' => 'required|valid_email',
							                'errors' => array(
									                        'required' => 'required',
									                        'valid_email'=>'v_email'
							                				),			                
							        	)
			        			)
				);

 		//validate
        $this->form_validation->set_rules($validationRules['login']);

	    if ($this->form_validation->run() == FALSE)
        {
            $fail_result=true;
       			$addition_info='';
       			$result_info=validation_errors();

       			// control the error messsages
       			$errors = $this->form_validation->error_array();
       			if(!isset($errors['password']))$errors['password']='';
       			if(!isset($errors['email']))$errors['email']='';

       			if($errors['password']=='required' || $errors['email']=='required')
       			{
       				$result_info='Please fill all Fields';	

       			}elseif ($errors['email']=='v_email') 
       			{
       				$result_info='Please Provide a Valid Email';
       			}
        }
        
	   // flesh load	
       if(!$fail_result)
       {
       		$pass_data = array('email' => $this->input->post('email'),
       					             'password' => $this->input->post('password')
       						);

       		$model_data=$this->Credentials_model->sign_in($pass_data);

       		$href=base_url().'admin';
       		$addition_info=$model_data['addition_info'];
       		$status=$model_data['status'];
       		$result_info=$model_data['data']['result_info'];
       }
       
       //check if is ajax call
       if($this->input->is_ajax_request())
       {
       	$data['info']['status']=$status;
       	$data['info']['data']=array(
       								 'href'=>$href,
       								 'result_info'=>$result_info	
       								);
       	$data['print_as']='json';         
         $this->load->view('ajaxCall/ajaxCall',$data);
       }
       else
       {

       	  //redirect if true
       	    if($status)
       		{
       			redirect($href,'refresh');
       		}


    			//incase of errors
    			$data['page_data']['status']=$status;

    			$data['page_data']['addition_info']=$addition_info;
    			$data['page_data']['result_info']=$result_info;

         
          $this->load->view('sign_in/sign_in',$data);
        }

	}	

  public function sign_out()
  {
   
    // redirect all unlogined_users to home when trying to access this page
    $this->user_account->header_control(array('redirect_loggedIn'=>false,
                          'redirect_unlogged'=>true,));

    //default valiables 
    $status=false;
    $fail_result=false;
    $addition_info='';
    $result_info="";
    $result_array=array();
    $href='';

       if(!$fail_result)
       {
          
          $model_data=$this->Credentials_model->sign_out();

          $href=base_url();
          $addition_info=$model_data['addition_info'];
          $status=$model_data['status'];
          $result_info=$model_data['data']['result_info'];

       }

       //check if is ajax call
       if($this->input->is_ajax_request())
       {
          $data['info']['status']=$status;
          $data['info']['data']=array(
                         'href'=>$href,
                         'addition_info'=>$addition_info, 
                         'result_info'=>$result_info  
                        );
          $data['print_as']='json';         
          $this->load->view('ajaxCall/ajaxCall',$data);
       }
       else
       {
      if($status)
          {
            redirect($href,'refresh');
          }         
       }
  } 

}
