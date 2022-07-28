<?php

class memberController extends View
{
  var $model;
  function __construct ()
  {
    include './system/controller/partial/__construct.php';
  }
}
class signin extends memberController
{
  function __construct()
  {
    parent::__construct();

    if(isset($_SESSION['facebookid']))
    {
      $url = $this->config['site']['path'].$this->config['member']['default-page'];
      header("Location: $url");
    }
    else
    {
      include './system/extension/facebookLogin.php';
    }
  }
}
class logout extends memberController
{
  function __construct()
  {
    parent::__construct();

    session_destroy();
    $url = $this->config['site']['path'];
    header("Location: $url");
  }
}
class register extends memberController
{
  function __construct()
  {
    parent::__construct();

    if(isset($_GET['from']))
    {
      $_SESSION['from'] = urlencode($_GET['from']);
    }

    $og_url = './system/view/_og.php';
    require_once './system/view/_header.php';
    require_once './system/view/register.php';
    require_once './system/view/_footer.php';
  }
}
class member extends memberController
{
  function __construct()
  {
    parent::__construct();

    echo pageCreator($this->config,'default',array(
      '$content' => viewParser('member.html',array(
        '$message' => 'You are member'
      ))
    ));
  }
}
?>
