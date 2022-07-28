<?php

class imageController extends View
{
  var $model;
  function __construct ()
  {
    include './system/controller/partial/__construct.php';
  }
}
class ajax extends imageController
{
  function __construct()
  {
    parent::__construct();

    include './system/class/createImage.php';

    //post attribute
    @$text1 = $_POST['text1'];
    @$text2 = $_POST['text2'];
    @$text3 = $_POST['text3'];
    @$role = $_POST['role'];
    @$source = $_POST['source'];
    @$directpost = 2;

    //create object
    $obj = new createImage();
    $obj -> Create($text1, $text2, $text3, $role, $source, $directpost);
  }
}
class generate extends imageController
{
  function __construct()
  {
    parent::__construct();

    include './system/class/createImage.php';

    //post attribute
    @$text1 = $_POST['text1'];
    @$text2 = $_POST['text2'];
    @$text3 = $_POST['text3'];
    @$role = $_POST['role'];
    @$source = $_POST['source'];
    @$directpost = $_POST['directpost'];

    //create object
    $obj = new createImage();
    $obj -> Create($text1, $text2, $text3, $role, $source, $directpost);
  }
}
class facebookpost extends imageController
{
  function __construct()
  {
    parent::__construct();

    if(isset($_GET['photo'])) {
      $photo = "./temp/".$_GET['photo'].".png";
      if(file_exists($photo)) {
        require_once('./system/extension/php-sdk/facebook.php');

        $config = array(
        'appId' => '',
        'secret' => '',
        'fileUpload' => true,
        );

        $facebook = new Facebook($config);
        $user_id = $facebook->getUser();

        if($user_id) {
          try {
            $user = $facebook->api('/'.$user_id.'/?fields=albums.fields(id,name)');
            $albums=$user['albums']['data'];
            for($i=0;$i<count($albums);$i++) {
              if($albums[$i]['name']=='Timeline Photos') {
                $timelinealbumid=$albums[$i]['id'];
                break;
              }
            }
            $ret_obj = $facebook->api('/'.$timelinealbumid.'/photos', 'POST', array('source' => '@' . $photo));
            //redirect to users facebook
            $url="https://www.facebook.com/".$user_id;
            header("Location: $url");
          } catch(FacebookApiException $e) {
            $login_url = $facebook->getLoginUrl( array('scope' => 'user_photos,photo_upload'));
            error_log($e->getType());
            error_log($e->getMessage());
            header("Location: $login_url");
          }
        } else {
          $login_url = $facebook->getLoginUrl( array( 'scope' => 'user_photos,photo_upload') );
          header("Location: $login_url");
        }
      } else {
        header("Location: index.php");
      }
    } else {
      header("Location: index.php");
    }
  }
}
class example extends imageController
{
  function __construct()
  {
    parent::__construct();

    include './system/extension/example/list.php';

    foreach ($example as $key => $value)
    {
      if($_POST['role']!=='custom')
      {
        if($key!=='custom')
        {
          if($key==$_POST['role']) $select=' selected'; else $select=null;
          $options=$options.'<option value="'.$key.'"'.$select.'>'.$value[0].'</option>';
        }
      }
      else
      {
        if($key==$_POST['role']) $select=' selected'; else $select=null;
        $options=$options.'<option value="'.$key.'"'.$select.'>'.$value[0].'</option>';
      }
    }

    echo viewParser('partial/html/exampleTheme.html', array(
      '$options' => $options,
      '$text1' => $example[$_POST['role']][1],
      '$text2' => $example[$_POST['role']][2],
      '$text3' => $example[$_POST['role']][3]
    ));
  }
}
class uploader extends imageController
{
  function __construct()
  {
    parent::__construct();

    $allowed = array('png', 'jpg', 'jpeg', 'gif');
    if(isset($_FILES['upload']) && $_FILES['upload']['error'] == 0) {

      $extension = strtolower(pathinfo($_FILES['upload']['name'], PATHINFO_EXTENSION));


      if(!in_array(strtolower($extension), $allowed)){
        //echo '{"status":"error1"}';
        exit;
      }

      if($_FILES['upload']['tmp_name']){

        $rolesrc=$_FILES['upload']['tmp_name'];
        if($extension=='jpeg' || $extension=='jpg')
          $roleimage=imagecreatefromjpeg($rolesrc);
        elseif($extension=='png')
          $roleimage=imagecreatefrompng($rolesrc);
        elseif($extension=='gif')
          $roleimage=imagecreatefromgif($rolesrc);

        imagefilter($roleimage,IMG_FILTER_GRAYSCALE);
        imagefilter($roleimage,IMG_FILTER_CONTRAST,-30);

        $roleimage_w=imagesx($roleimage);
        $roleimage_h=imagesy($roleimage);
        $new_w=$roleimage_w/($roleimage_h/315);

        $image = imagecreate($new_w, 315);
        imagecopyresampled($image, $roleimage, 0, 0, 0, 0, $new_w, 315, $roleimage_w, $roleimage_h);

        $save = 'upload/'.$_POST['filename'];
        if($extension=='jpeg' || $extension=='jpg')
          imagejpeg($image,$save,75);
        elseif($extension=='png')
          imagepng($image,$save,9,null);
        elseif($extension=='gif')
          imagegif($image,$save);

        @imagedestroy($image);
        @imagedestroy($roleimage);
        unlink($_FILES['upload']['tmp_name']);

        //echo '{"status":"success"}';
        exit;
      }
    }
    //echo '{"status":"error2"}';
    exit;
  }
}
class contribute extends imageController
{
  function __construct()
  {
    parent::__construct();


    //get user ip
    if (empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      $ip = $_SERVER['REMOTE_ADDR'];
    } else {
      $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
      $ip = $ip[0];
    }
    $datatimes = date("Y-m-d H:i:s");

    @$role = clearString($_POST['role']);
    @$text1 = clearString($_POST['text1']);
    @$text2 = clearString($_POST['text2']);
    @$text3 = clearString($_POST['text3']);
    @$egg = clearString($_POST['egg']);

    //send mail notify
    $message = "<h3>打臉內容投稿</h3>打誰臉：".$role."<br>打臉內容：".$text1."<br>落款：".$text2."<br>小字：".$text3."<br>彩蛋：".$egg."<br><br>瀏覽IP位置: ".$ip."<br>使用設備: ".$_SERVER['HTTP_USER_AGENT']."<br>";

    $sMailTo = 'info@kxgen.net';
    $sBccTo = 'hello@kxgen.net';
    $sSubject = "[打臉內容投稿] 要打 ".$role." 的臉！";
    $sMessage = $message;
    $sHeader  = 'MIME-Version: 1.0' . "\r\n";
    $from_name="打臉圖產生器";
    $from_name="=?UTF-8?B?".base64_encode($from_name)."?=";
    $sHeader .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
    $sHeader .="BCC: $sBccTo\r\n" . 'From: '.$from_name.' <hello@kxgen.net>';
    mail($sMailTo, $sSubject, $sMessage, $sHeader);
  }
}
