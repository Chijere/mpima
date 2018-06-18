<?php
class Message_model extends CI_Model {

    //default variables
    private $addition_info="";
    private $confirm_code="";
    private $status = false;
    private $result_info=" Sorry something went wrong, Try Again "; 
    private $fail_result=false;

    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
    }

	private function reset_defaults()
    {
	    $this->addition_info="";
	    $this->status = false;
	    $this->result_info="Sorry something went wrong, Try Again "; 
	    $this->fail_result=false;
    } 

    //replace key1 with key2
    private function array_key_replace($array, $key1, $key2)
	{
	    $keys = array_keys($array);
	    $index = array_search($key1, $keys);

	    if ($index !== false) {
	        $keys[$index] = $key2;
	        $array = array_combine($keys, $array);
	    }

	    return $array;
	}

 ##------------------------------------------
	public function getThread($filters=array())
	{
        // set defaults
        $this->reset_defaults(); 
         
		 $count=0;
		 $reply_to ='';
		 $return_subject_id='';
		 $result=array();
		 $total_records=false;		 
		 $get_total_records=false;
		 $filteringCondtion=' AND ';
    	 $from=0;$take=20;
		 $usersPerLocation=array();
		 $usersPerVerification=array();	 	 
	 	 $order_by='`date` DESC';		 
 		 $WhereSubQueryArry=array();		
		 $whereSubquery_message_deletion="";

	 	 $data['default_profile_pic']="media/default/shop/image/profile";		 
	 	 $data['default_profile_pic_buyer']="media/default/shop/image/avatar";		 
	 	 $data['default_profile_cover_pic']="media/default/shop/image/profile_cover";		 
		 
		 if(isset($filters['default_profile_pic_buyer']))
		 { $data['default_profile_pic_buyer']=$filters['default_profile_pic_buyer'];
		   unset($filters['default_profile_pic_buyer']);
		 }

		 if(isset($filters['default_profile_pic']))
		 { $data['default_profile_pic']=$filters['default_profile_pic'];
		   unset($filters['default_profile_pic']);
		 }

		 if(isset($filters['default_profile_cover_pic']))
		 { $data['default_profile_cover_pic']=$filters['default_profile_cover_pic'];
		   unset($filters['default_profile_cover_pic']);		 
		 }

	 	 // check if user is_sender/is_receiver		 
	 	 #for threads set to default to true for all
	 	 $isSenderOrReceiver=array();
	 	 if(!isset($filters['is_sender'])) $filters['is_sender'] = 1;		 
	 	 if(!isset($filters['is_receiver'])) $filters['is_receiver'] = 1;		 
         
	 	if(isset($filters['is_sender']) && $filters['is_sender'])
	    {
	    	$isSenderOrReceiver['is_sender'] = 1;
	    	$isSenderOrReceiver['is_receiver'] = 0;
            $isSenderOrReceiver['selected'] = 1;
	    	
	    }else	
	    {
 			$isSenderOrReceiver['is_sender'] = 0;
 			$isSenderOrReceiver['is_receiver'] = 1;
            $isSenderOrReceiver['selected'] = 1;
	 	
	 	}
		unset($filters['is_sender']);

	    if(isset($filters['is_receiver']) && $filters['is_receiver'])
	    {
 			$isSenderOrReceiver['is_receiver'] = 1;
            if(!$isSenderOrReceiver['is_sender'])
            	$isSenderOrReceiver['selected'] = 1;
            else
            	$isSenderOrReceiver['selected'] = 2;

	    }else
	 	{
 			$isSenderOrReceiver['is_receiver'] = 0;
            if(!$isSenderOrReceiver['is_sender'])
            	$isSenderOrReceiver['selected'] = 0;
            else
            	$isSenderOrReceiver['selected'] = 1;
	 	}
	 	unset($filters['is_receiver']);


		 if(!isset($filters['deletion_level']))
		 {
		   $filters['deletion_level']= 0;
		 }

		 if(isset($filters['from']))
		 { $from=$filters['from'];
		   unset($filters['from']);
		 }
		 
		 if(isset($filters['take']))
		 { $take=$filters['take'];
		   unset($filters['take']);
		 }

		 if(!isset($filters['subject_id']) || !isset($filters['user_id']))
		 { $filters['invalid'] =true;
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

        array_push($WhereSubQueryArry,'(`sender_id` IS NOT NULL) ');			
        array_push($WhereSubQueryArry,'(`receiver_id` IS NOT NULL) ');			
			
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
				if($key==='message_read' || ($key==='message_read'.$n) )
				{
					array_push($WhereSubQueryArry,'`message_read`=:message_read'.$n ); 
					$n++;
				}elseif($key==='subject_id' || ($key==='subject_id'.$n) )
				{
					array_push($WhereSubQueryArry,'`subject_id`=:subject_id'.$n ); 
					$n++;
				}elseif($key==='deletion_level' || ($key==='deletion_level'.$n) )
				{ 
					if($value2==0)
					{
						array_push($WhereSubQueryArry,'ISNULL(`deletion_level`)'); 
					    unset($filters['deletion_level']); 
					}
					else
					array_push($WhereSubQueryArry,'`deletion_level`=:deletion_level'.$n ); 
					$n++;
				}elseif($key==='user_id' || ($key==='user_id'.$n) )
				{
                    if(!isset($loopOnce))
                    {
                    	$whereSubquery_message_deletion =  "WHERE `deleter_id`=:user_id".$n;
                    	$loopOnce=true;
                    }


                    if($isSenderOrReceiver['selected']==2) 
					{
						array_push($WhereSubQueryArry,'( `sender_id`=:user_id'.$n.' OR `receiver_id`=:user_id'.$n.' )' );   
                    }
                    elseif($isSenderOrReceiver['is_sender'])  
					{
						array_push($WhereSubQueryArry,'`sender_id`=:user_id'.$n ); 
					}
					elseif($isSenderOrReceiver['is_receiver'])  
					{
						array_push($WhereSubQueryArry,'`receiver_id`=:user_id'.$n ); 					
					}
					else
					{
						//make it invalid
						array_push($WhereSubQueryArry,'1=0' );
						$whereSubquery_message_deletion="";
						//define a dummy variable not to mess up the loops
						$filters = $this->array_key_replace($filters,'user_id','dummy_1');
						
					}				
					
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
													SELECT  *,`s1`.`date` as `message_date`,
													CASE WHEN ISNULL(`deletion_level`) THEN 0 ELSE `deletion_level` END AS `deletion`
											   			FROM `message` `s1`
														
														LEFT JOIN (
														SELECT `user_name` AS `sender_user_name`, `user_id` AS `s3_user_id`
														FROM `user_info`
														) AS `s3` ON `s3`.`s3_user_id` = `s1`.`sender_id`
																	
														LEFT JOIN (
														SELECT `user_pic` as `sender_profile_pic` , `user_id` AS `s4_user_id`
														FROM `profile_pic`
														) AS `s4` ON `s4`.`s4_user_id` = `s1`.`sender_id`
														
														LEFT JOIN (
														SELECT `subject`,`date` AS `subject_date`,`subject_id` AS `s5_subject_id`
														FROM `message_subject` `s5_main`
														) AS `s5` ON `s5`.`s5_subject_id` = `s1`.`subject_id`

														LEFT JOIN (
														SELECT `message_read`,`reception_type`,`receiver_id`,`message_id` AS `s6_message_id`,`receiver_user_name`
														FROM `message_receiver` `s6_main`
																LEFT JOIN (
																SELECT `user_name` AS `receiver_user_name`,`user_id` AS `s6_1_user_id`
																FROM `user_info`
																) AS `s6_1` ON `s6_1`.`s6_1_user_id` = `s6_main`.`receiver_id`
														) AS `s6` ON `s6`.`s6_message_id` = `s1`.`message_id`
														
														LEFT JOIN (
															SELECT `deletion_level`,`subject_id` AS `s7_subject_id`
															FROM `message_deletion` 
															".$whereSubquery_message_deletion."
														) AS `s7` ON `s7`.`s7_subject_id` = `s1`.`subject_id`

														LEFT JOIN (
														SELECT `user_type` AS `sender_user_type`, `user_id` AS `s8_user_id`
														FROM `login_security`
														) AS `s8` ON `s8`.`s8_user_id` = `s1`.`sender_id`
																	
														
														".$WheresubQuery."
														ORDER BY `message_date` DESC
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
			    
				if($key==='user_id' || ($key==='user_id'.$n) ) 
					{
						$dbquery1->bindValue(":user_id".$n,$value2);  						
						if(isset($loopOnce))
						{
							$dbquery1->bindValue(":user_id".$n,$value2);
							unset($loopOnce);
						}
						$n++;
					}

				if($key==='deletion_level' || ($key==='deletion_level'.$n) ) 
				{$dbquery1->bindValue(":deletion_level".$n,$value2); 	$n++;}
					
				if($key==='message_read' || ($key==='message_read'.$n) ) 
				{$dbquery1->bindValue(":message_read".$n,$value2); 	$n++; }
					
					
				if($key==='subject_id' || ($key==='subject_id'.$n) ) 
				{$dbquery1->bindValue(":subject_id".$n,$value2);  $n++; }
				
				if($key==='dummy_1'  || ($key==='dummy_1'.$n))
				{$n++;}	
					
		   }
			
		  }		 
		 

		if(!$this->fail_result)
		{

			if(!$dbquery1->execute())
			{
				$this->status=false;
				$this->fail_result=true;
				//echo '<pre>';
				//print_r($valueFormated);
				//print_r($dbquery1);
				//print_r($dbquery1->errorInfo());
				$this->addition_info = $dbquery1->errorInfo();
				//$this->addition_info = 'error_100';
				//echo '<pre>';
			}
				
		}	

		//get the subjects
		if(!$this->fail_result)
		{
			$dbquery2 =  $this->db->conn_id->prepare("	
														SELECT ".$get_total_recordsQuery." *
														FROM `temp_table` AS `s1`		
														
														GROUP BY `message_id`
														ORDER BY ".$order_by."											
														LIMIT :from,:take									
													");
			
			$dbquery2->bindValue(':from', (int)$from, PDO::PARAM_INT);
			$dbquery2->bindValue(':take', (int)$take, PDO::PARAM_INT);

			if(!$dbquery2->execute())
			{
				$this->status=false;
				$this->fail_result=true;
				//echo '<pre>';
				//print_r($valueFormated);
				//print_r($dbquery1);
				//print_r($dbquery1->errorInfo());
				$this->addition_info = $dbquery2->errorInfo();
				//$this->addition_info = 'error_100';
		   		//echo '<pre>';
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

		//collect and organise for users + user groups
		if(!$this->fail_result)
		{ 		
			$this->status = true;
			$sql_result = $dbquery2->fetchAll();
			$count=count($sql_result);
			  
			  //echo '<pre>';
			  //print_r($sql_result);
			  //print_r($dbquery1);
			  
			if($get_total_records)
			{
			 	$dbqueryCount =  $this->db->conn_id->query('SELECT FOUND_ROWS()');
			 	$total_records = (int) $dbqueryCount->fetchColumn();
			}				  
				  
			if($count>0 && !empty($sql_result))
			{
				foreach( $sql_result as $key => $value)
				{
					
					//do some format
					
					#format profile_pic
					$profile_picArray=array();
					$delimiter1_profile_pic='|#$(delimiter-1)$#|';
					$delimiter2_profile_pic='|#$(delimiter-2)$#|';
			        $ArrayTemp1=explode($delimiter1_profile_pic,$value['sender_profile_pic']);
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
						if($value['sender_user_type']=='buyer')	                            							
						$profile_picArray[0]['path']=$data['default_profile_pic_buyer'];	                            							
						$profile_picArray[0]['default']=1;	                            							
					}
					
					if(($key+1) == $count && isset($filters['user_id']))
					{
						$return_subject_id = $value['subject_id'];

						if($filters['user_id']==$value['sender_id'])
							$reply_to = $value['receiver_id'];
						elseif($filters['user_id']==$value['receiver_id'])
							$reply_to = $value['sender_id'];	
					}


					$result[$key]=array(					
								"sender_id"=>$value['sender_id'],
								"receiver_id"=>$value['receiver_id'],
								"deletion_level"=>$value['deletion'],
								"message"=>$value['message'],
								"message_read"=>$value['message_read'],
								"subject"=>$value['subject'],
								"sender_user_name"=>$value['sender_user_name'],
								"receiver_user_name"=>$value['receiver_user_name'],
								"subject_id"=>$value['subject_id'],
								"message_date"=>$value['message_date'],
								"subject_date"=>$value['subject_date'],
								"message_id"=>$value['message_id'],			
								"sender_profile_pic"=>$profile_picArray,									
								);
				}
			}				 
        }  
		   
        return $info = array("status"=>$this->status,
                            "data"=> array('records'=> $result,
										   'count'=>$count,
										   'reply_to'=>$reply_to,
										   'subject_id'=>$return_subject_id,
										   'total_records'=>$total_records,
										   ),
                            "addition_info"=>$this->addition_info);
   	       
	}
 ##------------------------------------------
	public function getSubject($filters=array())
	{
        // set defaults
        $this->reset_defaults(); 
         
		 $count=0;
		 $result=array();
		 $total_records=false;		 
		 $get_total_records=false;
		 $filteringCondtion=' AND ';
		 $from=0;$take=20;
		 $usersPerLocation=array();
		 $usersPerVerification=array();	 	 
	 	 $order_by='`date` DESC';
 		 $WhereSubQueryArry=array();		
		 $whereSubquery_message_deletion="";

	 	 $data['default_profile_pic']="media/default/shop/image/profile";		 
	 	 $data['default_profile_pic_buyer']="media/default/shop/image/avatar";		 
	 	 $data['default_profile_cover_pic']="media/default/shop/image/profile_cover";		 
		 
		 if(isset($filters['default_profile_pic_buyer']))
		 { $data['default_profile_pic_buyer']=$filters['default_profile_pic_buyer'];
		   unset($filters['default_profile_pic_buyer']);
		 }

		 if(isset($filters['default_profile_pic']))
		 { $data['default_profile_pic']=$filters['default_profile_pic'];
		   unset($filters['default_profile_pic']);
		 }

		 if(isset($filters['default_profile_cover_pic']))
		 { $data['default_profile_cover_pic']=$filters['default_profile_cover_pic'];
		   unset($filters['default_profile_cover_pic']);		 
		 }

	 	 // check if user is_sender/is_receiver
	 	 #default is set to false for all 		 
	 	 $isSenderOrReceiver=array();		 
         
	 	if(isset($filters['is_sender']) && $filters['is_sender'])
	    {
	    	$isSenderOrReceiver['is_sender'] = 1;
	    	$isSenderOrReceiver['is_receiver'] = 0;
            $isSenderOrReceiver['selected'] = 1;
	    	
	    }else	
	    {
 			$isSenderOrReceiver['is_sender'] = 0;
 			$isSenderOrReceiver['is_receiver'] = 1;
            $isSenderOrReceiver['selected'] = 1;
	 	
	 	}
		    unset($filters['is_sender']);

	    if(isset($filters['is_receiver']) && $filters['is_receiver'])
	    {
 			$isSenderOrReceiver['is_receiver'] = 1;
            if(!$isSenderOrReceiver['is_sender'])
            	$isSenderOrReceiver['selected'] = 1;
            else
            	$isSenderOrReceiver['selected'] = 2;

	    }else
	 	{
 			$isSenderOrReceiver['is_receiver'] = 0;
            if(!$isSenderOrReceiver['is_sender'])
            	$isSenderOrReceiver['selected'] = 0;
            else
            	$isSenderOrReceiver['selected'] = 1;
	 	}

	 	unset($filters['is_receiver']);
			

		 if(!isset($filters['deletion_level']))
		 {
		   $filters['deletion_level']= 0;
		 }


		 if(isset($filters['from']))
		 { $from=$filters['from'];
		   unset($filters['from']);
		 }
		 
		 if(isset($filters['take']))
		 { $take=$filters['take'];
		   unset($filters['take']);
		 }

		 if(!isset($filters['user_id']))
		 { $filters['invalid'] =true;
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
		 

        array_push($WhereSubQueryArry,'(`sender_id` IS NOT NULL) ');			
        array_push($WhereSubQueryArry,'(`receiver_id` IS NOT NULL) ');			
			
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
				if($key==='message_read' || ($key==='message_read'.$n) )
				{
					array_push($WhereSubQueryArry,'`message_read`=:message_read'.$n ); 
					$n++;
				}elseif($key==='subject_id' || ($key==='subject_id'.$n) )
				{
					array_push($WhereSubQueryArry,'`subject_id`=:subject_id'.$n ); 
					$n++;
				}elseif($key==='deletion_level' || ($key==='deletion_level'.$n) )
				{ 
					if($value2==0)
					{
						array_push($WhereSubQueryArry,'ISNULL(`deletion_level`)'); 
					    unset($filters['deletion_level']); 
					}
					else
					array_push($WhereSubQueryArry,'`deletion_level`=:deletion_level'.$n ); 
					$n++;
				}elseif($key==='user_id' || ($key==='user_id'.$n) )
				{
                    //an inside whereSubquery; but not part of main query
                    if(!isset($loopOnce))
                    {
                    	$whereSubquery_message_deletion =  "WHERE `deleter_id`=:user_id".$n;
                    	$loopOnce=true;
                    }	

                    if($isSenderOrReceiver['selected']==2) 
					{
						array_push($WhereSubQueryArry,'( `sender_id`=:user_id'.$n.' OR `receiver_id`=:user_id'.$n.' )' );   
                    }
                    elseif($isSenderOrReceiver['is_sender'])  
					{
						array_push($WhereSubQueryArry,'`sender_id`=:user_id'.$n ); 
					}
					elseif($isSenderOrReceiver['is_receiver'])  
					{
						array_push($WhereSubQueryArry,'`receiver_id`=:user_id'.$n ); 					
					}
					else
					{
						//make it invalid
						array_push($WhereSubQueryArry,'1=0' );
						$whereSubquery_message_deletion="";
						//define a dummy variable not to mess up the loops
						$filters = $this->array_key_replace($filters,'user_id','dummy_1');
					}


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
													SELECT  *,`s1`.`date` as `message_date`,
													CASE WHEN ISNULL(`deletion_level`) THEN 0 ELSE `deletion_level` END AS `deletion`
											   			FROM `message` `s1`
														
														LEFT JOIN (
														SELECT `user_name` AS `sender_user_name`, `user_id` AS `s3_user_id`
														FROM `user_info`
														) AS `s3` ON `s3`.`s3_user_id` = `s1`.`sender_id`
																	
														LEFT JOIN (
														SELECT `user_pic` as `sender_profile_pic` , `user_id` AS `s4_user_id`
														FROM `profile_pic`
														) AS `s4` ON `s4`.`s4_user_id` = `s1`.`sender_id`
														
														LEFT JOIN (
														SELECT `subject`,`date` AS `subject_date`,`subject_id` AS `s5_subject_id`
														FROM `message_subject` `s5_main`
														) AS `s5` ON `s5`.`s5_subject_id` = `s1`.`subject_id`

														LEFT JOIN (
														SELECT `message_read`,`reception_type`,`receiver_id`,`message_id` AS `s6_message_id`,`receiver_user_name`
														FROM `message_receiver` `s6_main`
																LEFT JOIN (
																SELECT `user_name` AS `receiver_user_name`,`user_id` AS `s6_1_user_id`
																FROM `user_info`
																) AS `s6_1` ON `s6_1`.`s6_1_user_id` = `s6_main`.`receiver_id`
														) AS `s6` ON `s6`.`s6_message_id` = `s1`.`message_id`
														
														LEFT JOIN (	
															SELECT `deletion_level`,`subject_id` AS `s7_subject_id`
															FROM `message_deletion` 
															".$whereSubquery_message_deletion."
														) AS `s7` ON `s7`.`s7_subject_id` = `s1`.`subject_id`


														LEFT JOIN (
														SELECT `user_type` AS `sender_user_type`, `user_id` AS `s8_user_id`
														FROM `login_security`
														) AS `s8` ON `s8`.`s8_user_id` = `s1`.`sender_id`
																	
														
														".$WheresubQuery."
														ORDER BY `s1`.`subject_id`,`message_date` DESC
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
			    
				if($key==='user_id' || ($key==='user_id'.$n) ) 
					{
						$dbquery1->bindValue(":user_id".$n,$value2);  						
						if(isset($loopOnce))
						{
							$dbquery1->bindValue(":user_id".$n,$value2);
							unset($loopOnce);
						}
						$n++;
					}

				if($key==='deletion_level' || ($key==='deletion_level'.$n) ) 
				{$dbquery1->bindValue(":deletion_level".$n,$value2); 	$n++;}
					
				if($key==='message_read' || ($key==='message_read'.$n) ) 
				{$dbquery1->bindValue(":message_read".$n,$value2); 	$n++;}
					
					
				if($key==='subject_id' || ($key==='subject_id'.$n) ) 
				{$dbquery1->bindValue(":subject_id".$n,$value2); 	$n++;}
					
				if($key==='dummy_1'  || ($key==='dummy_1'.$n))
				{$n++;}						
		   }
			
		  }		 
		 

		if(!$this->fail_result)
		{

			if(!$dbquery1->execute())
			{
				$this->status=false;
				$this->fail_result=true;
				//echo '<pre>';
				//print_r($valueFormated);
				//print_r($dbquery1);
				//print_r($dbquery1->errorInfo());
				$this->addition_info = $dbquery1->errorInfo();
				//$this->addition_info = 'error_100';
				//echo '<pre>';
			}
				
		}	

		//get the subjects
		if(!$this->fail_result)
		{
			$dbquery2 =  $this->db->conn_id->prepare("	
														SELECT ".$get_total_recordsQuery." *,COUNT(*) AS `message_count`
														FROM `temp_table` AS `s1`		
														
														GROUP BY `subject_id`
														ORDER BY ".$order_by."											
														LIMIT :from,:take									
													");
			
			$dbquery2->bindValue(':from', (int)$from, PDO::PARAM_INT);
			$dbquery2->bindValue(':take', (int)$take, PDO::PARAM_INT);

			if(!$dbquery2->execute())
			{
				$this->status=false;
				$this->fail_result=true;
				//echo '<pre>';
				//print_r($valueFormated);
				//print_r($dbquery1);
				//print_r($dbquery1->errorInfo());
				$this->addition_info = $dbquery2->errorInfo();
				//$this->addition_info = 'error_100';
		   		//echo '<pre>';
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

		//collect and organise for users + user groups
		if(!$this->fail_result)
		{ 		
			$this->status = true;
			$sql_result = $dbquery2->fetchAll();
			$count=count($sql_result);
			  
			  //echo '<pre>';
			  //print_r($sql_result);
			  //print_r($dbquery1);
			  
			if($get_total_records)
			{
			 	$dbqueryCount =  $this->db->conn_id->query('SELECT FOUND_ROWS()');
			 	$total_records = (int) $dbqueryCount->fetchColumn();
			}				  
				  
			if(count($sql_result)>0 && !empty($sql_result))
			{
				foreach( $sql_result as $key => $value)
				{
					
					//do some format
					
					#format profile_pic
					$profile_picArray=array();
					$delimiter1_profile_pic='|#$(delimiter-1)$#|';
					$delimiter2_profile_pic='|#$(delimiter-2)$#|';
			        $ArrayTemp1=explode($delimiter1_profile_pic,$value['sender_profile_pic']);
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
						if($value['sender_user_type']=='buyer')	                            							
						$profile_picArray[0]['path']=$data['default_profile_pic_buyer'];	                            							
						$profile_picArray[0]['default']=1;	                            					                    							
					}
					


					$result[$key]=array(					
								"sender_id"=>$value['sender_id'],
								"receiver_id"=>$value['receiver_id'],
								"message"=>$value['message'],
								"deletion_level"=>$value['deletion'],
								"message_read"=>$value['message_read'],
								"subject"=>$value['subject'],
								"sender_user_name"=>$value['sender_user_name'],
								"receiver_user_name"=>$value['receiver_user_name'],
								"subject_id"=>$value['subject_id'],
								"message_date"=>$value['message_date'],
								"subject_date"=>$value['subject_date'],
								"message_id"=>$value['message_id'],									
								"message_count"=>$value['message_count'],
								"sender_profile_pic"=>$profile_picArray,									
								);
				}
			}				 
        }  
		   
        return $info = array("status"=>$this->status,
                            "data"=> array('records'=> $result,
										   'count'=>$count,
										   'total_records'=>$total_records,
										   ),
                            "addition_info"=>$this->addition_info);
   	       
	} 

	public function sendReplyMessage($data=array())
	{
	    //reset default variable;
	    $this->reset_defaults();

	    if(!isset($data['sender_id'])) $data['sender_id']='';
	    if(!isset($data['receiver_id'])) $data['receiver_id']='';
	    if(!isset($data['message'])) $data['message']='';
	    if(!isset($data['subject_id'])) $data['subject_id']='';

	    // check if required data is supplied      
	    if(empty($data['sender_id']))
	    {
	        $this->addition_info='error 100_1 : sender_id not provided';
	        $this->fail_result=true;   
	    }
	    if(empty($data['receiver_id']))
	    {
	        $this->addition_info='error 100_2 : receiver_id not provided';
	        $this->fail_result=true;   
	    }
	    if(empty($data['subject_id']))
	    {
	        $this->addition_info='error 100_4 : subject_id not provided';
	        $this->fail_result=true;   
	    }

	    // store in Db
	    $this->db->conn_id->beginTransaction();

	    if(!$this->fail_result)   
	    {             
			$dbquery2=$this->db->conn_id->prepare("
							INSERT INTO `message`(`subject_id`,`message`,`sender_id`) 
							VALUES(:subject_id,:message,:sender_id)
			");
			$dbquery2->bindParam(":subject_id",$data['subject_id']);
			$dbquery2->bindParam(":message",$data['message']);
			$dbquery2->bindParam(":sender_id",$data['sender_id']);
	                 
	        if(!($dbquery2->execute()))
	        {
	            //print_r($dbquery2->errorInfo());
	            $this->fail_result=true;
	            $this->addition_info='error 101_2';
	        }else
	        {
	        	$data['message_id']=$this->db->conn_id->lastInsertId();       
	        }
	    }

	    if(!$this->fail_result)   
	    {             
			$dbquery3=$this->db->conn_id->prepare("
							INSERT INTO `message_receiver`(`message_id`,`receiver_id`) 
							VALUES(:message_id,:receiver_id)
			");
			$dbquery3->bindParam(":message_id",$data['message_id']);
			$dbquery3->bindParam(":receiver_id",$data['receiver_id']);
	                 
	        if(!($dbquery3->execute()))
	        {
	            //print_r($dbquery2->errorInfo());
	            $this->fail_result=true;
	            $this->addition_info='error 101_3';
	        }else
	        {
	        	//cool      
	        }
	    }
	             
		if(!$this->fail_result)
		{
			$this->db->conn_id->commit();
			$this->status=true;
			$this->result_info=" Message sent ";
		}
		else
		{
			$this->db->conn_id->rollBack();
			$this->fail_result=true;							
			//print_r($dbquery2->errorInfo());
			//echo $data['event_id']; 
			//$this->addition_info="error 107_1";
			//$addition_info="data_roll_back";
			//$addition_info=$dbquery3->errorInfo();	
		}

	    //return results
	            return $info = array("status"=>$this->status,
	                                   "data"=> array('result_info'=> $this->result_info,
	                                                   ),
	                                  "addition_info"=>$this->addition_info);	    
	}
	public function sendNewMessage($data=array())
	{
	    //reset default variable;
	    $this->reset_defaults();

	    if(!isset($data['sender_id'])) $data['sender_id']='';
	    if(!isset($data['receiver_id'])) $data['receiver_id']='';
	    if(!isset($data['message'])) $data['message']='';
	    if(!isset($data['subject'])) $data['subject']='';
	    if(!isset($data['subject_id'])) $data['subject_id']='';
	    if(!isset($data['message_id'])) $data['message_id']='';

	    // check if required data is supplied      
	    if(empty($data['sender_id']) && !is_numeric($data['sender_id']))
	    {
	        $this->addition_info='error 100_1 : sender_id not provided';
	        $this->fail_result=true;   
	    }
	    if(empty($data['receiver_id']) && !is_numeric($data['receiver_id']))
	    {
	        $this->addition_info='error 100_2 : receiver_id not provided';
	        $this->fail_result=true;   
	    }
	    if(empty($data['message']) && !is_numeric($data['message']))
	    {
	        $this->addition_info='error 100_3 : message not provided';
	        $this->result_info='Write something';
	        $this->fail_result=true;  
	    }

	    // store in Db
	    $this->db->conn_id->beginTransaction();
	    if(!$this->fail_result)   
	    {             
			$dbquery1=$this->db->conn_id->prepare("
							INSERT INTO `message_subject`(`subject`) 
							VALUES(:subject)
			");
			$dbquery1->bindParam(":subject",$data['subject']);
	                 
	        if(!($dbquery1->execute()))
	        {
	            //print_r($dbquery1->errorInfo());
	            $this->fail_result=true;
	            $this->addition_info='error 101_1';
	        }else
	        {
	            $data['subject_id']=$this->db->conn_id->lastInsertId();
	        }
	    }

	    if(!$this->fail_result)   
	    {             
			$dbquery2=$this->db->conn_id->prepare("
							INSERT INTO `message`(`subject_id`,`message`,`sender_id`) 
							VALUES(:subject_id,:message,:sender_id)
			");
			$dbquery2->bindParam(":subject_id",$data['subject_id']);
			$dbquery2->bindParam(":message",$data['message']);
			$dbquery2->bindParam(":sender_id",$data['sender_id']);
	                 
	        if(!($dbquery2->execute()))
	        {
	            //print_r($dbquery2->errorInfo());
	            $this->fail_result=true;
	            $this->addition_info='error 101_2';
	        }else
	        {
	        	$data['message_id']=$this->db->conn_id->lastInsertId();       
	        }
	    }

	    if(!$this->fail_result)   
	    {             
			$dbquery3=$this->db->conn_id->prepare("
							INSERT INTO `message_receiver`(`message_id`,`receiver_id`) 
							VALUES(:message_id,:receiver_id)
			");
			$dbquery3->bindParam(":message_id",$data['message_id']);
			$dbquery3->bindParam(":receiver_id",$data['receiver_id']);
	                 
	        if(!($dbquery3->execute()))
	        {
	            //print_r($dbquery2->errorInfo());
	            $this->fail_result=true;
	            $this->addition_info='error 101_3';
	        }else
	        {
	        	//cool      
	        }
	    }
	             
		if(!$this->fail_result)
		{
			$this->db->conn_id->commit();
			$this->status=true;
			$this->result_info=" Message sent ";
		}
		else
		{
			$this->db->conn_id->rollBack();
			$this->fail_result=true;							
			//print_r($dbquery2->errorInfo());
			//echo $data['event_id']; 
			//$this->addition_info="error 107_1";
			//$addition_info="data_roll_back";
			//$addition_info=$dbquery3->errorInfo();	
		}

	    //return results
	            return $info = array("status"=>$this->status,
	                                   "data"=> array('result_info'=> $this->result_info,
	                                   				   'message_id'=>$data['message_id'],	 
	                                   				   'subject_id'=>$data['subject_id'],	 
	                                                   ),
	                                  "addition_info"=>$this->addition_info);	    
	}

	public function updateMessageDeleteStatus($data=array())
	{
	    //reset default variable;
	    $this->reset_defaults();
	    $action='';

	    if(!isset($data['deletion_level'])) $data['deletion_level']='';
	    if(!isset($data['deleter_id'])) $data['deleter_id']='';
	    if(!isset($data['subject_id'])) $data['subject_id']='';
	    
	    if($data['deletion_level']==0)
	    	$action='delete'; // restore to normal or remove from delete list
	    elseif($data['deletion_level']==1 || $data['deletion_level']==2)
    	{
			 //cool
    	}
	    else
	    {
	    		$this->fail_result=true;
	    		$this->addition_info='error 100_0 :invalid deletion_level';	  	
	    }

	    // check if required data is supplied      
	    if(empty($data['deletion_level']) && !is_numeric($data['deletion_level']))
	    {
	    	echo $data['deletion_level'];
	        $this->addition_info='error 100_1 : deletion_level not provided';
	        $this->fail_result=true;   
	    }
	    if(empty($data['deleter_id'])  && !is_numeric($data['deleter_id']))
	    {
	        $this->addition_info='error 100_2 : deleter_id not provided';
	        $this->fail_result=true;   
	    }
	    if(empty($data['subject_id']) && !is_numeric($data['subject_id']))
	    {
	        $this->addition_info='error 100_4 : subject_id not provided';
	        $this->fail_result=true;   
	    }

	    if(!is_array($data['subject_id']))
	    {
	    	$data['subject_id'] = array($data['subject_id']);
	    }

	    	$whereSubquery=array();
	    	foreach ($data['subject_id'] as $key => $value) {
	    	array_push($whereSubquery,'(`subject_id`=:subject_id'.$key.')');
	    	}
	    	$whereSubquery = implode('OR', $whereSubquery);
	    	$whereSubquery='WHERE '.$whereSubquery.' AND `deleter_id`=:deleter_id';

	    if(!$this->fail_result && $data['deletion_level']!=0)   
	    {            
			$dbquery1=$this->db->conn_id->prepare("
						SELECT * 
						FROM `message_deletion` 
						".$whereSubquery."							
			");
			
			$dbquery1->bindParam(":deleter_id",$data['deleter_id']);
	        foreach ($data['subject_id'] as $key => $value) {
	        	$dbquery1->bindParam(":subject_id".$key,$value);
	    	}

	        if(!($dbquery1->execute()))
	        {
	            //print_r($dbquery1->errorInfo());
	            $this->fail_result=true;
	            $this->addition_info='error 101_1';
	        }else
	        {
	        	$sql_result=$dbquery1->fetch();
                   
                if(!empty($sql_result))
                {
                    $action = 'update';                      
                }
                else
                {
                	$action = 'insert';
                }	
	        }
	    }
        
	    if(!$this->fail_result && $action=='update')   
	    {             
			$dbquery2=$this->db->conn_id->prepare("
							UPDATE `message_deletion` 
							SET `deletion_level`=:deletion_level  
							".$whereSubquery."							
			");
			$dbquery2->bindValue(":deleter_id",$data['deleter_id']);
			$dbquery2->bindValue(":deletion_level",$data['deletion_level']);
	        foreach ($data['subject_id'] as $key => $value) {
	        	$dbquery2->bindValue(":subject_id".$key,$value);
	    	}
	                 
	        if(!($dbquery2->execute()))
	        {
	            //print_r($dbquery2->errorInfo());
	            $this->fail_result=true;
	            $this->addition_info='error 101_2';
	        }else
	        {
				$this->status=true;
				$this->result_info=" successful deleted";	
	        }
	    }

	    	$valuesSubquery=array();
	    	foreach ($data['subject_id'] as $key => $value) {
	    	array_push($valuesSubquery,'(:subject_id'.$key.',:deleter_id'.$key.',:deletion_level'.$key.')');
	    	}
	    	$valuesSubquery = implode(',', $valuesSubquery);
	    	$valuesSubquery = 'VALUES '.$valuesSubquery;


	    if(!$this->fail_result && $action=='insert')   
	    {             
			$dbquery3=$this->db->conn_id->prepare("
							INSERT INTO `message_deletion`(`subject_id`,`deleter_id`,`deletion_level`) 
							".$valuesSubquery."
			");
			foreach ($data['subject_id'] as $key => $value) {
		        $dbquery3->bindValue(":subject_id".$key,$value);
				$dbquery3->bindValue(":deleter_id".$key,$data['deleter_id']);
				$dbquery3->bindValue(":deletion_level".$key,$data['deletion_level']);
	        }	                 
	        if(!($dbquery3->execute()))
	        {
	            //print_r($dbquery3->errorInfo());
	            $this->fail_result=true;
	            $this->addition_info='error 101_3';
	        }else
	        {
	        	$this->status=true;
				$this->result_info=" successful deleted";	
	        }
	    }

	    if(!$this->fail_result && $action=='delete')   
	    {             
			$dbquery4=$this->db->conn_id->prepare("
							DELETE FROM `message_deletion`
							".$whereSubquery."						
			");
			$dbquery4->bindValue(":deleter_id",$data['deleter_id']);
	        foreach ($data['subject_id'] as $key => $value) {
	        	$dbquery4->bindValue(":subject_id".$key,$value);
	    	}
			         
	        if(!($dbquery4->execute()))
	        {
	            //print_r($dbquery4->errorInfo());
	            $this->fail_result=true;
	            $this->addition_info='error 101_4';
	        }else
	        {
				$this->status=true;
				$this->result_info=" successful restored";	
	        }
	    }

	    //return results
	            return $info = array("status"=>$this->status,
	                                   "data"=> array('result_info'=> $this->result_info,
	                                                   ),
	                                  "addition_info"=>$this->addition_info);	    
	}


    
}  


?>