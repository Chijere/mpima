<?php

   # ========================================================================#
   #
   #  Purpose:   (1) resizeImage
   #          	  	   :Resize options  
   #          	   		 - portrait 
   #          	   		 - landscape 
   #         	   		 - auto 
   #			 (2) saveImage
   #			 (3) destroyClass
   #			 
   #  Requires : Requires PHP5, GD library.
   #  Usage Example:
   #                     include("classes/resize_class.php");
   #                     $resizeObj = new image_upload('images/cars/large/input.jpg');
   #                     $resizeObj -> resizeImage(150, 100, 0);
   #                     $resizeObj -> saveImage('images/cars/large/output.jpg', 100);
   #
   #
   # ========================================================================#


		Class Image_manipulation
		{
			// *** Class variables
			private $image;
		    private $imageResized;
			private $input_image_width;
		    private $input_image_height;
		    private $default_width;
		    private $default_height;
		    
		    private $new_image_path;
		    private $image_source;
		    private $user_given_width;
		    private $user_given_height;
		    private $allowedExts;
		    private $max_size; 
		    private $quality;
			private $resize_option;
			
			private $data;
			private $status;
			private $result_info;
			private $addition_info;
			
			/*
				$config= array(
                                'file_name'=>200,
                                'image_source'=>200,
                                'width'=>200,
                                'height'=>200,
                                'max_size'=>'crop',
                                'new_image'=>'crop',
                                'option'=>'crop',
							   );
			
			*/
			function __construct($config=array())
			{	
				#work on the image
				if(!empty($config))
				{	$workedOnImage= $this->Initialize($config);
					return $workedOnImage;
				}
			}

			#--------------------------------------------------
	        private function reset_defaults()
	        {
				// ***reset Class variables
			    
			    $this->default_width=20;
			    $this->default_height=20;

			    $this->user_given_width=$this->default_width;
			    $this->user_given_height=$this->default_height;
			    $this->allowedExts=array("gif", "jpeg", "jpg", "png");
			    $this->max_size=2048000; #in bytes
			    $this->quality=100;
				$this->resize_option='auto';
				
				$this->data = array();
				$this->status = false;
				$this->result_info = false;
				$this->addition_info = false;
				
	        } 			

			## --------------------------------------------------------

			/*
				$config= array(
                                'width'=>200,
                                'height'=>200,
                                'option'=>'crop',
							   );
			
			*/

			public function Initialize($config=array())
			{

				//define variable
				$fail_result=false;
                
                //reset defaults
				$this->reset_defaults();

				#Entry point - assign user given variables and set defaults
                if(isset($config['image_source']))$this->image_source=$config['image_source'];

				if(isset($config['resize_option']))$this->resize_option =$config['resize_option'];
				if(isset($config['height']))$this->user_given_height =$config['height'];
				if(isset($config['width']))$this->user_given_width =$config['width'];
				if(isset($config['allowed_extension']))
				{
					if(is_array($config['allowed_extension']))$this->allowedExts =$config['allowed_extension'];
					else
					{
						$this->allowedExts = explode('|', $config['allowed_extension']);
					}
				}	
				if(isset($config['max_size']))
				{
					$this->max_size =$config['max_size']*1000;
				}
				if(isset($config['quality']))$this->quality =$config['quality'];
				if(isset($config['new_image']))$this->new_image_path =$config['new_image'];
				else$this->new_image_path =$this->image_source;

				# Open up the file
				$openImage = $this->openImage();
				if($openImage==false)$fail_result=true;

				if(!$fail_result)
				{
				    #assign Global variable
					$this->image = $openImage;

					# Get width and height
					$this->input_image_width  = imagesx($this->image);
					$this->input_image_height = imagesy($this->image);
				}

				#resize the image
				if(!$fail_result)
				{
					$resizedImage= $this->resizeImage();
					if(!$resizedImage['status'])$fail_result=true;
				}

				#save the image
				if(!$fail_result)
				{
					$savedImage= $this->saveImage();

					if(!$savedImage['status'])$fail_result=true;
				}			
				
				return $this->get_result_Info();
			}

			## ---------------@-----------------------------------------

			private function openImage()
			{
				//define variable
				$fail_result=false;
				$img = false;
				$uploadedFile = false;
			//reset defaults
				$this->reset_defaults();

				#check for array
				if(is_array($this->image_source))
				{ 
					#for uploaded files/images
					$uploadedFile=true;
					$filePath = $this->image_source["tmp_name"];
				}
				else 
				{
					$extension = explode(".",$this->image_source);
					$extension = end($extension);
					$extension = strtolower($extension);
					$extension=array('data'=>array('extension'=>$extension));
					$filePath = $this->image_source;
				}				
				
				if(!$fail_result && $uploadedFile) #for uploaded files/images
				{
					$extension = $this->check_extension();
					if(!$extension['status'])$fail_result=true;
				}

				if(!$fail_result && $uploadedFile) #for uploaded files/images
				{
					$imageSize = $this->check_size();
					if(!$imageSize['status'])$fail_result=true;
				}	

				
				if(!$fail_result)
				{
						if($extension['data']['extension']=='jpg' || $extension['data']['extension']=='jpeg')
						{
							 if ($img=@imagecreatefromjpeg($filePath))
							     $this->status=true;
							 else
							 	{
							 		$this->status=false;
								 	$this->addition_info='error 100_1';
									$this->result_info='The image seems to be corrupted'; 
								}
						}	
						elseif($extension['data']['extension']=='gif')
						{
							 if ($img=@imagecreatefromgif($filePath))
							     $this->status=true;
							 else
							 	{
							 		$this->status=false;
								 	$this->addition_info='error 100_2';
									$this->result_info='The image seems to be corrupted'; 
								}
						}	
						elseif($extension['data']['extension']=='png')
						{
							 if ($img=imagecreatefrompng($filePath))
							     $this->status=true;
							 else
							 	{
							 		$this->status=false;
								 	$this->addition_info='error 100_3';
									$this->result_info='The image seems to be corrupted'; 
								}
						}	
						else
						{	
						    $this->status=false;
							$this->addition_info='error 100_4';
							$this->result_info='The selected file is not an image';  
						}
					
				}

				return $img;
				
			}

			private function resizeImage($config=array())
			{

			    if($this->user_given_width==0 || $this->user_given_height==0)
				{
					$this->resize_option="default";
				}
					
				if($this->status)
				{
					// *** Get optimal width and height - based on $option
					$optionArray = $this->getDimensions();

					$optimalWidth  = $optionArray['optimalWidth'];
					$optimalHeight = $optionArray['optimalHeight'];


					// *** Resample - create image canvas of x, y size
					if(!($this->imageResized = imagecreatetruecolor($optimalWidth, $optimalHeight)))
					{	
						$addition_info="error 102_1";
						$result_info="Try again, an error occured";
						$this->status=false;
					}	
					
					if($this->status)
						if(!(imagecopyresampled($this->imageResized, $this->image, 0, 0, 0, 0, $optimalWidth, $optimalHeight, $this->input_image_width, $this->input_image_height)))
						{	
							$addition_info="error 102_2";
							$result_info="Try again, an error occured";
							$this->status=false;
						}	
					// *** if option is 'crop', then crop too
					if ($this->resize_option == 'crop' && $this->status) 
					{
						$this->crop($optimalWidth, $optimalHeight);
					}				
				}

				return $this->get_result_Info();				
			}

			## --------------------------------------------------------
			
			private function getDimensions()
			{

			   switch ($this->resize_option)
				{
					case 'default':
						$optimalWidth = $this->input_image_width;
						$optimalHeight= $this->input_image_height;
						break;
					case 'portrait':
						$optimalWidth = $this->getSizeByFixedHeight();
						$optimalHeight= $this->user_given_height;
						break;
					case 'landscape':
						$optimalWidth = $this->user_given_width;
						$optimalHeight= $this->getSizeByFixedWidth();
						break;
					case 'auto':
												
						if(($this->user_given_width==$this->default_width)&&($this->user_given_height=$this->default_height))
						{
							$optimalWidth = $this->input_image_width;
							$optimalHeight = $this->input_image_height;	
						}
						else
						{
							$optionArray = $this->getSizeByAuto();
							$optimalWidth = $optionArray['optimalWidth'];
							$optimalHeight = $optionArray['optimalHeight'];
						}
						break;
					case 'crop':
						$optionArray = $this->getOptimalCrop();
						$optimalWidth = $optionArray['optimalWidth'];
						$optimalHeight = $optionArray['optimalHeight'];
						break;
				}
				return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
			}

			## --------------------------------------------------------

			private function getSizeByFixedHeight()
			{
				$ratio = $this->input_image_width / $this->input_image_height;
				$this->user_given_width = $this->user_given_height * $ratio;
				return $this->user_given_width;
			}

			private function getSizeByFixedWidth()
			{
				$ratio = $this->input_image_height / $this->input_image_width;
				$this->user_given_height = $this->user_given_width * $ratio;
				return $this->user_given_height;
			}

			private function getSizeByAuto()
			{
				if ($this->input_image_height < $this->input_image_width)
				#Image to be resized is wider (landscape)
				{
					$optimalWidth = $this->user_given_width;
					$optimalHeight= $this->getSizeByFixedWidth($this->user_given_width);
				}
				elseif ($this->input_image_height > $this->input_image_width)
				#Image to be resized is taller (portrait)
				{
					$optimalWidth = $this->getSizeByFixedHeight($this->user_given_height);
					$optimalHeight= $this->user_given_height;
				}
				else
				#Image to be resizerd is a square
				{
					if ($this->user_given_height < $this->user_given_width) {
						$optimalWidth = $this->user_given_width;
						$optimalHeight= $this->getSizeByFixedWidth($this->user_given_width);
					} else if ($this->user_given_height > $this->user_given_width) {
						$optimalWidth = $this->getSizeByFixedHeight($this->user_given_height);
						$optimalHeight= $this->user_given_height;
					} else {
						#Sqaure being resized to a square
						$optimalWidth = $this->user_given_width;
						$optimalHeight= $this->user_given_height;
					}
				}

				return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
			}

			## --------------------------------------------------------

			private function getOptimalCrop()
			{

				$heightRatio = $this->input_image_height / $this->user_given_height;
				$widthRatio  = $this->input_image_width /  $this->user_given_width;

				if ($heightRatio < $widthRatio) {
					$optimalRatio = $heightRatio;
				} else {
					$optimalRatio = $widthRatio;
				}

				$optimalHeight = $this->input_image_height / $optimalRatio;
				$optimalWidth  = $this->input_image_width  / $optimalRatio;

				return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
			}

			## --------------------------------------------------------

			private function crop($optimalWidth, $optimalHeight)
			{
				// *** Find center - this will be used for the crop
				$cropStartX = ( $optimalWidth / 2) - ( $this->user_given_width /2 );
				$cropStartY = ( $optimalHeight/ 2) - ( $this->user_given_height/2 );

				$crop = $this->imageResized;
				//imagedestroy($this->imageResized);

				// *** Now crop from center to exact requested size
				if(!($this->imageResized = imagecreatetruecolor($this->user_given_width , $this->user_given_height)))
					{	
						$addition_info="error 103_1";
						$result_info="Try again, an error occured";
						$this->status=false;
					}

				if($this->status )
				{
					if(!(imagecopyresampled($this->imageResized, $crop , 0, 0, $cropStartX, $cropStartY, $this->user_given_width, $this->user_given_height , $this->user_given_width, $this->user_given_height)))
						{	
							$addition_info="error 103_2";
							$result_info="Try again, an error occured";
							$this->status=false;
						}						
				}
			}

			## --------------------------------------------------------
			
			private function check_extension()
			{
				//define variable
				$fail_result=false;
				$result_info=false;
				$addition_info=false;
				$data=false;
				$status=false;
				
				
				if ($fail_result || !(($this->image_source["type"] == "image/jpeg")|| ($this->image_source["type"] == "image/jpg")
					|| ($this->image_source["type"] == "image/gif")|| ($this->image_source["type"] == "image/x-png")|| ($this->image_source["type"] == "image/png")))
				{
				  $addition_info =  "error 104_1";
				  $result_info = " The selected file is not an image";
				  $fail_result=true;
				}

				// *** force to array
				if(!$fail_result && !is_array($this->allowedExts))
				{
					$this->allowedExts = array($this->allowedExts);
				}

				if (!$fail_result && ($this->image_source["error"] <= 0)) 
				{
					$extension = explode(".", $this->image_source["name"]);
					$extension = end($extension);
					$extension = strtolower($extension);
				    $data=array('extension'=>$extension);
					$status= true;
				}
				else
				{
					$addition_info = "error 104_3";
					$result_info =  $this->image_source["error"];
					$fail_result=true;
				}


				#compare to allowed extension
				if($fail_result || !in_array($extension, $this->allowedExts))
				{
					$addition_info =  "error 104_2";
					//$result_info =  "bad_extension";
					$result_info =  "The selected file is not an image";
					$fail_result=true;
				}

				  $info = Array(
								"addition_info" => $addition_info,
								"result_info" => $result_info,
								"data"=>$data,
								"status" => $status
								);
				
				$this->setGlobalError($info);	
				return $this->get_result_Info();
			}	
			
			## --------------------------------------------------------
			
			private function check_size()
			{
				//define variable
				$addition_info=false;
				$data=false;
				$result_info=false;
				$fail_result=false;
				$status=false;
				
				  
				if ($fail_result || $this->image_source["size"] > $this->max_size)
				{  
					$addition_info =  "error 103_1";
					$result_info =  "Image size should be ".$this->max_size.'kb or less';
					$status=false;
				}else
				{
					$data=array('size'=>$this->image_source["size"]);
					$status=True;
				}
					
				  $info = Array(
								"addition_info" => $addition_info,
								"result_info" => $result_info,
								"data"=>$data,
								"status" => $status
								);
				
				$this->setGlobalError($info);	
				return $this->get_result_Info();
			}	
			
			## --------------------------------------------------------
			
			private function directory_check($directory=array())
			{
			    //define variable
				$addition_info=false;
				$result_info=false;
				$fail_result=false;
				$status=false;

				$compare=explode('/',$directory);
				$compare=end($compare);
				$compare=strtolower($compare);
				$directory = substr( $directory, 0, strpos( $directory, $compare ) );
			
				if ($fail_result || !file_exists($directory)) //check-if-temp-dir-exists
				{
				   $fail_result=false;
				   $addition_info = "error 105_1";
				   //$result_info = "The specified directory does not exist";
				   $result_info = "Try again, an error occured";
				}
				else
				{
					$status=true;
				}

				$this->setGlobalError(array('status'=>$status,'addition_info'=>$addition_info,'result_info'=>$result_info));	
				return $this->get_result_Info();	
			}
			
			## --------------------------------------------------------

			private function saveImage()
			{
			    
			    //define variable
			    $addition_info=false;
				$data=false;
				$result_info=false;
				$fail_result=false;
				$status=false;

				#check if there is no existing error
				if(!$this->status)
				{
					$fail_result=true;
				
				}
				
				if(!$fail_result)
				{	
				  	#check if directory exists
				  	$directory_check = $this->directory_check($this->new_image_path);
					if(!$directory_check['status'])$fail_result=true;
				}

				if(!$fail_result)
				{

					#Get extension
					$extension = strtolower(strrchr($this->new_image_path, '.'));
					switch($extension)
					{
						case '.jpg':
						case '.jpeg':
							if (imagetypes() & IMG_JPG) {
								if(imagejpeg($this->imageResized, $this->new_image_path, $this->quality))
									$status=true;		
								else
								{	
									$addition_info="error 106_1";
									$result_info="Try again, an error occured";
								}
							}
							break;

						case '.gif':
							if (imagetypes() & IMG_GIF) {
								if(imagegif($this->imageResized, $this->new_image_path))
									$status=true;
								else
								{	
									$addition_info="error 106_2";
									$result_info="Try again, an error occured";
								}
							}
							break;

						case '.png':
							#Scale quality from 0-100 to 0-9
							$scaleQuality = round(($this->quality/100) * 9);

							#Invert quality setting as 0 is best, not 9
							$invertScaleQuality = 9 - $scaleQuality;

							if (imagetypes() & IMG_PNG) {
								if(imagepng($this->imageResized, $this->new_image_path, $invertScaleQuality))
								 	$status=true;
								else
								{	
									$addition_info="error 106_3";
									$result_info="Try again, an error occured";
								}		
							}
							break;

						// ... etc if any

						default:
							// *** No extension - No save.
							$addition_info="error 106_4";
							//$addition_info="no_extension";
							$result_info="The selected file is not an image";
							break;
					}
                    
					#free space
					imagedestroy($this->imageResized);					
				}
			    
				$this->setGlobalError(array('status'=>$status,'addition_info'=>$addition_info,'result_info'=>$result_info));
			 	return $this->get_result_Info();       		  			    
			}
			
			## --------------------------------------------------------
			private function setGlobalError($pdata=array()){

				if(!isset($pdata['data']))$pdata['data']=array();
				if(!isset($pdata['status']))$pdata['status']='';
				if(!isset($pdata['addition_info']))$pdata['addition_info']='';
				if(!isset($pdata['result_info']))$pdata['result_info']='';
			
							$this->status=$pdata['status'];
							$this->data=$pdata['data'];
							$this->addition_info=$pdata['addition_info'];
							$this->result_info=$pdata['result_info'];
			}
			
			## --------------------------------------------------------
			public function get_result_Info(){
				$this->data['result_info']= $this->result_info;
				return $info=array(
								'status'=>$this->status,
								'data'=>$this->data,
								'addition_info'=>$this->addition_info,
								);
			}
			
			## --------------------------------------------------------
		}
?>
