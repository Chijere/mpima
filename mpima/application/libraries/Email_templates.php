<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Email_templates
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
      $this->CI->load->model('simple_mailer_model');
        
    }


#--------------------------------------------------
    private function reset_defaults()
    {
       $this->addition_info="";
       $this->status = false;
       $this->result_info="Sorry something went wrong, try again"; 
       $this->result_array=false; 
       $this->fail_result=false;
    } 

    public function sendSignUpConfirm($data=array())
    {

      //set defaults
      $this->reset_defaults();
      if(!isset($data['confirm_code']))$data['confirm_code']="";  
      if(!isset($data['email']))$data['email']="";  

      if(empty($data['confirm_code']))
      {
        $this->fail_result=true;
        $this->addition_info="error 100 : confirm_code not provided";
      }

      if(empty($data['email']))
      {
        $this->fail_result=true;
        $this->addition_info="error 100 : email not provided";
      }

      if(!$this->fail_result)
      {
      
      // compose email 
        #Your subject
        $subject="Shopped Complete SignUp | Confirmation Link";

        #From

        #Your message
        $message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                    <html xmlns="http://www.w3.org/1999/xhtml">
                     <head>
                      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                      <title>Demystifying Email Design</title>
                      <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
                    </head>
                    <body>
                      
                        <div><h3>Confirmation Link | Shopped</h3></div>
                        <div><span>Click on the link to finish signing up for Shopped</span></div>
                        <div><span><a href="'.base_url()."complete_sign_up/".$data['confirm_code'].'">'.base_url().'complete_sign_up</a></span></div>

                    </body>
                    </html>';

        $mail_data=array('subject'=>$subject,
                          'message'=>$message,  
                          'receiver_email'=>$data['email'],  
                          );
        
        //$this->fail_result=true;
        $mailer= $this->CI->simple_mailer_model->e_mailer($mail_data); 
        if (!$mailer['status']) {
          $this->fail_result=true;
          $this->result_info=$mailer['data']['result_info'];
          $this->addition_info=$mailer['addition_info'];
        }
      }

      if(!$this->fail_result)
      {
        $this->status=true;
      }

     return $info=Array
      (
        "status" => $this->status,  
        "data"   => array(   
                          "result_array"=>$this->result_array,
                          "result_info"=>$this->result_info
                        ),  
        "addition_info"=>$this->addition_info,
      );
    }

}

?>