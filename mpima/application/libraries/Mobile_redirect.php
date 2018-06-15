<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mobile_redirect 
{


    // set defaults
    protected $CI;

    //default variables
    private $addition_info="";
    private $status = false;
    private $result_info=false; 
    private $result_array=false; 
    private $is_mobile=false; 
    private $fail_result=false;
    private $user_preference=false;

    public function __construct()
    {
      // Assign the CodeIgniter super-object
      $this->CI =& get_instance();
        
    }


#--------------------------------------------------
    private function reset_defaults()
    {
       $this->addition_info="";
       $this->status = false;
       $this->result_info=false; 
       $this->result_array=false; 
       $this->is_mobile=false; 
       $this->fail_result=false;
       $this->user_preference=false;
    } 

#--------------------------------------------------
    public function mobile_redirect($data=array())
    {
      //defaults
      $this->reset_defaults();
      if(!isset($data['redirect_mobile_url']))$data['redirect_mobile_url']=MOBILE_VERSION_URL; 

        $detect = new Mobile_Detect;

        if($detect->isMobile())
          $this->is_mobile=1;
        else
          $this->is_mobile=0;
      
      if(isset($_SESSION['user_prefered_display_version']))
      $this->user_preference = $_SESSION['user_prefered_display_version'];

      if(SITE_VERSION=='desktop')
        {
          if(($this->is_mobile || $this->user_preference =='mobile') && $this->user_preference !='desktop')
          {
            //redirect if not logged in
            redirect($data['redirect_mobile_url'],'refresh');
          }
        }

    } 

}

?>