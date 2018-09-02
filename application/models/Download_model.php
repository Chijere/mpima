<?php
class Download_model extends CI_Model {

    //default variables
    private $addition_info="";
    private $status = false;
    private $result_info='Sorry an Error occurred'; 
    private $fail_result=false;
    private $keepDefaults =array();

    public function __construct()
    {
            // Call the CI_Model constructor
            parent::__construct();
            
    }


 #--------------------------------------------------
    private function reset_defaults()
    {

             $this->addition_info="";
             $this->status = false;
             $this->result_info='Sorry an Error occurred'; 
             $this->fail_result=false;
    }

 #--------------------------------------------------
    /*
	keep & restore the defaults incase a fuction within the class calls 
	another method that can change the variable in the process

	do this for all private methods coz are called by others
    */
    private function keep_defaults($key)
    {

            $this->keepDefaults[$key] =array(
            	'addition_info'=>$this->addition_info,
            	'status'=>$this->status, 
            	'result_info'=>	$this->result_info,
            	'fail_result'=>$this->fail_result,
            	);
    } 

    private function restore_defaults($key)
    {
             $this->addition_info=$this->keepDefaults[$key]['addition_info'];
             $this->status = $this->keepDefaults[$key]['status'];
             $this->result_info=$this->keepDefaults[$key]['result_info'];
             $this->fail_result=$this->keepDefaults[$key]['fail_result'];

             unset($this->keepDefaults[$key]);
    } 


			  
	//----------------------------------------------------------------
	public function  force_download($data=array()){
		//reset variables
		$this->reset_defaults();

		// sanitize the file request, keep just the name and extension
		// also, replaces the file location with a preset one
		
		if(!isset($data['file_path'])) $data['file_path']='';
		if(!isset($data['file_name'])) $data['file_name']='';
		if(!isset($_SERVER['HTTP_RANGE'])) $data['server_http_range']='';else $data['server_http_range']=$_SERVER['HTTP_RANGE'];
	  	  
		// make sure the file exists
		if(!$this->fail_result)
		{
			if (!is_file($data['file_path']))
			{
					
	          	$this->fail_result=true;
	          	$this->addition_info="error 100_2 file:not found in dir";
	          	$this->result_info="Sorry The file Is No longer Available";
			}
		}

		 // try open the file
		if(!$this->fail_result)
		{
			$file = @fopen($data['file_path'],"rb");
			if (!$file)
			{ 	
			   // file couldn't be opened
	          	$this->fail_result=true;
	          	$this->addition_info="error 100_2 file:fail open";
	          	$this->result_info="Sorry An Error Occured";		   
				//header("HTTP/1.0 500 Internal Server Error");
				//exit;
			}					   	
		}	

		 // get file info
		if(!$this->fail_result)
		{
			$fileInfo = pathinfo($data['file_path']);
			$data['file_name']=str_ireplace(' ','_',$data['file_name']);
			$data['file_name'] =$data['file_name'].'.'.$fileInfo['extension'];
			$file_size  = filesize($data['file_path']);
				   	
		}					   	
		
		if(!$this->fail_result)
		{					   
			// required for IE
			    if(ini_get('zlib.output_compression')) { ini_set('zlib.output_compression', 'Off'); }
			
			// set the headers, prevent caching
			header("Pragma: public");
			header("Expires: -1");
			header("Cache-Control: public, must-revalidate, post-check=0, pre-check=0");
			  
			header("Content-Disposition: attachment; filename=".$data['file_name']);
			header("Content-Type: audio/mpeg");
					  
			//check if http_range is sent by browser (or download manager)
			if(isset($data['server_http_range']) && preg_match('/^bytes=(\d+)-(\d*)/',$data['server_http_range'], $range))
			{
			  // in parts
			}

				
			//figure out download piece from range (if set)
			list($seek_start, $seek_end) = explode('-', $range, 2);

			//set start and end based on range (if set), else set defaults
			//also check for invalid ranges.
		    $seek_end   = (empty($seek_end)) ? ($file_size - 1) : min(abs(intval($seek_end)),($file_size - 1));
		    $seek_start = (empty($seek_start) || $seek_end < abs(intval($seek_start))) ? 0 : max(abs(intval($seek_start)),0);

			 //Only send partial content header if downloading a piece of the file (IE workaround)
		    if ($seek_start > 0 || $seek_end < ($file_size - 1))
			{
			   	header('HTTP/1.1 206 Partial Content');
			   	header('Content-Range: bytes '.$seek_start.'-'.$seek_end.'/'.$file_size);
			   	header('Content-Length: '.($seek_end - $seek_start + 1));
			}
		   else
			{	
				header("Content-Length: ".$file_size);
			}

			header('Accept-Ranges: bytes');
			set_time_limit(0);
			fseek($file, $seek_start);
						 
			while(!feof($file)) 
			{
				print(@fread($file, 1024*8));
				ob_flush();
				flush();
					  if (connection_status()!=0) 
						{
				          	$this->fail_result=true;
				          	$this->addition_info="error 100_2 client:connection terminated";
				          	$this->result_info="Connection problem check Your internet services";							
							@fclose($file);
						}
			}
		}

		if(!$this->fail_result)
		{	
		  	@fclose($file);
  			$this->result_info="success";	
  			$this->status=true;	
		  
		}


		return $info = array("status"=>$this->status,
			  "data"=> array("result_info"=> $this->result_info,),
			"addition_info"=>$this->addition_info);	
	
	}
	
}

?>