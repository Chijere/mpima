<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Error extends CI_Controller {

	//set defults
	private $ownerCheck = 0;
	private $userInfo = array();
    
    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();


        // call password_crypt class 
        $this->load->library('user_account');
        
        $this->load->model('user_info_model');
        
		//load header global vars
		$this->user_account->header_control(array('redirect_unlogged'=>false));
		
    }	
 

	public function index()
	{

		//default valiables	
		$status=false;
		$fail_result=false;
		$addition_info='';
		$result_info="";
		$href='';
		
		echo 'error';

  	  //fallback page	
      // organise data required to help load the collective pages(view)
      $data['header']['page_title']='Shopped';

      $data['header']['css']=array( 'assets/right_bar/css/right_bar.css',
                                    'assets/left_bar/css/left_bar.css',
                                    'assets/header/css/header.css',
			                              'assets/footer/css/footer.css');

			$data['header']['js']=array(	'assets/general/js/plugin/jquery.form.min.js',
              											'assets/header/js/header.js',
              										);

      $data['main_content']['page_path']='home/home_view';
			$data['main_content']['right_bar']='template/right_bar';

			//incase of errors
			$data['main_content']['status']=$status;

			$data['main_content']['addition_info']=$addition_info;
			$data['main_content']['result_info']=$result_info;

      // load the collective pages together with sending the data
			//$this->load->view('holder/holder',$data);

	}	

	public function shop_error()
	{

		//default valiables	
		$status=false;
		$fail_result=false;
		$addition_info='';
		$result_info="";
		$href='';
		
		echo 'error';

  	  //fallback page	
      // organise data required to help load the collective pages(view)
      $data['header']['page_title']='Shopped';

      $data['header']['css']=array( 'assets/right_bar/css/right_bar.css',
                                    'assets/left_bar/css/left_bar.css',
                                    'assets/header/css/header.css',
			                              'assets/footer/css/footer.css');

			$data['header']['js']=array(	'assets/general/js/plugin/jquery.form.min.js',
              											'assets/header/js/header.js',
              										);

      $data['main_content']['page_path']='home/home_view';
			$data['main_content']['right_bar']='template/right_bar';

			//incase of errors
			$data['main_content']['status']=$status;

			$data['main_content']['addition_info']=$addition_info;
			$data['main_content']['result_info']=$result_info;

      // load the collective pages together with sending the data
			//$this->load->view('holder/holder',$data);

	}	

}
