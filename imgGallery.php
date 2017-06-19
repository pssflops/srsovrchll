<!doctype html>
<html>
 <head>
  <link rel="stylesheet" href="css/foundation.min.css" type="text/css">
  <link rel="stylesheet" href="icons/foundation-icons.css" type="text/css">
  
  <title>User-submitted Artwork</title>

  <style>
    body{
      background: #f1f7f9;
      color: #212121;
    }
    #imgg{
      text-align: center;
      vertical-align:middle;
    }
    #imgg p{
      display:inline-block;
      overflow:auto!important;
      background-color:white;
      padding:5px;
    }
    #imgg u{
      color: #0000FF;
    }
    ul >li {
      padding: none;
    }
  </style>
 </head>

 <body>

 <div class='icon-bar three-up'>
   <a href='/index.html' class="item" target='_blank'>
    <i class="fi-upload-cloud"></i>
    <label>Upload your own artwork.</label> 
  </a>
  <a href='/index.html' class="item" target='_blank'>
    <i class="fi-blind"></i> 
    <label>Browse ALL Attachments.</label> 
  </a>
  <a href='/index.html' class="item" target='_blank'>
    <i class="fi-social-windows"></i> 
	<label>Return to Serious Overchill.</label> 
  </a>
 </div>
 
  <div id="imgContainer" class="container-fluid">
  <?php
    /* function:  generates thumbnail */
    function make_thumb($src,$dest,$desired_width) {
    	/* read the source image */
    	$info = getimagesize($src);
        $mime = $info['mime'];
    	$width = $info[0];
    	$height = $info[1];
    	/* find the "desired height" of this thumbnail, relative to the desired width  */
    	$desired_height = floor($height*($desired_width/$width));
    	/* create a new, "virtual" image */
    	$virtual_image = imagecreatetruecolor($desired_width,$desired_height);
    	/* copy source image at a resized size */
    	    // APPENDED RESIZE CODE //
        switch ($mime) {
                case 'image/jpeg':
    imagealphablending($virtual_image, false);
    imagesavealpha($virtual_image, true);			
    			
                        $image_create_func = 'imagecreatefromjpeg';
                        $image_save_func = 'imagejpeg';
                        $new_image_ext = 'jpg';
                        break;
    
                case 'image/png':	
    imagealphablending($virtual_image, false);
    imagesavealpha($virtual_image, true);			
    			
                        $image_create_func = 'imagecreatefrompng';
                        $image_save_func = 'imagepng';
                        $new_image_ext = 'png';
                        break;
    
                case 'image/gif':
    imagealphablending($virtual_image, false);
    imagesavealpha($virtual_image, true);			
     	                $image_create_func = 'imagecreatefromgif';
                        $image_save_func = 'imagegif';
                        $new_image_ext = 'gif';
                        break;
    
                default: 
                        throw Exception('Unknown image type.');
        }
        $img = $image_create_func($src);
    
    	imagecopyresampled($virtual_image,$img,0,0,0,0,$desired_width,$desired_height,$width,$height);
        
        $image_save_func($virtual_image, $dest);
    	/* create the physical thumbnail image to its destination
    	imagejpeg($virtual_image,$dest); */
    }
    
    /* function:  returns files from dir */
    function get_files($images_dir,$exts = array('jpg','gif','png','jpeg')) {
    	$files = array();
    	if($handle = opendir($images_dir)) {
    		while(false !== ($file = readdir($handle))) {
    			$extension = strtolower(get_file_extension($file));
    			if($extension && in_array($extension,$exts)) {
    				$files[] = $file;
    			}
    		}
    		closedir($handle);
    	}
    	return $files;
    }
    
    /* function:  returns a file's extension */
    function get_file_extension($file_name) {
    	return substr(strrchr($file_name,'.'),1);
    }
  ?>
  
  <h1> Recent Uploads: </h1>
  <ul class="large-block-grid-4" >
  <?
    /** settings **/
    $images_dir = 'tmp/';
    $thumbs_dir = 'tmp-thumbs/';
    $thumbs_width = 300;
    $images_per_row = 4;
    
    /** generate photo gallery **/
    $image_files = get_files($images_dir);
    if(count($image_files)) {
    	$index = 0;
    	foreach($image_files as $index=>$file) {
    		$index++;
    		$thumbnail_image = $thumbs_dir.$file;
    		if(!file_exists($thumbnail_image)) {
    			$extension = get_file_extension($thumbnail_image);
    			if($extension) {
    				make_thumb($images_dir.$file,$thumbnail_image,$thumbs_width);
    			}
    		}
    		echo '<li><span> <a href="#" id="rel" filename="',$images_dir.$file,'" data-reveal-id="imgLarge" class="th" data-reveal title="',$file,'"><img src="',$thumbnail_image,'" class="thumbnail" /></a> </span> </li>';
    		if($index % $images_per_row == 0) { echo '<div class="clear"></div>'; }
    	}
    }
    else {
    	echo '<p>There are no images in this gallery.</p>';
    }
    
  ?>
  </ul>

  </div>

  <div id="imgLarge" class="large reveal-modal" data-reveal>
    <div id="imgg"><p></p> </div>
    <a class="close-reveal-modal">&#215;</a>
  </div>

  <script src="js/jquery-3.2.1.min.js" type="text/javascript"></script>
  <script type="text/javascript" src="js/foundation.min.js"></script>
  </body>
</html>