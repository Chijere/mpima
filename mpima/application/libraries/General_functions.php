<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class General_functions 
{

  	public  function wordTrimmer($text, $max=100, $append='&hellip;')
  	{
      	if (strlen($text) <= $max) return $text;
      	$out = substr($text,0,$max);
      	if (strpos($text,' ') === FALSE) return $out.$append;
      	return preg_replace('/\w+$/','',$out).$append;
  	}

    public function maxValueInArray($array, $keyToSearch)
    {
        $currentMax = NULL;
        foreach($array as $arr)
        {
            foreach($arr as $key => $value)
            {
                if ($key == $keyToSearch && ($value >= $currentMax))
                {
                    $currentMax = $value;
                }
            }
        }
        return $currentMax;
    }

    public function minValueInArray($arr, $key)
    {
        $min = array();
        foreach ($arr as $val) {
            if (!isset($val[$key]) and is_array($val)) {
                $min2 = min_by_key($val, $key);
                $min[$min2] = 1;
            } elseif (!isset($val[$key]) and !is_array($val)) {
                return false;
            } elseif (isset($val[$key])) {
                $min[$val[$key]] = 1;
            }
        }

        return min( array_keys($min) );
    }     

// flattens a multi-dimension array
// testing up to 3 levels :default
	public function array_flattener($array=array(),$levels=4) { 
	  if (empty($array)) { 
		return FALSE; 
	  }

   	if (count($array) != count($array, COUNT_RECURSIVE))
      	{	
      		for ($i=0; $i <$levels ; $i++) { 
      			$array=$this->array_single_level_flattener($array);
      		}
       	 	 
      	}

	  return $array; 
	}

  #flattener sub method
  public function array_single_level_flattener($array=array()) { 
    if (empty($array)) { 
    return FALSE; 
    }

    foreach ($array as $key => $value) { 
      #flaten multidimension array
       if (count($value) != count($value, COUNT_RECURSIVE))
          {
            $varsTemp = $value;
            unset($array[$key]);
            $array=array_merge($array,$varsTemp); 
          }
    }

    return $array; 
  }

  #sorts array based on specified key
  public  function sksort(&$array, $subkey="id", $sort_ascending=false) 
  {

      if (count($array))
          $temp_array[key($array)] = array_shift($array);

      foreach($array as $key => $val){
          $offset = 0;
          $found = false;
          foreach($temp_array as $tmp_key => $tmp_val)
          {
              if(!$found and strtolower($val[$subkey]) > strtolower($tmp_val[$subkey]))
              {
                  $temp_array = array_merge(    (array)array_slice($temp_array,0,$offset),
                                              array($key => $val),
                                              array_slice($temp_array,$offset)
                                            );
                  $found = true;
              }
              $offset++;
          }
          if(!$found) $temp_array = array_merge($temp_array, array($key => $val));
      }

      if ($sort_ascending) $array = array_reverse($temp_array);

      else $array = $temp_array;
  }
  
}

?>