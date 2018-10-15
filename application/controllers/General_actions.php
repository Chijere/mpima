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

  public function test_1()
  {
    //default valiables 
    $status=false;
    $fail_result=false;
    $addition_info='';
    $result_info="";

            $temp_directory = "media/user/1";
            
            if(mkdir($temp_directory,0777,true))
              $status=true;
            else
            {    
              $addition_info='error_102_1 :failed to create temp_dir';
              $result_info=" An Error Occurred, Try Again ";
              $status=false;
            }

              $info=array("status"=>$status,
              "data"=>array(
                             'result_info'=>$result_info, 
                            ),
              "addition_info"=>$addition_info
            );

     echo "<pre>";
     print_r($info);

  }

public function general_file_attach($pdata=array())
  {
    //default valiables 
    $status=false;
    $fail_result=false;
    $addition_info='';
    $result_info="";
    $result_array=array();
    $pic_ref='';
    $file_data=array();
    $file_extension='';
    $path='';
    $href='';
      

    $this->load->library('attach_file');
    $model_data=$this->attach_file->attach_file_to_temp($pdata);

    $addition_info=$model_data['addition_info'];
    $status=$model_data['status'];
    $pic_ref=$model_data['data']['ref'];
    $result_info=$model_data['data']['result_info'];
    $file_data=$model_data['data']['file_data'];
    $file_extension=$model_data['data']['file_extension'];
    $result_array=$model_data['data']['result_array'];
    
    //remove file data paths
    if(isset($file_data['file_path']))$file_data['file_path']="";
    if(isset($file_data['full_path']))$file_data['full_path']="";

    if($this->input->is_ajax_request())
    {
        $data['info']['status']=$status;
        $data['info']['data']=array(
                       'href'=>$href,
                       'file_data'=>$file_data,
                       'file_extension'=>$file_extension,
                       'ref'=>$pic_ref,
                       'path'=>$path,
                       'result_info'=>$result_info,
                       'result_array'=>$result_array,
                       'addition_info'=>$addition_info,    
                      );
        $data['print_as']='json';         
        $this->load->view('ajaxCall/ajaxCall',$data);  
     }
     else
     { 
      
      $info=array("status"=>$status,
            "data"=>array(
                           'href'=>$href,
                           'ref'=>$pic_ref,
                           'path'=>$path,
                           'result_info'=>$result_info, 
                           'result_array'=>$result_array,
                           'addition_info'=>$addition_info,     
                          ),
            "addition_info"=>$addition_info
          );

      return $info;
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

    if(!isset($pdata['thumbnail_width']))$pdata['thumbnail_width']=255;
    if(!isset($pdata['thumbnail_height']))$pdata['thumbnail_height']=160;
    if(!isset($pdata['medium_width']))$pdata['medium_width']=530;
    if(!isset($pdata['medium_height']))$pdata['medium_height']=300;
    if(!isset($pdata['normal_width']))$pdata['normal_width']=1280;
    if(!isset($pdata['normal_height']))$pdata['normal_height']=720;    
    

    if(!empty($this->input->get('i_fmrt',true)))// image format                
      {
        if($this->input->get('i_fmrt',true)=="wd")
        $pdata['format_type'] = "widescreen";
      }    

    $this->load->library('attach_image');
    $model_data=$this->attach_image->attach_image_to_temp($pdata);

    $addition_info=$model_data['addition_info'];
    $status=$model_data['status'];
    $pic_ref=$model_data['data']['ref'];
    $result_info=$model_data['data']['result_info'];
    $result_array=$model_data['data']['result_array'];

    if($this->input->is_ajax_request())
    {
        $data['info']['status']=$status;
        $data['info']['data']=array(
                       'href'=>$href,
                       'ref'=>$pic_ref,
                       'path'=>$path,
                       'result_info'=>$result_info,
                       'result_array'=>$result_array,
                       'addition_info'=>$addition_info,    
                      );
        $data['print_as']='json';         
        $this->load->view('ajaxCall/ajaxCall',$data);  
     }
     else
     { 
      
      $info=array("status"=>$status,
            "data"=>array(
                           'href'=>$href,
                           'ref'=>$pic_ref,
                           'path'=>$path,
                           'result_info'=>$result_info, 
                           'result_array'=>$result_array,
                           'addition_info'=>$addition_info,     
                          ),
            "addition_info"=>$addition_info
          );

      return $info;

     }
  }

}
