<?php
class Category_model extends CI_Model {

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
//displays given categories in order of the categories and with their child levels
//---returns level number and corresponding data/info at that level
/*
	categoryArray = array(
							0=>array(
									'parent_id'=>,
									'parent_name'=>,
									'categor_id'=>,
									'category_name'=>

									),
						)
 #call : category_levels_setter($categoryArray,'000011110000111100001',0);        
*/	
	public	function category_levels_setter ($categoryArray,$parent_level_id,$parent_level_num)
	{
		$parent_level_num++;
		$ordered_category=array();
		
		foreach ($categoryArray as $key => $value)
		{
			if($parent_level_id==$value['parent_id'])
				{
					$ordered_category[$key]['parent_id']=$value['parent_id'];
					$ordered_category[$key]['category_id']=$value['category_id'];
					$ordered_category[$key]['parent_name']=$value['parent_name'];
					$ordered_category[$key]['category_name']=$value['category_name'];
					$ordered_category[$key]['level']=$parent_level_num;
					
					$ordered_category2= $this->category_levels_setter($categoryArray,$value['category_id'],$parent_level_num);
					
					$ordered_category2=array_filter($ordered_category2);
					if(count($ordered_category)>0)
					array_push($ordered_category,$ordered_category2);	
				}
				
		}						
		$parent_level_num=0;	
	 

		$ordered_category=array_filter($ordered_category);
		return $ordered_category;
	}


/*
  array[0] = array (
                    'parent_id'=>
                    'parent_name'=>
                    'category_id'=>
                    'category_name'=>
                    'level'=>
                  )
   */
	public function category_children_setter($array=array()) { 
	  if (empty($array)) { 
		return $array; 
	  }

  	  foreach ($array as $key => $value) { 
      	
      	#add child array
      	if(!isset($value['children']))
      	$array[$key]['children']=array();

        #add to parent as a child
          $index = array_search($value['parent_id'],array_column($array, 'category_id'));    
          if(!isset($array[$index]['children']))
          $array[$index]['children']=array();
          if(is_numeric($index))
          array_push($array[$index]['children'],$value);    	  
      }

    return $array;     
	}

