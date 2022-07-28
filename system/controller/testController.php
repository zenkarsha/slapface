<?php

class testController extends View
{
  var $model;
  function __construct()
  {
    include './system/controller/partial/__construct.php';
  }
}
class test extends testController
{
  function __construct()
  {
    parent::__construct();
  }
}

?>
