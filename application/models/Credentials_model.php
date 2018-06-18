<?php
class Credentials_model extends CI_Model {

        //default variables
        private $addition_info="";
        private $confirm_code="";
        private $status = false;
        private $result_info=false; 
        private $fail_result=false;

        public function __construct()
        {
                // Call the CI_Model constructor
                parent::__construct();

                // call password_crypt class 
                $this->load->library('Pass_crypt');

        }


 #--------------------------------------------------
        private function reset_defaults()
        {

                 $this->addition_info="";
                 $this->confirm_code="";
                 $this->status = false;
                 $this->result_info=false; 
                 $this->fail_result=false;
        } 
 
 ##-----------------

        public function sign_out($data=array())
        {
            //set defaults
              $this->reset_defaults();

            session_destroy();
            session_write_close();
            session_unset();
            $_SESSION=array();

            return   $info = Array
                            (
                                    "status" => true,    
                                    "data" => array(     
                                                    "result_info"=>$this->result_info,
                                                    ),      
                                    "addition_info"=>$this->addition_info
                            );
        }

 ##------------------------------------------
         /*
           format
            $data=array(
                                         'password'=>'yiiyeer',
                                         'email'=>'tita@gmail.com',
                                   )
         
         */
        public function sign_in($data=array())
        {
              //set defaults
                $this->reset_defaults();
                if(!isset($data['email'])) $data['email']='';
                if(!isset($data['phone'])) $data['phone']=$data['email'];
                if(!isset($data['password'])) $data['password']='';
                $user_id="";




                $dbquery1=$this->db->conn_id->prepare("SELECT `password`,`email`,`phone`,`user_id`,`user_type` FROM `users`  WHERE `email` = :email OR `phone`=:phone LIMIT 1");
                $dbquery1->bindParam(":email",$data['email']);
                $dbquery1->bindParam(":phone",$data['phone']);
                               
                if(($dbquery1->execute()))
                {
                        $sql_result=$dbquery1->fetch();
                }
                else
                {
                        // db error
                        //$addition_info="server_error1";
                        //print_r($dbquery1->errorInfo());
                        $this->addition_info='error_104 :'.$dbquery1->errorInfo();
                        $this->result_info=" An Error Occurred, Try Again ";
                        $this->fail_result=true; 
                }



                
                if(!$this->fail_result && (count($sql_result)<=0 || empty($sql_result)))
                {
                   //$addition_info = "password";
                   $this->addition_info='error_105';
                   $this->result_info="Wrong Email/Phone number or password ";
                   $this->fail_result=true;                     
                }
                                 

                if( !$this->fail_result)
                {      
                        #############---Hash The Password------#######                
                        $password = new Pass_crypt($data['password']);
                        $hashed_passw = $password->verify ($sql_result['password']);
                        //print_r ($sql_result);
                        //$hashed_passw2 = $password->crypt ();
                        //print_r($data);
                        //print_r($hashed_passw);
                        ///print_r($hashed_passw2);
                  
                         ##--------------
                  
                        if($hashed_passw['status'] && $hashed_passw['data']['report']=='match')
                         {
                                $this->status=true;
                                $user_id=$sql_result['user_id'];
                                $user_type=$sql_result['user_type'];
                                
                                //set session
                                  $_SESSION['user_id']=$user_id;
                                  $_SESSION['user_type']=$user_type;
                                  $_SESSION['login']=true;
                                  $_SESSION['expire'] = time()+3*60*60;
                                  
                                        /*//activity log
                                        $detail=Array(
                                                  'Action'=>'LOGIN',
                                                  'User_name'=>$_SESSION["user_name"],
                                                  'User_id'=>$_SESSION["user_id"],  
                                                  'Time_stamp(php)'=>date('Y-m-d H:i:s a'),                                                
                                                );

                                        activityLog::insertLog($PDO_DB_Object,$_SESSION["user_id"],$detail);            */                                                        
                                 
                                
                         }elseif($hashed_passw['data']['report'] == 'not_match')
                         {
                                $this->status=false;
                                //$addition_info = "password";
                                $this->addition_info='error_107';
                                $this->result_info="Wrong Password or email/phone";                          
                         }else
                         {
                                $this->status=false;
                                //$addition_info = "server_error";
                                $this->addition_info='error_106';
                                $this->result_info=" An Error Occurred, Try Again ";                                                  
                         }
                 }         
                           

                return   $info = Array
                                (
                                        "status" => $this->status,    
                                        "data" => array(
                                                        "user_id" => $user_id,     
                                                        "result_info"=>$this->result_info,
                                                        "email"=>$data['email'],
                                                        ),      
                                        "addition_info"=>$this->addition_info
                                );      

        }   

