<?php
class Order_items_model extends CI_Model {

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
 
	public function add_an_order($data=array())
	{

		$this->reset_defaults();		 
		$order_id_shown_to_user = "";
		if(!isset($data['user_id'])) $data['user_id']='';
		if(!isset($data['item_id'])) $data['item_id']='';
		if(!isset($data['phone'])) $data['phone']='';
		if(!isset($data['payment'])) $data['payment']='';
		if(!isset($data['status'])) $data['status']='waiting for call';
		
		//clean
		$data['user_id']=preg_replace("/[^a-zA-Z0-9]+/","",$data['user_id']);
		$data['item_id']=preg_replace("/[^a-zA-Z0-9]+/","",$data['item_id']);
				 
		if(empty($data['user_id']) ||empty($data['payment']) || empty($data['item_id']) || empty($data['phone']))
		{
			$this->addition_info='error 100_1';
			$this->fail_result=true;	
		}

		//Avoid ordering multiple times same item
	    if(!$this->fail_result)   
	    {
	            $dbquery1=$this->db->conn_id->prepare("SELECT `status`,`order_id_shown_to_user` FROM `ordered_items`
	                                                    WHERE `item_id` = :item_id
	                                                    AND   `user_id` = :user_id
	                                                    AND   `status` = :status
	                                                    LIMIT 1 
	                                                  ");
	            
	            $dbquery1->bindParam(":item_id",$data['item_id']);
	            $dbquery1->bindParam(":user_id",$data['user_id']);
	            $dbquery1->bindParam(":status",$data['status']);
	                //---execute query ---                  
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
	              	#if its already ordered
	                if($sql_result['status']=="waiting for call")
	                {
	                    $this->addition_info='error_101_2';
	                    $this->result_info=" Item is already Ordered ";                          
	                    $this->fail_result=true;
	                    $this->status=true;
	                    $order_id_shown_to_user = $sql_result['order_id_shown_to_user'];
	                }                       
	              }
	            }   
	    }

