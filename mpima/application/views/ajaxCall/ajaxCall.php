<?php
   if($print_as=='array')
   		print_r($info);
   elseif ($print_as=='json')
    {
    	header('content-type: application/json; charset=utf-8');
       	echo json_encode($info);
    }	
   else
   	  	print($info);

?>