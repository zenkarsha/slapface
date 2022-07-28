<?php

function pageCreator($config,$layout='default',$replace=array())
{
  $replace['$head'] = viewParser('_head.html', array(
    '$path' => $config['site']['path'],
    '$title' => $config['site']['title'],
    '$shortcut-icon' => $config['site']['shortcut-icon'],
    '$apple-touch-icon' => $config['site']['apple-touch-icon']
  ));
  $replace['$foot'] = viewParser('_foot.html', array(
    '$path' => $config['site']['path']
  ));
  $replace['$kangxi-analytics'] = viewParser('partial/html/kangxiAnalytics.html',null,$config['site']['parent']);
  $replace['$google-analytics'] = viewParser('partial/html/googleAnalytics.html', array(
    '$analytics-id' => $config['google']['analytics-id']
  ));
  $replace['$facebook-api'] = viewParser('partial/html/facebookApi.html', array(
    '$app-id' => $config['facebook']['app-id']
  ));

  if(!isset($replace['$head-custom']))
    $replace['$head-custom'] = null;
  if(!isset($replace['$foot-custom']))
    $replace['$foot-custom'] = null;
  if(!isset($replace['$navbar']))
  {
    if($config['setting']['enable-navbar-search'] == true)
    {
      $search = viewParser('partial/html/navbarSearch.html');
    }
    else $search = null;
    if($config['setting']['enable-member-system'] == true)
    {
      if(isset($_SESSION['facebookid']))
      {
        $member = viewParser('partial/html/navbarMemberLogged.html', array(
          '$username' => $_SESSION['facebookname'],
          '$path' => $config['site']['path']
        ));
        if(in_array($_SESSION['facebookid'],$config['admin']))
        {
          $member .= viewParser('partial/html/navbarAdmin.html', array(
            '$path' => $config['site']['path']
          ));
        }
      }
      else
      {
        $member = viewParser('partial/html/navbarMember.html', array(
          '$path' => $config['site']['path']
        ));
      }
    }
    else $member = null;
    $replace['$navbar'] = viewParser('_navbar.html', array(
      '$path' => $config['site']['path'],
      '$brand' => $config['site']['name']
    ));
  }
  if(!isset($replace['$og']))
  {
    $replace['$og'] = viewParser('_og.html', array(
      '$title' => $config['og']['title'],
      '$type' => $config['og']['type'],
      '$url' => $config['og']['url'],
      '$image' => $config['og']['image'],
      '$sitename' => $config['og']['sitename'],
      '$description' => $config['og']['description']
    ));
  }
  if(!isset($replace['$header']))
  {
    $replace['$header'] = viewParser('_header.html', null);
  }
  if(!isset($replace['$footer']))
  {
    $replace['$footer'] = viewParser('_footer.html', array(
      '$copyright' => $config['site']['copyright']
    ));
  }
  if(!isset($replace['$content']))
  {
    $replace['$content'] = 'Hello World!';
  }

  $page = file_get_contents('./system/view/layout/'.$layout.'.html');
  foreach($replace as $key => $value)
  {
    $page = str_replace('{{'.$key.'}}', $value, $page);
  }
  $page = preg_replace("/---[\s\S]*---/", "", $page);
  $page = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $page);
  return $page;
}
function navbarDropdownCreator($title,$array=array(),$target=null)
{
  if(isset($target)) $target = ' target="_'.$target.'"';
  else $target = null;
  foreach ($array as $key => $value)
  {
    if($key == 'divider')
      $list .= '<li class="divider"></li>';
    else
      $list .= '<li><a href="'.$value.'"'.$target.'>'.$key.'</a></li>';
  }
  $dropdown = viewParser('partial/html/navbarDropdown.html', array(
    '$title' => $title,
    '$list' => $list
  ));
  return $dropdown;
}
?>