		//verify unique id is generated
	    if(!$this->fail_result)   
	    {
	    	$this->load->library('Id_generator');
	         $n=0;
	         do
	         {
	         //**--make order_id
	            # try to check it to prevent duplicate order_ids
	            # loop or make another code till its unique, send error if it reaches 15 tries

	         
	            $order_id_shown_to_user=Id_generator::rand_sha1(20);
	        
	            $dbquery2=$this->db->conn_id->prepare("SELECT `item_id` FROM `ordered_items`
	                                                    WHERE `order_id_shown_to_user` = :order_id_shown_to_user
	                                                    LIMIT 1 
	                                                  ");
	            
	            $dbquery2->bindParam(":order_id_shown_to_user",$order_id_shown_to_user);
	                //---execute query ---                  
	            if(!($dbquery2->execute()))
	            {
	                //print_r($dbquery2->errorInfo());
	                $this->fail_result=true;
	                $this->addition_info='error 101_1';
	                $this->result_info=" An Error Occurred, Try Again ";
	            }else
	            {
	            
	              $sql_result=$dbquery2->fetch();	               
	              if(!empty($sql_result))
	              {
	                #if its same email and order_id_shown_to_user ..just maintain and exit looop
	                if($sql_result==$data['item_id'])
	                    $n=16;
	                
	                #if exceeded tries return error
	                if($n>14)
	                {
	                    $this->addition_info='error_101_2';
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
				
		if(!$this->fail_result)	
		{			
			$dbquery2=$this->db->conn_id->prepare("
			INSERT INTO `ordered_items`(`item_id`,`user_id`,`phone`,`order_id_shown_to_user`,`payment`) 
			VALUES(:item_id,:user_id,:phone,:order_id_shown_to_user,:payment)
			");
			$dbquery2->bindParam(':user_id',$data['user_id']);
			$dbquery2->bindParam(':item_id',$data['item_id']);
			$dbquery2->bindParam(':phone',$data['phone']);
			$dbquery2->bindParam(':payment',$data['payment']);
			$dbquery2->bindParam(':order_id_shown_to_user',$order_id_shown_to_user);

				//---execute query1 ---					
			if(!($dbquery2->execute()))
			{
				//print_r($dbquery2->errorInfo());
				$this->fail_result=true;
				$this->addition_info='error 103_1';
			}else
			{
	          $this->status=true;
			  $this->result_info="ordered";
			}	
		}

	    return $info = array("status"=>$this->status,
	                    "data"=> array('result_info'=> $this->result_info,									   
	                    				'order_id_shown_to_user'=> $order_id_shown_to_user,									   
									   ),
	                    "addition_info"=>$this->addition_info);
	}    
 
	public function edit_an_order($data=array())
	{

		$this->reset_defaults();		 
		$order_id_shown_to_user = "";
		if(!isset($data['order_id'])) $data['order_id']='';
		if(!isset($data['phone'])) $data['phone']='';
		if(!isset($data['payment'])) $data['payment']='';
		if(!isset($data['status'])) $data['status']='waiting for call';
		
		//clean
		$data['user_id']=preg_replace("/[^a-zA-Z0-9]+/","",$data['user_id']);
				 
		if(empty($data) || (empty($data['order_id']) && empty($data['user_id']) && !is_numeric($data['user_id'])))
		{
			$this->addition_info='error 100_1';
			$this->fail_result=true;	
		}

		//Avoid edit an item beyond "called" phase
	    if(!$this->fail_result)   
	    {
	            $dbquery1=$this->db->conn_id->prepare("SELECT `status`,`order_id_shown_to_user` FROM `ordered_items`
	                                                    WHERE `order_id` = :order_id
	                                                    AND   `status` = :status
	                                                    AND   `user_id` = :user_id
	                                                    LIMIT 1 
	                                                  ");
	            
	            $dbquery1->bindParam(":order_id",$data['order_id']);
	            $dbquery1->bindParam(":user_id",$data['user_id']);
	            $dbquery1->bindParam(":status",$data['status']);
	                //---execute query ---                  
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
	              	$order_id_shown_to_user = $sql_result['order_id_shown_to_user'];
	                if($sql_result['status']!="waiting for call")
	                {
	                    $this->addition_info='error_101_2';
	                    $this->result_info=" Please contact support to edit the order ";                          
	                    $this->fail_result=true;
	                    $this->status=true;	                    
	                }                       
	              }else{
	                    $this->addition_info='error_101_3';
	                    $this->result_info=" An Error Occurred, Try Again ";                          
	                    $this->fail_result=true;
	                    $this->status=true;	                    
	              }
	            }   
	    }

	    //clean/maintain empty values
	    if(!$this->fail_result)
	    {	
	    	 $emptyField='XXX1234NULLVALUE1234XXX';	
             foreach($data as $key =>$value){ 
				if(empty($value) && !is_numeric($value))
				 	$data[$key]=$emptyField;	
			 		
			 };	
	    }

	
		if(!$this->fail_result)	
		{			
			$dbquery2=$this->db->conn_id->prepare("
				UPDATE `ordered_items` SET 
				`phone`= CASE WHEN :phone=:emptyField THEN `phone` ELSE :phone END,
				`payment`= CASE WHEN :payment=:emptyField THEN `payment` ELSE :payment END,
				`date`= `date`
				WHERE (`order_id`=:order_id AND `order_id`=:order_id )
			");
			$dbquery2->bindParam(':order_id',$data['order_id']);
			$dbquery2->bindParam(':user_id',$data['user_id']);
			$dbquery2->bindParam(':phone',$data['phone']);
			$dbquery2->bindParam(':payment',$data['payment']);
			$dbquery2->bindParam(":emptyField",$emptyField); 

				//---execute query1 ---					
			if(!($dbquery2->execute()))
			{
				//print_r($dbquery2->errorInfo());
				$this->fail_result=true;
				$this->addition_info='error 103_1';

				//return 0;
			}else
			{
	          $this->status=true;
			  $this->result_info="Order edited";
			}	
		}

	    return $info = array("status"=>$this->status,
	                    "data"=> array('result_info'=> $this->result_info,									   
	                    				'order_id_shown_to_user'=> $order_id_shown_to_user,									   
									   ),
	                    "addition_info"=>$this->addition_info);
	}    
		
	public function delete_order($data=array())
	{

		$this->reset_defaults(); 
		$order_id_shown_to_user	= ""; 
		if(!isset($data['order_id'])) $data['order_id']='';
		if(!isset($data['user_id'])) $data['user_id']='';
		if(!isset($data['status'])) $data['status']='waiting for call';

		//clean
		$data['user_id']=preg_replace("/[^a-zA-Z0-9]+/","",$data['user_id']);
				 
		if(empty($data) || (empty($data['order_id']) && empty($data['user_id']) && !is_numeric($data['user_id'])))
		{
			$this->addition_info='error 100_1';
			$this->fail_result=true;
		}


		//Avoid ordering multiple times same item
	    if(!$this->fail_result)   
	    {
	            $dbquery1=$this->db->conn_id->prepare("SELECT `status`,`order_id_shown_to_user` FROM `ordered_items`
	                                                    WHERE `order_id` = :order_id
	                                                    AND   `status` = :status
	                                                    AND   `user_id` = :user_id
	                                                    LIMIT 1 
	                                                  ");
	            
	            $dbquery1->bindParam(":order_id",$data['order_id']);
	            $dbquery1->bindParam(":user_id",$data['user_id']);
	            $dbquery1->bindParam(":status",$data['status']);
	                //---execute query ---              
	                  
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
	              	$order_id_shown_to_user = $sql_result['order_id_shown_to_user'];
	                if($sql_result['status']!="waiting for call")
	                {
	                    $this->addition_info='error_101_2';
	                    $this->result_info=" Please contact support to delete the order ";                          
	                    $this->fail_result=true;
	                    $this->status=true;	                    
	                }                       
	              }else{
	                    $this->addition_info='error_101_3';
	                    $this->result_info=" An Error Occurred, Try Again ";                          
	                    $this->fail_result=true;
	              }
	            }   
	    }

		if(!$this->fail_result)	
		{			
			$dbquery1=$this->db->conn_id->prepare("
				DELETE FROM `ordered_items` 
				WHERE `order_id`=:order_id AND `status`=:status AND `user_id`=:user_id");
			$dbquery1->bindParam(':user_id',$data['user_id']);
			$dbquery1->bindParam(':order_id',$data['order_id']);
			$dbquery1->bindParam(':status',$data['status']);

			//---execute query1 ---					
			if(!($dbquery1->execute()))
			{
				//print_r($dbquery1->errorInfo());
				$this->fail_result=true;
				$this->addition_info='error 102_3';
			}else
			{
	          $this->status=true;
			  $this->result_info="Order deleted";
			}	
		}
		
	    return $info = array("status"=>$this->status,
	                    "data"=> array('result_info'=> $this->result_info,
	                    				'order_id_shown_to_user'=> $order_id_shown_to_user,										   
									   ),
	                    "addition_info"=>$this->addition_info);		
	}  

	public function getOrderedItem($filters=array())
	{
	        // set defaults
	         $this->reset_defaults(); 
         
			 $searchWord='';
			 $count=0;
			 $result=array();
			 $total_records=false;		 
			 $get_total_records=false;
			 $filteringCondtion=' AND ';
			 $itemsPerLocation=array();
			 $itemsPerCategory=array();
			 $itemsPerVerification=array();			 
	    	 $from=0;$take=20;
			 $order_by='`date` DESC';
		 	 $data['default_profile_pic']="media/default/shop/image/profile";		 
		 	 $data['default_item_pic']="media/default/shop/image/profile_cover";		 
			 
			 if(isset($filters['default_profile_pic']))
			 { $data['default_profile_pic']=$filters['default_profile_pic'];
			   unset($filters['default_profile_pic']);
			 }

			 if(isset($filters['default_item_pic']))
			 { $data['default_item_pic']=$filters['default_item_pic'];
			   unset($filters['default_item_pic']);
			 }			 	

			 if(isset($filters['from']))
			 { $from=$filters['from'];
			   unset($filters['from']);
			 }
			 
			 if(isset($filters['take']))
			 { $take=$filters['take'];
			   unset($filters['take']);
			 }
			 
			 if(isset($filters['order_by']))
			 { 
			 	if($filters['order_by']=='oldest')
			 	$order_by='`date` ASC';
			 	elseif($filters['order_by']=='recent' || $filters['order_by']=='most_recent')
			 	$order_by='`date` DESC';

			   unset($filters['order_by']);
			 }

			 if(isset($filters['get_total_records']))
			 { $get_total_records=$filters['get_total_records'];
			   unset($filters['get_total_records']);
			 }	
			 
			 if(isset($filters['filtering_condtion']))
			 { $filteringCondtion=$filters['filtering_condtion'];
			   unset($filters['filtering_condtion']);
			 }	
			 
			$WhereSubQueryArry=array();		

	        array_push($WhereSubQueryArry,'(`item_id` IS NOT NULL) ');			
				
			$n=1;
			foreach($filters as $key => $value)
			{  
			  
				if(is_array($value))
				 {
					$valueFormated=$value;
				 }
				 else
				 {
				   $valueFormated=array($value);
				 }
		       
				   
	            $miniWhereSubQueryArry=array();			   
			   foreach($valueFormated as $key2 => $value2)
			   {
					if($key==='category_id' || ($key==='category_id'.$n) )
					{
						array_push($miniWhereSubQueryArry,'`category_id`=:category_id'.$n ); 
						$n++;
					}elseif($key==='not_category_id' || ($key==='not_category_id'.$n) )
					{
						array_push($miniWhereSubQueryArry,'`category_id`!=:not_category_id'.$n ); 
						$n++;
					}elseif($key==='verification' || ($key==='verification'.$n) )
					{
						array_push($miniWhereSubQueryArry,'`verification`=:verification'.$n ); 
						$n++;
					}elseif($key==='not_verification' || ($key==='not_verification'.$n) )
					{
						array_push($miniWhereSubQueryArry,'`verification`!=:not_verification'.$n ); 
						$n++;
					}elseif($key==='condition' || ($key==='condition'.$n) )
					{
						array_push($miniWhereSubQueryArry,'`condition`=:condition'.$n ); 
						$n++;
					}elseif($key==='not_condition' || ($key==='not_condition'.$n) )
					{
						array_push($miniWhereSubQueryArry,'`condition`!=:not_condition'.$n ); 
						$n++;
					}elseif($key==='user_id' || ($key==='user_id'.$n) )
					{
						array_push($miniWhereSubQueryArry,'`user_id`=:user_id'.$n ); 
						$n++;
					}elseif($key==='not_user_id' || ($key==='not_user_id'.$n) )
					{
						array_push($miniWhereSubQueryArry,'`user_id`!=:not_user_id'.$n ); 
						$n++;
					}elseif($key==='item_id' || ($key==='item_id'.$n) )
					{
						array_push($miniWhereSubQueryArry,'`item_id`=:item_id'.$n ); 
						$n++;
					}elseif($key==='order_id' || ($key==='order_id'.$n) )
					{
						array_push($miniWhereSubQueryArry,'`order_id`=:order_id'.$n ); 
						$n++;
					}elseif($key==='order_id_shown_to_user' || ($key==='order_id_shown_to_user'.$n) )
					{
						array_push($miniWhereSubQueryArry,'`order_id_shown_to_user`=:order_id_shown_to_user'.$n ); 
						$n++;
					}elseif($key==='phone' || ($key==='phone'.$n) )
					{
						array_push($miniWhereSubQueryArry,'`phone`=:phone'.$n ); 
						$n++;
					}elseif($key==='payment' || ($key==='payment'.$n) )
					{
						array_push($miniWhereSubQueryArry,'`payment`=:payment'.$n ); 
						$n++;
					}elseif($key==='status' || ($key==='status'.$n) )
					{
						array_push($miniWhereSubQueryArry,'`status`=:status'.$n ); 
						$n++;
					}elseif($key==='not_item_id' || ($key==='not_item_id'.$n) )
					{
						array_push($miniWhereSubQueryArry,'`item_id`!=:not_item_id'.$n ); 
						$n++;
					}elseif($key==='price_from' || ($key==='price_from'.$n) )
					{
						array_push($miniWhereSubQueryArry,'`price`>=:price_from'.$n ); 
						$n++;
					}elseif($key==='price_to' || ($key==='price_to'.$n) )
					{
						array_push($miniWhereSubQueryArry,'`price`<=:price_to'.$n ); 
						$n++;
					}elseif($key==='invalid' || ($key==='invalid'.$n))
					{
						array_push($WhereSubQueryArry,'1=0' ); 
					}else
					{
						array_push($WhereSubQueryArry,'1=0' ); 
					}		   
			   }
		   
			   if(!empty($miniWhereSubQueryArry))
			   { 
					$miniWheresubQuery= '('.implode(' OR ',$miniWhereSubQueryArry).')';	
					array_push($WhereSubQueryArry,$miniWheresubQuery ); 
			   }
			}
				
			$WheresubQuery="";
			$filteringCondtion=' '.$filteringCondtion.' ';
			if(!empty($WhereSubQueryArry))
			$WheresubQuery='WHERE '.implode($filteringCondtion,$WhereSubQueryArry);			 
		 
			$get_total_recordsQuery="";
			if($get_total_records)
			{
			  $get_total_recordsQuery='SQL_CALC_FOUND_ROWS';
			}
		
			$dbquery1 =  $this->db->conn_id->prepare("	
														CREATE TEMPORARY TABLE temp_table AS(								
															SELECT *
															FROM(
																SELECT *,`date` AS `date_ordered`
																	FROM `ordered_items` `s10`	

																	LEFT JOIN( 	
																		SELECT `item_id` AS `s1_item_id`,`name`,`price`,`item_pic`,`category_id`,`category_name`,`category_parent_name`,`category_parent_id`,
																			`condition_id`,`condition_name`,`user_id` AS `s1_user_id`,`description`,`user_name`,`about`,`date` AS `added_date`,
																			`profile_pic`,`user_pic`,`location`, 
																			CASE WHEN ISNULL(`s7_verification`) THEN 'Unverified' ELSE `s7_verification` END  AS `verification`,
																			CASE WHEN ISNULL(`s7_user_rank`) THEN '0' ELSE `s7_user_rank` END AS `user_rank` 
																	   	FROM `item` `s1` 
																 	
																			LEFT JOIN( 
																			SELECT `about` ,`user_name`, `user_id` AS `s3_user_id`
																			FROM `user_info`
																			)AS `s3` ON  `s3`.`s3_user_id`= `s1`.`user_id`	
																			
																			LEFT JOIN( 
																			SELECT `user_pic` , `user_id` AS `s4_user_id`
																			FROM `profile_pic`
																			) AS `s4` ON  `s4`.`s4_user_id`= `s1`.`user_id`
																			
																			LEFT JOIN( 
																			SELECT `parent_name` AS `category_parent_name`,`parent_id` AS `category_parent_id`, `category_name`, `category_id` AS `s5_category_id`
																			FROM `category` `s5_a1`
																					LEFT JOIN( SELECT `category_name` As parent_name,`category_id` AS `s5_b1_category_id` 
																								FROM `category` 
																					)AS `s5_b1` ON  `s5_b1`.`s5_b1_category_id` = `s5_a1`.`parent_id`							
																			) AS `s5` ON  `s5`.`s5_category_id`= `s1`.`category_id`
																					
																			LEFT JOIN( 
																			SELECT `condition_name`, `condition_id` AS `s6_condition_id`
																			FROM `item_condition`
																			) AS `s6` ON  `s6`.`s6_condition_id`= `s1`.`condition_id`
																					
																			LEFT JOIN( 
																			SELECT `rank` AS `s7_user_rank`, `user_id` AS `s7_user_id`, `verification` AS `s7_verification`
																			FROM `verification`
																			) AS `s7` ON  `s7`.`s7_user_id`= `s1`.`user_id`

																			LEFT JOIN (
																			SELECT `user_pic` as `profile_pic` , `user_id` AS `s8_user_id`
																			FROM `profile_pic`
																			) AS `s8` ON `s8`.`s8_user_id` = `s1`.`user_id`
																						
																			LEFT JOIN (
																			SELECT `user_id` AS `s9_user_id` ,`location`
																			FROM `contact_map`
																			GROUP BY `user_id`
																			) AS `s9` ON `s9`.`s9_user_id` = `s1`.`user_id`

																	) AS `s1X` ON `s1X`.`s1_item_id` = `s10`.`item_id`

																		
							                                ) `s_whole`
																		
																".$WheresubQuery."

															GROUP BY `item_id`
														)			
													");					
			$n=1;	
			foreach($filters as $key => $value)
			{
				if(is_array($value))
				{
					$valueFormated=$value;
				}
				 else
				{
				   $valueFormated=array($value);
				}

			   foreach($valueFormated as $key2 => $value2)
			   {					   
					if($key==='category_id' || ($key==='category_id'.$n) ) 
					{$dbquery1->bindValue(":category_id".$n,$value2); 	$n++;}
					
					if($key==='condition' || ($key==='condition'.$n) ) 
					{$dbquery1->bindValue(":condition".$n,$value2); $n++; }
					
					if($key==='verification' || ($key==='verification'.$n) ) 
					{$dbquery1->bindValue(":verification".$n,$value2); $n++; }
					
					if($key==='user_id' || ($key==='user_id'.$n) ) 
					{$dbquery1->bindValue(":user_id".$n,$value2); $n++; }
														
					if($key==='item_id' || ($key==='item_id'.$n) ) 
					{$dbquery1->bindValue(":item_id".$n,$value2); $n++; }

					if($key==='order_id' || ($key==='order_id'.$n) ) 
					{$dbquery1->bindValue(":order_id".$n,$value2); $n++; }

					if($key==='status' || ($key==='status'.$n) ) 
					{$dbquery1->bindValue(":status".$n,$value2); $n++; }

					if($key==='order_id_shown_to_user' || ($key==='order_id_shown_to_user'.$n) ) 
					{$dbquery1->bindValue(":order_id_shown_to_user".$n,$value2); $n++; }

					if($key==='phone' || ($key==='phone'.$n) ) 
					{$dbquery1->bindValue(":phone".$n,$value2); $n++; }

					if($key==='payment' || ($key==='payment'.$n) ) 
					{$dbquery1->bindValue(":payment".$n,$value2); $n++; }

					if($key==='not_category_id' || ($key==='not_category_id'.$n) ) 
					{$dbquery1->bindValue(":not_category_id".$n,$value2); 	$n++;}
					
					if($key==='not_condition' || ($key==='not_condition'.$n) ) 
					{$dbquery1->bindValue(":not_condition".$n,$value2); $n++; }
					
					if($key==='not_verification' || ($key==='not_verification'.$n) ) 
					{$dbquery1->bindValue(":not_verification".$n,$value2); $n++; }
					
					if($key==='not_user_id' || ($key==='not_user_id'.$n) ) 
					{$dbquery1->bindValue(":not_user_id".$n,$value2); $n++; }
														
					if($key==='not_item_id' || ($key==='not_item_id'.$n) ) 
					{$dbquery1->bindValue(":not_item_id".$n,$value2); $n++; }
														
					if($key==='price_from' || ($key==='price_from'.$n) ) 
					{$dbquery1->bindValue(':price_from'.$n, (int)$value2, PDO::PARAM_INT); $n++; }

					if($key==='price_to' || ($key==='price_to'.$n) ) 
					{$dbquery1->bindValue(':price_to'.$n, (int)$value2, PDO::PARAM_INT); $n++; }
								
			   }					
			}		 
			 

			
			if(!$this->fail_result)
			{

			 if(!$dbquery1->execute())
			   {
				$this->status=false;
				!$this->fail_result=true;
				//echo '<pre>';
				//print_r($valueFormated);
				//print_r($dbquery1);
				//print_r($dbquery1->errorInfo());
				$this->addition_info = $dbquery1->errorInfo();
				//$this->addition_info = 'error_100';
			   }
			}	

			//get the items
			if(!$this->fail_result)
			{

			$dbquery2 =  $this->db->conn_id->prepare("	
														SELECT ".$get_total_recordsQuery." *
														FROM `temp_table` AS `s1`		
														
														GROUP BY `item_id`
														ORDER BY ".$order_by."											
														LIMIT :from,:take									
													");
			
			$dbquery2->bindValue(':from', (int)$from, PDO::PARAM_INT);
			$dbquery2->bindValue(':take', (int)$take, PDO::PARAM_INT);

			 if(!$dbquery2->execute())
			   {
				$this->status=false;
				!$this->fail_result=true;
				//echo '<pre>';
				//print_r($valueFormated);
				//print_r($dbquery1);
				//print_r($dbquery1->errorInfo());
				$this->addition_info = $dbquery2->errorInfo();
				//$this->addition_info = 'error_100';
			   }else{

			   	//get total records
			   	if($get_total_records)
				{
				 	$dbqueryCount =  $this->db->conn_id->query('SELECT FOUND_ROWS()');
				 	$total_records = (int) $dbqueryCount->fetchColumn();
				}
			   }
			}				

			//get the items' groups of verification
			if(!$this->fail_result)
			{

				$dbquery3 =  $this->db->conn_id->prepare("	
															SELECT COUNT(*) AS `itemsPerVerification`,
																	CASE WHEN ISNULL(`verification`) THEN 'Unverified' ELSE `verification` END  AS `verification`
															FROM `temp_table`
															GROUP BY `verification`											
															LIMIT 15									
														");
					
				if(!$dbquery3->execute())
				{
					$this->status=false;
					!$this->fail_result=true;
					//echo '<pre>';
					//print_r($valueFormated);
					//print_r($dbquery1);
					//print_r($dbquery1->errorInfo());
					$this->addition_info = $dbquery3->errorInfo();
					//$this->addition_info = 'error_100';
				}
			}						

			//get the items' groups of categories
			if(!$this->fail_result)
			{

				$dbquery4 =  $this->db->conn_id->prepare("	
															SELECT COUNT(*) AS `itemsPerCategory`,`category_id`,
																	`category_parent_name` AS `parent_name`,`category_parent_id` AS `parent_id`,`category_name`
															FROM `temp_table`
															GROUP BY `category_id`											
															LIMIT 15									
														");
					
				if(!$dbquery4->execute())
				{
					$this->status=false;
					!$this->fail_result=true;
					//echo '<pre>';
					//print_r($valueFormated);
					//print_r($dbquery1);
					//print_r($dbquery1->errorInfo());
					$this->addition_info = $dbquery4->errorInfo();
					//$this->addition_info = 'error_100';
				}
			}	

			//get the items' groups of location
			if(!$this->fail_result)
			{

				$dbquery5 =  $this->db->conn_id->prepare("	
															SELECT COUNT(*) AS `itemsPerLocation`,`location`
															FROM `temp_table`
															GROUP BY `location`											
															LIMIT 15									
														");
					
				if(!$dbquery5->execute())
				{
					$this->status=false;
					!$this->fail_result=true;
					//echo '<pre>';
					//print_r($valueFormated);
					//print_r($dbquery1);
					//print_r($dbquery1->errorInfo());
					$this->addition_info = $dbquery5->errorInfo();
					//$this->addition_info = 'error_100';
				}
			}		

			//delete the temp table
			if(!$this->fail_result)
			{

				$dbquery6 =  $this->db->conn_id->prepare("	
															DROP TEMPORARY TABLE IF EXISTS `temp_table`									
														");
					
				if(!$dbquery6->execute())
				{
					$this->status=false;
					$this->fail_result=true;
					//echo '<pre>';
					//print_r($valueFormated);
					//print_r($dbquery1);
					//print_r($dbquery1->errorInfo());
					$this->addition_info = $dbquery6->errorInfo();
					//$this->addition_info = 'error_100';
				}
			}
			//collect and organise for items + item groups
			if(!$this->fail_result)
			{ 
			   

				#collect for items'group
			   	$itemsPerVerification = $dbquery3->fetchAll();
			   	$itemsPerCategory = $dbquery4->fetchAll();
			   	$itemsPerLocation = $dbquery5->fetchAll();
				
			   	#add levels to the categories
			   	$this->load->model('category_model');
			   	$itemsPerCategory=$this->category_model->category_levels_setter ($itemsPerCategory,1,0);

				#collect for item
			  	$this->status = true;
			  	$sql_result = $dbquery2->fetchAll();
			  	$count=count($sql_result);
			  
			   	//echo '<pre>';
			  	//print_r($sql_result);
				  
				  
				if(count($sql_result)>0)
				{
				    foreach( $sql_result as $key => $value)
					{
						
						#format item_pic
						$item_picArray=array();
						$mainPicCheck=false;
						$delimiter1_item_pic='|#$(delimiter-1)$#|';
						$delimiter2_item_pic='|#$(delimiter-2)$#|';
				        $ArrayTemp1=explode($delimiter1_item_pic,$value['item_pic']);
			            $ArrayTemp1=array_filter($ArrayTemp1);
			            foreach ($ArrayTemp1 as $keytemp => $valuetemp) 
			            {
			            	$ArrayTemp2=explode($delimiter2_item_pic,$valuetemp);
			            	if(isset($ArrayTemp2[0]))
			            	{

				            	#set only 1 front pic
				            	if($ArrayTemp2[2]==1 && !$mainPicCheck)
								{
									$mainPicCheck=true;
									$keytemp='main';
									$item_picArray[$keytemp]['front_pic']=$ArrayTemp2[2];						
								}else
								{
									$item_picArray[$keytemp]['front_pic']=0;	
								}
				            	$item_picArray[$keytemp]['id']=$ArrayTemp2[0];
				            	$item_picArray[$keytemp]['path']=$ArrayTemp2[1];	
				            	
			            	}	
			            }

						//set default pic if no pic
						if(!empty($item_picArray))
						{
							#set default front pic, if not set
						  if(!$mainPicCheck)
						  {
							$item_picArray['main']['front_pic']=1;
							$item_picArray['main']['id']=$item_picArray[0]['id'];
							$item_picArray['main']['path']=$item_picArray[0]['path'];
							
							unset($item_picArray[0]);
						  }

						}else{
						
							$item_picArray['main']['id']='null';
							$item_picArray['main']['path']=$data['default_item_pic'];
							$item_picArray['main']['front_pic']=1;	                            							
						}
					

						#format profile_pic
						$profile_picArray=array();
						$delimiter1_profile_pic='|#$(delimiter-1)$#|';
						$delimiter2_profile_pic='|#$(delimiter-2)$#|';
				        $ArrayTemp1=explode($delimiter1_profile_pic,$value['profile_pic']);
			            $ArrayTemp1=array_filter($ArrayTemp1);
			            foreach ($ArrayTemp1 as $keytemp => $valuetemp) 
			            {
			            	$ArrayTemp2=explode($delimiter2_profile_pic,$valuetemp);
			            	if(isset($ArrayTemp2[0]))
			            	{
				            	$profile_picArray[$keytemp]['id']=$ArrayTemp2[0];
				            	$profile_picArray[$keytemp]['path']=$ArrayTemp2[1];	
				            	$profile_picArray[$keytemp]['default']=0;
			            	}	
			            }

			            #set default profile_pic
						if(empty($profile_picArray))
						{
							$profile_picArray[0]['id']='null';
							$profile_picArray[0]['path']=$data['default_profile_pic'];	                            							
							$profile_picArray[0]['default']=1;	                            							
						}


						if($value['status']=="waiting for call") 
						{$value['status'] = "Wait to be called";}
						elseif($value['status']=="waiting for schedule" || $value['status']== "waiting for delivery") 
						{$value['status'] = "Wait for delivery"; }
						elseif($value['status']== "done_finished") 
						{$value['status'] = "Finalised"; }

						$result[$key]=array(									
											"user_id"=>$value['user_id'],
											"item_id"=>$value['item_id'],
											"item_name"=>$value['name'],
											"item_pic"=>$item_picArray,
											"date"=>$value['date'],
											"price"=>$value['price'],
											"category_id"=>$value['category_id'],
											"category_name"=>$value['category_name'],
											"category_parent_name"=>$value['category_parent_name'],
											"category_parent_id"=>$value['category_parent_id'],
											"user_verification"=>$value['verification'],
											"condition_name`"=>$value['condition_name'],
											"condition_id"=>$value['condition_id'],
											"item_description"=>$value['description'],
											"user_profile_pic"=>$profile_picArray,
											"user_name"=>$value['user_name'],									
											"user_about"=>$value['about'],												
											"order_id"=>$value['order_id'],												
											"order_id_shown_to_user"=>$value['order_id_shown_to_user'],												
											"date_ordered"=>$value['date_ordered'],												
											"phone"=>$value['phone'],												
											"payment"=>$value['payment'],												
											"order_status"=>$value['status'],												
											);
					}
				}
			}    
	          	           
		   
	        return $info = array("status"=>$this->status,
	                            "data"=> array('records'=> $result,
											   'count'=>$count,
											   'total_records'=>$total_records,
											   'itemsPerVerification'=>$itemsPerVerification,
											   'itemsPerCategory'=>$itemsPerCategory,
											   'itemsPerLocation'=>$itemsPerLocation,
											   ),
	                            "addition_info"=>$this->addition_info);	   	       
	}

	public function searchOrderedItem($filters=array())
	{
	        // set defaults
	         $this->reset_defaults(); 
         
			 $searchWord='';
			 $count=0;
			 $result=array();
			 $total_records=false;		 
			 $get_total_records=false;
			 $filteringCondtion=' AND ';
			 $itemsPerLocation=array();
			 $itemsPerCategory=array();
			 $itemsPerVerification=array();			 
	    	 $from=0;$take=20;
			 $order_by='`date` DESC';
		 	 $data['default_profile_pic']="media/default/shop/image/profile";		 
		 	 $data['default_item_pic']="media/default/shop/image/profile_cover";		 
			 
			 if(isset($filters['default_profile_pic']))
			 { $data['default_profile_pic']=$filters['default_profile_pic'];
			   unset($filters['default_profile_pic']);
			 }

			 if(isset($filters['default_item_pic']))
			 { $data['default_item_pic']=$filters['default_item_pic'];
			   unset($filters['default_item_pic']);
			 }			 	

			 if(isset($filters['search_word']))
			 { $searchWord=$filters['search_word'];
			   unset($filters['search_word']);// to avoid set to invalid hence returning nothing
			 }else if(isset($filters['searchWord']))
			 { $searchWord=$filters['searchWord'];
			   unset($filters['searchWord']);
			 }

			 if(isset($filters['from']))
			 { $from=$filters['from'];
			   unset($filters['from']);
			 }
			 
			 if(isset($filters['take']))
			 { $take=$filters['take'];
			   unset($filters['take']);
			 }
			 
			 if(isset($filters['order_by']))
			 { 
			 	if($filters['order_by']=='oldest')
			 	$order_by='`date` ASC';
			 	elseif($filters['order_by']=='recent' || $filters['order_by']=='most_recent')
			 	$order_by='`date` DESC';

			   unset($filters['order_by']);
			 }

			 if(isset($filters['get_total_records']))
			 { $get_total_records=$filters['get_total_records'];
			   unset($filters['get_total_records']);
			 }	
			 
			 if(isset($filters['filtering_condtion']))
			 { $filteringCondtion=$filters['filtering_condtion'];
			   unset($filters['filtering_condtion']);
			 }	
			 
			$WhereSubQueryArry=array();		

	        array_push($WhereSubQueryArry,'(`item_id` IS NOT NULL) ');			
				
			$n=1;

			foreach($filters as $key => $value)
			{  
			  
				if(is_array($value))
				 {
					$valueFormated=$value;
				 }
				 else
				 {
				   $valueFormated=array($value);
				 }
		       
				   
	            $miniWhereSubQueryArry=array();			   
			   foreach($valueFormated as $key2 => $value2)
			   {
					if($key==='category_id' || ($key==='category_id'.$n) )
					{
						array_push($miniWhereSubQueryArry,'`category_id`=:category_id'.$n ); 
						$n++;
					}elseif($key==='not_category_id' || ($key==='not_category_id'.$n) )
					{
						array_push($miniWhereSubQueryArry,'`category_id`!=:not_category_id'.$n ); 
						$n++;
					}elseif($key==='verification' || ($key==='verification'.$n) )
					{
						array_push($miniWhereSubQueryArry,'`verification`=:verification'.$n ); 
						$n++;
					}elseif($key==='not_verification' || ($key==='not_verification'.$n) )
					{
						array_push($miniWhereSubQueryArry,'`verification`!=:not_verification'.$n ); 
						$n++;
					}elseif($key==='condition' || ($key==='condition'.$n) )
					{
						array_push($miniWhereSubQueryArry,'`condition`=:condition'.$n ); 
						$n++;
					}elseif($key==='not_condition' || ($key==='not_condition'.$n) )
					{
						array_push($miniWhereSubQueryArry,'`condition`!=:not_condition'.$n ); 
						$n++;
					}elseif($key==='user_id' || ($key==='user_id'.$n) )
					{
						array_push($miniWhereSubQueryArry,'`user_id`=:user_id'.$n ); 
						$n++;
					}elseif($key==='not_user_id' || ($key==='not_user_id'.$n) )
					{
						array_push($miniWhereSubQueryArry,'`user_id`!=:not_user_id'.$n ); 
						$n++;
					}elseif($key==='item_id' || ($key==='item_id'.$n) )
					{
						array_push($miniWhereSubQueryArry,'`item_id`=:item_id'.$n ); 
						$n++;
					}elseif($key==='order_id' || ($key==='order_id'.$n) )
					{
						array_push($miniWhereSubQueryArry,'`order_id`=:order_id'.$n ); 
						$n++;
					}elseif($key==='order_id_shown_to_user' || ($key==='order_id_shown_to_user'.$n) )
					{
						array_push($miniWhereSubQueryArry,'`order_id_shown_to_user`=:order_id_shown_to_user'.$n ); 
						$n++;
					}elseif($key==='phone' || ($key==='phone'.$n) )
					{
						array_push($miniWhereSubQueryArry,'`phone`=:phone'.$n ); 
						$n++;
					}elseif($key==='payment' || ($key==='payment'.$n) )
					{
						array_push($miniWhereSubQueryArry,'`payment`=:payment'.$n ); 
						$n++;
					}elseif($key==='status' || ($key==='status'.$n) )
					{
						array_push($miniWhereSubQueryArry,'`status`=:status'.$n ); 
						$n++;
					}elseif($key==='not_item_id' || ($key==='not_item_id'.$n) )
					{
						array_push($miniWhereSubQueryArry,'`item_id`!=:not_item_id'.$n ); 
						$n++;
					}elseif($key==='price_from' || ($key==='price_from'.$n) )
					{
						array_push($miniWhereSubQueryArry,'`price`>=:price_from'.$n ); 
						$n++;
					}elseif($key==='price_to' || ($key==='price_to'.$n) )
					{
						array_push($miniWhereSubQueryArry,'`price`<=:price_to'.$n ); 
						$n++;
					}elseif($key==='invalid' || ($key==='invalid'.$n))
					{
						array_push($WhereSubQueryArry,'1=0' ); 
					}else
					{
						array_push($WhereSubQueryArry,'1=0' ); 
					}		   
			   }
		   
			   if(!empty($miniWhereSubQueryArry))
			   { 
					$miniWheresubQuery= '('.implode(' OR ',$miniWhereSubQueryArry).')';	
					array_push($WhereSubQueryArry,$miniWheresubQuery ); 
			   }
			}
				
			$WheresubQuery="";
			$filteringCondtion=' '.$filteringCondtion.' ';
			if(!empty($WhereSubQueryArry))
			$WheresubQuery='WHERE '.implode($filteringCondtion,$WhereSubQueryArry);			 
		 
			$get_total_recordsQuery="";
			if($get_total_records)
			{
			  $get_total_recordsQuery='SQL_CALC_FOUND_ROWS';
			}
		
			$dbquery1 =  $this->db->conn_id->prepare("	
														CREATE TEMPORARY TABLE temp_table AS(								
															SELECT *
															FROM(
																SELECT *,`date` AS `date_ordered`
																	FROM `ordered_items` `s10`	

																	LEFT JOIN( 	
																		SELECT `item_id` AS `s1_item_id`,`name`,`price`,`item_pic`,`category_id`,`category_name`,`category_parent_name`,`category_parent_id`,
																			`condition_id`,`condition_name`,`user_id` AS `s1_user_id`,`description`,`user_name`,`about`,`date` AS `added_date`,
																			`profile_pic`,`user_pic`,`location`, 
																			CASE WHEN ISNULL(`s7_verification`) THEN 'Unverified' ELSE `s7_verification` END  AS `verification`,
																			CASE WHEN ISNULL(`s7_user_rank`) THEN '0' ELSE `s7_user_rank` END AS `user_rank` 
																	   	FROM `item` `s1` 
																 	
																			LEFT JOIN( 
																			SELECT `about` ,`user_name`, `user_id` AS `s3_user_id`
																			FROM `user_info`
																			)AS `s3` ON  `s3`.`s3_user_id`= `s1`.`user_id`	
																			
																			LEFT JOIN( 
																			SELECT `user_pic` , `user_id` AS `s4_user_id`
																			FROM `profile_pic`
																			) AS `s4` ON  `s4`.`s4_user_id`= `s1`.`user_id`
																			
																			LEFT JOIN( 
																			SELECT `parent_name` AS `category_parent_name`,`parent_id` AS `category_parent_id`, `category_name`, `category_id` AS `s5_category_id`
																			FROM `category` `s5_a1`
																					LEFT JOIN( SELECT `category_name` As parent_name,`category_id` AS `s5_b1_category_id` 
																								FROM `category` 
																					)AS `s5_b1` ON  `s5_b1`.`s5_b1_category_id` = `s5_a1`.`parent_id`							
																			) AS `s5` ON  `s5`.`s5_category_id`= `s1`.`category_id`
																					
																			LEFT JOIN( 
																			SELECT `condition_name`, `condition_id` AS `s6_condition_id`
																			FROM `item_condition`
																			) AS `s6` ON  `s6`.`s6_condition_id`= `s1`.`condition_id`
																					
																			LEFT JOIN( 
																			SELECT `rank` AS `s7_user_rank`, `user_id` AS `s7_user_id`, `verification` AS `s7_verification`
																			FROM `verification`
																			) AS `s7` ON  `s7`.`s7_user_id`= `s1`.`user_id`

																			LEFT JOIN (
																			SELECT `user_pic` as `profile_pic` , `user_id` AS `s8_user_id`
																			FROM `profile_pic`
																			) AS `s8` ON `s8`.`s8_user_id` = `s1`.`user_id`
																						
																			LEFT JOIN (
																			SELECT `user_id` AS `s9_user_id` ,`location`
																			FROM `contact_map`
																			GROUP BY `user_id`
																			) AS `s9` ON `s9`.`s9_user_id` = `s1`.`user_id`

																	) AS `s1X` ON `s1X`.`s1_item_id` = `s10`.`item_id`

																WHERE `s10`.`item_id` IN (
																			SELECT `item_id` 
																			FROM `item`  
																			WHERE MATCH (`name`) AGAINST ( '>':searchWord '<':searchWord'*' IN BOOLEAN MODE)																	
																	)	

							                                ) `s_whole`
																		
																".$WheresubQuery."

															GROUP BY `item_id`
														)			
													");	
			$dbquery1->bindParam(':searchWord',$searchWord);														
			$n=1;	
			foreach($filters as $key => $value)
			{
				if(is_array($value))
				{
					$valueFormated=$value;
				}
				 else
				{
				   $valueFormated=array($value);
				}

			   foreach($valueFormated as $key2 => $value2)
			   {					   
					if($key==='category_id' || ($key==='category_id'.$n) ) 
					{$dbquery1->bindValue(":category_id".$n,$value2); 	$n++;}
					
					if($key==='condition' || ($key==='condition'.$n) ) 
					{$dbquery1->bindValue(":condition".$n,$value2); $n++; }
					
					if($key==='verification' || ($key==='verification'.$n) ) 
					{$dbquery1->bindValue(":verification".$n,$value2); $n++; }
					
					if($key==='user_id' || ($key==='user_id'.$n) ) 
					{$dbquery1->bindValue(":user_id".$n,$value2); $n++; }
														
					if($key==='item_id' || ($key==='item_id'.$n) ) 
					{$dbquery1->bindValue(":item_id".$n,$value2); $n++; }

					if($key==='order_id' || ($key==='order_id'.$n) ) 
					{$dbquery1->bindValue(":order_id".$n,$value2); $n++; }

					if($key==='status' || ($key==='status'.$n) ) 
					{$dbquery1->bindValue(":status".$n,$value2); $n++; }

					if($key==='order_id_shown_to_user' || ($key==='order_id_shown_to_user'.$n) ) 
					{$dbquery1->bindValue(":order_id_shown_to_user".$n,$value2); $n++; }

					if($key==='phone' || ($key==='phone'.$n) ) 
					{$dbquery1->bindValue(":phone".$n,$value2); $n++; }

					if($key==='payment' || ($key==='payment'.$n) ) 
					{$dbquery1->bindValue(":payment".$n,$value2); $n++; }

					if($key==='not_category_id' || ($key==='not_category_id'.$n) ) 
					{$dbquery1->bindValue(":not_category_id".$n,$value2); 	$n++;}
					
					if($key==='not_condition' || ($key==='not_condition'.$n) ) 
					{$dbquery1->bindValue(":not_condition".$n,$value2); $n++; }
					
					if($key==='not_verification' || ($key==='not_verification'.$n) ) 
					{$dbquery1->bindValue(":not_verification".$n,$value2); $n++; }
					
					if($key==='not_user_id' || ($key==='not_user_id'.$n) ) 
					{$dbquery1->bindValue(":not_user_id".$n,$value2); $n++; }
														
					if($key==='not_item_id' || ($key==='not_item_id'.$n) ) 
					{$dbquery1->bindValue(":not_item_id".$n,$value2); $n++; }
														
					if($key==='price_from' || ($key==='price_from'.$n) ) 
					{$dbquery1->bindValue(':price_from'.$n, (int)$value2, PDO::PARAM_INT); $n++; }

					if($key==='price_to' || ($key==='price_to'.$n) ) 
					{$dbquery1->bindValue(':price_to'.$n, (int)$value2, PDO::PARAM_INT); $n++; }
								
			   }					
			}		 
			 
			
			if(!$this->fail_result)
			{

			 if(!$dbquery1->execute())
			   {
				$this->status=false;
				!$this->fail_result=true;
				//echo '<pre>';
				//print_r($valueFormated);
				//print_r($dbquery1);
				//print_r($dbquery1->errorInfo());
				$this->addition_info = $dbquery1->errorInfo();
				//$this->addition_info = 'error_100';
			   }
			}	

			//get the items
			if(!$this->fail_result)
			{

			$dbquery2 =  $this->db->conn_id->prepare("	
														SELECT ".$get_total_recordsQuery." *
														FROM `temp_table` AS `s1`		
														
														GROUP BY `item_id`
														ORDER BY (`user_rank`) DESC,".$order_by."									
														LIMIT :from,:take									
													");
			
			$dbquery2->bindValue(':from', (int)$from, PDO::PARAM_INT);
			$dbquery2->bindValue(':take', (int)$take, PDO::PARAM_INT);

			 if(!$dbquery2->execute())
			   {
				$this->status=false;
				!$this->fail_result=true;
				//echo '<pre>';
				//print_r($valueFormated);
				//print_r($dbquery1);
				//print_r($dbquery1->errorInfo());
				$this->addition_info = $dbquery2->errorInfo();
				//$this->addition_info = 'error_100';
			   }else{

			   	//get total records
			   	if($get_total_records)
				{
				 	$dbqueryCount =  $this->db->conn_id->query('SELECT FOUND_ROWS()');
				 	$total_records = (int) $dbqueryCount->fetchColumn();
				}
			   }
			}				

			//get the items' groups of verification
			if(!$this->fail_result)
			{

				$dbquery3 =  $this->db->conn_id->prepare("	
															SELECT COUNT(*) AS `itemsPerVerification`,
																	CASE WHEN ISNULL(`verification`) THEN 'Unverified' ELSE `verification` END  AS `verification`
															FROM `temp_table`
															GROUP BY `verification`											
															LIMIT 15									
														");
					
				if(!$dbquery3->execute())
				{
					$this->status=false;
					!$this->fail_result=true;
					//echo '<pre>';
					//print_r($valueFormated);
					//print_r($dbquery1);
					//print_r($dbquery1->errorInfo());
					$this->addition_info = $dbquery3->errorInfo();
					//$this->addition_info = 'error_100';
				}
			}						

			//get the items' groups of categories
			if(!$this->fail_result)
			{

				$dbquery4 =  $this->db->conn_id->prepare("	
															SELECT COUNT(*) AS `itemsPerCategory`,`category_id`,
																	`category_parent_name` AS `parent_name`,`category_parent_id` AS `parent_id`,`category_name`
															FROM `temp_table`
															GROUP BY `category_id`											
															LIMIT 15									
														");
					
				if(!$dbquery4->execute())
				{
					$this->status=false;
					!$this->fail_result=true;
					//echo '<pre>';
					//print_r($valueFormated);
					//print_r($dbquery1);
					//print_r($dbquery1->errorInfo());
					$this->addition_info = $dbquery4->errorInfo();
					//$this->addition_info = 'error_100';
				}
			}	

			//get the items' groups of location
			if(!$this->fail_result)
			{

				$dbquery5 =  $this->db->conn_id->prepare("	
															SELECT COUNT(*) AS `itemsPerLocation`,`location`
															FROM `temp_table`
															GROUP BY `location`											
															LIMIT 15									
														");
					
				if(!$dbquery5->execute())
				{
					$this->status=false;
					!$this->fail_result=true;
					//echo '<pre>';
					//print_r($valueFormated);
					//print_r($dbquery1);
					//print_r($dbquery1->errorInfo());
					$this->addition_info = $dbquery5->errorInfo();
					//$this->addition_info = 'error_100';
				}
			}		

			//delete the temp table
			if(!$this->fail_result)
			{

				$dbquery6 =  $this->db->conn_id->prepare("	
															DROP TEMPORARY TABLE IF EXISTS `temp_table`									
														");
					
				if(!$dbquery6->execute())
				{
					$this->status=false;
					$this->fail_result=true;
					//echo '<pre>';
					//print_r($valueFormated);
					//print_r($dbquery1);
					//print_r($dbquery1->errorInfo());
					$this->addition_info = $dbquery6->errorInfo();
					//$this->addition_info = 'error_100';
				}
			}
			//collect and organise for items + item groups
			if(!$this->fail_result)
			{ 
			   

				#collect for items'group
			   	$itemsPerVerification = $dbquery3->fetchAll();
			   	$itemsPerCategory = $dbquery4->fetchAll();
			   	$itemsPerLocation = $dbquery5->fetchAll();
				
			   	#add levels to the categories
			   	$this->load->model('category_model');
			   	$itemsPerCategory=$this->category_model->category_levels_setter ($itemsPerCategory,1,0);

				#collect for item
			  	$this->status = true;
			  	$sql_result = $dbquery2->fetchAll();
			  	$count=count($sql_result);
			  
			   	//echo '<pre>';
			  	//print_r($sql_result);
				  
				  
				if(count($sql_result)>0)
				{
				    foreach( $sql_result as $key => $value)
					{
						
						#format item_pic
						$item_picArray=array();
						$mainPicCheck=false;
						$delimiter1_item_pic='|#$(delimiter-1)$#|';
						$delimiter2_item_pic='|#$(delimiter-2)$#|';
				        $ArrayTemp1=explode($delimiter1_item_pic,$value['item_pic']);
			            $ArrayTemp1=array_filter($ArrayTemp1);
			            foreach ($ArrayTemp1 as $keytemp => $valuetemp) 
			            {
			            	$ArrayTemp2=explode($delimiter2_item_pic,$valuetemp);
			            	if(isset($ArrayTemp2[0]))
			            	{

				            	#set only 1 front pic
				            	if($ArrayTemp2[2]==1 && !$mainPicCheck)
								{
									$mainPicCheck=true;
									$keytemp='main';
									$item_picArray[$keytemp]['front_pic']=$ArrayTemp2[2];						
								}else
								{
									$item_picArray[$keytemp]['front_pic']=0;	
								}
				            	$item_picArray[$keytemp]['id']=$ArrayTemp2[0];
				            	$item_picArray[$keytemp]['path']=$ArrayTemp2[1];	
				            	
			            	}	
			            }

						//set default pic if no pic
						if(!empty($item_picArray))
						{
							#set default front pic, if not set
						  if(!$mainPicCheck)
						  {
							$item_picArray['main']['front_pic']=1;
							$item_picArray['main']['id']=$item_picArray[0]['id'];
							$item_picArray['main']['path']=$item_picArray[0]['path'];
							
							unset($item_picArray[0]);
						  }

						}else{
						
							$item_picArray['main']['id']='null';
							$item_picArray['main']['path']=$data['default_item_pic'];
							$item_picArray['main']['front_pic']=1;	                            							
						}
					

						#format profile_pic
						$profile_picArray=array();
						$delimiter1_profile_pic='|#$(delimiter-1)$#|';
						$delimiter2_profile_pic='|#$(delimiter-2)$#|';
				        $ArrayTemp1=explode($delimiter1_profile_pic,$value['profile_pic']);
			            $ArrayTemp1=array_filter($ArrayTemp1);
			            foreach ($ArrayTemp1 as $keytemp => $valuetemp) 
			            {
			            	$ArrayTemp2=explode($delimiter2_profile_pic,$valuetemp);
			            	if(isset($ArrayTemp2[0]))
			            	{
				            	$profile_picArray[$keytemp]['id']=$ArrayTemp2[0];
				            	$profile_picArray[$keytemp]['path']=$ArrayTemp2[1];	
				            	$profile_picArray[$keytemp]['default']=0;
			            	}	
			            }

			            #set default profile_pic
						if(empty($profile_picArray))
						{
							$profile_picArray[0]['id']='null';
							$profile_picArray[0]['path']=$data['default_profile_pic'];	                            							
							$profile_picArray[0]['default']=1;	                            							
						}

						if($value['status']=="waiting for call") 
						{$value['status'] = "Wait to be called";}
						elseif($value['status']=="waiting for schedule" || $value['status']== "waiting for delivery") 
						{$value['status'] = "Wait for delivery"; }
						elseif($value['status']== "done_finished") 
						{$value['status'] = "Finalised"; }

						$result[$key]=array(									
											"user_id"=>$value['user_id'],
											"item_id"=>$value['item_id'],
											"item_name"=>$value['name'],
											"item_pic"=>$item_picArray,
											"ordered_date"=>$value['date'],
											"date"=>$value['added_date'],
											"price"=>$value['price'],
											"category_id"=>$value['category_id'],
											"category_name"=>$value['category_name'],
											"category_parent_name"=>$value['category_parent_name'],
											"category_parent_id"=>$value['category_parent_id'],
											"user_verification"=>$value['verification'],
											"condition_name`"=>$value['condition_name'],
											"condition_id"=>$value['condition_id'],
											"item_description"=>$value['description'],
											"user_profile_pic"=>$profile_picArray,
											"user_name"=>$value['user_name'],									
											"user_about"=>$value['about'],												
											"order_id"=>$value['order_id'],												
											"order_id_shown_to_user"=>$value['order_id_shown_to_user'],												
											"date_ordered"=>$value['date_ordered'],												
											"phone"=>$value['phone'],												
											"payment"=>$value['payment'],												
											"order_status"=>$value['status'],												
											);
					}
				}
			}    
	          	           
		   
	        return $info = array("status"=>$this->status,
	                            "data"=> array('records'=> $result,
											   'count'=>$count,
											   'total_records'=>$total_records,
											   'itemsPerVerification'=>$itemsPerVerification,
											   'itemsPerCategory'=>$itemsPerCategory,
											   'itemsPerLocation'=>$itemsPerLocation,
											   ),
	                            "addition_info"=>$this->addition_info);	   	       
	}



} 
?>
