<?php
	class ImageManager{
	    private static $instance;
        public function __construct(){ }
        public static function getInstance() {
			if (self::$instance == NULL) { self::$instance = new ImageManager(); } 
			return self::$instance;
		}
        // Resize
            function resizeImage($img, $imgPath = "", $suffix = "-resized", $width = 120, $height = 120, $quality = 100, $crop = 0){
                $real_image = $img;
                $type = strtolower(substr(strrchr($img,"."),1));
                if($type == 'jpeg') $type = 'jpg';
                if(!list($w, $h) = getimagesize($imgPath.$img)) return null;
                switch($type){
                    case 'bmp': $img = imagecreatefromwbmp($imgPath.$img); break;
                    case 'gif': $img = imagecreatefromgif($imgPath.$img); break;
                    case 'jpg': $img = imagecreatefromjpeg($imgPath.$img); break;
                    case 'png': $img = imagecreatefrompng($imgPath.$img); break;
                    default : return null;
                }
                //++ resize
                    if($crop){
                        if($w < $width or $h < $height) return null;
                        $ratio = max($width/$w, $height/$h);
                        $x = ($w - $width / $ratio) / 2;
                        $y = ($h - $height / $ratio) / 2;
                        $h = $height / $ratio;
                        $w = $width / $ratio;
                    }
                    else{
                        if($w < $width and $h < $height) return null;
                        $ratio = min($width/$w, $height/$h);
                        $width = $w * $ratio;
                        $height = $h * $ratio;
                        $x = 0;
                        $y = 0;                        
                    }
                //--
                //++ create
                    $newName = explode(".", $real_image);
                    $newName = ''. $newName[0] .''. $suffix .'.'. $newName[1] .'';
                    
                    $new = imagecreatetruecolor($width, $height);
                    if($type == "gif" or $type == "png"){
                        imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
                        imagealphablending($new, false);
                        imagesavealpha($new, true);
                    }
                    imagecopyresampled($new, $img, 0, 0, $x, $y, $width, $height, $w, $h);
                    switch($type){
                        case 'bmp': imagewbmp($new, $imgPath.$newName); break;
                        case 'gif': imagegif($new, $imgPath.$newName); break;
                        case 'jpg': imagejpeg($new, $imgPath.$newName); break;
                        case 'png': imagepng($new, $imgPath.$newName); break;
                    }
                    imagedestroy($new);
                    imagedestroy($img);
                    return $newName;
                //--
                
                // Open the original image.
                    if($extension == "jpg" || $extension == "jpeg") $original = imagecreatefromjpeg($imgPath.$img) or die($imgPath.$real_image);
                    else if($extension == "png") $original = imagecreatefrompng($imgPath.$img) or die($imgPath.$real_image);
                    else if($extension == "gif") $original = imagecreatefromgif($imgPath.$img) or die($imgPath.$real_image);
                    list($width, $height, $type, $attr) = getimagesize($imgPath.$img);
                    if($width <= $new_width || $height <= $new_height){ $new_width = $width; $new_height = $height; };
                // Determine new width and height.
                    if($priority == "width"){
                        $newWidth = $new_width;
                        $newHeight = $height/($width/$new_width);
                    }else{
                        $newHeight = $new_height;
                        $newWidth = $width/($height/$new_height);
                    }
                // Create the new file name.
                    $newNameE = explode(".", $img);
                    $newName = ''. $newNameE[0] .''. $suffix .'.'. $newNameE[1] .'';
                // Save the image.
                    if($extension == "jpg" || $extension == "jpeg"){
                        $tempImg = imagecreatetruecolor($newWidth, $newHeight) or die($imgPath.$real_image);
                        imagecopyresampled($tempImg, $original, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height) or die($imgPath.$real_image);
                        imagejpeg($tempImg, $imgPath.$newName, $quality) or die($imgPath.$real_image);
                    }else if($extension == "png"){
                        $tempImg = imagecreatetruecolor($newWidth, $newHeight);
                        imagealphablending($tempImg, false);
                        imagesavealpha($tempImg, true);
                        $transparent = imagecolorallocatealpha($tempImg, 0, 0, 0, 127);
                        imagefilledrectangle($tempImg, 0, 0, $newWidth, $newHeight, $transparent);
                        imagecopyresampled($tempImg, $original, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                        imagepng($tempImg, $imgPath.$newName) or die($imgPath.$real_image);
                    }else if($extension == "gif"){
                        $tempImg = imagecreatetruecolor($newWidth, $newHeight) or die($imgPath.$real_image);
                        imagecopyresampled($tempImg, $original, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height) or die($imgPath.$real_image);
                        imagegif($tempImg, $imgPath.$newName) or die($imgPath.$real_image);
                    }
                // Clean up.
                    imagedestroy($original);
                    imagedestroy($tempImg);
                return $newName;
            }
        // Tinify
            function tinify($image, $key = "", $binary = false){
                require_once "tinify/Tinify.php";
                require_once "tinify/Tinify/Source.php";
                require_once "tinify/Tinify/Client.php";
                require_once "tinify/Tinify/ResultMeta.php";
                require_once "tinify/Tinify/Result.php";
                require_once "tinify/Tinify/Exception.php";
                Tinify\setKey($key);
                $result = null;
                if($binary){ 
                    $result = Tinify\fromBuffer($image)->toBuffer();
                }else{
                    $source = \Tinify\fromFile($image);
                    $source->toFile($image);
                    $result = $image;
                };
                return $result;
            }
	}
?>