<?php

class Model
{
	public $dataAccess;

	function __construct($dataAccess)
	{
		$this->dataAccess=$dataAccess;
	}
	function getData()
	{
		if ($data=$this->dataAccess->getRow())
			return $data;
		else
			return false;
	}

	//read
	function commonSelect($table,$where=null,$order=null,$sort=null,$limit=null)
	{
    if($where!==null)
    {
      $where=explode('|',$where);
      $whereScript=' WHERE `'.$where[0].'` = \''.$where[1].'\' ';
    }
		if($order!==null) $orderScript=' ORDER BY `'.$order.'` '.$sort.' ';
		if($limit!==null)
		{
			$limit=explode('|',$limit);
			$limitScript=' LIMIT '.$limit[0].','.$limit[1].' ';
		}

		$sql = "SELECT * FROM `$table`".$whereScript.$orderScript.$limitScript;
		$this->dataAccess->fetch($sql);
	}
  function checkFav($imgid,$fbid)
  {
    $sql = "SELECT * FROM `favorite` WHERE `imgid` = '$imgid' AND `fbid` = '$fbid'";
    $this->dataAccess->fetch($sql);
  }
  function selectFavorite($fbid,$limit=null)
  {
    if($limit!==null)
    {
      $limit=explode('|',$limit);
      $limitScript=' LIMIT '.$limit[0].','.$limit[1].' ';
    }
    $sql = "SELECT `og_image`.`id`,`og_image`.`title` FROM `favorite` LEFT JOIN `og_image` ON `favorite`.`imgid` = `og_image`.`id` WHERE `favorite`.`fbid` = '$fbid' ORDER BY `favorite`.`id` DESC".$limitScript;
    $this->dataAccess->fetch($sql);
  }
  function randImage($total)
  {
    $sql = "SELECT * FROM `og_image` ORDER BY RAND() LIMIT 0,$total;";
    $this->dataAccess->fetch($sql);
  }
  function selectCategoryWithImage($limit=null)
  {
    if($limit!==null)
    {
      $limit=explode('|',$limit);
      $limitScript=' LIMIT '.$limit[0].','.$limit[1].' ';
    }
    $sql = "SELECT distinct `category`.`id`,`category`.`name`,`category`.`total`,`og_image`.`title` FROM `category` INNER JOIN `og_image` ON `og_image`.`tag` LIKE CONCAT('%', `category`.`name` ,'%') GROUP BY `category`.`id`".$limitScript;
    $this->dataAccess->fetch($sql);
  }

  //insert
  function ogimageInsert($title,$image,$fbid,$date,$tag)
  {
    $sql = "INSERT INTO `og_image` (`id`, `title`, `image`, `fbid`, `date`, `tag`) VALUES ( '', '$title', '$image', '$fbid', '$date', '$tag')";
    $this->dataAccess->fetch($sql);
  }
  function pendingInsert($title,$image,$tag,$fbid,$ip,$device)
  {
    $sql = "INSERT INTO `pending` (`id`, `title`, `image`, `tag`, `fbid`, `date`, `ip`, `device`) VALUES ( '', '$title', '$image', '$tag', '$fbid', NOW(), '$ip', '$device')";
    $this->dataAccess->fetch($sql);
  }
  function favInsert($fbid,$imgid)
  {
    $sql = "INSERT INTO `favorite` (`id`, `fbid`, `imgid`) VALUES ( '', '$fbid', '$imgid')";
    $this->dataAccess->fetch($sql);
  }
  function categoryInsert($name)
  {
    $sql = "INSERT INTO `category` (`id`, `name`, `view`, `total`) VALUES ( '', '$name', '0', '1')";
    $this->dataAccess->fetch($sql);
  }

  //delete
  function commonDelete($table,$id)
  {
    $sql = "DELETE FROM `$table` WHERE `id` = '$id'";
    $this->dataAccess->fetch($sql);
  }
  function deleteFavorite($fbid,$imgid)
  {
    $sql = "DELETE FROM `favorite` WHERE `fbid` = '$fbid' AND `imgid` = '$imgid'";
    $this->dataAccess->fetch($sql);
  }

  //search
  function search($keyword)
  {
    $sql = "SELECT * FROM `og_image` WHERE `title` LIKE '%$keyword%' OR `tag` LIKE '%$keyword%'";
    $this->dataAccess->fetch($sql);
  }
  function searchTags($keyword)
  {
    $sql = "SELECT * FROM `og_image` WHERE `tag` LIKE '%$keyword%'";
    $this->dataAccess->fetch($sql);
  }

  //count
  function countTotal($table)
  {
    $sql = "SELECT count(*) as total FROM $table";
    $res=mysql_query($sql);
    $data=mysql_fetch_assoc($res);
    return $data[total];
  }
  function favoriteTotal($fbid)
  {
    $sql = "SELECT count(*) as total FROM `favorite` WHERE `fbid` = '$fbid'";
    $res=mysql_query($sql);
    $data=mysql_fetch_assoc($res);
    return $data[total];
  }

  //update
  function viewUpdate($id)
  {
    $sql = "UPDATE `og_image` SET view = view + 1 WHERE `id` = $id";
    $this->dataAccess->fetch($sql);
  }
  function favoriteUpdate($id)
  {
    $sql = "UPDATE `og_image` SET favorite = favorite + 1 WHERE `id` = $id";
    $this->dataAccess->fetch($sql);
  }
  function favoriteRemove($id)
  {
    $sql = "UPDATE `og_image` SET favorite = favorite - 1 WHERE `id` = $id";
    $this->dataAccess->fetch($sql);
  }
  function categoryUpdate($name)
  {
    $sql = "UPDATE `category` SET total = total + 1 WHERE `name` = $name";
    $this->dataAccess->fetch($sql);
  }
}

?>