	public function getCategory($filters=array())
	{
	        //reset variables
			$this->reset_defaults();

	        // set defaults
	        $this->addition_info="";
	        $this->status = false;
			$count=0;
			$result=array();
			$total_records=false;		 
			$get_total_records=false;
			$filteringCondtion=' AND ';
			$from=0;$take=20;
			$parent_id='';		 
 
			if(isset($filters['from']))
			{ 	
				$from=$filters['from'];
				unset($filters['from']);
			}

			if(isset($filters['take']))
			{ 	
				$take=$filters['take'];
				unset($filters['take']);
			}

			if(isset($filters['get_total_records']))
			{ 	
				$get_total_records=$filters['get_total_records'];
				unset($filters['get_total_records']);
			}	

			if(isset($filters['filtering_condtion']))
			{ 	
				$filteringCondtion=$filters['filtering_condtion'];
				unset($filters['filtering_condtion']);
			}	

		
			$WhereSubQueryArry=array();		

			array_push($WhereSubQueryArry,'(`category_id` IS NOT NULL) ');			

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
					}elseif($key==='category_name' || ($key==='category_name'.$n) )
					{
						array_push($miniWhereSubQueryArry,'`category_name`=:category_name'.$n ); 
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
														SELECT ".$get_total_recordsQuery." * 
														FROM `category` `s1` 
														 	
															LEFT JOIN( SELECT `lft` As left_most_number,`rght` As right_most_number,`category_id` AS `s2_category_id` 
																		FROM `category` 
																		".$WheresubQuery."
															)AS `s2` ON  1 = 1

															LEFT JOIN( SELECT `category_name` As parent_name,`category_id` AS `s3_category_id` 
																		FROM `category` 
																		".$WheresubQuery."
															)AS `s3` ON  `s3`.`s3_category_id` = `s1`.`parent_id`
														
														WHERE `lft` BETWEEN `left_most_number` AND `right_most_number` 
														GROUP BY `category_id`
														ORDER BY `rank` DESC											
														LIMIT :from,:take
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
						
						if($key==='category_name' || ($key==='category_name'.$n) ) 
						{$dbquery1->bindValue(":category_name".$n,$value2); $n++; }
						
									
				   	}
					
			  	}		 
				 
				$dbquery1->bindValue(':from', (int)$from, PDO::PARAM_INT);
				$dbquery1->bindValue(':take', (int)$take, PDO::PARAM_INT);
				
				if($dbquery1->execute())
			   	{
					$this->status = true;
					$this->result_info="success";
					$sql_result = $dbquery1->fetchAll();
					$count=count($sql_result);
					  
					//echo '<pre>';
					//print_r($sql_result);
					  
					if($get_total_records)
					{
						$dbqueryCount =  $this->db->conn_id->query('SELECT FOUND_ROWS()');
					 	$total_records = (int) $dbqueryCount->fetchColumn();
					}				  
					  
					if(count($sql_result)>0)
					{
					    foreach( $sql_result as $key => $value)
						{
						
							//do some format
							$result[$key]=array(									
												"category_id"=>$value['category_id'],
												"parent_id"=>$value['parent_id'],
												"category_name"=>$value['category_name'],
												"parent_name"=>$value['parent_name'],
												);
						}

						$this->load->library('general_functions');

						//put levels to the categories
						
						$parent_id = $this->general_functions->minValueInArray($result, 'category_id');
						$key=array_search($parent_id, array_column($result,'category_id'));
						$result=$this->category_levels_setter($result,$result[$key]['parent_id'],0);

						$result=$this->general_functions->array_flattener($result);
						$result=$this->category_children_setter($result);
					}
					  
				}       
				else
				{
					$this->status=false;
					//echo '<pre>';
					//print_r($valueFormated);
					//print_r($dbquery1);
					//print_r($dbquery1->errorInfo());
					$this->result_info="Try again, an error Occurred";
					//$this->addition_info = $dbquery1->errorInfo();
					$this->addition_info = 'error_100_1';
				} 
	        		   
	        	return $info = array("status"=>$this->status,
	                            "data"=> array('records'=> $result,
											   'parent_id'=>$parent_id,
											   'count'=>$count,
											   'total_records'=>$total_records,
											   'result_info'=>$this->result_info,
											   ),
	                            "addition_info"=>$this->addition_info);	   	       
	}
    
	public function addCategory($data=array())
	{
	    //reset default variable;
	    $this->reset_defaults();

	    if(!isset($data['user_id'])) $data['user_id']='';
	    if(!isset($data['add_category'])) $data['add_category']=array();


	    // check if required data is supplied      
	    if(empty($data) || empty($data['add_category']))
	    {
	        $this->addition_info='error 100_1';
	        //$addition_info = "empty user_id ";
	         
	        $this->fail_result=true;   
	    }
	    // get parent position
	    if(!$this->fail_result)   
	    {   

			$dbquery1=$this->db->conn_id->prepare("
			SELECT * 
			FROM `category`
			WHERE `category_id`=:category_id
			");
			$data['add_category'][0]['parent_id']=preg_replace("/[^a-zA-Z0-9]+/","",$data['add_category'][0]['parent_id']);
			$dbquery1->bindParam(":category_id",$data['add_category'][0]['parent_id']);
			       
	        if(!($dbquery1->execute()))
	        {
	            //print_r($dbquery1->errorInfo());
	            $this->fail_result=true;
	            $this->addition_info='error 102_1';
	            
	        }else
	        {
	            $sql_result = $dbquery1->fetch();
	            //if parent not found
	            if(count($sql_result)<1 || empty($sql_result))
	            {
		            $this->fail_result=true;
		            $this->addition_info='error 102_2';
		            
	            }
	        }
	    }

	    // create space for the new entry
	    $this->db->conn_id->beginTransaction();
	    if(!$this->fail_result)   
	    {   
			$lft=$sql_result['lft'];
			$rght=$sql_result['rght'];
	    	$increaseBy=count($data['add_category'])*2;

			$dbquery2=$this->db->conn_id->prepare("
							UPDATE `category` 
							SET `rght`=`rght`+ ".$increaseBy." 
							WHERE `rght`>".$lft." 
							ORDER BY `rght` DESC
			");

			$dbquery3=$this->db->conn_id->prepare("
							UPDATE `category` 
							SET `lft`=`lft`+ ".$increaseBy." 
							WHERE `lft`>".$lft." 
							ORDER BY `lft` DESC
			");    
	        
	        if(!($dbquery2->execute()) || !($dbquery3->execute()))
	        {
	            //print_r($dbquery2->errorInfo());
	            //print_r($dbquery3->errorInfo());
	            //print_r($dbquery2);
	            $this->fail_result=true;
	            $this->addition_info='error 103_1';
	            
	        }
	    }

	    // create space for the new entry
	    if(!$this->fail_result)   
	    {   
			$rght_temp=$lft+1;
			
			foreach($data['add_category'] as $key =>$value)
			{
				$dbquery[$key]=$this->db->conn_id->prepare("INSERT INTO `category` 
															SET `parent_id`=:parent_id,	
																`lft`=".$rght_temp.",
																`rght`=".($rght_temp+1).",
																`category_name`=:category_name");

				$dbquery[$key]->bindParam(":parent_id",$data['add_category'][0]['parent_id']);
				$dbquery[$key]->bindParam(":category_name",$data['add_category'][$key]['category_name']);
						
				if(!($dbquery[$key]->execute()))
				{
		            //print_r($data);
		           // print_r($dbquery[0]->errorInfo());
		            $this->fail_result=true;
		            $this->addition_info='error 104_1 :'.$key;
		            
				}
				
				$rght_temp=$rght_temp+2;			
			}	
	    }	
	
		//commit
		if(!$this->fail_result)
		{
			$this->db->conn_id->commit();
			$this->status=true;
			$this->result_info="category(s) added ";
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

	public function editCategory($data=array())
	{
	    //reset default variable;
	    $this->reset_defaults();

	    if(!isset($data['user_id'])) $data['user_id']='';
	    if(!isset($data['edit_category'])) $data['edit_category']=array();


	    // check if required data is supplied      
	    if(empty($data) || empty($data['edit_category']))
	    {
	        $this->addition_info='error 100_1';
	        //$addition_info = "empty user_id ";
	         
	        $this->fail_result=true;   
	    }

	    // create space for the new entry
	    $this->db->conn_id->beginTransaction();
	    if(!$this->fail_result)   
	    {   
			$dbquery2=$this->db->conn_id->prepare("
							UPDATE `category` 
							SET `category_name`=:category_name
							WHERE `category_id` =:category_id
			");
			$dbquery2->bindParam(":category_id",$data['edit_category'][0]['category_id']);
			$dbquery2->bindParam(":category_name",$data['edit_category'][0]['category_name']);
	        
	        if(!($dbquery2->execute()))
	        {
	            //print_r($dbquery2->errorInfo());
	            //print_r($dbquery3->errorInfo());
	            //print_r($dbquery2);
	            $this->fail_result=true;
	            $this->addition_info='error 103_1';
	            
	        }
	    }
	
		//commit
		if(!$this->fail_result)
		{
			$this->db->conn_id->commit();
			$this->status=true;
			$this->result_info="category(s) edited ";
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

    
	public function deleteCategory($data=array())
	{
	    //reset default variable;
	    $this->reset_defaults();

	    if(!isset($data['user_id'])) $data['user_id']='';
	    if(!isset($data['delete_category'])) $data['delete_category']=array();


	    // check if required data is supplied      
	    if(empty($data) || empty($data['delete_category']))
	    {
	        $this->addition_info='error 100_1';
	        //$addition_info = "empty user_id ";
	         
	        $this->fail_result=true;   
	    }
	    // get parent position
	    if(!$this->fail_result)   
	    {   

			$dbquery1=$this->db->conn_id->prepare("
			SELECT * 
			FROM `category`
			WHERE `category_id`=:category_id
			");
			$data['delete_category'][0]['category_id']=preg_replace("/[^a-zA-Z0-9]+/","",$data['delete_category'][0]['category_id']);
			$dbquery1->bindParam(":category_id",$data['delete_category'][0]['category_id']);
			       
	        if(!($dbquery1->execute()))
	        {
	            //print_r($dbquery1->errorInfo());
	            $this->fail_result=true;
	            $this->addition_info='error 102_1';
	            
	        }else
	        {
	            $sql_result = $dbquery1->fetch();
	            //if parent not found
	            if(count($sql_result)<1 || empty($sql_result))
	            {
		            $this->fail_result=true;
		            $this->addition_info='error 102_2';
		            
	            }
	        }
	    }

	    
	    $this->db->conn_id->beginTransaction();
	    // delete
	    if(!$this->fail_result)   
	    {   

			$lft=$sql_result['lft'];
			$rght=$sql_result['rght'];
	    	$reduceBy=(($rght+1)-$lft);
			
			$dbquery3=$this->db->conn_id->prepare("DELETE FROM `category` 
														WHERE `lft`> ".($lft-1)." AND `lft`<".($rght+1)
														);
					
			if(!($dbquery3->execute()))
			{
	            //print_r($data);
	           // print_r($dbquery3->errorInfo());
	            $this->fail_result=true;
	            $this->addition_info='error 104_1 ';
			}				
	    }	    

	    //update positions
	    if(!$this->fail_result)   
	    {   
			$dbquery2=$this->db->conn_id->prepare("
							UPDATE `category` 
							SET `rght`=`rght`-".$reduceBy." 
							WHERE `rght`>".$lft." ORDER BY `rght` ASC
			");

			$dbquery3=$this->db->conn_id->prepare("
							UPDATE `category` 
							SET `lft`=`lft`-".$reduceBy." 
							WHERE `lft`>".$lft ."
							ORDER BY `lft` ASC
			");    
	        
	        if(!($dbquery2->execute()) || !($dbquery3->execute()))
	        {
	            //print_r($dbquery2->errorInfo());
	            //print_r($dbquery3->errorInfo());
	            //print_r($dbquery2);
	            $this->fail_result=true;
	            $this->addition_info='error 103_1';
	            
	        }
	    }	
	
		//commit
		if(!$this->fail_result)
		{
			$this->db->conn_id->commit();
			$this->status=true;
			$this->result_info="category(s) added ";
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

}

?>