<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attach_file {

    // set defaults
    protected $CI;

    //default variables
    private $addition_info="";
    private $confirm_code="";
    private $status = false;
    private $result_info=false; 
    private $result_array=false; 
    private $fail_result=false;

    public function __construct()
    {
      // Assign the CodeIgniter super-object
      $this->CI =& get_instance();

      // call upload class 
      $this->CI->load->library('upload');

      $this->CI->load->library('Id_generator');

      // call other class
      $this->CI->load->helper(array('form', 'url'));
      
    }


#--------------------------------------------------
    private function reset_defaults()
    {
       $this->addition_info="";
       $this->confirm_code="";
       $this->status = false;
       $this->result_info=false; 
       $this->result_array=false; 
       $this->fail_result=false;
    } 

  public function attach_file_to_temp($pdata=array())
  {
    

    //default valiables 
    $status=false;
    $fail_result=false;
    $addition_info='';
    $result_info="";
    $result_array=array();
    $pic_ref='';
    $file_data = array();
    $file_extension = "";
    if(!isset($pdata['form_field']))$pdata['form_field']='file';
    $temp_directory = "temp/file/";

        
      if(!$fail_result)
      {     
          if (!file_exists($temp_directory)) //check-if-temp-dir-exists
          {
            if(mkdir($temp_directory,0777,true))
              $fail_result=true;
            else
            {    
              $addition_info='error_102_1 :failed to create temp_dir';
              $result_info=" An Error Occurred, Try Again ";
              $fail_result=true;
            }
          }
        }   

     if(!$fail_result)
       {

          $pdata1= array('upload_path' => $temp_directory);
          $pdata1['form_field']=$pdata['form_field'];
          $model_data = $this->upload_to_temp_folder($pdata1);

          $addition_info=$model_data['addition_info'];
          $status=$model_data['status'];
          $pic_ref=$model_data['data']['file_id'];
          $file_data = $model_data['data']['file_data']; 
          $file_extension = $model_data['data']['file_extension']; 
          $result_info=$model_data['data']['result_info'];

        if(!$status) $fail_result=true;
       }

       
        $info=array("status"=>$status,
              "data"=>array(
                             'ref'=>$pic_ref,
                             'result_info'=>$result_info, 
                             'file_data'=>$file_data, 
                             'file_extension'=>$file_extension, 
                             'result_info'=>$result_info, 
                             'result_array'=>$result_array, 
                            ),
              "addition_info"=>$addition_info
            );

      return $info;
  }

  //upload transferring
public function upload_to_temp_folder($pdata=array())
  {

      $addition_info = '';
      $status = false;
      $result_info = 'Sorry an error occured';
      $max_size = 10240;
      $file_data = array();  

      if(isset($pdata['form_field']))
      {
        $form_field = $pdata['form_field'];
        unset($pdata['form_field']);
      }else $form_field = 'file';  


      if(isset($pdata['file_extension']))
      {
        $file_extension = $pdata['file_extension'];
        unset($pdata['file_extension']);
      }else $file_extension = pathinfo($_FILES[$form_field]["name"], PATHINFO_EXTENSION);

      $this->CI->load->library('Id_generator');
      $file_id=Id_generator::rand_sha1(20);

      $config['file_name'] = $file_id.'.'.$file_extension;
      $config['allowed_types'] = 'doc|pdf|docx|txt|text|png|jpg|jpeg|gif';
      $config['max_size']     = $max_size;
      //$config['max_width'] = '1024';
      //$config['max_height'] = '768';
      $config['file_ext_tolower'] = true;
      $config['overwrite'] = true;


      foreach ($pdata as $key => $value) {
        if (array_key_exists($key, $config)) 
        {
          $config[$key] = $value;
          unset($pdata[$key]);
        }
      }
       $config=$config + $pdata;

      $this->CI->upload->initialize($config);
  
      if( !$this->CI->upload->do_upload($form_field,FALSE))
      {

        if (strlen(stristr($this->CI->upload->display_errors('',''),strtolower('filetype you are attempting to upload is not allowed')))>0) {
         $result_info ='error 100_1 : '. $this->CI->upload->display_errors('','');
        }if (strlen(stristr($this->CI->upload->display_errors('',''),strtolower('file you are attempting to upload is larger than the permitted size')))>0) {
         $result_info = 'max file size should be '.(ceil($max_size/1024)).'mb';
         $addition_info = 'error 100_2 : '. $this->CI->upload->display_errors('','');
        }if (strlen(stristr($this->CI->upload->display_errors('',''),strtolower('You did not select a file to upload')))>0) {
         $result_info = 'Please select an file';
         $addition_info = 'error 100_3 : '. $this->CI->upload->display_errors('','');
        }else{
         $addition_info = 'error 100_4 : '. $this->CI->upload->display_errors('','');
        }

        // set file id to " " on fail
        $file_id ="";
        $file_data = $this->CI->upload->data();
      
      }
      else
      {
        $status = true;
        $result_info = 'successful';
      $file_data = $this->CI->upload->data();
      }

      $info = Array(
      "addition_info" => $addition_info,
      "status" => $status,
      "data"=>array(
                      'result_info'=>$result_info,
                      'file_id'=>$file_id,
                      'file_extension'=>$file_extension,
                      'file_data'=>$file_data,
        ),
      );


      return $info;
  } 
//----------------------------------------------------------------------
/*
  pdata=array(
              path=>,
              user_id=>,
              create_user_dir=>,
              create_week_dir=>,
            )
*/

public function create_dir_by_week($pdata)
{
   //--define variables
   $addition_info='';
   $new_directory='';
   $week='';
   $makeUser=false;
   $makeWeek=true;
   $user_id="TxxYxxRxxExxExxR"; #fake
   $path=array();
   $fail_result=false;
   $status=false;
   $result_info='';
   $result_array=array();
   

   if(isset($pdata['path']))$path=$pdata['path'];
   if(isset($pdata['user_id']))$user_id=$pdata['user_id'];
   if(isset($pdata['create_user_dir']))$makeUser=$pdata['create_user_dir'];
   if(isset($pdata['create_week_dir']))$makeWeek=$pdata['create_week_dir'];
  
    //###############################-GET-THE-CURRENT-WEEK-###################################
      if($makeWeek)
      $week=date('Y')."_".date("W");

      //****-split-the-path-into-an-array
      $path_arry = explode($user_id,$path);
      $user_directory = $path_arry[0].$user_id; 
    
     
    if(!$fail_result)
    {
     if(!file_exists($user_directory) && $makeUser)
      {
        if(!mkdir($user_directory,0777,true))
        {
          $fail_result=true;
          $addition_info="error 100_1";
          $result_info="Try again, an error Occurred";
          array_push($array_push,"failure_dir_creation:user");  
        }
      }elseif(!file_exists($user_directory) && !$makeUser)
      {
        $fail_result=true;
        $addition_info="error 100_2";
        $result_info="Try again, an error Occurred";
        array_push($array_push,"root_folder_doesn't_exists:user");
      }
    }
      
    #Check if every directory exists otherwise create it
    if(!$fail_result && isset($path_arry[1]))
    { 
      $pathLoop=explode('/',$path_arry[1]);
      $pathLoop=array_filter($pathLoop);
      $pathLoop=array_values($pathLoop);
      
      $i=0;$v=count($pathLoop);
      do{
          $user_directory.="/".$pathLoop[$i];
          
            #check the newly specifed dir
            if(!file_exists($user_directory)) 
            {
              #on fail create it
              if(!mkdir($user_directory,0777,true))
              {
                $fail_result=true;
                $addition_info="error 101_1";
                $result_info="Try again, an error Occurred";
                array_push($array_push,"failure_dir_creation:".$value);  
              }
            }
          $i++;
      }while (($i<$v) && !$fail_result);
    }

    #Check if week directory exists otherwise create it
    if(!$fail_result)
    {
      $user_directory.="/".$week.'/';

      if(!file_exists($user_directory) && $makeWeek)
      {
        if(!mkdir($user_directory,0777,true))
        {
          $fail_result=true;
          $addition_info="error 102_1";
          $result_info="Try again, an error Occurred";
          array_push($array_push,"failure_dir_creation:week");  
        }
      }elseif(!file_exists($user_directory) && !$makeWeek)
      {
        $fail_result=true;
        $addition_info="error 102_2";
        $result_info="Try again, an error Occurred";
        array_push($array_push,"root_folder_doesn't_exists:week");
      }
    }  

    #return path 
    if(!$fail_result)
    {
      $new_directory = $user_directory;
      $status=true;
    }  
         
    $info = Array(
        "addition_info" => $addition_info,
        "status" => $status,
        "data"=>array(
                            'result_info'=>$result_info,
                            'result_array'=>$result_array,
                            "directory"=>$new_directory,
          ),
        );
    
    return $info;   

}

}

?>