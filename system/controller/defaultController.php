<?php

class View
{
	var $model;
	function __construct()
	{
    include './system/controller/partial/__construct.php';
	}
}
class index extends View
{
  function __construct()
  {
    parent::__construct();

    echo pageCreator($this->config,'default',array(
      '$content' => viewParser('index.html',array(
        '$title' => $this->config['site']['title'],
        '$description' => $this->config['site']['description'],
        '$path' => $this->config['site']['path'],
      )),
      '$foot-custom' => ''
    ));
  }
}
class og extends View
{
  function __construct()
  {
    parent::__construct();

    echo json_encode($this->config['og']);
  }
}
?>
