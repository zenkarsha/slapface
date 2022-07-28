<?php

class adminController extends View
{
  var $model;
  function __construct ()
  {
    include './system/controller/partial/__construct.php';
  }
}
class admin extends adminController
{
  function __construct()
  {
    parent::__construct();

    echo pageCreator($this->config,'default',array(
      '$content' => viewParser('admin.html',array(
        '$message' => 'You are ADMINISTRATOR!'
      ))
    ));
  }
}
?>
