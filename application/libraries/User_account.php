<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_account 
{


    // set defaults
    protected $CI;

    //default variables
    private $addition_info="";
    private $status = false;
    private $result_info=false; 
    private $result_array=false; 
    private $fail_result=false;

    public function __construct()
    {
      // Assign the CodeIgniter super-object
      $this->CI =& get_instance();

      // call password_crypt class 
      $this->CI->load->library('user_account');
        
      $this->CI->load->model('user_info_model');
        
    }


#--------------------------------------------------
    private function reset_defaults()
    {
       $this->ownerUserInfo=array();
       $this->user_id='0x7x2xx5';
       $this->login=false;
       $this->addition_info="";
       $this->status = false;
       $this->result_info=false; 
       $this->result_array=false; 
       $this->fail_result=false;
    } 

#--------------------------------------------------
    public function header_control($data=array())
    {
      //defaults
      $this->reset_defaults();
    
      if(!isset($data['redirect_unlogged']))$data['redirect_unlogged']=true;      
      if(!isset($data['redirect_unlogged_url']))$data['redirect_unlogged_url']=base_url();      
      if(!isset($data['redirect_loggedIn']))$data['redirect_loggedIn']=false;      
      if(!isset($data['redirect_loggedIn_url']))$data['redirect_loggedIn_url']=base_url();      
      if(isset($_SESSION['login']))$this->login=$_SESSION['login'];      
      if(isset($_SESSION['user_id']))$this->user_id=$_SESSION['user_id'];      

      $getConfig=array('user_id'=>$this->user_id);
      $this->ownerUserInfo=$this->CI->user_info_model->getUser($getConfig);

      if($this->login)
      {
        //load userInfo vars globally  
          $this->CI->load->vars('ownerUserInfo',$this->ownerUserInfo);
      }

      //load categories vars globally
      $this->CI->load->model('category_model');
      $categories = $this->CI->category_model->getCategory();      
      $this->CI->load->vars('categories',$categories);
         
      if(!$this->login && $data['redirect_unlogged'])
      {
        //redirect if not logged in
        redirect($data['redirect_unlogged_url'],'refresh');
      }

      if($this->login && $data['redirect_loggedIn'])
      {
        //redirect if not logged in
        redirect($data['redirect_loggedIn_url'],'refresh');
      }
      
      return $this->ownerUserInfo;         
    } 

    public function is_account_owner($pdata=array())
    {

      //set defaults
      $this->reset_defaults();
      

      if(!empty($pdata))
      {
        if(!is_array($pdata))
          $this->user_id=$pdata;
        else
          $this->user_id=$pdata['user_id'];
      }

      $ownerCheck=0;
        
      if(isset($_SESSION) && isset($_SESSION['user_id']))
        {
          if($_SESSION['user_id']==$this->user_id)
          { $ownerCheck=1; }
          
        }  

      return $ownerCheck;
    }

}

?>