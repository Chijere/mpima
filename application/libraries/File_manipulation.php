<?php
class File_manipulation {

            // *** Class variables
            private $file;
            private $allowedExts;
            private $max_size; 
            
            private $data;
            private $status;
            private $result_info='An error occured';
            private $addition_info;
            
            /*
                $config= array(
                                'file_source'=>200,
                                'max_size'=>'crop',
                                'allowed_extension'=>'crop',
                               );
            
            */
            function __construct($config=array())
            {   
                #work on the image
                if(!empty($config))
                {   $workedOnFile= $this->Initialize($config);
                    return $workedOnFile;
                }
            }

            #--------------------------------------------------
            private function reset_defaults()
            {
                // ***reset Class variables
                
                $this->allowedExts=array("txt");
                $this->max_size=2048000; #in bytes
                
                $this->data = array();
                $this->status = false;
                $this->result_info='An error occured';
                $this->addition_info = false;
                
            }           

            ## --------------------------------------------------------

            /*
                $config= array(
                                'max_size'=>200,
                                'allowed_extension'=>'txt',
                               );
            
            */

            public function Initialize($config=array())
            {

                //define variable
                $fail_result=false;
                
                //reset defaults
                $this->reset_defaults();

                #Entry point - assign user given variables and set defaults
                if(isset($config['file_source']))$this->file=$config['file_source'];

                if(isset($config['allowed_extension']))
                {
                    if(is_array($config['allowed_extension']))$this->allowedExts =$config['allowed_extension'];
                    else
                    {
                        $this->allowedExts = explode('|', $config['allowed_extension']);
                    }
                }   
                if(isset($config['max_size']))
                {
                    $this->max_size =$config['max_size']*1000;
                }

                # check if file is available
                if(!$this->file["error"] <= 0)
                {
                    $fail_result=true;
                    $this->addition_info = 'error 100_1 : '.$this->file["error"];
                }   

                # check if file is available
                if(empty($this->file["name"]))
                {
                    $fail_result=true;
                    $this->addition_info = 'error 100_2 :file not available';
                }   

                # validate size
                if(!$fail_result)
                {
                    # Get width and height
                    $this->check_size();
                }

                # validate extension
                if(!$fail_result)
                {
                    # Get width and height
                    $this->check_extension();
                }       
                

                return $this->get_result_Info();
            }

    #-----------------------------------------------------
            private function check_extension()
            {
                //define variable
                $fail_result=false;
                $result_info=false;
                $addition_info=false;
                $data=false;
                $status=false;
                
                /* enable for file types/MIME
                #compare alowed extensions with file type
                if ($fail_result || !(($this->file["type"] == "image/jpeg")|| ($this->file["type"] == "image/jpg")
                    || ($this->file["type"] == "image/gif")|| ($this->file["type"] == "image/x-png")|| ($this->file["type"] == "image/png")))
                {
                  $addition_info =  "error 104_1";
                  $result_info = " The selected file is not an image";
                  $fail_result=true;
                }*/

                // *** force to array
                if(!$fail_result && !is_array($this->allowedExts))
                {
                    $this->allowedExts = array($this->allowedExts);
                }

                //take file extension and check for errors
                if (!$fail_result) 
                {
                    $extension = explode(".", $this->file["name"]);
                    $extension = end($extension);
                    $extension = strtolower($extension);
                    $data=array('extension'=>$extension);
                    
                }


                #compare to allowed extension with file name
                if($fail_result || !in_array($extension, $this->allowedExts))
                {
                    $addition_info =  "error 101_2";
                    //$result_info =  "bad_extension";
                    $result_info =  "The selected file is not supported";
                    $fail_result=true;
                }

                if(!$fail_result)
                    $status= true;

                  $info = Array(
                                "addition_info" => $addition_info,
                                "result_info" => $result_info,
                                "data"=>$data,
                                "status" => $status
                                );
                
                $this->setGlobalError($info);   
                return $this->get_result_Info();
            }   
            
            ## --------------------------------------------------------
            
            private function check_size()
            {
                //define variable
                $addition_info=false;
                $data=false;
                $result_info=false;
                $fail_result=false;
                $status=false;
                
                  
                if ($fail_result || $this->file["size"] > $this->max_size)
                {  
                    $addition_info =  "error 103_1";
                    $result_info =  "Image size should be ".$this->max_size.'kb or less';
                    $fail_result=true;
                }else
                {
                    $data=array('size'=>$this->file["size"]);
                }
                    
                if(!$fail_result)
                $status= true;
                        
                  $info = Array(
                                "addition_info" => $addition_info,
                                "result_info" => $result_info,
                                "data"=>$data,
                                "status" => $status
                                );
                
                $this->setGlobalError($info);   
                return $this->get_result_Info();
            }         

            ## --------------------------------------------------------
            private function setGlobalError($pdata=array()){

                if(!isset($pdata['data']))$pdata['data']=array();
                if(!isset($pdata['status']))$pdata['status']='';
                if(!isset($pdata['addition_info']))$pdata['addition_info']='';
                if(!isset($pdata['result_info']))$pdata['result_info']='';
            
                            $this->status=$pdata['status'];
                            $this->data=$pdata['data'];
                            $this->addition_info=$pdata['addition_info'];
                            $this->result_info=$pdata['result_info'];
            }
            
            ## --------------------------------------------------------
            public function get_result_Info(){
                $this->data['result_info']= $this->result_info;
                return $info=array(
                                'status'=>$this->status,
                                'data'=>$this->data,
                                'addition_info'=>$this->addition_info,
                                );
            }            
        
}  


?>