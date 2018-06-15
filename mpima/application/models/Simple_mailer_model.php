<?php
class Simple_mailer_model extends CI_Model {

        //default variables
        private $status=false;
        private $addition_info=false;
        private $result_info=false; 
        private $fail_result=false;  

        public function __construct()
        {
                // Call the CI_Model constructor
                parent::__construct();

                // call email library
                $this->load->library('email');
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


        public function e_mailer($data=array())
        {

            //reset defaults
            $this->reset_defaults();

            //set defaults
            $config = Array(
                'protocol' => 'smtp',
                'smtp_host' => 'smtp.mailgun.org',
                'smtp_port' => 587,
                'smtp_user' => 'info@shopped.online',
                'smtp_pass' => 'smtpmailgun',
                'mailtype'  => 'html', 
                'charset'   => 'iso-8859-1',
                'wordwrap'  =>TRUE,
            );
            $this->load->library('email', $config);
            $this->email->set_newline("\r\n");

            $sender_email='info@shopped.online';
            $sender_name='Shopped';

            if(isset($data['config']) && empty($data['config'])) $config=$data['config'];
            if(!isset($data['message'])) $data['message']='';
            if(!isset($data['subject'])) $data['subject']='';
            if(!isset($data['receiver_email'])) $data['receiver_email']='';
            if(!isset($data['sender_email']) && empty($data['sender_email'])) $data['sender_email']=$sender_email;
            if(!isset($data['sender_name']) && empty($data['sender_name'])) $data['sender_name']=$sender_name;

            if(empty($data['receiver_email']) || empty($data['sender_email']) || empty($data['message']))
             {   
                $this->addition_info='error 100_1 : empty emails/message';
                $this->result_info=" An Error Occurred, Try Again ";
                $this->fail_result=true;    
             } 
             
            if(!$this->fail_result)  
            {  
                $this->load->library('email');

                $this->email->initialize($config); 

                $this->email->from($data['sender_email'], $data['sender_name']);
                $this->email->to($data['receiver_email']);

                $this->email->subject($data['subject']);
                $this->email->message($data['message']);

                if($this->email->send())
                {
                    $this->status=true;
                    $this->result_info="email Sent";
                }
                else
                {
                    $this->addition_info='error 101_1 : sending email failed :'.$this->email->print_debugger();
                    $this->result_info=" An Error Occurred, Try Again ";
                    $this->fail_result=true;  

                }
            }

                //return results
                return   $info = Array
                                (
                                        "status" => $this->status,    
                                        "data" => array(   
                                                        "result_info"=>$this->result_info,
                                                        ),      
                                        "addition_info"=>$this->addition_info
                                );                    
        }

}  


?>