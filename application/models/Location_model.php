<?php
class Location_model extends CI_Model {

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

	public function getLocation($filters=array())
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

			array_push($WhereSubQueryArry,'(`id` IS NOT NULL) ');			

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
					if($key==='location_id' || ($key==='location_id'.$n) )
					{
						array_push($miniWhereSubQueryArry,'`id`=:id'.$n ); 
						$n++;
					}elseif($key==='location_name' || ($key==='location_name'.$n) )
					{
						array_push($miniWhereSubQueryArry,'`name`=:name'.$n ); 
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
														FROM `location` 
														 	".$WheresubQuery."

														GROUP BY `id`
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
						if($key==='location_id' || ($key==='location_id'.$n) ) 
						{$dbquery1->bindValue(":id".$n,$value2); 	$n++;}
						
						if($key==='location_name' || ($key==='location_name'.$n) ) 
						{$dbquery1->bindValue(":name".$n,$value2); $n++; }
						
									
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
												"location_id"=>$value['id'],
												"location_name"=>$value['name'],
												"location_rank"=>$value['rank'],
												"region"=>$value['region'],
												"description"=>$value['description'],
												);
						}
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
											   'count'=>$count,
											   'total_records'=>$total_records,
											   'result_info'=>$this->result_info,
											   ),
	                            "addition_info"=>$this->addition_info);	   	       
	}
    
}

?>