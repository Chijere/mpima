<?php
class Pass_crypt {

        //default variables
        private $password_entered;
        private $status=false;
        private $addition_info=false;
        private $result_info=false; 
        private $fail_result=false;  

        public function __construct($password_entered="0000")
        {
            if(defined("CRYPT_BLOWFISH") && CRYPT_BLOWFISH) 
            { 
                $this->password_entered=$password_entered; 
                $this->status=true;
            } 
            else 
            { 
                $this->status=false;
                $this->addition_info="CRYPT_BLOWFISH is NOT enabled!";
                return $this->getError();
            }                 
        }


         private $confirm_code="";
 #--------------------------------------------------
        private function reset_defaults()
        {
                 $this->addition_info="";
                 $this->status = false;
                 $this->result_info=false; 
                 $this->fail_result=false;
        } 

    #-----------------------------------------------------
        function crypt($method =PASSWORD_BCRYPT, $rounds = 10)
        { 
            if($this->status)
            {
                $crypt_options = array( 'cost' => $rounds );
                $data= password_hash($this->password_entered, $method, $crypt_options); 

               return $info=array
               (  'status'=>true,
                   'data'=> array('hash'=>$data),
                    'addition_info'=>$this->addition_info
               );
            }else{return $this->getError();}
            
        }
    #-----------------------------------------------------------
        
        
        function verify ($password_hash='0000')
        {
            if($this->status)
            {       
                if(crypt($this->password_entered, $password_hash) === $password_hash) {
                      $status=true;
                      $data='match';
                }else{
                     $status=false;
                     $data='not_match';
                }
                    
               return $info=array
               (  'status'=>$status,
                   'data'=>array('report'=>$data),
                    'addition_info'=>$this->addition_info
               );
            }else{return $this->getError();}
         }         
        
    # --------------------------------------------------------
        public function getError(){
        return $info=array(
                        'status'=>$this->status,
                        'addition_info'=>$this->addition_info
                        );
        }
        
}  


?>