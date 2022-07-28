<?php

//cofing
include './system/config/systemConfig.php';
$this->config=$config;

//database
if($this->config['setting']['enable-database'] == true)
{
  include './system/class/dataAccess.php';
  include './system/model/siteModel.php';
  $dao=new dataAccess($this->config['database']['host'],$this->config['database']['user'],$this->config['database']['pass'],$this->config['database']['db']);
  $this->model=new Model($dao);
}

?>
