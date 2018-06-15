<?php
class Sign_up_model extends CI_Model {

        //default variables
        private $addition_info="";
        private $confirm_code="";
        private $otp="";
        private $status = false;
        private $result_info=false; 
        private $fail_result=false;

        public function __construct()
        {
                // Call the CI_Model constructor
                parent::__construct();

                # call password_crypt class 
                $this->load->library('Pass_crypt');
                # call id generator class
                $this->load->library('Id_generator');

        }


 #--------------------------------------------------
        private function reset_defaults()
        {

                 $this->addition_info="";
                 $this->confirm_code="";
                 $this->otp="";
                 $this->status = false;
                 $this->result_info=false; 
                 $this->fail_result=false;
        } 
 
 ##------------------------------------------
         /*
           format
            $data=array(
                         'name'=>'Tyeer'
                         'user_type'=>'shop',
                         'password'=>'yiiyeer',
                         'email'=>'tita@gmail.com',
                         'location'=>'lilongwe',//optional
                         'phone'=>'lilongwe',//optional
                       )
         
         */
public function startSignUpByConfirmationCode($data=array())
{
    //reset default variable;
    $this->reset_defaults();
    $last_insert_id='';
    if(!isset($data['user_type'])) $data['user_type']='';
    if(!isset($data['email'])) $data['email']='';
    if(!isset($data['password'])) $data['password']='';
    if(!isset($data['name'])) $data['name']='';
    
    //format 
    $data['user_type']=strtolower($data['user_type']);
    $data['email']=strtolower($data['email']);

    // check if required data is supplied      
    if(empty($data)|| empty($data['user_type']) || empty($data['password']) || empty($data['name']) || empty($data['email']))
    {
        $this->addition_info='error 100_1';
        //$addition_info = "empty user_id || email";
        $this->result_info=" Error: An Error Occurred, Try Again "; 
        $this->fail_result=true;   
    }

    //check if the email Is Already in use
    
    //clean/maintain empty values
    if(!$this->fail_result)
    {   
         $emptyField='XXX1234NULLVALUE1234XXX'; 
         $dataTemp1=array();
         foreach($data as $key =>$value){ 
            if(empty($value) && !is_numeric($value))
                $dataTemp1[$key]=$emptyField;
            else
                $dataTemp1[$key]=$value;                
         }; 
    }

    if(!$this->fail_result)   
    {
            $dbquery1=$this->db->conn_id->prepare("SELECT `user_id` FROM `login_security` 
                                                     WHERE `email`= :email 
                                                     LIMIT 1 ");
                            
            $dbquery1->bindParam(":email",$dataTemp1['email']);
                              
            if(!($dbquery1->execute()))
            {
                //print_r($dbquery1->errorInfo());
                $this->fail_result=true;
                $this->addition_info='error 101_1';
                $this->result_info=" An Error Occurred, Try Again ";
            }else
            { 
                $sql_result=$dbquery1->fetch();
                if(!empty($sql_result))
                {
                    $this->addition_info='error_101_2';
                    $this->result_info=" The email Is Already in use ";   
                    $this->fail_result=true;                  
                }
            }   
    }
    
    if(!$this->fail_result )   
    {
         $n=0;
         do
         {
         //**--make confirm code
            # make code here and try to check it to prevent duplicate confirm codes
            # loop or make another code till its unique, send error if it reaches 15 tries
            $this->confirm_code=Id_Generator::rand_sha1(20);
        
            $dbquery2=$this->db->conn_id->prepare("SELECT `email` FROM `sign_up_by_confirmcode`
                                                    WHERE `confirm_code` = :confirm_code  
                                                    LIMIT 1 ");
            
            $dbquery2->bindParam(":confirm_code",$this->confirm_code);
                //---execute query ---                  
            if(!($dbquery2->execute()))
            {
                //print_r($dbquery2->errorInfo());
                $this->fail_result=true;
                $this->addition_info= $this->general_functions->report_error("database");
                $this->result_info=" An Error Occurred, Try Again ";
            }else
            {
            
              $sql_result=$dbquery2->fetchAll();
               
              if(count($sql_result)>0)
              {
                #if its same email and confirm_code ..just maintain and exit looop
                if($sql_result['email']==$data['email'])
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
    
    // Hash the new Password
    if(!$this->fail_result)   
    {                 
        $password = new Pass_crypt($data['password']);
        $hashed_passw = $password->crypt ();              
        
        if(!$hashed_passw['status'])
        {
                $this->fail_result=true;
                $this->addition_info='error 104_1';
                $this->result_info=" An Error Occurred, Try Again ";                  
        } 
    }
    
    // store in Db
    if(!$this->fail_result)   
    {             
        $dbquery3=$this->db->conn_id->prepare("INSERT INTO `sign_up_by_confirmcode` 
                                               (`confirm_code`,`user_type`,`email`,`password`,`name`)
                                                VALUES(:confirm_code,:user_type,:email_pass,:password,:name)
                                                ON DUPLICATE KEY UPDATE `name`=:name,
                                                                        `password`=:password,
                                                                        `user_type`=:user_type,
                                                                        `confirm_code`=:confirm_code,
                                                                        `status`=`status`
                                                ");
        $dbquery3->bindParam(':confirm_code',$this->confirm_code);
        $dbquery3->bindParam(":user_type",$data['user_type']);
        $dbquery3->bindParam(":email_pass",$data['email']);
        $dbquery3->bindParam(":password",$hashed_passw['data']['hash']);
        $dbquery3->bindParam(":name",$data['name']);
                 
        if(!($dbquery3->execute()))
        {
            //print_r($dbquery3->errorInfo());
            $this->fail_result=true;
            $this->addition_info= $this->general_functions->report_error("database");
            $this->result_info=" An Error Occurred, Try Again ";
        }else
        {
            $this->status=true;
            $this->result_info=" successful ";
            $last_insert_id=$this->db->conn_id->lastInsertId();
        }
    }
             
    
    
    
    //return results
            return $info = array("status"=>$this->status,
                                   "data"=> array('result_info'=> $this->result_info,
                                                   'confirm_code'=>$this->confirm_code,
                                                   'email'=>$data['email'],
                                                   'last_insert_id'=>$last_insert_id,
                                                   ),
                                  "addition_info"=>$this->addition_info);       
}
public function startSignUpByOtp($data=array())
{
    //reset default variable;
    $this->reset_defaults();
    $last_insert_id='';
    if(!isset($data['user_type'])) $data['user_type']='';
    if(!isset($data['password'])) $data['password']='';
    if(!isset($data['phone'])) $data['phone']='';
    if(!isset($data['name'])) $data['name']='';
    
    //format 
    $data['user_type']=strtolower($data['user_type']);
    
    // check if required data is supplied      
    if(empty($data)|| empty($data['user_type']) || empty($data['password']) || empty($data['name']) || empty($data['phone']))
    {
            $this->addition_info='error 100_1';
            //$addition_info = "empty user_id || email";
            $this->result_info=" Error: An Error Occurred, Try Again "; 
            $this->fail_result=true;   
    }

    //check if the email Is Already in use
    
    //clean/maintain empty values
    if(!$this->fail_result)
    {   
         $emptyField='XXX1234NULLVALUE1234XXX'; 
         $dataTemp1=array();
         foreach($data as $key =>$value){ 
            if(empty($value) && !is_numeric($value))
                $dataTemp1[$key]=$emptyField;
            else
                $dataTemp1[$key]=$value;                
         }; 
    }

    if(!$this->fail_result)   
    {
            $dbquery1=$this->db->conn_id->prepare("SELECT `user_id` FROM `login_security` 
                                                     WHERE `phone`= :phone
                                                     LIMIT 1 ");
                            
            $dbquery1->bindParam(":phone",$dataTemp1['phone']);
                              
            if(!($dbquery1->execute()))
            {
                //print_r($dbquery1->errorInfo());
                $this->fail_result=true;
                $this->addition_info='error 101_1';
                $this->result_info=" An Error Occurred, Try Again ";
            }else
            { 
                $sql_result=$dbquery1->fetch();
                if(!empty($sql_result))
                {
                    $this->addition_info='error_101_2';
                    $this->result_info=" The phone Is Already in use ";   
                    $this->fail_result=true;                  
                }
            }   
    }
    

    if(!$this->fail_result) // For phone OTP
    {
         //**--make OTP
            $this->otp=mt_rand(100000,900000);        
    }
    
    // Hash the new Password
    if(!$this->fail_result)   
    {                 
        $password = new Pass_crypt($data['password']);
        $hashed_passw = $password->crypt ();              
        
        if(!$hashed_passw['status'])
        {
                $this->fail_result=true;
                $this->addition_info='error 104_1';
                $this->result_info=" An Error Occurred, Try Again ";                  
        } 
    }
    
    // store in Db
    if(!$this->fail_result)   
    {             
        $dbquery3=$this->db->conn_id->prepare("INSERT INTO `sign_up_by_otp` 
                                               (`user_type`,`password`,`name`,`phone`,`otp`)
                                                VALUES(:user_type,:password,:name,:phone,:otp)
                                                ON DUPLICATE KEY UPDATE `name`=:name,
                                                                        `password`=:password,
                                                                        `user_type`=:user_type,
                                                                        `otp`=:otp,
                                                                        `status`=`status`
                                                ");
        $dbquery3->bindParam(':otp',$this->otp);
        $dbquery3->bindParam(":user_type",$data['user_type']);
        $dbquery3->bindParam(":password",$hashed_passw['data']['hash']);
        $dbquery3->bindParam(":name",$data['name']);
        $dbquery3->bindParam(":phone",$data['phone']);
                 
        if(!($dbquery3->execute()))
        {
           // print_r($dbquery3->errorInfo());
            $this->fail_result=true;
            $this->addition_info= $this->general_functions->report_error("database error");
            $this->result_info=" An Error Occurred, Try Again ";
        }else
        {
            $this->status=true;
            $this->result_info=" successful ";
            $last_insert_id=$this->db->conn_id->lastInsertId();
        }
    }
             
    
    
    
    //return results
            return $info = array("status"=>$this->status,
                                   "data"=> array('result_info'=> $this->result_info,
                                                   'otp'=>$this->otp,
                                                   'phone'=>$data['phone'],
                                                   'last_insert_id'=>$last_insert_id,
                                                   ),
                                  "addition_info"=>$this->addition_info);       
}  
        

 ##------------------------------------------
         /*
           format
            $data=array(
                         'otp'=>'423',
                       )
         
         */
public function completeSignUpByOtp($data=array())
{

    $this->reset_defaults();
    
    if(is_array($data['otp']) || !isset($data['otp'])) $data['otp']='';
    if(is_array($data['phone']) || !isset($data['phone'])) $data['phone']='';
    $data['user_type']='';
    $data['password']='';
    $data['name']='';
                 
    if(empty($data) || empty($data['otp']))
    {
            $this->addition_info='error 100_1';
            $this->fail_result=true;
            //$addition_info = "empty code";
            $this->result_info=" Error: An Error Occurred, Try Again ";   
    }   

    //check if phone exists  and matches with otp       
    if(!$this->fail_result)   
    {           

            $dbquery1=$this->db->conn_id->prepare("SELECT * FROM `sign_up_by_otp`
                                                    WHERE  `phone`= :phone AND `otp`=:otp
                                                    LIMIT 1 ");
            
            $dbquery1->bindValue(":phone",$data['phone']);
            $dbquery1->bindValue(":otp",$data['otp']);

            if(!($dbquery1->execute()))
            {
                //print_r($dbquery2->errorInfo());
                $this->fail_result=true;
                $this->addition_info= $this->general_functions->report_error("database","");
                $this->result_info=" An Error Occurred, Try Again ";
            }else
            {                
                $sql_result=$dbquery1->fetchALL();
                 
                #check if code exists in sign_up_by_otp_table   
                if(!empty($sql_result) && count($sql_result)>0)
                {   
                    $data['user_type']=$sql_result[0]['user_type'];
                    $data['password']=$sql_result[0]['password'];
                    $data['phone']=$sql_result[0]['phone'];
                    $data['name']=$sql_result[0]['name'];  
                    $data['status']=$sql_result[0]['status'];
                    
                    #is already confirmed
                    if($data['status']=='confirmed')  
                    {
                        // check if its in users table if isnt.. register it 

                        $dbquery1=$this->db->conn_id->prepare("SELECT `user_id` FROM `login_security` 
                                                                 WHERE `phone`= :phone
                                                                 LIMIT 1 ");
                                        
                        $dbquery1->bindParam(":phone",$dataTemp1['phone']);
                                          
                        if(!($dbquery1->execute()))
                        {
                            //print_r($dbquery1->errorInfo());
                            $this->fail_result=true;
                            $this->addition_info='error 101_1';
                            $this->result_info=" An Error Occurred, Try Again ";
                        }else
                        { 
                            $sql_result=$dbquery1->fetch();
                            if(!empty($sql_result))
                            {
                                $this->addition_info='error_101_2';
                                $this->result_info=" Seems You are already registered. You can now login.";   
                                $this->fail_result=true;   
                                $this->status = true;
                   
                            }else
                            {

                                // continue....
                            }
                        }                 
                    }        
                }
                else
                {
                    #doesn't exist
                    $this->addition_info= $this->general_functions->report_error("database","Invalid verifcation code");
                    $this->result_info=" Verification code is invalid ";                          
                    $this->fail_result=true;
               
                }
            
            }              
    }
    
    //insert into Db the creditials [email,password]
    if(!$this->fail_result)   
    {           
        //make a unique rand for email column just to go with the unique identifier rules of MYSQL 
        $uniqueIdentifier='UniqDummyValue:'.Id_Generator::rand_sha1(20);

        $dbquery2=$this->db->conn_id->prepare("
                                                INSERT INTO `login_security` (`phone`,`password`,`email`) VALUES(:phone,:password,:email)
                                              ");
        $dbquery2->bindParam(":phone",$data['phone']);
        $dbquery2->bindParam(":email",$uniqueIdentifier);
        $dbquery2->bindParam(":password",$data['password']);
       
        if(!($dbquery2->execute()))
        {
            //print_r($dbquery2->errorInfo());
            $this->fail_result=true;
            $this->addition_info= $this->general_functions->report_error("database","1062|Duplicate|entry");
            
            if((preg_match('~\b(1062|Duplicate|entry)\b~i', json_encode($dbquery2->errorInfo())))){
                $this->result_info=" Seems you are already registered, You can now log in";    
                $this->status=true;
            }
            else{
                $this->result_info=" An Error Occurred, Try Again ";
            }
        }else
        {
          $data['user_id']=$this->db->conn_id->lastInsertId();
        }
    }   
    

    //insert the other data into Db on transactional
    $this->db->conn_id->beginTransaction();
    #insert user_name
    if(!$this->fail_result)   
    {           
        $dbquery3=$this->db->conn_id->prepare("
                                                INSERT INTO `user_info` (`user_name`,`user_id`) VALUES(:name,:user_id)
                                             ");
        $dbquery3->bindParam(":user_id",$data['user_id']);
        $dbquery3->bindParam(":name",$data['name']);

            //---execute query ---                  
        if(!($dbquery3->execute()))
        {
            //print_r($dbquery3->errorInfo());
            $this->fail_result=true;
            $this->addition_info= $this->general_functions->report_error("database","fail to insert into table");
            $this->result_info=" An Error Occurred, Try Again ";
        }
     }   

    #set to 'confirmed' in security(main) table, Db
    if(!$this->fail_result)   
    {           
    
                $dbquery7=$this->db->conn_id->prepare("
                                                        UPDATE `login_security` SET `registration_status`='confirmed' WHERE `user_id`=:user_id
                                                      ");
                $dbquery7->bindParam(":user_id",$data['user_id']);

                //---execute query ---                  
                if(!($dbquery7->execute()))
                {
                    //print_r($dbquery3->errorInfo());
                    $this->fail_result=true;
                    $this->addition_info='error 107_1';
                    $this->result_info=" An Error Occurred, Try Again ";
                }
    }
    
    #set to 'comfirmed' in sign_up_confirm table, Db  
    if(!$this->fail_result)   
    {           
        $dbquery8=$this->db->conn_id->prepare("
                                                UPDATE `sign_up_by_otp` SET `status`='confirmed' WHERE `otp`=:otp AND `phone`=:phone
                                              ");
        $dbquery8->bindParam(":otp",$data['otp']);
        $dbquery8->bindParam(":phone",$data['phone']);

        //---execute query ---                  
        if(!($dbquery8->execute()))
        {
            //print_r($dbquery3->errorInfo());
            $this->fail_result=true;
            $this->addition_info='error 108_1';
            $this->result_info=" An Error Occurred, Try Again ";
        }
    }   
     
    #commit the transaction to Db 
    if(!$this->fail_result)
    {
        $this->db->conn_id->commit();
        $this->status=true;
        $this->result_info="You have Been Registered ";
    }
    else
    {
        #fallBack on failure.
        #
        $this->db->conn_id->rollBack();
        
        $dbqueryDelete=$this->db->conn_id->prepare("
                                                    DELETE FROM `login_security` 
                                                    WHERE `user_id`=:user_id");
        $dbqueryDelete->bindParam(":user_id",$data['user_id']);              

        //
        if(!($dbqueryDelete->execute()))
        {
            //print_r($dbquery3->errorInfo());
            $this->fail_result=true;
        }else
        {
            $this->fail_result=true;
            //comment this $addition_info to get the correct error
            $this->addition_info.= $this->general_functions->report_error("database");
        }   
        //echo '<pre>';
        
        //print_r($data);
        //print_r($dbquery6->errorInfo());
        //print_r( $dbqueryDelete->errorInfo());
        //echo $data['map_id']; 
        //$addition_info="error 105_1";
        //$addition_info="data_roll_back";
        //$addition_info=$dbquery3->errorInfo();
        //$addition_info='error 109_2';
    }           
    

    

    // return result
    return $info = array("status"=>$this->status,
                           "data"=> array('result_info'=> $this->result_info,
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
public function completeSignUpByConfirmationCode($data=array())
{

    $this->reset_defaults();
    
    if(is_array($data['confirm_code']) || !isset($data['confirm_code'])) $data['confirm_code']='';
    $data['user_type']='';
    $data['email']='';
    $data['password']='';
    $data['name']='';
                 
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
            $dbquery1=$this->db->conn_id->prepare("SELECT * FROM `sign_up_by_confirmcode`
                                                    WHERE `confirm_code` = :confirm_code
                                                    LIMIT 1 ");
                        
            $dbquery1->bindParam(":confirm_code",$data['confirm_code']);
                        
            if(!($dbquery1->execute()))
            {
                //print_r($dbquery2->errorInfo());
                $this->fail_result=true;
                $this->addition_info='error 102_1';
                $this->result_info=" An Error Occurred, Try Again ";
            }else
            {
                
                $sql_result=$dbquery1->fetchALL();
                 
                #code exists   
                if(!empty($sql_result) && count($sql_result)>0)
                {   
                    $data['user_type']=$sql_result[0]['user_type'];
                    $data['email']=$sql_result[0]['email'];
                    $data['password']=$sql_result[0]['password'];
                    $data['name']=$sql_result[0]['name'];  
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
                    $this->addition_info='error_102_3';
                    $this->result_info=" An Error Occurred, Try Again ";                          
                    $this->fail_result=true;                    
                }
            }              
    }
    
    //insert into Db the creditials [email,password]
    if(!$this->fail_result)   
    {           
        //make a unique rand for email column just to go with the unique identifier rules of MYSQL 
        $uniqueIdentifier='UniqDummyValue:'.Id_Generator::rand_sha1(20);
        
        $dbquery2=$this->db->conn_id->prepare("
                                                INSERT INTO `login_security` (`email`,`password`,`phone`) VALUES(:email,:password,:phone)
                                              ");
        $dbquery2->bindParam(":email",$data['email']);
        $dbquery2->bindParam(":password",$data['password']);
        $dbquery2->bindParam(":phone",$uniqueIdentifi);
       
        if(!($dbquery2->execute()))
        {
            //print_r($dbquery3->errorInfo());
            $this->fail_result=true;
            $this->addition_info='error 103_1';
            $this->result_info=" An Error Occurred, Try Again ";
        }else
        {
          $data['user_id']=$this->db->conn_id->lastInsertId();
        }
    }   
    

    //insert the other data into Db on transactional
    $this->db->conn_id->beginTransaction();
    #insert user_name
    if(!$this->fail_result)   
    {           
                $dbquery3=$this->db->conn_id->prepare("
                                                        INSERT INTO `user_info` (`user_name`,`user_id`) VALUES(:name,:user_id)
                                                     ");
                $dbquery3->bindParam(":user_id",$data['user_id']);
                $dbquery3->bindParam(":name",$data['name']);

                    //---execute query ---                  
                if(!($dbquery3->execute()))
                {
                    //print_r($dbquery3->errorInfo());
                    $this->fail_result=true;
                    $this->addition_info='error 104_1';
                    $this->result_info=" An Error Occurred, Try Again ";
                }
     }  
    

    #set to 'confirmed' in security(main) table, Db
    if(!$this->fail_result)   
    {           
    
                $dbquery7=$this->db->conn_id->prepare("
                                                        UPDATE `login_security` SET `registration_status`='confirmed' WHERE `user_id`=:user_id
                                                      ");
                $dbquery7->bindParam(":user_id",$data['user_id']);

                //---execute query ---                  
                if(!($dbquery7->execute()))
                {
                    //print_r($dbquery3->errorInfo());
                    $this->fail_result=true;
                    $this->addition_info='error 107_1';
                    $this->result_info=" An Error Occurred, Try Again ";
                }
    }
    
    #set to 'comfirmed' in sign_up_confirm table, Db  
    if(!$this->fail_result)   
    {           
    
                $dbquery8=$this->db->conn_id->prepare("
                                                        UPDATE `sign_up_by_confirmcode` SET `status`='confirmed' WHERE `confirm_code`=:confirm_code
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
     
    #commit the transaction to Db 
    if(!$this->fail_result)
    {
        $this->db->conn_id->commit();
        $this->status=true;
        $this->result_info="You have Been Registered ";
    }
    else
    {
        #fallBack on failure.
        #
        $this->db->conn_id->rollBack();
        
        $dbqueryDelete=$this->db->conn_id->prepare("
                                                    DELETE FROM `login_security` 
                                                    WHERE `user_id`=:user_id");
        $dbqueryDelete->bindParam(":user_id",$data['user_id']);              
        
        //
        if(!($dbqueryDelete->execute()))
        {
            //print_r($dbquery3->errorInfo());
            $this->fail_result=true;
        }else
        {
            $this->fail_result=true;
            //comment this $addition_info to get the correct error
            $this->addition_info.=' + error 109_1';                         
        }   
        //echo '<pre>';
        
        //print_r($data);
        //print_r($dbquery6->errorInfo());
        //print_r( $dbqueryDelete->errorInfo());
        //echo $data['map_id']; 
        //$addition_info="error 105_1";
        //$addition_info="data_roll_back";
        //$addition_info=$dbquery3->errorInfo();
        //$addition_info='error 109_2';
    }           
    

    

    // return result
    return $info = array("status"=>$this->status,
                           "data"=> array('result_info'=> $this->result_info,
                                           ),
                          "addition_info"=>$this->addition_info);   
}  
        
}  


?>