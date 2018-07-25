<?php
class Item_request_model extends CI_Model {

        //default variables
        private $addition_info="";
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
                 $this->status = false;
                 $this->result_info=false; 
                 $this->fail_result=false;
        } 


 ##------------------------------------------
	public function searchItem($filters=array())
	{
	        // set defaults
			$this->reset_defaults(); 
         
			 $searchWord='';
			 $count=0;
			 $result=array();
			 $filters['deleted_by_seller'] = 0; // get only those that aren't deleted unless otherwise
			 $filters['verification'] = 'verified';
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
					}elseif($key==='deleted_by_seller' || ($key==='deleted_by_seller'.$n) )
					{
						array_push($miniWhereSubQueryArry,'`deleted_by_seller`=:deleted_by_seller'.$n ); 
						$n++;
					}elseif($key==='item_id' || ($key==='item_id'.$n) )
					{
						array_push($miniWhereSubQueryArry,'`item_id`=:item_id'.$n ); 
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
																		SELECT *,(MATCH (`name`) AGAINST ( '>':searchWord '<':searchWord'*' IN BOOLEAN MODE)) AS `rel1` 
																	       FROM `item_request` `s1` 
																		 	
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
																					
																			LEFT JOIN (SELECT  `user_id` AS `s7_user_id`,
																			CASE WHEN ISNULL(`rank`) THEN '0' ELSE `rank` END  AS `user_rank`,
																			CASE WHEN ISNULL(`verification`) THEN 'unverified' ELSE `verification` END  AS `verification`
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
																				
																		WHERE (MATCH (`name`) AGAINST ( '>':searchWord '<':searchWord'*' IN BOOLEAN MODE))									

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
														
					if($key==='deleted_by_seller' || ($key==='deleted_by_seller'.$n) ) 
					{$dbquery1->bindValue(":deleted_by_seller".$n,$value2); $n++; }
														
					if($key==='item_id' || ($key==='item_id'.$n) ) 
					{$dbquery1->bindValue(":item_id".$n,$value2); $n++; }

					if($key==='not_category_id' || ($key==='not_category_id'.$n) ) 
					{$dbquery1->bindValue(":not_category_id".$n,$value2); 	$n++;}

					if($key==='not_location_id' || ($key==='not_location_id'.$n) ) 
					{$dbquery1->bindValue(":not_location_id".$n,$value2); 	$n++;}

					if($key==='not_type_id' || ($key==='not_type_id'.$n) ) 
					{$dbquery1->bindValue(":not_type_id".$n,$value2); 	$n++;}
					
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
														ORDER BY ((`rel1`*0.7)+(`user_rank`*0.3)) DESC,".$order_by."											
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

						$result[$key]=array(									
											"user_id"=>$value['user_id'],
											"item_id"=>$value['item_id'],
											"item_name"=>$value['name'],
											"item_pic"=>$item_picArray,
											"on_display"=>$value['on_display'],
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

	public function getItem($filters=array())
	{
	        // set defaults
	         $this->reset_defaults(); 
         
			 $searchWord='';
			 $count=0;
			 $result=array();
			 $filters['deleted_by_seller'] = 0; // get only those that aren't deleted unless otherwise
			 $filters['verification'] = 'verified';
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
					}
					elseif($key==='location_id' || ($key==='location_id'.$n) )
					{
						array_push($miniWhereSubQueryArry,'`location_id`=:location_id'.$n ); 
						$n++;
					}elseif($key==='not_location_id' || ($key==='not_location_id'.$n) )
					{
						array_push($miniWhereSubQueryArry,'`location_id`!=:not_location_id'.$n ); 
						$n++;
					}
					elseif($key==='type_id' || ($key==='type_id'.$n) )
					{
						array_push($miniWhereSubQueryArry,'`type_id`=:type_id'.$n ); 
						$n++;
					}elseif($key==='not_type_id' || ($key==='not_type_id'.$n) )
					{
						array_push($miniWhereSubQueryArry,'`type_id`!=:not_type_id'.$n ); 
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
					}elseif($key==='email' || ($key==='email'.$n) )
					{
						array_push($miniWhereSubQueryArry,'`email`=:email'.$n ); 
						$n++;
					}elseif($key==='phone' || ($key==='phone'.$n) )
					{
						array_push($miniWhereSubQueryArry,'`phone`=:phone'.$n ); 
						$n++;
					}elseif($key==='deleted_by_seller' || ($key==='deleted_by_seller'.$n) )
					{
						array_push($miniWhereSubQueryArry,'`deleted_by_seller`=:deleted_by_seller'.$n ); 
						$n++;
					}elseif($key==='not_item_id' || ($key==='not_item_id'.$n) )
					{
						array_push($miniWhereSubQueryArry,'`item_id`!=:not_item_id'.$n ); 
						$n++;
					}elseif($key==='on_display' || ($key==='on_display'.$n) )
					{
						array_push($miniWhereSubQueryArry,'`on_display`=:on_display'.$n ); 
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
																		SELECT *
																	       FROM `item_request` `s1` 
																		 	
																			LEFT JOIN( 
																			SELECT `about` ,`user_name`, `user_id` AS `s3_user_id`
																			FROM `user_info`
																			)AS `s3` ON  `s3`.`s3_user_id`= `s1`.`user_id`	
																			
																			LEFT JOIN( 
																			SELECT `user_pic` , `user_id` AS `s4_user_id`
																			FROM `profile_pic`
																			) AS `s4` ON  `s4`.`s4_user_id`= `s1`.`user_id`
																			
																			LEFT JOIN( 
																			SELECT `parent_name` AS `category_parent_name`,`parent_id` AS `category_parent_id`, `name` AS `category_name`, `id` AS `s5_category_id`
																			FROM `category` `s5_a1`
																					LEFT JOIN( SELECT `name` As parent_name,`id` AS `s5_b1_category_id` 
																								FROM `category` 
																					)AS `s5_b1` ON  `s5_b1`.`s5_b1_category_id` = `s5_a1`.`parent_id`							
																			) AS `s5` ON  `s5`.`s5_category_id`= `s1`.`category_id`
																					
																			LEFT JOIN( 
																			SELECT `condition_name`, `condition_id` AS `s6_condition_id`
																			FROM `item_condition`
																			) AS `s6` ON  `s6`.`s6_condition_id`= `s1`.`condition_id`
																					

																			LEFT JOIN (SELECT  `user_id` AS `s7_user_id`,
																			CASE WHEN ISNULL(`rank`) THEN '0' ELSE `rank` END  AS `user_rank`,
																			CASE WHEN ISNULL(`verification`) THEN 'unverified' ELSE `verification` END  AS `verification`
																			FROM `verification`
																			) AS `s7` ON  `s7`.`s7_user_id`= `s1`.`user_id`
																		
																			LEFT JOIN (
																			SELECT `user_pic` as `profile_pic` , `user_id` AS `s8_user_id`
																			FROM `profile_pic`
																			) AS `s8` ON `s8`.`s8_user_id` = `s1`.`user_id`
																						
																			LEFT JOIN( 
																			SELECT `name` AS `type_name`, `id` AS `s10_type_id`
																			FROM `type`
																			) AS `s10` ON  `s10`.`s10_type_id`= `s1`.`type_id`
																					
																			LEFT JOIN( 
																			SELECT `name` AS `location_name`, `id` AS `s11_location_id`
																			FROM `location`
																			) AS `s11` ON  `s11`.`s11_location_id`= `s1`.`location_id`
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

					if($key==='location_id' || ($key==='location_id'.$n) ) 
					{$dbquery1->bindValue(":location_id".$n,$value2); 	$n++;}

					if($key==='type_id' || ($key==='type_id'.$n) ) 
					{$dbquery1->bindValue(":type_id".$n,$value2); 	$n++;}
					
					if($key==='condition' || ($key==='condition'.$n) ) 
					{$dbquery1->bindValue(":condition".$n,$value2); $n++; }
					
					if($key==='verification' || ($key==='verification'.$n) ) 
					{$dbquery1->bindValue(":verification".$n,$value2); $n++; }
					
					if($key==='user_id' || ($key==='user_id'.$n) ) 
					{$dbquery1->bindValue(":user_id".$n,$value2); $n++; }
														
					if($key==='item_id' || ($key==='item_id'.$n) ) 
					{$dbquery1->bindValue(":item_id".$n,$value2); $n++; }
														
					if($key==='email' || ($key==='email'.$n) ) 
					{$dbquery1->bindValue(":email".$n,$value2); $n++; }
														
					if($key==='phone' || ($key==='phone'.$n) ) 
					{$dbquery1->bindValue(":phone".$n,$value2); $n++; }
														
					if($key==='on_display' || ($key==='on_display'.$n) ) 
					{$dbquery1->bindValue(":on_display".$n,$value2); $n++; }
														
					if($key==='deleted_by_seller' || ($key==='deleted_by_seller'.$n) ) 
					{$dbquery1->bindValue(":deleted_by_seller".$n,$value2); $n++; }

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
				$this->fail_result=true;
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
				$this->fail_result=true;
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
															SELECT COUNT(*) AS `itemsPerLocation`,`location_name`
															FROM `temp_table`
															GROUP BY `location_id`											
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

			//get the items' groups of location
			if(!$this->fail_result)
			{

				$dbquery6 =  $this->db->conn_id->prepare("	
															SELECT COUNT(*) AS `itemsPerType`,`type_name`
															FROM `temp_table`
															GROUP BY `type_id`											
															LIMIT 15									
														");
					
				if(!$dbquery6->execute())
				{
					$this->status=false;
					!$this->fail_result=true;
					//echo '<pre>';
					//print_r($valueFormated);
					//print_r($dbquery1);
					//print_r($dbquery1->errorInfo());
					$this->addition_info = $dbquery6->errorInfo();
					//$this->addition_info = 'error_100';
				}
			}		

			//delete the temp table
			if(!$this->fail_result)
			{

				$dbquery7 =  $this->db->conn_id->prepare("	
															DROP TEMPORARY TABLE IF EXISTS `temp_table`									
														");
					
				if(!$dbquery7->execute())
				{
					$this->status=false;
					$this->fail_result=true;
					//echo '<pre>';
					//print_r($valueFormated);
					//print_r($dbquery1);
					//print_r($dbquery1->errorInfo());
					$this->addition_info = $dbquery7->errorInfo();
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
			   	$itemsPerType = $dbquery6->fetchAll();
				
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

						$result[$key]=array(									
											"user_id"=>$value['user_id'],
											"item_id"=>$value['item_id'],
											"email"=>$value['email'],
											"phone"=>$value['phone'],
											"item_name"=>$value['name'],
											"item_pic"=>$item_picArray,
											"on_display"=>$value['on_display'],
											"date"=>$value['date'],
											"price"=>$value['price'],
											"category_id"=>$value['category_id'],
											"category_name"=>$value['category_name'],
											"category_parent_name"=>$value['category_parent_name'],
											"category_parent_id"=>$value['category_parent_id'],
											"user_verification"=>$value['verification'],
											"condition_name`"=>$value['condition_name'],
											"condition_id"=>$value['condition_id'],
											"type_id"=>$value['type_id'],
											"type_name"=>$value['type_name'],
											"location_id"=>$value['location_id'],
											"location_name"=>$value['location_name'],
											"item_description"=>$value['description'],
											"user_profile_pic"=>$profile_picArray,
											"user_name"=>$value['user_name'],									
											"user_about"=>$value['about'],												
											"summary"=>$value['summary'],												
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
											   'itemsPerType'=>$itemsPerType,
											   ),
	                            "addition_info"=>$this->addition_info);	   	       
	}

    
	public function addItem($data=array())
	{
	    //reset default variable;
	    $this->reset_defaults();
	    $this->load->library('attach_image');

	    //set defaults
	    $item_id = '';
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

	    if(!isset($data['user_id'])) $data['user_id']='';
	    if(!isset($data['addPic'])) $data['addPic']=array();
	    if(!isset($data['front_pic'])) $data['front_pic']='';
	    if(!isset($data['description'])) $data['description']='';
	    if(!isset($data['summary'])) $data['summary']='';
	    if(!isset($data['location_id'])) $data['locati_idon']='';
	    if(!isset($data['type_id'])) $data['type_id']='';
	    if(!isset($data['name'])) $data['name']='';
	    if(!isset($data['email'])) $data['email']='';
	    if(!isset($data['phone'])) $data['phone']='';
	    if(!isset($data['condition_id'])) $data['condition_id']=1;
	    if(!isset($data['category_id'])) $data['category_id']='';
	    if(!isset($data['price'])) $data['price']='';
	    if(!isset($data['on_display'])) $data['on_display']=1;// default is to put it on display 


	    // check if required data is supplied      
	    if(empty($data) || (!is_numeric($data['user_id']) && empty($data['user_id'])))
	    {
	        $this->addition_info='error 100_1';
	        //$addition_info = "empty user_id ";
	        $this->result_info=" Error: An Error Occurred, Try Again "; 
	        $this->fail_result=true;   
	    }

	    // collect received pics      
	    /*if(!$this->fail_result)
	    {   
	        if(empty($data['addPic']))
	        {
		        $this->addition_info='error 101_1';
		        //$addition_info = "empty fields provided";
		        $this->result_info=" Atleast add one Image "; 
		        $this->fail_result=true;	        	
	        }
	    }*/

	    //create permanent folder
	    if(!$this->fail_result)
	    {
	    	$temp_data=array(
	    					'path'=>IMAGE_SRC_DIR.'media/user/'.$data['user_id'].'/image/item/',
	    					'user_id'=>$data['user_id'],
	    					'create_user_dir'=>true,
	    				);
			$file_directory=$this->attach_image->create_dir_by_week($temp_data);
	    	
	    	if(!$file_directory['status'])
	    	{
	    		$this->fail_result=true;
	    		$this->addition_info='error 102_1';
	    		$this->result_info='Try again, an error Occurred';
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
	            $this->addition_info='error 103_1';
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
			    			$this->addition_info="error 104_1:".$i.":path_old:".$fileIndexArray['extension'];
			    			
			    			$this->fail_result=true;

			    			echo $fileChange[$i]['path_old'].$fileIndexArray['extension'];
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
            	
            	#add main pic
            	if(isset($data['front_pic']) && $data['front_pic']==$value )
            		$value.=$delimiter2.'1';
            	else
            		$value.=$delimiter2.'0';	

            	$existingRecords[$key]=$value;
            }    
            $replaceRecordsString=implode($delimiter1,$existingRecords);
        
	    }

	    // store in Db
	    $this->db->conn_id->beginTransaction();
	    if(!$this->fail_result)   
	    {             
			$dbquery2=$this->db->conn_id->prepare("
							INSERT INTO `item_request`(`user_id`,`item_pic`,`name`,`category_id`,`price`,
												`condition_id`,`on_display`,`description`,`summary`,`location_id`,`type_id`,`email`,`phone`) 
							VALUES(:user_id,:item_pic,:name,:category_id,:price,:condition_id,:on_display,
									:description,:summary,:location_id,:type_id,:email,:phone)
			");
			$data['user_id']=preg_replace("/[^a-zA-Z0-9]+/","",$data['user_id']);
			$dbquery2->bindParam(":user_id",$data['user_id']);
			$dbquery2->bindParam(":item_pic",$replaceRecordsString);
			$dbquery2->bindParam(":name",$data['name']);
			$dbquery2->bindParam(":category_id",$data['category_id']);
			$dbquery2->bindParam(":price",$data['price']);
			$dbquery2->bindParam(":on_display",$data['on_display']);
			$dbquery2->bindParam(":description",$data['description']);
			$dbquery2->bindParam(":summary",$data['summary']);
			$dbquery2->bindParam(":condition_id",$data['condition_id']);      
			$dbquery2->bindParam(":location_id",$data['location_id']);      
			$dbquery2->bindParam(":type_id",$data['type_id']);      
			$dbquery2->bindParam(":email",$data['email']);      
			$dbquery2->bindParam(":phone",$data['phone']);      
	        

	        if(!($dbquery2->execute()))
	        {
	            //print_r($dbquery2->errorInfo());
	            //print_r($data);
	            $this->fail_result=true;
	            $this->addition_info='error 105_1';
	            $this->addition_info=$dbquery2->errorInfo();
	            $this->result_info=" An Error Occurred, Try Again ";
	        }
	        else
	        {
	        	$item_id=$this->db->conn_id->lastInsertId();
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
					
						if((@rename($fileChange[$i]['path_old'].$fileIndexArray['extension'],$fileChange[$i]['path_new'].$fileIndexArray['extension']))) 
						{		 
						    array_push($fileSystemRollBack2,array(  'path_old'=>$fileChange[$i]['path_old'].$fileIndexArray['extension'],
																	'path_new'=>$fileChange[$i]['path_new'].$fileIndexArray['extension'],
																));
						}else
						{
							$this->addition_info="error 106_1:".$i.":".$fileIndexArray['extension'];
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
			$this->result_info="item added ";   
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
                               					'item_id'=>$item_id,
                                               ),
                              "addition_info"=>$this->addition_info);
	}

	public function editItem($data=array())
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
       
	    if(!isset($data['user_id'])) $data['user_id']='';
	    if(!isset($data['item_id'])) $data['item_id']='';
	    if(!isset($data['addPic'])) $data['addPic']=array();
	    if(!isset($data['deletePic'])) $data['deletePic']=array();
	    if(!isset($data['front_pic'])) $data['front_pic']='';
	    if(!isset($data['description'])) $data['description']='';
	    if(!isset($data['name'])) $data['name']='';
	    if(!isset($data['condition_id'])) $data['condition_id']='';
	    if(!isset($data['category_id'])) $data['category_id']='';
	    if(!isset($data['price'])) $data['price']='';
	    if(!isset($data['on_display'])) $data['on_display']="";
	    if(!isset($data['allow_null_photos'])) $data['allow_null_photos']=false;


	    // check if required data is supplied      
	    if(empty($data) || (empty($data['user_id']) && !is_numeric($data['user_id']))) 
	    {
	        $this->addition_info='error 100_1 : empty user_id';
	        //$addition_info = "empty user_id ";
	        $this->result_info=" Error: An Error Occurred, Try Again "; 
	        $this->fail_result=true;   
	    }

	    
	    // take pics from Db for re-organise
	    if(!$this->fail_result)   
	    {   

			$dbquery1=$this->db->conn_id->prepare("
			SELECT `item_pic` 
			FROM `item_request`
			WHERE `item_id`=:item_id
			");
			$data['item_id']=preg_replace("/[^a-zA-Z0-9]+/","",$data['item_id']);
			$dbquery1->bindParam(":item_id",$data['item_id']);
			       
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
            	
            	// change front pic if set
            	if(!isset($data['front_pic']) || empty($data['front_pic']))
            	{	
            	  	if($ArrayTemp2[2]==1)
            			$data['front_pic']=$ArrayTemp2[1];
            	}
            }

            //remove empty values
            $existingRecords=array_filter($existingRecords);

          	if((count($existingRecords)-count($data['deletePic']))>=$max_number_of_file)
          	{
          		$this->fail_result=true;
          		//delete some pics first
	            $this->addition_info='error 103_1 :delete some images first';
	            $this->result_info=" An Error Occurred, Try Again ";
          	}            

	    }


	    //create permanent folder
	    if(!$this->fail_result)
	    {
	    	$temp_data=array(
	    					'path'=>IMAGE_SRC_DIR.'media/user/'.$data['user_id'].'/image/item/',
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
            	
            	if(isset($data['front_pic']) && $data['front_pic']==$value)
            		$value.=$delimiter2.'1';
            	else
            		$value.=$delimiter2.'0';
            
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
							 UPDATE `item_request` SET 
							`name`= CASE WHEN :name=:emptyField THEN `name` ELSE :name END,
							`category_id`= CASE WHEN :category_id=:emptyField THEN `category_id` ELSE :category_id END,
							`location_id`= CASE WHEN :location_id=:emptyField THEN `location_id` ELSE :location_id END,
							`type_id`= CASE WHEN :type_id=:emptyField THEN `type_id` ELSE :type_id END,
							`price`= CASE WHEN :price=:emptyField THEN `price` ELSE :price END,
							`date`= `date`,
							`condition_id`= CASE WHEN :condition_id=:emptyField THEN `condition_id` ELSE :condition_id END,
							`on_display`= CASE WHEN :on_display=:emptyField THEN `on_display` ELSE :on_display END,
							`description`= CASE WHEN :description=:emptyField THEN `description` ELSE :description END,
							`summary`= CASE WHEN :summary=:emptyField THEN `summary` ELSE :summary END,
							`item_pic`= :item_pic
							WHERE (`item_id`=:item_id AND `user_id`=:user_id )
			");
			$dbquery2->bindParam(":item_id",$data['item_id']);
			$dbquery2->bindParam(":user_id",$data['user_id']);
			$dbquery2->bindParam(":item_pic",$replaceRecordsString);
			$dbquery2->bindParam(":name",$data['name']);
			$dbquery2->bindParam(":category_id",$data['category_id']);
			$dbquery2->bindParam(":location_id",$data['location_id']);
			$dbquery2->bindParam(":type_id",$data['type_id']);
			$dbquery2->bindParam(":price",$data['price']);
			$dbquery2->bindParam(":on_display",$data['on_display']);
			$dbquery2->bindParam(":description",$data['description']);
			$dbquery2->bindParam(":summary",$data['summary']);
			$dbquery2->bindParam(":condition_id",$data['condition_id']);
			$dbquery2->bindParam(":emptyField",$emptyField);                 
	        
	        if(!($dbquery2->execute()))
	        {
	            //print_r($dbquery2->errorInfo());
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
					
						if((@rename($fileChange[$i]['path_old'].$fileIndexArray['extension'],$fileChange[$i]['path_new'].$fileIndexArray['extension']))) 
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
			$this->result_info="item edited ";
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
	public function deleteItemPermanently($data=array())
	{
	    //reset default variable;
	    $this->reset_defaults();

	    //set defaults
	    $max_number_of_file=4;
	    $temp_directory=IMAGE_SRC_DIR.'temp/image/';
	    $existingRecords=array();
	    $fileSystemRollBack1=array();
	    $fileSystemRollBack2=array();
	    $fileChange=array();//all files to have their dir changed,whether delete files or add files
	    $toAddFiles=array();
	    $delimiter1='|#$(delimiter-1)$#|';
	    $delimiter2='|#$(delimiter-2)$#|';
       
	    if(!isset($data['item_id'])) $data['item_id']='';
	    if(!isset($data['user_id'])) $data['user_id']='';

	    // check if required data is supplied      
	    if(empty($data) || (empty($data['user_id']) && !is_numeric($data['user_id']))) 
	    {
	        $this->addition_info='error 100_1';
	        //$addition_info = "empty user_id ";
	        $this->result_info=" Error: An Error Occurred, Try Again "; 
	        $this->fail_result=true;   
	    }

	    

	    // take pics from Db for re-organise
	    if(!$this->fail_result)   
	    {   

			$dbquery1=$this->db->conn_id->prepare("
			SELECT `item_pic` 
			FROM `item_request`
			WHERE `item_id`=:item_id
			");
			$data['item_id']=preg_replace("/[^a-zA-Z0-9]+/","",$data['item_id']);
			$dbquery1->bindParam(":item_id",$data['item_id']);
			       
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
            	
            	// change front pic if set
            	if(!isset($data['front_pic']) || empty($data['front_pic']))
            	{	
            	  	if($ArrayTemp2[2]==1)
            			$data['front_pic']=$ArrayTemp2[1];
            	}
            }

            //remove empty values
            $existingRecords=array_filter($existingRecords);     
	    }

	    //organise all pics to be deleted
	    if(!$this->fail_result )
	    {

          	foreach ($existingRecords as $key => $value) {
    	      		array_push($fileChange, array(	'path_new'=>$temp_directory,
	      											'path_old'=>IMAGE_SRC_DIR.$value));
    	      		          			
          			unset($existingRecords[$key]);
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
			    			

			    			#dont stop the process ..continue anyway: despite the error that the image was not found
			    			//$this->fail_result=true;
			    		}
			    		$v++;

		    		}while ( $v<3 && !$this->fail_result);

	    		$i++;
	    	}

	    }	


	    // store in Db
	    $this->db->conn_id->beginTransaction();
	    if(!$this->fail_result)   
	    {             
			$dbquery2=$this->db->conn_id->prepare("
							DELETE FROM
							`item_request` 
							WHERE (`item_id`=:item_id AND `user_id`=:user_id  )
			");
			$dbquery2->bindParam(":item_id",$data['item_id']);               
			$dbquery2->bindParam(":user_id",$data['user_id']);               
	        
	        if(!($dbquery2->execute()))
	        {
	            //print_r($dbquery2->errorInfo());
	            $this->fail_result=true;
	            $this->addition_info='error 110_1';
	            $this->result_info=" An Error Occurred, Try Again ";
	        }
	    }	

	    //move files from temporary dir to permanent dir or vice versa
	    ##add/delete pics
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
					
						if((@rename($fileChange[$i]['path_old'].$fileIndexArray['extension'],$fileChange[$i]['path_new'].$fileIndexArray['extension']))) 
						{		 
						    array_push($fileSystemRollBack2,array(  'path_old'=>$fileChange[$i]['path_old'].$fileIndexArray['extension'],
																	'path_new'=>$fileChange[$i]['path_new'].$fileIndexArray['extension'],
																));
						}else
						{
							$this->addition_info="error 111_1:".$i.":".$fileIndexArray['extension'];
							$this->result_info="Sorry An Error Occurred,Try Again";
							#dont stop the process ..continue anyway: despite the error that the image was not found
			    			//$this->fail_result=true;
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
			$this->result_info="item edited permanently";
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
	public function deleteItemTemporarily($data=array())
	{
	    //reset default variable;
	    $this->reset_defaults();

	    //set defaults
	    $max_number_of_file=4;
	    $temp_directory=IMAGE_SRC_DIR.'temp/image/';
	    $existingRecords=array();
	    $fileSystemRollBack1=array();
	    $fileSystemRollBack2=array();
	    $fileChange=array();//all files to have their dir changed,whether delete files or add files
	    $toAddFiles=array();
	    $delimiter1='|#$(delimiter-1)$#|';
	    $delimiter2='|#$(delimiter-2)$#|';
       
	    if(!isset($data['item_id'])) $data['item_id']='';
	    if(!isset($data['user_id'])) $data['user_id']='';
	    if(!isset($data['deleted_by_seller'])) $data['deleted_by_seller']=1;

	    // check if required data is supplied      
	    if(empty($data) || (empty($data['user_id']) && !is_numeric($data['user_id']))) 
	    {
	        $this->addition_info='error 100_1';
	        //$addition_info = "empty user_id ";
	        $this->result_info=" Error: An Error Occurred, Try Again "; 
	        $this->fail_result=true;   
	    }

	    
	    // store in Db
	    $this->db->conn_id->beginTransaction();
	    if(!$this->fail_result)   
	    {             
			$dbquery2=$this->db->conn_id->prepare("
							 UPDATE `item_request` SET 
							`date`= `date`,
							`deleted_by_seller`= CASE WHEN :deleted_by_seller=:emptyField THEN `deleted_by_seller` ELSE :deleted_by_seller END
							WHERE (`item_id`=:item_id AND `user_id`=:user_id )
			");
			$dbquery2->bindParam(":item_id",$data['item_id']);
			$dbquery2->bindParam(":user_id",$data['user_id']);
			$dbquery2->bindParam(":deleted_by_seller",$data['deleted_by_seller']);
			$dbquery2->bindParam(":emptyField",$emptyField);                 
	        
	        if(!($dbquery2->execute()))
	        {
	            //print_r($dbquery2->errorInfo());
	            $this->fail_result=true;
	            $this->addition_info='error 110_1';
	            $this->result_info=" An Error Occurred, Try Again ";
	        }
	    }		

		if(!$this->fail_result)
		{
			$this->db->conn_id->commit();
			$this->status=true;
			$this->result_info="item edited ";
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
	

	    //return results
        return $info = array("status"=>$this->status,
                               "data"=> array('result_info'=> $this->result_info,
                                               ),
                              "addition_info"=>$this->addition_info);
	}

}



?>