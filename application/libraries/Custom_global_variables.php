<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Custom_global_variables {

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
      
    }


#--------------------------------------------------
    private function reset_defaults()
    {
       $this->addition_info="";
       $this->status = false;
       $this->result_info=false; 
       $this->result_array=false; 
       $this->fail_result=false;
    } 

public function custom_global_variables(){

/*
|-----------------------------------
|default Global variables
|-------------------
|e.g default profile pic
|
|
*/

  $defaultGlobal=array();
  $defaultGlobal['user_profile_pic']=base_url()."media/default/shop/image/profile.jpg";
  $defaultGlobal['user_profile_cover_pic']=base_url()."media/default/shop/image/profile_cover.jpg";

  return $info = array('custom_global_variables' => $defaultGlobal, );

}

}

?>