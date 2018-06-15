<?php
class Id_Generator {

    //default variables

    static function rand_md5($length) 
    {
      $max = ceil($length / 32);
      $random = '';
      for ($i = 0; $i < $max; $i ++) {
        $random .= md5(microtime(true).mt_rand(10000,90000));
      }
      return substr($random, 0, $length);
    }

    static function rand_sha1($length) 
    {
      $max = ceil($length / 40);
      $random = '';
      for ($i = 0; $i < $max; $i ++) {
        $random .= sha1(microtime(true).mt_rand(10000,90000));
      }
      return substr($random, 0, $length);
    }  
            
}  


?>