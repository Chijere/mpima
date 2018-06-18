<?php
class User_info_model extends CI_Model {

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
    }

	private function reset_defaults()
    {
	    $this->addition_info="";
	    $this->status = false;
	    $this->result_info=false; 
	    $this->fail_result=false;
    } 

 ##------------------------------------------
	public function searchUser($filters=array())
	{
        // set defaults
         $this->reset_defaults(); 
         $searchWord='';
		 $count=0;
		 $result=array();
		 $total_records=false;		 
		 $get_total_records=false;
		 $filteringCondtion=' AND ';
    	 $from=0;$take=20;
		 $is_user_id_followerQuery="";
	 	 $followingQuery="";
		 $usersPerLocation=array();
		 $usersPerVerification=array();	 	 
	 	 $order_by='`date` DESC';		 

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

		 if(isset($filters['search_word']))
		 { $searchWord=$filters['search_word'];
		   unset($filters['search_word']);// to avoid set to invalid hence returning nothing
		 }
		 elseif(isset($filters['searchWord']))
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

        array_push($WhereSubQueryArry,'(`user_id` IS NOT NULL) ');			
			
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
				if(	$key==='follower_id_or_followed_id' || ($key==='follower_id_or_followed_id'.$n) || 
					$key==='followed_id' || ($key==='followed_id'.$n) || $key==='follower_id' || ($key==='follower_id'.$n) )
				{
					//this subQuery should be written once
					 	if(!isset($loopOnceChecker))
					 		$loopOnceChecker=0; else $loopOnceChecker=1;

					   if($loopOnceChecker==0)
					   {
						 $followingQuery=
						 "LEFT JOIN (SELECT `followed_id`,`user_id` as `follower_id`
									FROM `followed_page` `s11f_main`	
									) AS `s11f` ON (`s11f`.`followed_id` = `s1`.`user_id`	 OR `s11f`.`follower_id` = `s1`.`user_id`)";
					   }
				}

				if($key==='exclude_user_id' || ($key==='exclude_user_id'.$n) )
				{
					array_push($miniWhereSubQueryArry,'`user_id`<>:exclude_user_id'.$n ); 
					$n++;
				}elseif($key==='followed_id' || ($key==='followed_id'.$n) )
				{
					array_push($miniWhereSubQueryArry,'`followed_id`=:followed_id'.$n );
					$n++;
				}
				elseif($key==='follower_id' || ($key==='follower_id'.$n) )
				{
					array_push($miniWhereSubQueryArry,'`follower_id`=:follower_id'.$n );
					$n++;
				}elseif($key==='is_user_id_follower' || ($key==='is_user_id_follower'.$n) )
				{
						 $is_user_id_followerQuery=
						 "LEFT JOIN (SELECT `followed_id` AS `s10f_followed_id`,`user_id` AS `s10f_follower_id`,  
									CASE WHEN ISNULL(`user_id`) THEN '0' ELSE '1' END  AS `is_user_id_follower`
									FROM `followed_page` `s10f_main`	
									) AS `s10f` ON (`s10f`.`s10f_followed_id` = `s1`.`user_id`	 AND `s10f`.`s10f_follower_id` = :is_user_id_follower".$n.")";
					   
					$n++;
				}elseif($key==='follower_id_or_followed_id' || ($key==='follower_id_or_followed_id'.$n) )
				{
					array_push($miniWhereSubQueryArry,'`follower_id`=:follower_id_or_followed_id'.$n.' OR `followed_id`=:follower_id_or_followed_id'.$n );
					$n++;
				}elseif($key==='verification' || ($key==='verification'.$n) )
				{
					array_push($miniWhereSubQueryArry,'`verification`=:verification'.$n ); 
					$n++;
				}elseif($key==='user_id' || ($key==='user_id'.$n) )
				{
					array_push($miniWhereSubQueryArry,'`user_id`=:user_id'.$n ); 
					$n++;
				}elseif($key==='user_type' || ($key==='user_type'.$n) )
				{
					array_push($miniWhereSubQueryArry,'`user_type`=:user_type'.$n ); 
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
													FROM (
															SELECT  *,(MATCH (`user_name`) AGAINST ( '>':searchWord '<':searchWord'*' IN BOOLEAN MODE)) AS `rel1`,
													   			CASE WHEN ISNULL(`s10_rank`) THEN '0' ELSE `s10_rank` END  AS `rank`,
													       		CASE WHEN ISNULL(`s10_verification`) THEN 'Unverified' ELSE `s10_verification` END  AS `verification`
																FROM `user_info` `s1`
																
																LEFT JOIN (
																SELECT `user_pic` as `profile_pic` , `user_id` AS `s2_user_id`
																FROM `profile_pic`
																) AS `s2` ON `s2`.`s2_user_id` = `s1`.`user_id`
																
																LEFT JOIN (
																SELECT `post_box` AS `contact_post_box` , `user_id` AS `s3_user_id`
																FROM `contact_post`
																) AS `s3` ON `s3`.`s3_user_id` = `s1`.`user_id`
																			
																LEFT JOIN (
																SELECT `phone` AS `contact_phone` , `user_id` AS `s4_user_id`
																FROM `contact_phone`
																) AS `s4` ON `s4`.`s4_user_id` = `s1`.`user_id`
																																	
																LEFT JOIN (
																SELECT `email` AS `contact_email` , `user_id` AS `s5_user_id`
																FROM `contact_email`
																) AS `s5` ON `s5`.`s5_user_id` = `s1`.`user_id`	
																
																LEFT JOIN (
																SELECT `location`,`map_description` AS `contact_map_description`,`map_pic` AS `contact_map_pic`, `user_id` AS `s6_user_id`
																FROM `contact_map` `s6_main`
																) AS `s6` ON `s6`.`s6_user_id` = `s1`.`user_id`
																
																LEFT JOIN (
																SELECT `license_description` AS `license_description`,`license_pic` AS `license_pic`, `user_id` AS `s7_user_id`
																FROM `user_license` `s7_main`
																) AS `s7` ON `s7`.`s7_user_id` = `s1`.`user_id`
																
																LEFT JOIN (
																SELECT `email` AS `main_email`,`user_id` AS `s8_user_id`
																FROM `users` 
																) AS `s8` ON `s8`.`s8_user_id` = `s1`.`user_id`

																LEFT JOIN (SELECT  `followed_id` AS `s9_followed_id`, COUNT(*) AS followers
																FROM `followed_page` `s9_main`										
																GROUP BY `followed_id`
																) AS `s9` ON `s9`.`s9_followed_id` = `s1`.`user_id`

																LEFT JOIN (SELECT  `user_id` AS `s10_user_id`,`rank` AS `s10_rank`,`verification` AS `s10_verification`							       
																FROM `verification` `s10_main`	
																) AS `s10` ON `s10`.`s10_user_id` = `s1`.`user_id`
																

																LEFT JOIN (
																SELECT `user_pic` as `profile_cover_pic` , `user_id` AS `s11_user_id`
																FROM `profile_cover_pic`
																) AS `s11` ON `s11`.`s11_user_id` = `s1`.`user_id`

																LEFT JOIN (
																SELECT `user_type`, `user_id` AS `s12_user_id`
																FROM `users`
																) AS `s12` ON `s12`.`s12_user_id` = `s1`.`user_id`
																
															    ".$is_user_id_followerQuery."	
																
															    ".$followingQuery."											
																WHERE (MATCH (`user_name`) AGAINST ( '>':searchWord '<':searchWord'*' IN BOOLEAN MODE))									
																
														) `s_whole`
														
														".$WheresubQuery."
														GROUP BY `user_id`
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
				if($key==='is_user_id_follower' || ($key==='is_user_id_follower'.$n))
				{
					$dbquery1->bindValue(":is_user_id_follower".$n,$value2);
					 $n++;
			 	}

				if($key==='follower_id_or_followed_id' || ($key==='follower_id_or_followed_id'.$n))
				{$dbquery1->bindValue(":follower_id_or_followed_id".$n,$value2); $n++;}
										
				if($key==='followed_id' || ($key==='followed_id'.$n))
				{$dbquery1->bindValue(":followed_id".$n,$value2); $n++;}
				
				if($key==='follower_id' || ($key==='follower_id'.$n))
				{$dbquery1->bindValue(":follower_id".$n,$value2); $n++;}
				
				if($key==='exclude_user_id' || ($key==='exclude_user_id'.$n))
				{$dbquery1->bindValue(":exclude_user_id".$n,$value2); $n++;}
				
				if($key==='verification' || ($key==='verification'.$n))
				{$dbquery1->bindValue(":verification".$n,$value2); $n++;}
			    
				if($key==='user_id' || ($key==='user_id'.$n) ) 
				{$dbquery1->bindValue(":user_id".$n,$value2); $n++; }

				if($key==='user_type' || ($key==='user_type'.$n) ) 
				{$dbquery1->bindValue(":user_type".$n,$value2); $n++; }

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
				//$this->addition_info = $dbquery1->errorInfo();
				//$this->addition_info = 'error_100';
			}
		}	

		//get the users
		if(!$this->fail_result)
		{

			$dbquery2 =  $this->db->conn_id->prepare("	
														SELECT ".$get_total_recordsQuery." *
														FROM `temp_table` AS `s1`		
														
														GROUP BY `user_id`
														ORDER BY ((`rel1`*0.7)+(`rank`*0.3)) DESC,".$order_by."											
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
		   	}
		}				

		//get the users' groups of verification
		if(!$this->fail_result)
		{

			$dbquery3 =  $this->db->conn_id->prepare("	
														SELECT COUNT(*) AS `usersPerVerification`,
																CASE WHEN ISNULL(`verification`) THEN 'Unverified' ELSE `verification` END  AS `verification`
														FROM `temp_table`
														GROUP BY `verification`											
														LIMIT 15									
													");
				
			if(!$dbquery3->execute())
			{
				$this->status=false;
				$this->fail_result=true;
				//echo '<pre>';
				//print_r($valueFormated);
				//print_r($dbquery1);
				//print_r($dbquery1->errorInfo());
				$this->addition_info = $dbquery3->errorInfo();
				//$this->addition_info = 'error_100';
			}
		}							

		//get the users' groups of location
		if(!$this->fail_result)
		{

			$dbquery5 =  $this->db->conn_id->prepare("	
														SELECT COUNT(*) AS `usersPerLocation`,`location`
														FROM `temp_table`
														GROUP BY `location`											
														LIMIT 15									
													");
				
			if(!$dbquery5->execute())
			{
				$this->status=false;
				$this->fail_result=true;
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

		//collect and organise for users + user groups
		if(!$this->fail_result)
		{ 
		 	#collect for user'group
		   	$usersPerVerification = $dbquery3->fetchAll();
		   	$usersPerLocation = $dbquery5->fetchAll();
		
			$this->status = true;
			$sql_result = $dbquery2->fetchAll();
			$count=count($sql_result);
			  
			  //echo '<pre>';
			  //print_r($sql_result);
			  
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
					#is This User_id a Follower
				 	if(!isset($value['is_user_id_follower']))$value['is_user_id_follower']=0;

					#format email
					$emailArray=array();
					$delimiter1_email='|#$(deli@m@iter-1)$#|';
					$delimiter2_email='|#$(deli@m@iter-2)$#|';
			        $ArrayTemp1=explode($delimiter1_email,$value['contact_email']);
		            $ArrayTemp1=array_filter($ArrayTemp1); 
		            foreach ($ArrayTemp1 as $keytemp => $valuetemp) 
		            {
		            	$ArrayTemp2=explode($delimiter2_email,$valuetemp);
		            	if(isset($ArrayTemp2[0]))
		            	{
			            	$emailArray[$keytemp]['id']=$ArrayTemp2[0];
			            	$emailArray[$keytemp]['email']=$ArrayTemp2[1];	
		            	}	
		            }

					#format phone
					$phoneArray=array();
					$delimiter1_phone='|#$(delimiter-1)$#|';
					$delimiter2_phone='|#$(delimiter-2)$#|';
			        $ArrayTemp1=explode($delimiter1_phone,$value['contact_phone']);
		            $ArrayTemp1=array_filter($ArrayTemp1);
		            foreach ($ArrayTemp1 as $keytemp => $valuetemp) 
		            {
		            	$ArrayTemp2=explode($delimiter2_phone,$valuetemp);
		            	if(isset($ArrayTemp2[0]))
		            	{
			            	$phoneArray[$keytemp]['id']=$ArrayTemp2[0];
			            	$phoneArray[$keytemp]['phone']=$ArrayTemp2[1];	
		            	}	
		            }

					#format map_pic
					$map_picArray=array();
					$delimiter1_map_pic='|#$(delimiter-1)$#|';
					$delimiter2_map_pic='|#$(delimiter-2)$#|';
			        $ArrayTemp1=explode($delimiter1_map_pic,$value['contact_map_pic']);
		            $ArrayTemp1=array_filter($ArrayTemp1);
		            foreach ($ArrayTemp1 as $keytemp => $valuetemp) 
		            {
		            	$ArrayTemp2=explode($delimiter2_map_pic,$valuetemp);
		            	if(isset($ArrayTemp2[0]))
		            	{
			            	$map_picArray[$keytemp]['id']=$ArrayTemp2[0];
			            	$map_picArray[$keytemp]['path']=$ArrayTemp2[1];	
		            	}	
		            }

					#format license_pic
					$license_picArray=array();
					$delimiter1_license_pic='|#$(delimiter-1)$#|';
					$delimiter2_license_pic='|#$(delimiter-2)$#|';
			        $ArrayTemp1=explode($delimiter1_license_pic,$value['license_pic']);
		            $ArrayTemp1=array_filter($ArrayTemp1);
		            foreach ($ArrayTemp1 as $keytemp => $valuetemp) 
		            {
		            	$ArrayTemp2=explode($delimiter2_license_pic,$valuetemp);
		            	if(isset($ArrayTemp2[0]))
		            	{
			            	$license_picArray[$keytemp]['id']=$ArrayTemp2[0];
			            	$license_picArray[$keytemp]['path']=$ArrayTemp2[1];	
		            	}	
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
						if($value['user_type']=='buyer')                            							
						$profile_picArray[0]['path']=$data['default_profile_pic_buyer'];                            							
						$profile_picArray[0]['default']=1;	                            							
					}
					

					#format profile_cover_pic
					$profile_cover_picArray=array();
					$delimiter1_profile_cover_pic='|#$(delimiter-1)$#|';
					$delimiter2_profile_cover_pic='|#$(delimiter-2)$#|';
			        $ArrayTemp1=explode($delimiter1_profile_cover_pic,$value['profile_cover_pic']);
		            $ArrayTemp1=array_filter($ArrayTemp1);
		            foreach ($ArrayTemp1 as $keytemp => $valuetemp) 
		            {
		            	$ArrayTemp2=explode($delimiter2_profile_cover_pic,$valuetemp);
		            	if(isset($ArrayTemp2[0]))
		            	{
			            	$profile_cover_picArray[$keytemp]['id']=$ArrayTemp2[0];
			            	$profile_cover_picArray[$keytemp]['path']=$ArrayTemp2[1];	
			            	$profile_cover_picArray[$keytemp]['default']=0;
		            	}	
		            }

		            #set default profile_cover_pic
					if(empty($profile_cover_picArray))
					{
						$profile_cover_picArray[0]['id']='null';
						$profile_cover_picArray[0]['path']=$data['default_profile_cover_pic'];	                            							
						$profile_cover_picArray[0]['default']=1;	                            							
					}
					




					$result[$key]=array(					
								"user_id"=>$value['user_id'],
								"main_email"=>$value['main_email'],
								"contact_post_box"=>$value['contact_post_box'],
								"contact_email"=>$emailArray,
								"contact_phone"=>$phoneArray,
								"contact_map_description"=>$value['contact_map_description'],
								"contact_map_pic"=>$map_picArray,
								"user_license_pic"=>$license_picArray,
								"user_name"=>$value['user_name'],
								"date"=>$value['date'],
								"profile_pic"=>$profile_picArray,
								"profile_cover_pic"=>$profile_cover_picArray,
								"verification"=>$value['verification'],				
								"about"=>$value['about'],											
								"user_type"=>$value['user_type'],									
								"rank"=>$value['rank'],									
								"followers"=>$value['followers'],									
								"is_user_id_follower"=>$value['is_user_id_follower'],									
								);
				}
			}				 
        }  
		   
        return $info = array("status"=>$this->status,
                            "data"=> array('records'=> $result,
										   'count'=>$count,
										   'total_records'=>$total_records,
										   'usersPerVerification'=>$usersPerVerification,
										   'usersPerLocation'=>$usersPerLocation,
										   ),
                            "addition_info"=>$this->addition_info,
                            );
   	       
	} 

 ##------------------------------------------
	public function getUser($filters=array())
	{
        // set defaults
        $this->reset_defaults(); 
         
		 $count=0;
		 $result=array();
		 $total_records=false;		 
		 $get_total_records=false;
		 $filteringCondtion=' AND ';
    	 $from=0;$take=20;
		 $is_user_id_followerQuery="";
	 	 $followingQuery="";
		 $usersPerLocation=array();
		 $usersPerVerification=array();	 	 
	 	 $order_by='`date` DESC';
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

        array_push($WhereSubQueryArry,'(`user_id` IS NOT NULL) ');			
			
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
				if(	$key==='follower_id_or_followed_id' || ($key==='follower_id_or_followed_id'.$n) || 
					$key==='followed_id' || ($key==='followed_id'.$n) || $key==='follower_id' || ($key==='follower_id'.$n) )
				{
					//this subQuery should be written once
					 	if(!isset($loopOnceChecker))
					 		$loopOnceChecker=0; else $loopOnceChecker=1;

					   if($loopOnceChecker==0)
					   {
						 $followingQuery=
						 "LEFT JOIN (SELECT `followed_id`,`user_id` as `follower_id`
									FROM `followed_page` `s11f_main`	
									) AS `s11f` ON (`s11f`.`followed_id` = `s1`.`user_id`	 OR `s11f`.`follower_id` = `s1`.`user_id`)";
					   }
				}

				if($key==='exclude_user_id' || ($key==='exclude_user_id'.$n) )
				{
					array_push($miniWhereSubQueryArry,'`user_id`<>:exclude_user_id'.$n ); 
					$n++;
				}elseif($key==='followed_id' || ($key==='followed_id'.$n) )
				{
					array_push($miniWhereSubQueryArry,'`followed_id`=:followed_id'.$n );
					$n++;
				}
				elseif($key==='follower_id' || ($key==='follower_id'.$n) )
				{
					array_push($miniWhereSubQueryArry,'`follower_id`=:follower_id'.$n );
					$n++;
				}elseif($key==='is_user_id_follower' || ($key==='is_user_id_follower'.$n) )
				{
						 $is_user_id_followerQuery=
						 "LEFT JOIN (SELECT `followed_id` AS `s10f_followed_id`,`user_id` AS `s10f_follower_id`,  
									CASE WHEN ISNULL(`user_id`) THEN '0' ELSE '1' END  AS `is_user_id_follower`
									FROM `followed_page` `s10f_main`	
									) AS `s10f` ON (`s10f`.`s10f_followed_id` = `s1`.`user_id`	 AND `s10f`.`s10f_follower_id` = :is_user_id_follower".$n.")";
					   
					$n++;
				}elseif($key==='follower_id_or_followed_id' || ($key==='follower_id_or_followed_id'.$n) )
				{
					array_push($miniWhereSubQueryArry,'`follower_id`=:follower_id_or_followed_id'.$n.' OR `followed_id`=:follower_id_or_followed_id'.$n );
					$n++;
				}elseif($key==='verification' || ($key==='verification'.$n) )
				{
					array_push($miniWhereSubQueryArry,'`verification`=:verification'.$n ); 
					$n++;
				}elseif($key==='user_id' || ($key==='user_id'.$n) )
				{
					array_push($miniWhereSubQueryArry,'`user_id`=:user_id'.$n ); 
					$n++;
				}elseif($key==='user_type' || ($key==='user_type'.$n) )
				{
					array_push($miniWhereSubQueryArry,'`user_type`=:user_type'.$n ); 
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
													SELECT  *,
											   			CASE WHEN ISNULL(`s10_rank`) THEN '0' ELSE `s10_rank` END  AS `rank`,
											       		CASE WHEN ISNULL(`s10_verification`) THEN 'Unverified' ELSE `s10_verification` END  AS `verification`
														FROM `user_info` `s1`
														
														LEFT JOIN (
														SELECT `user_pic` as `profile_pic` , `user_id` AS `s2_user_id`
														FROM `profile_pic`
														) AS `s2` ON `s2`.`s2_user_id` = `s1`.`user_id`
														
														LEFT JOIN (
														SELECT `post_box` AS `contact_post_box` , `user_id` AS `s3_user_id`
														FROM `contact_post`
														) AS `s3` ON `s3`.`s3_user_id` = `s1`.`user_id`
																	
														LEFT JOIN (
														SELECT `phone` AS `contact_phone` , `user_id` AS `s4_user_id`
														FROM `contact_phone`
														) AS `s4` ON `s4`.`s4_user_id` = `s1`.`user_id`
																															
														LEFT JOIN (
														SELECT `email` AS `contact_email` , `user_id` AS `s5_user_id`
														FROM `contact_email`
														) AS `s5` ON `s5`.`s5_user_id` = `s1`.`user_id`	
														
														LEFT JOIN (
														SELECT `location`,`map_description` AS `contact_map_description`,`map_pic` AS `contact_map_pic`, `user_id` AS `s6_user_id`
														FROM `contact_map` `s6_main`
														) AS `s6` ON `s6`.`s6_user_id` = `s1`.`user_id`
														
														LEFT JOIN (
														SELECT `license_description` AS `license_description`,`license_pic` AS `license_pic`, `user_id` AS `s7_user_id`
														FROM `user_license` `s7_main`
														) AS `s7` ON `s7`.`s7_user_id` = `s1`.`user_id`
														
														LEFT JOIN (
														SELECT `email` AS `main_email`,`user_id` AS `s8_user_id`
														FROM `users` 
														) AS `s8` ON `s8`.`s8_user_id` = `s1`.`user_id`

														LEFT JOIN (SELECT  `followed_id` AS `s9_followed_id`, COUNT(*) AS followers
														FROM `followed_page` `s9_main`										
														GROUP BY `followed_id`
														) AS `s9` ON `s9`.`s9_followed_id` = `s1`.`user_id`

														LEFT JOIN (SELECT  `user_id` AS `s10_user_id`,`rank` AS `s10_rank`,`verification` AS `s10_verification`							       
														FROM `verification` `s10_main`	
														) AS `s10` ON `s10`.`s10_user_id` = `s1`.`user_id`
														

														LEFT JOIN (
														SELECT `user_pic` as `profile_cover_pic` , `user_id` AS `s11_user_id`
														FROM `profile_cover_pic`
														) AS `s11` ON `s11`.`s11_user_id` = `s1`.`user_id`

														LEFT JOIN (
														SELECT `user_type`, `user_id` AS `s12_user_id`
														FROM `users`
														) AS `s12` ON `s12`.`s12_user_id` = `s1`.`user_id`
														
													    ".$is_user_id_followerQuery."	
														
													    ".$followingQuery."											
														
														".$WheresubQuery."
														GROUP BY `user_id`
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
				if($key==='is_user_id_follower' || ($key==='is_user_id_follower'.$n))
				{
					$dbquery1->bindValue(":is_user_id_follower".$n,$value2);
					$n++;
			 	}

				if($key==='follower_id_or_followed_id' || ($key==='follower_id_or_followed_id'.$n))
				{$dbquery1->bindValue(":follower_id_or_followed_id".$n,$value2); $n++;}
										
				if($key==='followed_id' || ($key==='followed_id'.$n))
				{$dbquery1->bindValue(":followed_id".$n,$value2); $n++;}
				
				if($key==='follower_id' || ($key==='follower_id'.$n))
				{$dbquery1->bindValue(":follower_id".$n,$value2); $n++;}
				
				if($key==='exclude_user_id' || ($key==='exclude_user_id'.$n))
				{$dbquery1->bindValue(":exclude_user_id".$n,$value2); $n++;}
				
				if($key==='verification' || ($key==='verification'.$n))
				{$dbquery1->bindValue(":verification".$n,$value2); $n++;}
			    
				if($key==='user_id' || ($key==='user_id'.$n) ) 
				{$dbquery1->bindValue(":user_id".$n,$value2); $n++; }

				if($key==='user_type' || ($key==='user_type'.$n) ) 
				{$dbquery1->bindValue(":user_type".$n,$value2); $n++; }

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
			}
		}	

		//get the users
		if(!$this->fail_result)
		{
			$dbquery2 =  $this->db->conn_id->prepare("	
														SELECT ".$get_total_recordsQuery." *
														FROM `temp_table` AS `s1`		
														
														GROUP BY `user_id`
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
		   	}
		}				

		//get the users' groups of verification
		if(!$this->fail_result)
		{

			$dbquery3 =  $this->db->conn_id->prepare("	
														SELECT COUNT(*) AS `usersPerVerification`,
																CASE WHEN ISNULL(`verification`) THEN 'Unverified' ELSE `verification` END  AS `verification`
														FROM `temp_table`
														GROUP BY `verification`											
														LIMIT 15									
													");
				
			if(!$dbquery3->execute())
			{
				$this->status=false;
				$this->fail_result=true;
				//echo '<pre>';
				//print_r($valueFormated);
				//print_r($dbquery1);
				//print_r($dbquery1->errorInfo());
				$this->addition_info = $dbquery3->errorInfo();
				//$this->addition_info = 'error_100';
			}
		}							

		//get the users' groups of location
		if(!$this->fail_result)
		{

			$dbquery5 =  $this->db->conn_id->prepare("	
														SELECT COUNT(*) AS `usersPerLocation`,`location`
														FROM `temp_table`
														GROUP BY `location`											
														LIMIT 15									
													");
				
			if(!$dbquery5->execute())
			{
				$this->status=false;
				$this->fail_result=true;
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

		//collect and organise for users + user groups
		if(!$this->fail_result)
		{ 
		 	#collect for user'group
		   	$usersPerVerification = $dbquery3->fetchAll();
		   	$usersPerLocation = $dbquery5->fetchAll();
		
			$this->status = true;
			$sql_result = $dbquery2->fetchAll();
			$count=count($sql_result);
			  
			  //echo '<pre>';
			  //print_r($sql_result);
			  
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
					#is This User_id a Follower
				 	if(!isset($value['is_user_id_follower']))$value['is_user_id_follower']=0;

					#format email
					$emailArray=array();
					$delimiter1_email='|#$(deli@m@iter-1)$#|';
					$delimiter2_email='|#$(deli@m@iter-2)$#|';
			        $ArrayTemp1=explode($delimiter1_email,$value['contact_email']);
		            $ArrayTemp1=array_filter($ArrayTemp1); 
		            foreach ($ArrayTemp1 as $keytemp => $valuetemp) 
		            {
		            	$ArrayTemp2=explode($delimiter2_email,$valuetemp);
		            	if(isset($ArrayTemp2[0]))
		            	{
			            	$emailArray[$keytemp]['id']=$ArrayTemp2[0];
			            	$emailArray[$keytemp]['email']=$ArrayTemp2[1];	
		            	}	
		            }

					#format phone
					$phoneArray=array();
					$delimiter1_phone='|#$(delimiter-1)$#|';
					$delimiter2_phone='|#$(delimiter-2)$#|';
			        $ArrayTemp1=explode($delimiter1_phone,$value['contact_phone']);
		            $ArrayTemp1=array_filter($ArrayTemp1);
		            foreach ($ArrayTemp1 as $keytemp => $valuetemp) 
		            {
		            	$ArrayTemp2=explode($delimiter2_phone,$valuetemp);
		            	if(isset($ArrayTemp2[0]))
		            	{
			            	$phoneArray[$keytemp]['id']=$ArrayTemp2[0];
			            	$phoneArray[$keytemp]['phone']=$ArrayTemp2[1];	
		            	}	
		            }

					#format map_pic
					$map_picArray=array();
					$delimiter1_map_pic='|#$(delimiter-1)$#|';
					$delimiter2_map_pic='|#$(delimiter-2)$#|';
			        $ArrayTemp1=explode($delimiter1_map_pic,$value['contact_map_pic']);
		            $ArrayTemp1=array_filter($ArrayTemp1);
		            foreach ($ArrayTemp1 as $keytemp => $valuetemp) 
		            {
		            	$ArrayTemp2=explode($delimiter2_map_pic,$valuetemp);
		            	if(isset($ArrayTemp2[0]))
		            	{
			            	$map_picArray[$keytemp]['id']=$ArrayTemp2[0];
			            	$map_picArray[$keytemp]['path']=$ArrayTemp2[1];	
		            	}	
		            }

					#format license_pic
					$license_picArray=array();
					$delimiter1_license_pic='|#$(delimiter-1)$#|';
					$delimiter2_license_pic='|#$(delimiter-2)$#|';
			        $ArrayTemp1=explode($delimiter1_license_pic,$value['license_pic']);
		            $ArrayTemp1=array_filter($ArrayTemp1);
		            foreach ($ArrayTemp1 as $keytemp => $valuetemp) 
		            {
		            	$ArrayTemp2=explode($delimiter2_license_pic,$valuetemp);
		            	if(isset($ArrayTemp2[0]))
		            	{
			            	$license_picArray[$keytemp]['id']=$ArrayTemp2[0];
			            	$license_picArray[$keytemp]['path']=$ArrayTemp2[1];	
		            	}	
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
						if($value['user_type']=='buyer')
						$profile_picArray[0]['path']=$data['default_profile_pic_buyer'];	
						$profile_picArray[0]['default']=1;	                            							
					}
					

					#format profile_cover_pic
					$profile_cover_picArray=array();
					$delimiter1_profile_cover_pic='|#$(delimiter-1)$#|';
					$delimiter2_profile_cover_pic='|#$(delimiter-2)$#|';
			        $ArrayTemp1=explode($delimiter1_profile_cover_pic,$value['profile_cover_pic']);
		            $ArrayTemp1=array_filter($ArrayTemp1);
		            foreach ($ArrayTemp1 as $keytemp => $valuetemp) 
		            {
		            	$ArrayTemp2=explode($delimiter2_profile_cover_pic,$valuetemp);
		            	if(isset($ArrayTemp2[0]))
		            	{
			            	$profile_cover_picArray[$keytemp]['id']=$ArrayTemp2[0];
			            	$profile_cover_picArray[$keytemp]['path']=$ArrayTemp2[1];	
			            	$profile_cover_picArray[$keytemp]['default']=0;
		            	}	
		            }

		            #set default profile_cover_pic
					if(empty($profile_cover_picArray))
					{
						$profile_cover_picArray[0]['id']='null';
						$profile_cover_picArray[0]['path']=$data['default_profile_cover_pic'];	                            							
						$profile_cover_picArray[0]['default']=1;	                            							
					}
					




					$result[$key]=array(					
								"user_id"=>$value['user_id'],
								"main_email"=>$value['main_email'],
								"contact_post_box"=>$value['contact_post_box'],
								"contact_email"=>$emailArray,
								"contact_phone"=>$phoneArray,
								"contact_map_description"=>$value['contact_map_description'],
								"contact_map_pic"=>$map_picArray,
								"user_license_pic"=>$license_picArray,
								"user_name"=>$value['user_name'],
								"date"=>$value['date'],
								"profile_pic"=>$profile_picArray,
								"profile_cover_pic"=>$profile_cover_picArray,
								"verification"=>$value['verification'],				
								"about"=>$value['about'],									
								"user_type"=>$value['user_type'],									
								"rank"=>$value['rank'],									
								"followers"=>$value['followers'],									
								"is_user_id_follower"=>$value['is_user_id_follower'],									
								);
				}
			}				 
        }  
		   
        return $info = array("status"=>$this->status,
                            "data"=> array('records'=> $result,
										   'count'=>$count,
										   'total_records'=>$total_records,
										   'usersPerVerification'=>$usersPerVerification,
										   'usersPerLocation'=>$usersPerLocation,
										   ),
                            "addition_info"=>$this->addition_info);
   	       
	} 

	public function editUserName($data=array())
	{
	    //reset default variable;
	    $this->reset_defaults();

	    if(!isset($data['user_id'])) $data['user_id']='';
	    if(!isset($data['name'])) $data['name']='';

	    // check if required data is supplied      
	    if(empty($data) || empty($data['user_id']))
	    {
	        $this->addition_info='error 100_1';
	        //$addition_info = "empty user_id";
	        $this->result_info=" Error: An Error Occurred, Try Again "; 
	        $this->fail_result=true;   
	    }

	    // check if name is supplied      
	    if(empty($data['name']))
	    {
	        $this->addition_info='error 101_1';
	        $this->result_info=" Provide a name "; 
	        $this->fail_result=true;   
	    }

	    //Safeguard empty strings
		$emptyField='XXX1234NULLVALUE1234XXX';	
	    foreach($data as $key =>$value){ 
		 	if(empty($value) && !is_numeric($value))
			$data[$key]=$emptyField;	
		};

	    // store in Db
	    if(!$this->fail_result)   
	    {             
			$dbquery1=$this->db->conn_id->prepare("
			UPDATE `user_info` SET
			`user_name`= CASE WHEN :name=:emptyField THEN `user_name` ELSE :name END,
			`date`= `date`
			WHERE `user_id`=:user_id
			");
			$data['user_id']=preg_replace("/[^a-zA-Z0-9]+/","",$data['user_id']);
			$dbquery1->bindParam(":user_id",$data['user_id']);
			$dbquery1->bindParam(":name",$data['name']);
			$dbquery1->bindParam(":emptyField",$emptyField);
			
	                 
	        if(!($dbquery1->execute()))
	        {
	            //print_r($dbquery1->errorInfo());
	            $this->fail_result=true;
	            $this->addition_info='error 102_1';
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
	                                                   ),
	                                  "addition_info"=>$this->addition_info);	    
	}

	public function addDeleteProfileCoverPic($data=array())
	{
	    //reset default variable;
	    $this->reset_defaults();
	    $this->load->library('attach_image');

	    //set defaults
	    $max_number_of_file=1;
	    $file_directory='';
	    $temp_directory=IMAGE_SRC_DIR.'temp/image/';
	    $existingRecords=array();
	    $fileSystemRollBack1=array();
	    $fileSystemRollBack2=array();
	    $fileChange=array();//all files to have their dir changed,whether delete files or add files
	    $toAddFiles=array();
	    $replaceRecordsString='';
	    $delimiter1='|#$(delimiter-1)$#|';
	    $delimiter2='|#$(delimiter-2)$#|';
       
	    if(!isset($data['user_id'])) $data['user_id']='';
	    if(!isset($data['addPic'])) $data['addPic']=array();
	    if(!isset($data['deletePic'])) $data['deletePic']=array();
	    	    if(!isset($data['replace_existing'])) $data['replace_existing']=false; // replace all the existing images with the ones being added
	    if(!isset($data['allow_null_photos'])) $data['allow_null_photos']=false;

	    // check if required data is supplied      
	    if(empty($data) || empty($data['user_id']))
	    {
	        $this->addition_info='error 100_1';
	        //$addition_info = "empty user_id ";
	        $this->result_info=" Error: An Error Occurred, Try Again "; 
	        $this->fail_result=true;   
	    }

	    // collect received pics      
	    if(!$this->fail_result)
	    {   
	        if(empty($data['addPic']) && empty($data['deletePic']))
	        {
		        $this->addition_info='error 101_1';
		        //$addition_info = "empty fields provided";
		        $this->result_info=" Error: An Error Occurred, Try Again "; 
		        $this->fail_result=true;	        	
	        }
	    }

	    // take pics from Db for re-organise
	    if(!$this->fail_result)   
	    {   

			$dbquery1=$this->db->conn_id->prepare("
			SELECT `user_pic` 
			FROM `profile_cover_pic`
			WHERE `user_id`=:user_id
			");
			$data['user_id']=preg_replace("/[^a-zA-Z0-9]+/","",$data['user_id']);
			$dbquery1->bindParam(":user_id",$data['user_id']);
			       
	        if(!($dbquery1->execute()))
	        {
	            //print_r($dbquery1->errorInfo());
	            $this->fail_result=true;
	            $this->addition_info='error 102_1';
	            $this->result_info=" An Error Occurred, Try Again ";
	        }else
	        {
	            $sql_result = $dbquery1->fetch();
	        }
	    }
        

        /* sample pic delimiter
          0|#$(t@ye@e@r@s@u@b)$#|temp/image/0997876787iigkygi6tu|#$(t@ye@e@r@s@u@b)$#|1
                 |#$(t@ye@e@r)$#|
          1|#$(t@ye@e@r@s@u@b)$#|temp/image/0997876787iigkygi6tu|#$(t@ye@e@r@s@u@b)$#|0
                 |#$(t@ye@e@r)$#|
          3|#$(t@ye@e@r@s@u@b)$#|temp/image/0997876787iigkygi6tu|#$(t@ye@e@r@s@u@b)$#|0
        */  

	    //explode collected the pics
	    if(!$this->fail_result && isset($sql_result) && !empty($sql_result['item_pic']))
	    {

            $ArrayTemp1=explode($delimiter1,$sql_result['item_pic']);
            foreach ($ArrayTemp1 as $key => $value) 
            {
            	$ArrayTemp2=explode($delimiter2,$value);
            	$existingRecords[$ArrayTemp2[0]]=$ArrayTemp2[1];
            	
            }

            //remove empty values
            $existingRecords=array_filter($existingRecords);

          	if(((count($existingRecords)-count($data['deletePic']))>=$max_number_of_file) && !$data['replace_existing'] )
          	{
          		$this->fail_result=true;
          		//delete some pics first
	            $this->addition_info='error 103_1 :delete some images first';
	            $this->result_info=" An Error Occurred, Try Again ";
          	}            

	    }

	    //replace existing
	    if(!$this->fail_result && $data['replace_existing'] && count($existingRecords)>0)
	    {
	    	/* you delete pics with received id '$data[addpics]' from the existing pics 'collectedpics'
			   then make a string 'addpicstring' out of it 'collectedpics' with delimiters to store in Db
			*/         	
		
          	foreach ($existingRecords as $key => $value) {

    	      		array_push($fileChange, array(	'path_new'=>$temp_directory,
	      											'path_old'=> IMAGE_SRC_DIR.$value));    	      		          			
          			unset($existingRecords[$key]);	
          	}

          	#rearrange the array
      		$existingRecords=array_values($existingRecords);
	    }

	    //create permanent folder
	    if(!$this->fail_result)
	    {
	    	$temp_data=array(
	    					'path'=>IMAGE_SRC_DIR.'media/user/'.$data['user_id'].'/image/profile/',
	    					'user_id'=>$data['user_id'],
	    					'create_user_dir'=>true,
	    				);
			$file_directory=$this->attach_image->create_dir_by_week($temp_data);
	    	
	    	if(!$file_directory['status'])
	    	{
	    		$this->fail_result=true;
	    		$this->addition_info='error 106_1';
	    		$this->result_info='Try again, an error Occurred';
	    	}
	    }

	    //delete/re-organise the pics
	    if(!$this->fail_result && !empty($data['deletePic']) && is_array($data['deletePic']) && count($data['deletePic'])>0)
	    {
	    	/* you delete pics with received id '$data[addpics]' from the existing pics 'collectedpics'
			   then make a string 'addpicstring' out of it 'collectedpics' with delimiters to store in Db
			*/
          	$i=0;
          	#remove empty values
          	$data['deletePic'] = array_filter($data['deletePic'],'is_numeric');          	
		
          	while (($i<count($data['deletePic'])))
          	{
          		if(isset($existingRecords[$data['deletePic'][$i]]))
          		{	
    	      		array_push($fileChange, array(	'path_new'=>$temp_directory,
	      											'path_old'=>IMAGE_SRC_DIR.$existingRecords[$data['deletePic'][$i]]));
    	      		          			
          			unset($existingRecords[$data['deletePic'][$i]]);
    	      	}

    	      	$i++;	
          	}

          	#rearrange the array
      		$existingRecords=array_values($existingRecords);

      		//prevent deleting all pic
      		if(empty($existingRecords) && !$data['allow_null_photos'])
      		{
      			$this->fail_result=true;
      			$this->addition_info='error 107_1';
      			$this->result_info="Can not delete all photos";
      		}

	    }

	    //add/re-organise the pics
	    if(!$this->fail_result  && !empty($data['addPic']) && is_array($data['addPic']) && count($data['addPic'])>0)
	    {
	    	/* you add received pics '$addPics' to the existing pics 'collectedpics'
			   then make a string 'addPicString' out of it 'collectedpics' with delimiters to store in Db
			   limit the pics to only 3
			*/
          	$i=0;$v=0;
          	#remove empty values
          	$data['addPic'] =array_filter($data['addPic']);
          	$data['addPic'] =array_values($data['addPic']);
          	
          	while (($i<$max_number_of_file-count($existingRecords)) && ($v<count($data['addPic'])))
          	{
          		array_push($existingRecords,substr(strstr($file_directory['data']['directory'], IMAGE_SRC_DIR), strlen(IMAGE_SRC_DIR)).$data['addPic'][$i]);
         		
	      		array_push($fileChange, array(	'path_new'=>$file_directory['data']['directory'].$data['addPic'][$i],
	      										'path_old'=>$temp_directory.$data['addPic'][$i]	));
    	      	
    	      	$i++;$v++;	
          	}

          	#remove duplicate values
          	$existingRecords =array_unique($existingRecords);
          	#remove empty values
          	$existingRecords =array_filter($existingRecords);
          	$fileChange =array_filter($fileChange);
          	#rearrange the array
      		$existingRecords=array_values($existingRecords);

          	if(empty($existingRecords))
          	{
          		$this->fail_result=true;
          		//empty 
	            $this->addition_info='error 108_1';
	            $this->result_info=" An Error Occurred, Try Again ";
          	}
	    }


	    //check if paths exists
	    if(!$this->fail_result)
	    {
	    	$i=0;
	    	while ($i<count($fileChange) && !$this->fail_result) 
	    	{
					$v=0;
					do
					{
						$fileIndexArray =array();
						if($v==0)
						{	
							$fileIndexArray['extension']='.jpg';
						}elseif($v==1)
						{	
							$fileIndexArray['extension']='_t.jpg';
						}elseif($v==2)
						{	
							$fileIndexArray['extension']='_m.jpg';
						}

						//	file transfer consist of two paths i.e. new and old path. so old path has to exist always
						if(!file_exists($fileChange[$i]['path_old'].$fileIndexArray['extension']))
			    		{
			    			if(empty($this->addition_info))
			    			$this->addition_info="error 109_1:".$i.":path_old:".$fileIndexArray['extension'];
			    			
			    			$this->fail_result=true;
			    		}
			    		$v++;

		    		}while ( $v<3 && !$this->fail_result);

	    		$i++;
	    	}

	    }	


	    //implode collected the pics
	    if(!$this->fail_result)
	    {
            foreach ($existingRecords as $key => $value) {
            	$value=$key.$delimiter2.$value;
            
            	$existingRecords[$key]=$value;
            }    
            $replaceRecordsString=implode($delimiter1,$existingRecords);
        
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

	    // store in Db
	    $this->db->conn_id->beginTransaction();
	    if(!$this->fail_result)   
	    {             
			$dbquery2=$this->db->conn_id->prepare("
							INSERT INTO `profile_cover_pic`(`user_id`,`user_pic`) 
							VALUES(:user_id,:user_pic)
							ON DUPLICATE KEY UPDATE `user_pic`=:user_pic
			");
			$data['user_id']=preg_replace("/[^a-zA-Z0-9]+/","",$data['user_id']);
			$dbquery2->bindParam(":user_id",$data['user_id']);
			$dbquery2->bindParam(":user_pic",$replaceRecordsString);
			$dbquery2->bindParam(":emptyField",$emptyField);                 
	        
	        if(!($dbquery2->execute()))
	        {
	           // print_r($dbquery2->errorInfo());
	            $this->fail_result=true;
	            $this->addition_info='error 110_1';
	            $this->result_info=" An Error Occurred, Try Again ";
	        }
	    }	

	    //move files from temporary dir to permanent dir
	    ##add pics
		if(!$this->fail_result)
		{					
			$i=0;
			while($i<count($fileChange) && !$this->fail_result)
			{   					
				$v=0;
				do
				{
					$fileIndexArray =array();
					if($v==0)
					{	
						$fileIndexArray['extension']='.jpg';
					}elseif($v==1)
					{	
						$fileIndexArray['extension']='_t.jpg';
					}elseif($v==2)
					{	
						$fileIndexArray['extension']='_m.jpg';
					}	

					if(!$this->fail_result)
					{	
					
						if((rename($fileChange[$i]['path_old'].$fileIndexArray['extension'],$fileChange[$i]['path_new'].$fileIndexArray['extension']))) 
						{		 
						    array_push($fileSystemRollBack2,array(  'path_old'=>$fileChange[$i]['path_old'].$fileIndexArray['extension'],
																	'path_new'=>$fileChange[$i]['path_new'].$fileIndexArray['extension'],
																));
						}else
						{
							$this->addition_info="error 111_1:".$i.":".$fileIndexArray['extension'];
							$this->result_info="Sorry An Error Occurred,Try Again";
							$this->fail_result=true;
						}
					
					}

					$v++;
				}while ($v<3 && !$this->fail_result);
				$i++;
			};
				
		}
	
		if(!$this->fail_result)
		{
			$this->db->conn_id->commit();
			$this->status=true;
			$this->result_info="Photo edited ";
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
			$this->result_info="Sorry An Error Occurred,Try Again";		
		}

		//revert file changes if changes happened
		if($this->fail_result)
		{     
			foreach($fileSystemRollBack2 as $key => $value)
			{
				rename($value['path_new'],$value['path_old']);

			};
		}				

	    //return results
        return $info = array("status"=>$this->status,
                               "data"=> array('result_info'=> $this->result_info,
                                               ),
                              "addition_info"=>$this->addition_info);
	}

	public function addDeleteProfilePic($data=array())
	{
	    //reset default variable;
	    $this->reset_defaults();
	    $this->load->library('attach_image');

	    //set defaults
	    $max_number_of_file=1;
	    $file_directory='';
	    $temp_directory=IMAGE_SRC_DIR.'temp/image/';
	    $existingRecords=array();
	    $fileSystemRollBack1=array();
	    $fileSystemRollBack2=array();
	    $fileChange=array();//all files to have their dir changed,whether delete files or add files
	    $toAddFiles=array();
	    $replaceRecordsString='';
	    $delimiter1='|#$(delimiter-1)$#|';
	    $delimiter2='|#$(delimiter-2)$#|';
       
	    if(!isset($data['user_id'])) $data['user_id']='';
	    if(!isset($data['addPic'])) $data['addPic']=array();
	    if(!isset($data['deletePic'])) $data['deletePic']=array();
	    if(!isset($data['allow_null_photos'])) $data['allow_null_photos']=false; // can allow deletion of all images within it without an add image
	    if(!isset($data['replace_existing'])) $data['replace_existing']=false; // replace all the existing images with the ones being added


	    // check if required data is supplied      
	    if(empty($data) || empty($data['user_id']))
	    {
	        $this->addition_info='error 100_1 empty user_id';
	        //$addition_info = "empty user_id ";
	        $this->result_info=" Error: An Error Occurred, Try Again "; 
	        $this->fail_result=true;   
	    }

	    // collect received pics      
	    if(!$this->fail_result)
	    {   
	        if(empty($data['addPic']) && empty($data['deletePic']))
	        {
		        $this->addition_info='error 101_2 empty fields';
		        //$addition_info = "empty fields provided";
		        $this->result_info=" Error: An Error Occurred, Try Again "; 
		        $this->fail_result=true;	        	
	        }

	    }

	    // take pics from Db for re-organise
	    if(!$this->fail_result)   
	    {   

			$dbquery1=$this->db->conn_id->prepare("
			SELECT `user_pic` 
			FROM `profile_pic`
			WHERE `user_id`=:user_id
			");
			$data['user_id']=preg_replace("/[^a-zA-Z0-9]+/","",$data['user_id']);
			$dbquery1->bindParam(":user_id",$data['user_id']);
			       
	        if(!($dbquery1->execute()))
	        {
	            //print_r($dbquery1->errorInfo());
	            $this->fail_result=true;
	            $this->addition_info='error 102_1';
	            $this->result_info=" An Error Occurred, Try Again ";
	        }else
	        {
	            $sql_result = $dbquery1->fetch();
	        }
	    }
              

        /* sample pic delimiter
          0|#$(t@ye@e@r@s@u@b)$#|temp/image/0997876787iigkygi6tu|#$(t@ye@e@r@s@u@b)$#|1
                 |#$(t@ye@e@r)$#|
          1|#$(t@ye@e@r@s@u@b)$#|temp/image/0997876787iigkygi6tu|#$(t@ye@e@r@s@u@b)$#|0
                 |#$(t@ye@e@r)$#|
          3|#$(t@ye@e@r@s@u@b)$#|temp/image/0997876787iigkygi6tu|#$(t@ye@e@r@s@u@b)$#|0
        */  

	    //explode collected the pics
	    if(!$this->fail_result && isset($sql_result) && !empty($sql_result['user_pic']))
	    {

            $ArrayTemp1=explode($delimiter1,$sql_result['user_pic']);
            foreach ($ArrayTemp1 as $key => $value) 
            {
            	$ArrayTemp2=explode($delimiter2,$value);
            	$existingRecords[$ArrayTemp2[0]]=$ArrayTemp2[1];
            	
            }

            //remove empty values
            $existingRecords=array_filter($existingRecords);

          	if(((count($existingRecords)-count($data['deletePic']))>=$max_number_of_file) && !$data['replace_existing'] )
          	{
          		$this->fail_result=true;
          		//delete some pics first
	            $this->addition_info='error 103_1 :delete some images first';
	            $this->result_info=" An Error Occurred, Try Again ";
          	}            

	    }


	    //replace existing
	    if(!$this->fail_result && $data['replace_existing'] && count($existingRecords)>0)
	    {
	    	/* you delete pics with received id '$data[addpics]' from the existing pics 'collectedpics'
			   then make a string 'addpicstring' out of it 'collectedpics' with delimiters to store in Db
			*/         	
		
          	foreach ($existingRecords as $key => $value) {

    	      		array_push($fileChange, array(	'path_new'=>$temp_directory,
	      											'path_old'=> IMAGE_SRC_DIR.$value));    	      		          			
          			unset($existingRecords[$key]);	
          	}

          	#rearrange the array
      		$existingRecords=array_values($existingRecords);
	    }


	    //create permanent folder
	    if(!$this->fail_result)
	    {
	    	$temp_data=array(
	    					'path'=>IMAGE_SRC_DIR.'media/user/'.$data['user_id'].'/image/profile/',
	    					'user_id'=>$data['user_id'],
	    					'create_user_dir'=>true,
	    				);
			$file_directory=$this->attach_image->create_dir_by_week($temp_data);
	    	
	    	if(!$file_directory['status'])
	    	{
	    		$this->fail_result=true;
	    		$this->addition_info='error 106_1';
	    		$this->result_info='Try again, an error Occurred';
	    	}
	    }

	    //delete/re-organise the pics
	    if(!$this->fail_result && !empty($data['deletePic']) && is_array($data['deletePic']) && count($data['deletePic'])>0)
	    {
	    	/* you delete pics with received id '$data[addpics]' from the existing pics 'collectedpics'
			   then make a string 'addpicstring' out of it 'collectedpics' with delimiters to store in Db
			*/
          	$i=0;
          	#remove empty values
          	$data['deletePic'] = array_filter($data['deletePic'],'is_numeric');          	
		
          	while (($i<count($data['deletePic'])))
          	{
          		if(isset($existingRecords[$data['deletePic'][$i]]))
          		{	
    	      		array_push($fileChange, array(	'path_new'=>$temp_directory,
	      											'path_old'=>IMAGE_SRC_DIR.$existingRecords[$data['deletePic'][$i]]));
    	      		          			
          			unset($existingRecords[$data['deletePic'][$i]]);
    	      	}

    	      	$i++;	
          	}

          	#rearrange the array
      		$existingRecords=array_values($existingRecords);

      		//prevent deleting all pic
      		if(empty($existingRecords) && !$data['allow_null_photos'])
      		{
      			$this->fail_result=true;
      			$this->addition_info='error 107_1';
      			$this->result_info="Can not delete all photos";
      		}

	    }



	    //add/re-organise the pics
	    if(!$this->fail_result  && !empty($data['addPic']) && is_array($data['addPic']) && count($data['addPic'])>0)
	    {
	    	/* you add received pics '$addPics' to the existing pics 'collectedpics'
			   then make a string 'addPicString' out of it 'collectedpics' with delimiters to store in Db
			   limit the pics to only 3
			*/
          	$i=0;$v=0;
          	#remove empty values
          	$data['addPic'] =array_filter($data['addPic']);
          	$data['addPic'] =array_values($data['addPic']);
          	
          	while (($i<$max_number_of_file-count($existingRecords)) && ($v<count($data['addPic'])))
          	{
          		array_push($existingRecords,substr(strstr($file_directory['data']['directory'], IMAGE_SRC_DIR), strlen(IMAGE_SRC_DIR)).$data['addPic'][$i]);
         		
	      		array_push($fileChange, array(	'path_new'=>$file_directory['data']['directory'].$data['addPic'][$i],
	      										'path_old'=>$temp_directory.$data['addPic'][$i]	));
    	      	
    	      	$i++;$v++;	
          	}

          	#remove duplicate values
          	$existingRecords =array_unique($existingRecords);
          	#remove empty values
          	$existingRecords =array_filter($existingRecords);
          	$fileChange =array_filter($fileChange);
          	#rearrange the array
      		$existingRecords=array_values($existingRecords);

          	if(empty($existingRecords))
          	{
          		$this->fail_result=true;
          		//empty 
	            $this->addition_info='error 108_1';
	            $this->result_info=" An Error Occurred, Try Again ";
          	}
	    }


	    //check if paths exists
	    if(!$this->fail_result)
	    {
	    	$i=0;
	    	while ($i<count($fileChange) && !$this->fail_result) 
	    	{
					$v=0;
					do
					{
						$fileIndexArray =array();
						if($v==0)
						{	
							$fileIndexArray['extension']='.jpg';
						}elseif($v==1)
						{	
							$fileIndexArray['extension']='_t.jpg';
						}elseif($v==2)
						{	
							$fileIndexArray['extension']='_m.jpg';
						}

						//	file transfer consist of two paths i.e. new and old path. so old path has to exist always
						if(!file_exists($fileChange[$i]['path_old'].$fileIndexArray['extension']))
			    		{
			    			if(empty($this->addition_info))
			    			$this->addition_info="error 109_1:".$i.":path_old:".$fileIndexArray['extension'];
			    			
			    			$this->fail_result=true;
			    		}
			    		$v++;

		    		}while ( $v<3 && !$this->fail_result);

	    		$i++;
	    	}

	    }	


	    //implode collected the pics
	    if(!$this->fail_result)
	    {
            foreach ($existingRecords as $key => $value) {
            	$value=$key.$delimiter2.$value;
            
            	$existingRecords[$key]=$value;
            }    
            $replaceRecordsString=implode($delimiter1,$existingRecords);
        
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

	    // store in Db
	    $this->db->conn_id->beginTransaction();
	    if(!$this->fail_result)   
	    {             
			$dbquery2=$this->db->conn_id->prepare("
							INSERT INTO `profile_pic`(`user_id`,`user_pic`) 
							VALUES(:user_id,:user_pic)
							ON DUPLICATE KEY UPDATE `user_pic`=:user_pic
			");
			$data['user_id']=preg_replace("/[^a-zA-Z0-9]+/","",$data['user_id']);
			$dbquery2->bindParam(":user_id",$data['user_id']);
			$dbquery2->bindParam(":user_pic",$replaceRecordsString);
			$dbquery2->bindParam(":emptyField",$emptyField);                 
	        
	        if(!($dbquery2->execute()))
	        {
	           // print_r($dbquery2->errorInfo());
	            $this->fail_result=true;
	            $this->addition_info='error 110_1';
	            $this->result_info=" An Error Occurred, Try Again ";
	        }
	    }	


	    //move files from temporary dir to permanent dir
		if(!$this->fail_result)
		{					
			$i=0;
			while($i<count($fileChange) && !$this->fail_result)
			{   					
				$v=0;
				do
				{
					$fileIndexArray =array();
					if($v==0)
					{	
						$fileIndexArray['extension']='.jpg';
					}elseif($v==1)
					{	
						$fileIndexArray['extension']='_t.jpg';
					}elseif($v==2)
					{	
						$fileIndexArray['extension']='_m.jpg';
					}	

					if(!$this->fail_result)
					{	
					
						if((rename($fileChange[$i]['path_old'].$fileIndexArray['extension'],$fileChange[$i]['path_new'].$fileIndexArray['extension']))) 
						{		 
						    array_push($fileSystemRollBack2,array(  'path_old'=>$fileChange[$i]['path_old'].$fileIndexArray['extension'],
																	'path_new'=>$fileChange[$i]['path_new'].$fileIndexArray['extension'],
																));
						}else
						{
							$this->addition_info="error 111_1:".$i.":".$fileIndexArray['extension'];
							$this->result_info="Sorry An Error Occurred,Try Again";
							$this->fail_result=true;
						}
					
					}

					$v++;
				}while ($v<3 && !$this->fail_result);
				$i++;
			};
				
		}
	
		if(!$this->fail_result)
		{
			$this->db->conn_id->commit();
			$this->status=true;
			$this->result_info="Photo edited ";
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

		//revert file changes if changes happened
		if($this->fail_result)
		{     
			foreach($fileSystemRollBack2 as $key => $value)
			{
				rename($value['path_new'],$value['path_old']);

			};
		}	

	    //return results
         $info = array("status"=>$this->status,
                               "data"=> array('result_info'=> $this->result_info,
                                               ),
                              "addition_info"=>$this->addition_info);
			

		return $info;
	}

	public function editPost($data=array())
	{
	    //reset default variable;
	    $this->reset_defaults();

	    if(!isset($data['user_id'])) $data['user_id']='';
	    if(!isset($data['post'])) $data['post']='';

	    // check if required data is supplied      
	    if(empty($data) || empty($data['user_id']))
	    {
	        $this->addition_info='error 100_1';
	        //$addition_info = "empty user_id";
	        $this->result_info=" Error: An Error Occurred, Try Again "; 
	        $this->fail_result=true;   
	    }

	    // store in Db
	    if(!$this->fail_result)   
	    {             
			$dbquery1=$this->db->conn_id->prepare("
							INSERT INTO `contact_post`(`user_id`,`post_box`) 
							VALUES(:user_id,:post)
							ON DUPLICATE KEY UPDATE `post_box`=:post
			");
			$data['user_id']=preg_replace("/[^a-zA-Z0-9]+/","",$data['user_id']);
			$dbquery1->bindParam(":user_id",$data['user_id']);
			$dbquery1->bindParam(":post",$data['post']);
			
	                 
	        if(!($dbquery1->execute()))
	        {
	            //print_r($dbquery1->errorInfo());
	            $this->fail_result=true;
	            $this->addition_info='error 102_1';
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
	                                                   ),
	                                  "addition_info"=>$this->addition_info);   
	}


	public function editUserDescription($data=array())
	{
	    //reset default variable;
	    $this->reset_defaults();

	    if(!isset($data['user_id'])) $data['user_id']='';
	    if(!isset($data['about'])) $data['about']='';
	    

	    // check if required data is supplied      
	    if(empty($data) || empty($data['user_id']))
	    {
	        $this->addition_info='error 100_1';
	        //$addition_info = "empty user_id || email";
	        $this->result_info=" Error: An Error Occurred, Try Again "; 
	        $this->fail_result=true;   
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

	    // store in Db
	    if(!$this->fail_result)   
	    {             
			$dbquery1=$this->db->conn_id->prepare("
			UPDATE `user_info` SET
			`about`= CASE WHEN :about=:emptyField THEN `about` ELSE :about END,
			`date`= `date`
			WHERE `user_id`=:user_id
			");
			$data['user_id']=preg_replace("/[^a-zA-Z0-9]+/","",$data['user_id']);
			$dbquery1->bindParam(":user_id",$data['user_id']);
			$dbquery1->bindParam(":about",$data['about']);
	        $dbquery1->bindParam(":emptyField",$emptyField);     

	        if(!($dbquery1->execute()))
	        {
	            //print_r($dbquery1->errorInfo());
	            $this->fail_result=true;
	            $this->addition_info='error 101_1';
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
	                                                   ),
	                                  "addition_info"=>$this->addition_info);
	}
    

	public function addDeleteEmail($data=array())
	{
	    //reset default variable;
	    $this->reset_defaults();

	    //set defaults
	    $existingRecords=array();
	    $replaceRecordsString='';
	    $delimiter1='|#$(deli@m@iter-1)$#|';
	    $delimiter2='|#$(deli@m@iter-2)$#|';

	    if(!isset($data['user_id'])) $data['user_id']=array();
	    if(!isset($data['addEmail'])) $data['addEmail']=array();
	    if(!isset($data['deleteEmail'])) $data['deleteEmail']=array();

	    // check if required data is supplied      
	    if(empty($data) || empty($data['user_id']))
	    {
	        $this->addition_info='error 100_1';
	        //$addition_info = "empty user_id || email";
	        $this->result_info=" Error: An Error Occurred, Try Again "; 
	        $this->fail_result=true;   
	    }

	    // collect received email      
	    if(!$this->fail_result)
	    {   
	        if(empty($data['addEmail']) && empty($data['deleteEmail']))
	        {
		        $this->addition_info='error 101_2 : no emails provided to add or delete';
		        //$addition_info = "empty email provided";
		        $this->result_info=" Error: An Error Occurred, Try Again "; 
		        $this->fail_result=true;
		        $this->status = true;	        	
	        }
	    }

	    // take email from Db for re-organise
	    if(!$this->fail_result)   
	    {             
			$dbquery1=$this->db->conn_id->prepare("
			SELECT `email` 
			FROM `contact_email`
			WHERE `user_id`=:user_id
			");
			$data['user_id']=preg_replace("/[^a-zA-Z0-9]+/","",$data['user_id']);
			$dbquery1->bindParam(":user_id",$data['user_id']);
			       
	        if(!($dbquery1->execute()))
	        {
	            //print_r($dbquery1->errorInfo());
	            $this->fail_result=true;
	            $this->addition_info='error 102_1';
	            $this->result_info=" An Error Occurred, Try Again ";
	        }else
	        {
	            $sql_result = $dbquery1->fetch();
	        }
	    }
        
        /* sample email delimiter
          0|#$(t@ye@e@r@s@u@b)$#|tiyachamdimba@gmail.com
                 |#$(t@ye@e@r)$#|
          1|#$(t@ye@e@r@s@u@b)$#|tiyachamdimba@gmail.com
                 |#$(t@ye@e@r)$#|
          2|#$(t@ye@e@r@s@u@b)$#|tiyachamdimba@gmail.com
        */  

	    //explode collected the emails
	    if(!$this->fail_result && isset($sql_result) && !empty($sql_result['email']))
	    {

            $ArrayTemp1=explode($delimiter1,$sql_result['email']);
            foreach ($ArrayTemp1 as $key => $value) {
            	$ArrayTemp2=explode($delimiter2,$value);
            	$existingRecords[$ArrayTemp2[0]]=$ArrayTemp2[1];
            }

            //remove empty values
            $existingRecords=array_filter($existingRecords);

          	if(count($existingRecords)>=3)
          	{
          		$this->fail_result=true;
          		//delete some emails first
	            $this->addition_info='error 103_1';
	            $this->result_info=" An Error Occurred, Try Again ";
          	}            

	    }

	    //delete/re-organise the emails
	    if(!$this->fail_result && !empty($data['deleteEmail']) && is_array($data['deleteEmail']) && count($data['deleteEmail'])>0)
	    {
	    	/* you delete emails with received id '$data[addEmails]' from the existing emails 'collectedEmails'
			   then make a string 'addEmailString' out of it 'collectedEmails' with delimiters to store in Db
			   limit the emails to only 3
			*/
          	$i=0;
          	$x=count($data['deleteEmail']);

          	while (($i<$x))
          	{
          		if(isset($existingRecords[$data['deleteEmail'][$i]]))
          		unset($existingRecords[$data['deleteEmail'][$i]]);
    	      	$i++;	
          	}

          	#rearrange the array
      		$existingRecords=array_values($existingRecords);
	    }

	    //add/re-organise the emails
	    if(!$this->fail_result  && !empty($data['addEmail']) && is_array($data['addEmail']) && count($data['addEmail'])>0)
	    {
	    	/* you add received emails '$addEmails' to the existing emails 'collectedEmails'
			   then make a string 'addEmailString' out of it 'collectedEmails' with delimiters to store in Db
			   limit the emails to only 3
			*/
          	$i=0;$v=0;
          	$z=count($existingRecords);
          	$x=count($data['addEmail']);

          	while (($i<3-$z) && ($v<$x))
          	{
          		array_push($existingRecords,$data['addEmail'][$i]);
    	      	
    	      	$i++;$v++;	
          	}

          	#remove duplicate values
          	$existingRecords =array_unique($existingRecords);
          	#remove empty values
          	$existingRecords =array_filter($existingRecords);
          	#rearrange the array
      		$existingRecords=array_values($existingRecords);

          	if(empty($existingRecords))
          	{
          		$this->fail_result=true;
          		//empty 
	            $this->addition_info='error 104_1';
	            $this->result_info=" An Error Occurred, Try Again ";
          	}
	    }

	    //implode collected the emails
	    if(!$this->fail_result)
	    {
            foreach ($existingRecords as $key => $value) {
            	$value=$key.$delimiter2.$value;
            	$existingRecords[$key]=$value;
            }    
            $replaceRecordsString=implode($delimiter1,$existingRecords);

            

	    }

	    // store in Db
	    if(!$this->fail_result)   
	    {             
			$dbquery2=$this->db->conn_id->prepare("
							INSERT INTO `contact_email`(`user_id`,`email`) 
							VALUES(:user_id,:email)
							ON DUPLICATE KEY UPDATE `email`=:email
			");
			$data['user_id']=preg_replace("/[^a-zA-Z0-9]+/","",$data['user_id']);
			$dbquery2->bindParam(":user_id",$data['user_id']);
			$dbquery2->bindParam(":email",$replaceRecordsString);
	                 
	        if(!($dbquery2->execute()))
	        {
	           // print_r($dbquery2->errorInfo());
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
	                                                   ),
	                                  "addition_info"=>$this->addition_info);
	}
    
	public function editMap($data=array())
	{
	    //reset default variable;
	    $this->reset_defaults();
	    $this->load->library('attach_image');

	    //set defaults
	    $max_number_of_file=4;
	    $file_directory='';
	    $temp_directory=IMAGE_SRC_DIR.'temp/image/';
	    $existingRecords=array();
	    $fileSystemRollBack1=array();
	    $fileSystemRollBack2=array();
	    $fileChange=array();//all files to have their dir changed,whether delete files or add files
	    $toAddFiles=array();
	    $replaceRecordsString='';
	    $delimiter1='|#$(delimiter-1)$#|';
	    $delimiter2='|#$(delimiter-2)$#|';

	    if(!isset($data['user_id'])) $data['user_id']=array();
	    if(!isset($data['addPic'])) $data['addPic']=array();
	    if(!isset($data['map_description'])) $data['map_description']='';
	    if(!isset($data['deletePic'])) $data['deletePic']=array();
	    if(!isset($data['replace_existing'])) $data['replace_existing']=false;
	    if(!isset($data['allow_null_photos'])) $data['allow_null_photos']=false;


	    // check if required data is supplied      
	    if(empty($data) || empty($data['user_id']))
	    {
	        $this->addition_info='error 100_1 empty user_id';
	        //$addition_info = "empty user_id ";
	        $this->result_info=" Error: An Error Occurred, Try Again "; 
	        $this->fail_result=true;   
	    }

	    // take pics from Db for re-organise
	    if(!$this->fail_result)   
	    {             
			$dbquery1=$this->db->conn_id->prepare("
			SELECT `map_pic` 
			FROM `contact_map`
			WHERE `user_id`=:user_id
			");
			$data['user_id']=preg_replace("/[^a-zA-Z0-9]+/","",$data['user_id']);
			$dbquery1->bindParam(":user_id",$data['user_id']);
			       
	        if(!($dbquery1->execute()))
	        {
	            //print_r($dbquery1->errorInfo());
	            $this->fail_result=true;
	            $this->addition_info='error 102_1 : db error';
	            $this->result_info=" An Error Occurred, Try Again ";
	        }else
	        {
	            $sql_result = $dbquery1->fetch();
	        }
	    }
        

        /* sample pic delimiter
          0|#$(t@ye@e@r@s@u@b)$#|temp/image/0997876787iigkygi6tu
                 |#$(t@ye@e@r)$#|
          1|#$(t@ye@e@r@s@u@b)$#|temp/image/0997876787iigkygi6tu
                 |#$(t@ye@e@r)$#|
          3|#$(t@ye@e@r@s@u@b)$#|temp/image/0997876787iigkygi6tu
        */  

	    //explode collected the pics
	    if(!$this->fail_result && isset($sql_result) && !empty($sql_result['map_pic']))
	    {

            $ArrayTemp1=explode($delimiter1,$sql_result['map_pic']);
            foreach ($ArrayTemp1 as $key => $value) {
            	$ArrayTemp2=explode($delimiter2,$value);
            	$existingRecords[$ArrayTemp2[0]]=$ArrayTemp2[1];
            }

            //remove empty values
            $existingRecords=array_filter($existingRecords);


          	if(((count($existingRecords)-count($data['deletePic']))>=$max_number_of_file) && !$data['replace_existing'] )
          	{
          		$this->fail_result=true;
          		//delete some pics first
	            $this->addition_info='error 103_1 :delete some images first';
	            $this->result_info=" An Error Occurred, Try Again ";
          	}            

	    }

	    //replace existing
	    if(!$this->fail_result && $data['replace_existing'] && count($existingRecords)>0)
	    {
	    	/* you delete pics with received id '$data[addpics]' from the existing pics 'collectedpics'
			   then make a string 'addpicstring' out of it 'collectedpics' with delimiters to store in Db
			*/         	
		
          	foreach ($existingRecords as $key => $value) {

    	      		array_push($fileChange, array(	'path_new'=>$temp_directory,
	      											'path_old'=> IMAGE_SRC_DIR.$value));    	      		          			
          			unset($existingRecords[$key]);	
          	}

          	#rearrange the array
      		$existingRecords=array_values($existingRecords);
	    }

	    //create permanent folder
	    if(!$this->fail_result)
	    {
	    	$temp_data=array(
	    					'path'=>IMAGE_SRC_DIR.'media/user/'.$data['user_id'].'/image/profile/',
	    					'user_id'=>$data['user_id'],
	    					'create_user_dir'=>true,
	    				);
			$file_directory=$this->attach_image->create_dir_by_week($temp_data);
	    	
	    	if(!$file_directory['status'])
	    	{
	    		$this->fail_result=true;
	    		$this->addition_info='error 106_1';
	    		$this->result_info='Try again, an error Occurred';
	    	}
	    }

	    //delete/re-organise the pics
	    if(!$this->fail_result && !empty($data['deletePic']) && is_array($data['deletePic']) && count($data['deletePic'])>0)
	    {
	    	/* you delete pics with received id '$data[addpics]' from the existing pics 'collectedpics'
			   then make a string 'addpicstring' out of it 'collectedpics' with delimiters to store in Db
			*/
          	$i=0;
          	#remove empty values
          	$data['deletePic'] = array_filter($data['deletePic'],'is_numeric');          	

          	while (($i<count($data['deletePic'])))
          	{
          		if(isset($existingRecords[$data['deletePic'][$i]]))
          		{	
    	      		array_push($fileChange, array(	'path_new'=>$temp_directory,
	      											'path_old'=>IMAGE_SRC_DIR.$existingRecords[$data['deletePic'][$i]]));
    	      		          			
          			unset($existingRecords[$data['deletePic'][$i]]);
    	      	}

    	      	$i++;	
          	}

          	#rearrange the array
      		$existingRecords=array_values($existingRecords);

      		//prevent deleting all pic
      		if(empty($existingRecords) && !$data['allow_null_photos'])
      		{
      			$this->fail_result=true;
      			$this->addition_info='error 107_1';
      			$this->result_info="Can not delete all photos";
      		}

	    }

	    //add/re-organise the pics
	    if(!$this->fail_result  && !empty($data['addPic']) && is_array($data['addPic']) && count($data['addPic'])>0)
	    {
	    	/* you add received pics '$addPics' to the existing pics 'collectedpics'
			   then make a string 'addPicString' out of it 'collectedpics' with delimiters to store in Db
			   limit the pics to only 3
			*/
          	$i=0;$v=0;
          	#remove empty values
          	$data['addPic'] =array_filter($data['addPic']);
          	$data['addPic'] =array_values($data['addPic']);
          	
          	while (($i<$max_number_of_file-count($existingRecords)) && ($v<count($data['addPic'])))
          	{
          		array_push($existingRecords,substr(strstr($file_directory['data']['directory'], IMAGE_SRC_DIR), strlen(IMAGE_SRC_DIR)).$data['addPic'][$i]);
         		
	      		array_push($fileChange, array(	'path_new'=>$file_directory['data']['directory'].$data['addPic'][$i],
	      										'path_old'=>$temp_directory.$data['addPic'][$i]	));
    	      	
    	      	$i++;$v++;	
          	}

          	#remove duplicate values
          	$existingRecords =array_unique($existingRecords);
          	#remove empty values
          	$existingRecords =array_filter($existingRecords);
          	$fileChange =array_filter($fileChange);
          	#rearrange the array
      		$existingRecords=array_values($existingRecords);

          	if(empty($existingRecords))
          	{
          		$this->fail_result=true;
          		//empty 
	            $this->addition_info='error 104_1';
	            $this->result_info=" An Error Occurred, Try Again ";
          	}
	    }

	    //check if paths exists
	    if(!$this->fail_result)
	    {
	    	$i=0;
	    	while ($i<count($fileChange) && !$this->fail_result) 
	    	{
					$v=0;
					do
					{
						$fileIndexArray =array();
						if($v==0)
						{	
							$fileIndexArray['extension']='.jpg';
						}elseif($v==1)
						{	
							$fileIndexArray['extension']='_t.jpg';
						}elseif($v==2)
						{	
							$fileIndexArray['extension']='_m.jpg';
						}

						//	file transfer consist of two paths i.e. new and old path. so old path has to exist always
						if(!file_exists($fileChange[$i]['path_old'].$fileIndexArray['extension']))
			    		{
			    			if(empty($this->addition_info))
			    			$this->addition_info="error 105_1:".$i.":path_old:".$fileIndexArray['extension'];
			    			
			    			$this->fail_result=true;
			    		}
			    		$v++;

		    		}while ( $v<3 && !$this->fail_result);

	    		$i++;
	    	}

	    }	

	    //implode collected the pics
	    if(!$this->fail_result)
	    {
            foreach ($existingRecords as $key => $value) {
            	$value=$key.$delimiter2.$value;
            	$existingRecords[$key]=$value;
            }    
            $replaceRecordsString=implode($delimiter1,$existingRecords);
        
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

	    // store in Db
	    $this->db->conn_id->beginTransaction();
	    if(!$this->fail_result)   
	    {             
			$dbquery2=$this->db->conn_id->prepare("
							INSERT INTO `contact_map`(`user_id`,`map_pic`,`map_description`) 
							VALUES(
								:user_id,
								:map_pic,
								CASE WHEN :map_description=:emptyField THEN NULL ELSE :map_description END
								)
							ON DUPLICATE KEY UPDATE 
							`map_pic`=:map_pic,
							`map_description`= CASE WHEN :map_description=:emptyField THEN `map_description` ELSE :map_description END

			");
			$data['user_id']=preg_replace("/[^a-zA-Z0-9]+/","",$data['user_id']);
			$dbquery2->bindParam(":user_id",$data['user_id']);
			$dbquery2->bindParam(":map_pic",$replaceRecordsString);
			$dbquery2->bindParam(":map_description",$data['map_description']);
	        $dbquery2->bindParam(":emptyField",$emptyField); 

	        if(!($dbquery2->execute()))
	        {
	           // print_r($dbquery2->errorInfo());
	            $this->fail_result=true;
	            $this->addition_info='error 106_1 : Db error';
	            $this->result_info=" An Error Occurred, Try Again ";
	        }
	    }	

	    //move files from temporary dir to permanent dir
	    ##add pics
		if(!$this->fail_result)
		{					
			$i=0;
			while($i<count($fileChange) && !$this->fail_result)
			{   					
				$v=0;
				do
				{
					$fileIndexArray =array();
					if($v==0)
					{	
						$fileIndexArray['extension']='.jpg';
					}elseif($v==1)
					{	
						$fileIndexArray['extension']='_t.jpg';
					}elseif($v==2)
					{	
						$fileIndexArray['extension']='_m.jpg';
					}	

					if(!$this->fail_result)
					{	
					
						if((rename($fileChange[$i]['path_old'].$fileIndexArray['extension'],$fileChange[$i]['path_new'].$fileIndexArray['extension']))) 
						{		 
						    array_push($fileSystemRollBack2,array(  'path_old'=>$fileChange[$i]['path_old'].$fileIndexArray['extension'],
																	'path_new'=>$fileChange[$i]['path_new'].$fileIndexArray['extension'],
																));
						}else
						{
							$this->addition_info="error 107_1:".$i.":".$fileIndexArray['extension'];
							$this->result_info="Sorry An Error Occurred,Try Again";
							$this->fail_result=true;
						}
					
					}

					$v++;
				}while ($v<3 && !$this->fail_result);
				$i++;
			};
				
		}
	
		if(!$this->fail_result)
		{
			$this->db->conn_id->commit();
			$this->status=true;
			$this->result_info="Map edited ";
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
			$this->result_info="Sorry An Error Occurred,Try Again";		
		}

		//revert file changes if changes happened
		if($this->fail_result)
		{     
			foreach($fileSystemRollBack2 as $key => $value)
			{
				rename($value['path_new'],$value['path_old']);

			};
		}				

	    //return results
        return $info = array("status"=>$this->status,
                               "data"=> array('result_info'=> $this->result_info,
                                               ),
                              "addition_info"=>$this->addition_info);
	}

	
	public function addDeleteLicensePic($data=array())
	{
	    //reset default variable;
	    $this->reset_defaults();
	    $this->load->library('attach_image');

	    //set defaults
	    $max_number_of_file=4;
	    $file_directory='';
	    $temp_directory=IMAGE_SRC_DIR.'temp/image/';
	    $existingRecords=array();
	    $fileSystemRollBack1=array();
	    $fileSystemRollBack2=array();
	    $fileChange=array();//all files to have their dir changed,whether delete files or add files
	    $toAddFiles=array();
	    $replaceRecordsString='';
	    $delimiter1='|#$(delimiter-1)$#|';
	    $delimiter2='|#$(delimiter-2)$#|';

	    if(!isset($data['user_id'])) $data['user_id']=array();
	    if(!isset($data['addPic'])) $data['addPic']=array();
	    if(!isset($data['deletePic'])) $data['deletePic']=array();
	    if(!isset($data['replace_existing'])) $data['replace_existing']=false;
	    if(!isset($data['allow_null_photos'])) $data['allow_null_photos']=false;


	    // check if required data is supplied      
	    if(empty($data) || empty($data['user_id']))
	    {
	        $this->addition_info='error 100_1';
	        //$addition_info = "empty user_id ";
	        $this->result_info=" Error: An Error Occurred, Try Again "; 
	        $this->fail_result=true;   
	    }

	    // collect received pics      
	    if(!$this->fail_result)
	    {   
	        if(empty($data['addPic']) && empty($data['deletePic']))
	        {
		        $this->addition_info='error 101_1';
		        //$addition_info = "empty fields provided";
		        $this->result_info=" Error: An Error Occurred, Try Again "; 
		        $this->fail_result=true;
		        $this->status = true;	        	
	        }
	    }

	    // take pics from Db for re-organise
	    if(!$this->fail_result)   
	    {             
			$dbquery1=$this->db->conn_id->prepare("
			SELECT `license_pic` 
			FROM `user_license`
			WHERE `user_id`=:user_id
			");
			$data['user_id']=preg_replace("/[^a-zA-Z0-9]+/","",$data['user_id']);
			$dbquery1->bindParam(":user_id",$data['user_id']);
			       
	        if(!($dbquery1->execute()))
	        {
	            //print_r($dbquery1->errorInfo());
	            $this->fail_result=true;
	            $this->addition_info='error 102_1';
	            $this->result_info=" An Error Occurred, Try Again ";
	        }else
	        {
	            $sql_result = $dbquery1->fetch();
	        }
	    }
        

        /* sample pic delimiter
          0|#$(t@ye@e@r@s@u@b)$#|temp/image/0997876787iigkygi6tu
                 |#$(t@ye@e@r)$#|
          1|#$(t@ye@e@r@s@u@b)$#|temp/image/0997876787iigkygi6tu
                 |#$(t@ye@e@r)$#|
          3|#$(t@ye@e@r@s@u@b)$#|temp/image/0997876787iigkygi6tu
        */  

	    //explode collected the pics
	    if(!$this->fail_result && isset($sql_result) && !empty($sql_result['license_pic']))
	    {

            $ArrayTemp1=explode($delimiter1,$sql_result['license_pic']);
            foreach ($ArrayTemp1 as $key => $value) {
            	$ArrayTemp2=explode($delimiter2,$value);
            	$existingRecords[$ArrayTemp2[0]]=$ArrayTemp2[1];
            }

            //remove empty values
            $existingRecords=array_filter($existingRecords);

          	if(((count($existingRecords)-count($data['deletePic']))>=$max_number_of_file) && !$data['replace_existing'] )
          	{
          		$this->fail_result=true;
          		//delete some pics first
	            $this->addition_info='error 103_1 :delete some images first';
	            $this->result_info=" An Error Occurred, Try Again ";
          	}            

	    }

	    //replace existing
	    if(!$this->fail_result && $data['replace_existing'] && count($existingRecords)>0)
	    {
	    	/* you delete pics with received id '$data[addpics]' from the existing pics 'collectedpics'
			   then make a string 'addpicstring' out of it 'collectedpics' with delimiters to store in Db
			*/         	
		
          	foreach ($existingRecords as $key => $value) {

    	      		array_push($fileChange, array(	'path_new'=>$temp_directory,
	      											'path_old'=> IMAGE_SRC_DIR.$value));    	      		          			
          			unset($existingRecords[$key]);	
          	}

          	#rearrange the array
      		$existingRecords=array_values($existingRecords);
	    }

	    //create permanent folder
	    if(!$this->fail_result)
	    {
	    	$temp_data=array(
	    					'path'=>IMAGE_SRC_DIR.'media/user/'.$data['user_id'].'/image/profile/',
	    					'user_id'=>$data['user_id'],
	    					'create_user_dir'=>true,
	    				);
			$file_directory=$this->attach_image->create_dir_by_week($temp_data);
	    	
	    	if(!$file_directory['status'])
	    	{
	    		$this->fail_result=true;
	    		$this->addition_info='error 106_1';
	    		$this->result_info='Try again, an error Occurred';
	    	}
	    }

	    //delete/re-organise the pics
	    if(!$this->fail_result && !empty($data['deletePic']) && is_array($data['deletePic']) && count($data['deletePic'])>0)
	    {
	    	/* you delete pics with received id '$data[addpics]' from the existing pics 'collectedpics'
			   then make a string 'addpicstring' out of it 'collectedpics' with delimiters to store in Db
			*/
          	$i=0;
          	#remove empty values
          	$data['deletePic'] = array_filter($data['deletePic'],'is_numeric');          	

          	while (($i<count($data['deletePic'])))
          	{
          		if(isset($existingRecords[$data['deletePic'][$i]]))
          		{	
    	      		array_push($fileChange, array(	'path_new'=>$temp_directory,
	      											'path_old'=>IMAGE_SRC_DIR.$existingRecords[$data['deletePic'][$i]]));
    	      		          			
          			unset($existingRecords[$data['deletePic'][$i]]);
    	      	}

    	      	$i++;	
          	}

          	#rearrange the array
      		$existingRecords=array_values($existingRecords);

      		//prevent deleting all pic
      		if(empty($existingRecords) && !$data['allow_null_photos'])
      		{
      			$this->fail_result=true;
      			$this->addition_info='error 107_1';
      			$this->result_info="Can not delete all photos";
      		}
      		      		
	    }

	    //add/re-organise the pics
	    if(!$this->fail_result  && !empty($data['addPic']) && is_array($data['addPic']) && count($data['addPic'])>0)
	    {
	    	/* you add received pics '$addPics' to the existing pics 'collectedpics'
			   then make a string 'addPicString' out of it 'collectedpics' with delimiters to store in Db
			   limit the pics to only 3
			*/
          	$i=0;$v=0;
          	#remove empty values
          	$data['addPic'] =array_filter($data['addPic']);
          	$data['addPic'] =array_values($data['addPic']);

          	while (($i<$max_number_of_file-count($existingRecords)) && ($v<count($data['addPic'])))
          	{
          		array_push($existingRecords,substr(strstr($file_directory['data']['directory'], IMAGE_SRC_DIR), strlen(IMAGE_SRC_DIR)).$data['addPic'][$i]);
         		
	      		array_push($fileChange, array(	'path_new'=>$file_directory['data']['directory'].$data['addPic'][$i],
	      										'path_old'=>$temp_directory.$data['addPic'][$i]	));
    	      	
    	      	$i++;$v++;	
          	}

          	#remove duplicate values
          	$existingRecords =array_unique($existingRecords);
          	#remove empty values
          	$existingRecords =array_filter($existingRecords);
          	$fileChange =array_filter($fileChange);
          	#rearrange the array
      		$existingRecords=array_values($existingRecords);

          	if(empty($existingRecords))
          	{
          		$this->fail_result=true;
          		//empty 
	            $this->addition_info='error 104_1';
	            $this->result_info=" An Error Occurred, Try Again ";
          	}
	    }

	    //check if paths exists
	    if(!$this->fail_result)
	    {
	    	$i=0;
	    	while ($i<count($fileChange) && !$this->fail_result) 
	    	{
					$v=0;
					do
					{
						$fileIndexArray =array();
						if($v==0)
						{	
							$fileIndexArray['extension']='.jpg';
						}elseif($v==1)
						{	
							$fileIndexArray['extension']='_t.jpg';
						}elseif($v==2)
						{	
							$fileIndexArray['extension']='_m.jpg';
						}

						//	file transfer consist of two paths i.e. new and old path. so old path has to exist always
						if(!file_exists($fileChange[$i]['path_old'].$fileIndexArray['extension']))
			    		{
			    			if(empty($this->addition_info))
			    			$this->addition_info="error 105_1:".$i.":path_old:".$fileIndexArray['extension'];
			    			
			    			$this->fail_result=true;
			    		}

			    		$v++;

		    		}while ( $v<3 && !$this->fail_result);
	    		$i++;
	    	}

	    }	

	    //implode collected the pics
	    if(!$this->fail_result)
	    {
            foreach ($existingRecords as $key => $value) {
            	$value=$key.$delimiter2.$value;
            	$existingRecords[$key]=$value;
            }    
            $replaceRecordsString=implode($delimiter1,$existingRecords);
        
	    }

	    // store in Db
	    $this->db->conn_id->beginTransaction();
	    if(!$this->fail_result)   
	    {             
			$dbquery2=$this->db->conn_id->prepare("
							INSERT INTO `user_license`(`user_id`,`license_pic`) 
							VALUES(:user_id,:license_pic)
							ON DUPLICATE KEY UPDATE `license_pic`=:license_pic
			");
			$data['user_id']=preg_replace("/[^a-zA-Z0-9]+/","",$data['user_id']);
			$dbquery2->bindParam(":user_id",$data['user_id']);
			$dbquery2->bindParam(":license_pic",$replaceRecordsString);
	                 
	        if(!($dbquery2->execute()))
	        {
	           // print_r($dbquery2->errorInfo());
	            $this->fail_result=true;
	            $this->addition_info='error 106_1';
	            $this->result_info=" An Error Occurred, Try Again ";
	        }
	    }	

	    //move files from temporary dir to permanent dir
	    ##add pics
		if(!$this->fail_result)
		{					
			$i=0;
			while($i<count($fileChange) && !$this->fail_result)
			{   					
				$v=0;
				do
				{
					$fileIndexArray =array();
					if($v==0)
					{	
						$fileIndexArray['extension']='.jpg';
					}elseif($v==1)
					{	
						$fileIndexArray['extension']='_t.jpg';
					}elseif($v==2)
					{	
						$fileIndexArray['extension']='_m.jpg';
					}	

					if(!$this->fail_result)
					{	
					
						if((rename($fileChange[$i]['path_old'].$fileIndexArray['extension'],$fileChange[$i]['path_new'].$fileIndexArray['extension']))) 
						{		 
						    array_push($fileSystemRollBack2,array(  'path_old'=>$fileChange[$i]['path_old'].$fileIndexArray['extension'],
																	'path_new'=>$fileChange[$i]['path_new'].$fileIndexArray['extension'],
																));
						}else
						{
							$this->addition_info="error 107_1:".$i.":".$fileIndexArray['extension'];
							$this->result_info="Sorry An Error Occurred,Try Again";
							$this->fail_result=true;
						}
					
					}

					$v++;
				}while ($v<3 && !$this->fail_result);
				$i++;
			};
				
		}
	
		if(!$this->fail_result)
		{
			$this->db->conn_id->commit();
			$this->status=true;
			$this->result_info="photos changed ";
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
			$this->result_info="Sorry An Error Occurred,Try Again";		
		}

		//revert file changes if changes happened
		if($this->fail_result)
		{     
			foreach($fileSystemRollBack2 as $key => $value)
			{
				rename($value['path_new'],$value['path_old']);

			};
		}				

	    //return results
        return $info = array("status"=>$this->status,
                               "data"=> array('result_info'=> $this->result_info,
                                               ),
                              "addition_info"=>$this->addition_info);
	}

	public function addDeletePhone($data=array())
	{
	    //reset default variable;
	    $this->reset_defaults();

	    //set defaults
	    $existingRecords=array();
	    $replaceRecordsString='';
	    $delimiter1='|#$(delimiter-1)$#|';
	    $delimiter2='|#$(delimiter-2)$#|';

	    if(!isset($data['user_id'])) $data['user_id']=array();
	    if(!isset($data['addPhone'])) $data['addPhone']=array();
	    if(!isset($data['deletePhone'])) $data['deletePhone']=array();
        

	    // check if required data is supplied      
	    if(empty($data) || empty($data['user_id']))
	    {
	        $this->addition_info='error 100_1';
	        //$addition_info = "empty user_id || email";
	        $this->result_info=" Error: An Error Occurred, Try Again "; 
	        $this->fail_result=true;   
	    }

	    // collect received phone      
	    if(!$this->fail_result)
	    {   
	        if(empty($data['addPhone']) && empty($data['deletePhone']))
	        {
		        $this->addition_info='error 101_2 : no emails provided to add or delete';
		        //$addition_info = "empty email provided";
		        $this->result_info=" Error: An Error Occurred, Try Again "; 
		        $this->fail_result=true;
		        $this->status = true;	        	
	        }
	    }

	    // take phone from Db for re-organise
	    if(!$this->fail_result)   
	    {             
			$dbquery1=$this->db->conn_id->prepare("
			SELECT `phone` 
			FROM `contact_phone`
			WHERE `user_id`=:user_id
			");
			$data['user_id']=preg_replace("/[^a-zA-Z0-9]+/","",$data['user_id']);
			$dbquery1->bindParam(":user_id",$data['user_id']);
			       
	        if(!($dbquery1->execute()))
	        {
	            //print_r($dbquery1->errorInfo());
	            $this->fail_result=true;
	            $this->addition_info='error 102_1';
	            $this->result_info=" An Error Occurred, Try Again ";
	        }else
	        {
	            $sql_result = $dbquery1->fetch();
	        }
	    }
        
        /* sample phone delimiter
          0|#$(t@ye@e@r@s@u@b)$#|tiyachamdimba@gmail.com
                 |#$(t@ye@e@r)$#|
          1|#$(t@ye@e@r@s@u@b)$#|tiyachamdimba@gmail.com
                 |#$(t@ye@e@r)$#|
          2|#$(t@ye@e@r@s@u@b)$#|tiyachamdimba@gmail.com
        */  

	    //explode collected the phone
	    if(!$this->fail_result && isset($sql_result) && !empty($sql_result['phone']))
	    {

            $ArrayTemp1=explode($delimiter1,$sql_result['phone']);
            foreach ($ArrayTemp1 as $key => $value) {
            	$ArrayTemp2=explode($delimiter2,$value);
            	$existingRecords[$ArrayTemp2[0]]=$ArrayTemp2[1];
            }

            //remove empty values
            $existingRecords=array_filter($existingRecords);

          	if(count($existingRecords)>=3)
          	{
          		$this->fail_result=true;
          		//delete some phone first
	            $this->addition_info='error 103_1';
	            $this->result_info=" An Error Occurred, Try Again ";
          	}            

	    }

	    //delete/re-organise the phone
	    if(!$this->fail_result && !empty($data['deletePhone']) && is_array($data['deletePhone']) && count($data['deletePhone'])>0)
	    {
	    	/* you delete phone with received id '$data[addPhones]' from the existing phone 'collectedphone'
			   then make a string 'addPhoneString' out of it 'collectedphone' with delimiters to store in Db
			   limit the phone to only 3
			*/
          	$i=0;
          	$x=count($data['deletePhone']);

          	while (($i<$x))
          	{
          		if(isset($existingRecords[$data['deletePhone'][$i]]))
          		unset($existingRecords[$data['deletePhone'][$i]]);
    	      	$i++;	
          	}

          	#rearrange the array
      		$existingRecords=array_values($existingRecords);
	    }

	    //add/re-organise the phone
	    if(!$this->fail_result  && !empty($data['addPhone']) && is_array($data['addPhone']) && count($data['addPhone'])>0)
	    {
	    	/* you add received phone '$addPhones' to the existing phone 'collectedphone'
			   then make a string 'addPhoneString' out of it 'collectedphone' with delimiters to store in Db
			   limit the phone to only 3
			*/
          	$i=0;$v=0;
          	$z=count($existingRecords);
          	$x=count($data['addPhone']);

          	while (($i<3-$z) && ($v<$x))
          	{
          		array_push($existingRecords,$data['addPhone'][$i]);
    	      	
    	      	$i++;$v++;	
          	}

          	#remove duplicate values
          	$existingRecords =array_unique($existingRecords);
          	#remove empty values
          	$existingRecords =array_filter($existingRecords);
          	#rearrange the array
      		$existingRecords=array_values($existingRecords);

          	if(empty($existingRecords))
          	{
          		$this->fail_result=true;
          		//empty 
	            $this->addition_info='error 104_1';
	            $this->result_info=" An Error Occurred, Try Again ";
          	}

	    }


	    //implode collected the phone
	    if(!$this->fail_result)
	    {
            foreach ($existingRecords as $key => $value) {
            	$value=$key.$delimiter2.$value;
            	$existingRecords[$key]=$value;
            } 
            $replaceRecordsString=implode($delimiter1,$existingRecords);   
	    }

	    // store in Db
	    if(!$this->fail_result)   
	    {

			$dbquery2=$this->db->conn_id->prepare("
							INSERT INTO `contact_phone`(`user_id`,`phone`) 
							VALUES(:user_id,:phone)
							ON DUPLICATE KEY UPDATE `phone`=:phone
			");
			$data['user_id']=preg_replace("/[^a-zA-Z0-9]+/","",$data['user_id']);
			$dbquery2->bindParam(":user_id",$data['user_id']);
			$dbquery2->bindParam(":phone",$replaceRecordsString);
	                 
	        if(!($dbquery2->execute()))
	        {
	            //print_r($dbquery2->errorInfo());
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
	                                                   ),
	                                  "addition_info"=>$this->addition_info);
	}
    
    
}  


?>