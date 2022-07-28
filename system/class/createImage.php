<?php
class createImage
{
  private $fontPath = './font/jellyfish20140617.ttf';
  function Create($text1, $text2, $text3, $role, $source, $directpost)
  {
    include_once './system/function/systemFunction.php';

    //create empty image
    $image = imagecreate(850, 315);

    //colors
    $white = imagecolorallocate($image, 255, 255, 255);
    $black = imagecolorallocate($image, 0, 0, 0);
    $grey = imagecolorallocate($image, 128, 128, 128);
    $red = ImageColorAllocate($image, 211,12,14);
    $yellow = ImageColorAllocate($image, 253,240,2);

    //draw background
    if($directpost==2)
    {
      imagesavealpha($image, true);
      $transparent = imagecolorallocatealpha($image, 127, 127, 127, 127);
      imagefill($image, 0, 0, $transparent);
    }
    else
    {
      imagefilledrectangle($image, 0, 0, 850, 315, $black);
    }

    if($role=='custom')
    {
      $rolesrc='./upload/'.$source;
      $ext=explode(".", $source);
      if($ext[1]=='jpeg' || $ext[1]=='jpg')
        $roleimage=imagecreatefromjpeg($rolesrc);
      elseif($ext[1]=='png')
        $roleimage=imagecreatefrompng($rolesrc);
      elseif($ext[1]=='gif')
        $roleimage=imagecreatefromgif($rolesrc);

      $roleimage_w=imagesx($roleimage);
      $roleimage_h=imagesy($roleimage);

      if($directpost!==2)
        imagecopy($image, $roleimage, 0, 0, 0, 0, $roleimage_w, $roleimage_h);
    }
    else
    {
      $rolesrc='./images/role/'.$role.'.jpg';
      $roleimage=imagecreatefromjpeg($rolesrc);
      $roleimage_w=imagesx($roleimage);
      $roleimage_h=imagesy($roleimage);

      if($directpost!==2)
        imagecopy($image, $roleimage, 0, 0, 0, 0, $roleimage_w, $roleimage_h);
    }

    //font path
    $font  = $this->fontPath;

    //line count
    $lines=count(explode("\n", $text1));

    //font size
    if($lines<=2)
    {
      $text1_fontsize = 32;
      $text2_fontsize = 20;
      $text3_fontsize = 14;
    }
    elseif($lines==3)
    {
      $text1_fontsize = 28;
      $text2_fontsize = 20;
      $text3_fontsize = 14;
    }
    elseif($lines==4)
    {
      $text1_fontsize = 22;
      $text2_fontsize = 20;
      $text3_fontsize = 14;
    }
    elseif($lines==5)
    {
      $text1_fontsize = 22;
      $text2_fontsize = 20;
      $text3_fontsize = 14;
    }
    elseif($lines==6)
    {
      $text1_fontsize = 18;
      $text2_fontsize = 18;
      $text3_fontsize = 14;
    }
    else
    {
      $text1_fontsize = 16;
      $text2_fontsize = 16;
      $text3_fontsize = 12;
    }

    //text position
    $text1_x = $roleimage_w + 5;
    $text2_dimensions = imagettfbbox($text2_fontsize, 0, $font, $text2);
    $text2_w = abs($text2_dimensions[4] - $text2_dimensions[0]);
    $text2_x = imagesx($image) - $text2_w - 40;
    $text3_dimensions = imagettfbbox($text3_fontsize, 0, $font, $text3);
    $text3_w = abs($text3_dimensions[4] - $text3_dimensions[0]);
    $text3_x = imagesx($image) - $text3_w - 40;

    //write text
    imagettftext($image, $text1_fontsize, 0, $text1_x, 70, $white, $font, $text1);
    imagettftext($image, $text2_fontsize, 0, $text2_x, 250, $white, $font, $text2);
    imagettftext($image, $text3_fontsize, 0, $text3_x, 274, $white, $font, $text3);

    switch ($directpost) {
      case 1:
        header('Content-Type: image/png');
        $filename=time();
        $save = "./temp/".$filename.".png";
        imagepng($image,$save,0,null);
        $url="facebookpost/?photo=".$filename;
        header("Location: $url");
        break;

      case 2:
        ob_start();
        imagepng($image,null,0,null);
        $image = ob_get_contents();
        //destroy
        ob_end_clean();
        @imagedestroy($image);
        @imagedestroy($roleimage);
        print '<img src="data:image/png;base64,'.base64_encode($image).'"/>';
        break;

      default:
        include './system/extension/example/list.php';
        $filename=$example[$role][0].' 的打臉圖.png';

        header('Content-Type: image/png');
        header("Content-Transfer-Encoding: binary");
        header('Content-Description: File Transfer');
        header('Content-Disposition: attachment; filename='.$filename);

        imagepng($image,null,0,null);
        @imagedestroy($image);
        break;
    }
  }
}
?>
