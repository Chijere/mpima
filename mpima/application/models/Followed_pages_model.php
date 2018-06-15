<?php
class Followed_pages_model extends CI_Model {

        //default variables
        private $addition_info="";
        private $status = false;
        private $result_info="An Error Occurred, Try Again"; 
        private $fail_result=false;

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
                 $this->result_info="An Error Occurred, Try Again"; 
                 $this->fail_result=false;
        }
 
 ##------------------------------------------
 		 /*
		   format
		    $data=array(
						 'user_id'=>'423',
						 'followed_id'=>'09338',
					   )
		 
			 */
	public function follow($data=array())
	{

		$this->reset_defaults();		 
		 
		if(!isset($data['user_id'])) $data['user_id']='';
		if(!isset($data['followed_id'])) $data['followed_id']='';
		
		//clean
		$data['user_id']=preg_replace("/[^a-zA-Z0-9]+/","",$data['user_id']);
				 
		if(empty($data['user_id']) || empty($data['followed_id']))
		{
			$this->addition_info='error 100_1';
			$this->fail_result=true;	
		}

		if(!$this->fail_result)	
		{
			$dbquery1=$this->db->conn_id->prepare("
			SELECT `followed_id` FROM `followed_page` 
			WHERE `user_id`=:user_id AND `followed_id`=:followed_id
			LIMIT 1
			");
			$dbquery1->bindValue(':user_id',$data['user_id']);
			$dbquery1->bindValue(':followed_id',$data['followed_id']);

			//---execute query1 ---					
			if(!($dbquery1->execute()))
			{
				//print_r($dbquery1->errorInfo());
				$this->fail_result=true;
				$this->addition_info='error 101_3';
				$this->result_info=" An Error Occurred, Try Again ";
			}else
			{					
				$follower = $dbquery1->fetchAll();
				//print_r($data);
				//print_r($follower);
				
				if(count($follower)>0)
				{
					$this->fail_result=true;
					$this->status=true;
					$this->result_info="Already Followed";
				}			
			}		
		}
				
		if(!$this->fail_result)	
		{			
			$dbquery2=$this->db->conn_id->prepare("
			INSERT INTO `followed_page`(`followed_id`,`user_id`) 
			VALUES(:followed_id,:user_id)
			");
			$dbquery2->bindParam(':user_id',$data['user_id']);
			$dbquery2->bindParam(':followed_id',$data['followed_id']);

				//---execute query1 ---					
			if(!($dbquery2->execute()))
			{
				//print_r($dbquery1->errorInfo());
				$this->fail_result=true;
				$this->addition_info='error 101_3';
			}else
			{
	          $this->status=true;
			  $this->result_info="Followed";
			}	
		}

	    return $info = array("status"=>$this->status,
	                    "data"=> array('result_info'=> $this->result_info,
									   
									   ),
	                    "addition_info"=>$this->addition_info);
	}    

 ##------------------------------------------
 		 /*
		   format
		    $data=array(
						 'user_id'=>'423',
						 'followed_id'=>'09338',
					   )
		 
		 */
public function unfollow($data=array())
{

	$this->reset_defaults(); 
		 
	if(!isset($data['user_id'])) $data['user_id']='';
	if(!isset($data['followed_id'])) $data['followed_id']='';
		
	//clean
	$data['user_id']=preg_replace("/[^a-zA-Z0-9]+/","",$data['user_id']);
				 
	if(empty($data['user_id']) || empty($data['followed_id']))
	{
		$this->addition_info='error 100_1';
		$this->fail_result=true;
	}

	if(!$this->fail_result)	
	{			
		$dbquery1=$this->db->conn_id->prepare("
			DELETE FROM `followed_page` 
			WHERE `user_id`=:user_id AND `followed_id`=:followed_id ");
		$dbquery1->bindParam(':user_id',$data['user_id']);
		$dbquery1->bindParam(':followed_id',$data['followed_id']);

		//---execute query1 ---					
		if(!($dbquery1->execute()))
		{
			//print_r($dbquery1->errorInfo());
			$this->fail_result=true;
			$this->addition_info='error 101_3';
		}else
		{
          $this->status=true;
		  $this->result_info="Unfollowed";
		}	
	}

	
    return $info = array("status"=>$this->status,
                    "data"=> array('result_info'=> $this->result_info,
								   
								   ),
                    "addition_info"=>$this->addition_info);
	
	
}   

} 
?>
