<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class General_actions extends CI_Controller {

  //set defults

    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }

 #--------------------------------------------------
    private function reset_defaults()
    {

    } 


  public function index()
  {

      #redirect ..this is illegal 
        if($this->userInfo['data']['count']<1)
        {
          //redirect(base_url().'shop/error','refresh');
          redirect(base_url(),'refresh');
        }

  }

    
    public function general_pic_attach($pdata=array())
    {
    //default valiables 
    $status=false;
    $fail_result=false;
    $addition_info='';
    $result_info="";
    $result_array=array();
    $pic_ref='';
    $path='';
    $href='';
    

    
       //check if is ajax call
       if($this->input->is_ajax_request())
       {
          // attach image 
         if(!$fail_result)
         {
       
            $model_data=$this->pic_attach();

            $addition_info=$model_data['addition_info'];
            $status=$model_data['status'];
            $pic_ref=$model_data['data']['ref'];
            $result_info=$model_data['data']['result_info'];
            $result_array=$model_data['data']['result_array'];
        $path=base_url().'temp/image/'.$pic_ref.'_t.jpg'; 
         }


          $data['info']['status']=$status;
          $data['info']['data']=array(
                         'href'=>$href,
                         'ref'=>$pic_ref,
                         'path'=>$path,
                         'result_info'=>$result_info  
                        );
          $data['print_as']='json';         
          $this->load->view('ajaxCall/ajaxCall',$data);
       }
       else
       {

       }
    }

  private function pic_attach($pdata=array())
  {

    //default valiables 
    $status=false;
    $fail_result=false;
    $addition_info='';
    $result_info="";
    $result_array=array();
    $pic_ref='';
    
          
          $pass_data = array();

          $this->load->library('attach_image');
          $model_data=$this->attach_image->attach_image_to_temp();

          $addition_info=$model_data['addition_info'];
          $status=$model_data['status'];
          $pic_ref=$model_data['data']['ref'];
          $result_info=$model_data['data']['result_info'];
          $result_array=$model_data['data']['result_array'];
     
       
        $info=array("status"=>$status,
              "data"=>array(
                             'ref'=>$pic_ref,
                             'result_info'=>$result_info, 
                             'result_array'=>$result_array, 
                            ),
              "addition_info"=>$addition_info
            );

      return $info;
  }


}