 ##------------------------------------------
         /*
           format
            $data=array(
                                         'password'=>,
                                         'user_id'=>,
                                   )
         
         */
        public function ChangeUserPassword($data=array())
        {
              //set defaults
                $this->reset_defaults();
                if(!isset($data['user_id'])) $data['user_id']='';
                if(!isset($data['old_password'])) $data['old_password']='';
                if(!isset($data['new_password'])) $data['new_password']='';
                $user_id="";




                $dbquery1=$this->db->conn_id->prepare("SELECT `password` ,`user_id`,`user_type` FROM `users`  WHERE `user_id` = :user_id LIMIT 1");
                $dbquery1->bindParam(":user_id",$data['user_id']);
                               
                if(($dbquery1->execute()))
                {
                        $sql_result=$dbquery1->fetch();
                        //echo '<pre>';
                        //print_r($sql_result);
                }
                else
                {
                        // db error
                        //$addition_info="server_error1";
                        //print_r($dbquery1->errorInfo());
                        $this->addition_info='error_104';
                        $this->result_info=" An Error Occurred, Try Again ";
                        $this->fail_result=true; 
                }

                
                if(!$this->fail_result && (count($sql_result)<=0 || empty($sql_result)))
                {
                   //$addition_info = "user_id not found";
                   $this->addition_info='error_105';
                   $this->result_info="An Error Occurred, Try Again ";
                   $this->fail_result=true;                     
                }
                                 

                if( !$this->fail_result)
                {      
                        //validate password
                        ##Hash The Password#                
                        $password = new Pass_crypt($data['old_password']);
                        $hashed_passw = $password->verify ($sql_result['password']);
                        //print_r ($sql_result);
                        //$hashed_passw2 = $password->crypt ();
                        //print_r($data);
                        //print_r($hashed_passw);
                        ///print_r($hashed_passw2);
                  
                         ##--------------
                  
                        if($hashed_passw['status'] && $hashed_passw['data']['report']=='match')
                         {
                          
                          //match

                         }elseif($hashed_passw['data']['report'] == 'not_match')
                         {
                                $this->fail_result=true;
                                //$addition_info = "password";
                                $this->addition_info='error_107';
                                $this->result_info="Wrong Password ";                          
                         }else
                         {
                                $this->fail_result=true;
                                //$addition_info = "server_error";
                                $this->addition_info='error_106';
                                $this->result_info=" An Error Occurred, Try Again ";                                                  
                         }
                 }         
                 
                // Hash the new Password
                if(!$this->fail_result)   
                {                 
                    $password = new Pass_crypt($data['new_password']);
                    $new_hashed_passw = $password->crypt ();              
                    
                    if(!$new_hashed_passw['status'])
                    {
                        $this->fail_result=true;
                        $this->addition_info='error 108_1';
                        $this->result_info=" An Error Occurred, Try Again ";                  
                    } 
                }

                // store in Db
                if(!$this->fail_result)   
                {             
                    $dbquery1=$this->db->conn_id->prepare("
                    UPDATE `users` SET
                    `password`= :password,
                    `date`= `date`
                    WHERE `user_id`=:user_id
                    ");
                    $data['user_id']=preg_replace("/[^a-zA-Z0-9]+/","",$data['user_id']);
                    $dbquery1->bindParam(":user_id",$data['user_id']);
                    $dbquery1->bindParam(":password",$new_hashed_passw['data']['hash']);
                    
                             
                    if(!($dbquery1->execute()))
                    {
                        //print_r($dbquery1->errorInfo());
                        $this->fail_result=true;
                        $this->addition_info='error 109_1';
                        $this->result_info=" An Error Occurred, Try Again ";
                    }else
                    {
                        $this->status=true;
                        $this->result_info=" successful ";
                    }
                }
            


                return   $info = Array
                                (
                                        "status" => $this->status,    
                                        "data" => array(
                                                        "user_id" => $user_id,     
                                                        "result_info"=>$this->result_info,
                                                        
                                                        ),      
                                        "addition_info"=>$this->addition_info
                                );      

        }   

##------------------------------------------
         /*
           format
            $data=array(
                         'user_id'=>'',
                         'email'=>'tita@gmail.com',
                       )
         
         */
public function startChangeEmailByConfirmationCode($data=array())
{
    //reset default variable;
    $this->reset_defaults();

    if(!isset($data['user_id'])) $data['user_id']='';
    if(!isset($data['new_email'])) $data['new_email']='';
    //format 
    $data['user_id']=strtolower($data['user_id']);
    $data['new_email']=strtolower($data['new_email']);

    // check if required data is supplied      
    if(empty($data) || empty($data['new_email']) || empty($data['user_id']))
    {
        $this->addition_info='error 100_1';
        //$addition_info = "empty user_id || email";
        $this->result_info=" Error: An Error Occurred, Try Again "; 
        $this->fail_result=true;   
    }

    //check if the email Is Already in use
    if(!$this->fail_result)   
    {
            $dbquery1=$this->db->conn_id->prepare("SELECT `user_id` FROM `users` 
                                                     WHERE `email` = :email 
                                                     LIMIT 1 ");
                            
            $dbquery1->bindParam(":email",$data['new_email']);
                              
            if(!($dbquery1->execute()))
            {
                //print_r($dbquery1->errorInfo());
                $this->fail_result=true;
                $this->addition_info='error 101_1';
                $this->result_info=" An Error Occurred, Try Again ";
            }else
            {
            
                $sql_result=$dbquery1->fetch();
                   
                if($data['user_id']==$sql_result)
                {
                    $this->addition_info='error_101_2';
                    $this->result_info=" email is already registered for by you ";   
                    $this->fail_result=true;                      
                }
                if(!empty($sql_result))
                {
                    $this->addition_info='error_101_3';
                    $this->result_info=" The email Is Already in use ";   
                    $this->fail_result=true;                      
                }
            }   
    }
    
    if(!$this->fail_result)   
    {
         $n=0;
         do
         {
         //**--make confirm code
            # make code here and try to check it to prevent duplicate confirm codes
            # loop or make another code till its unique, send error if it reaches 15 tries

            $this->load->library('Id_generator');

            $this->confirm_code=Id_generator::rand_sha1(20);
        
            $dbquery2=$this->db->conn_id->prepare("SELECT `email` FROM `change_email_confirm`
                                                    WHERE `confirm_code` = :confirm_code  
                                                    LIMIT 1 ");
            
            $dbquery2->bindParam(":confirm_code",$this->confirm_code);
                //---execute query ---                  
            if(!($dbquery2->execute()))
            {
                //print_r($dbquery2->errorInfo());
                $this->fail_result=true;
                $this->addition_info='error 103_1';
                $this->result_info=" An Error Occurred, Try Again ";
            }else
            {
            
              $sql_result=$dbquery2->fetch();
               
              if(!empty($sql_result))
              {
                #if its same email and confirm_code ..just maintain and exit looop
                if($sql_result==$data['new_email'])
                    $n=16;
                
                #if exceeded tries return error
                if($n>14)
                {
                    $this->addition_info='error_103_2';
                    $this->result_info=" An Error Occurred, Try Again ";                          
                    $this->fail_result=true;
                }                       
              }
              else
                {   #exit the loop ,given an okay 
                    $n=16;
                }
            }
            $n++;
           }
          while($n<15 && !$this->fail_result);                   
    }
    
    
    // store in Db
    if(!$this->fail_result)   
    {             
        $dbquery3=$this->db->conn_id->prepare("INSERT INTO `change_email_confirm` 
                                               (`confirm_code`,`user_id`,`email`)
                                                VALUES(:confirm_code,:user_id,:email)
                                                ON DUPLICATE KEY UPDATE `email`=:email,
                                                                        `confirm_code`=:confirm_code
                                                ");
        $dbquery3->bindParam(":user_id",$data['user_id']);
        $dbquery3->bindParam(":email",$data['new_email']);
        $dbquery3->bindParam(":confirm_code",$this->confirm_code);
                 
        if(!($dbquery3->execute()))
        {
            //print_r($dbquery3->errorInfo());
            $this->fail_result=true;
            $this->addition_info='error 105_1';
            $this->result_info=" An Error Occurred, Try Again ";
        }else
        {
            $this->status=true;
            $this->result_info=" successful ";
        }
    }
             
    
    
    
    //return results
            return $info = array("status"=>$this->status,
                                   "data"=> array('result_info'=> $this->result_info,
                                                   'confirm_code'=>$this->confirm_code,
                                                   'email'=>$data['new_email'],
                                                   ),
                                  "addition_info"=>$this->addition_info);
    
    
}  
        

 ##------------------------------------------
         /*
           format
            $data=array(
                         'confirm_code'=>'423',
                       )
         
         */
public function completeChangeEmailByConfirmationCode($data=array())
{

        $this->reset_defaults();
        
        if(is_array($data['confirm_code']) || !isset($data['confirm_code'])) $data['confirm_code']='';
        $data['user_id']='';
        $data['email']='';
                 
     if(empty($data) || empty($data['confirm_code']))
     {
        $this->addition_info='error 100_1';
        $this->fail_result=true;
        //$addition_info = "empty code";
        $this->result_info=" Error: An Error Occurred, Try Again ";   
    }
       
    //check if code exists        
    if(!$this->fail_result)   
    {           
            $dbquery1=$this->db->conn_id->prepare("SELECT * FROM `change_email_confirm`
                                                    WHERE `confirm_code` = :confirm_code
                                                    LIMIT 1 ");
                        
            $dbquery1->bindParam(":confirm_code",$data['confirm_code']);
                        
            if(!($dbquery1->execute()))
            {
               // print_r($dbquery1->errorInfo());
                $this->fail_result=true;
                $this->addition_info='error 102_1';
                $this->result_info=" An Error Occurred, Try Again ";
            }else
            {
                
                $sql_result=$dbquery1->fetchALL();
                 
                #code exists   
                if(!empty($sql_result) && count($sql_result)>0)
                {   
                    $data['user_id']=$sql_result[0]['user_id'];
                    $data['email']=$sql_result[0]['email'];
                    $data['status']=$sql_result[0]['status'];
                    #is already confirmed
                    if($data['status']=='confirmed')  
                    {
                        $this->fail_result=true;
                        $this->addition_info='error 102_2 already_confirmed';
                        $this->result_info=" An Error Occurred, Try Again ";                         
                    }        
                }
                else
                { 
                    #doesn't exist
                    $addition_info='error_102_3';
                    $result_info=" An Error Occurred, Try Again ";                          
                    $fail_result=true;                    
                }
            }              
    }
    
    //change in Db the creditials [email]
    if(!$this->fail_result)   
    {           
        $dbquery2=$this->db->conn_id->prepare("
                                                UPDATE `users` SET 
                                                `email`=:email
                                                WHERE `user_id`=:user_id
                                              ");
        $dbquery2->bindParam(":email",$data['email']);
        $dbquery2->bindParam(":user_id",$data['user_id']);
       
        if(!($dbquery2->execute()))
        {
            //print_r($dbquery3->errorInfo());
            $this->fail_result=true;
            $this->addition_info='error 103_1';
            $this->result_info=" An Error Occurred, Try Again ";
        }
    }   
    

    #set to 'comfirmed' in sign_up_confirm table, Db  
    if(!$this->fail_result)   
    {           
    
                $dbquery8=$this->db->conn_id->prepare("
                                                        UPDATE `change_email_confirm` SET `status`='confirmed' WHERE `confirm_code`=:confirm_code
                                                      ");
                $dbquery8->bindParam(":confirm_code",$data['confirm_code']);

                //---execute query ---                  
                if(!($dbquery8->execute()))
                {
                    //print_r($dbquery3->errorInfo());
                    $this->fail_result=true;
                    $this->addition_info='error 108_1';
                    $this->result_info=" An Error Occurred, Try Again ";
                }
    }   
     
    #success 
    if(!$this->fail_result)
    {
        $this->status=true;
        $this->result_info="Email has been changed ";
    }
    
    

    // return result
    return $info = array("status"=>$this->status,
                           "data"=> array('result_info'=> $this->result_info,
                                           ),
                          "addition_info"=>$this->addition_info);
    
    
}  
 
        
}  


?>