<?php
require_once('./system/extension/facebook-php-sdk-v4-4.0-dev/autoload.php');
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;

FacebookSession::setDefaultApplication(
  $this->config['facebook']['app-id'],
  $this->config['facebook']['app-secret']
);

$helper_url = $this->config['site']['path'].'signin';
$helper = new FacebookRedirectLoginHelper($helper_url);

try {
  $session = $helper->getSessionFromRedirect();
} catch( FacebookRequestException $ex ) {
  header("Location: index.php");
} catch( Exception $ex ) {
  header("Location: index.php");
}

if (isset($session))
{
  $request = new FacebookRequest( $session, 'GET', '/me?fields=name' );
  $response = $request->execute();

  $graphObject = $response->getGraphObject()->asArray();
  $userid = $graphObject[id];
  $username = $graphObject[name];

  $_SESSION['facebookid'] = $userid;
  $_SESSION['facebookname'] = $username;

  if($_SESSION['from']!==null) {
    $header_url = $this->config['site']['path'].$_SESSION['from'];
    $_SESSION['from'] = null;
  }
  else $header_url = $this->config['site']['path'].$this->config['member']['default-page'];
  echo '<meta http-equiv="refresh" content="0;URL=\''.$header_url.'\'" />';
}
else
{
  $login_url = $helper->getLoginUrl( array( 'scope' => 'public_profile') );
  header("Location: $login_url");
}
?>
