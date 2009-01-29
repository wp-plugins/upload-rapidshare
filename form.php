<?php

require_once("rapidphp.php");
$rapidprenium = $_GET['rapidpremium'];
$rapidid = $_GET['rapidid'];
$rapidpw = $_GET['rapidpw'];
$rapidform = false;
echo '<form name="form_upload" method="post" enctype="multipart/form-data"';
	if (($rapidpremium)&&($rapidid == '')){
		$rapidform = true; 
		echo '<fieldset><div align="center">COMPTE PREMIUM</div><br />';
		echo '<div align="center">identifiant : <input type="text" name="rapidid" />   mot de passe : <input type="password" name="rapidpw" /></div></fieldset><br />';
	}	
	echo '<input type="hidden" name="recu" value="votre lien" "style:/><input type="file" name="fileupload" id="upfile_0" class="myinput-file" size="25" tabindex="1" />
		<input type="checkbox" name="urlnom" /> lien avec un nom  <input type="text" name="nomurl" />

	<input type="submit" name="submit" value="Upload" />
	</form>';
if ($rapidform) {
	$rapidid = $_POST['rapidid'];
	$rapidpw = $_POST['rapidpw'];
}
	$urlnom = $_POST["urlnom"];
	$source = $_FILES[fileupload][tmp_name];
 	$dest = '/tmp/'.$_FILES[fileupload][name];
if ($source){
	copy($source,$dest);
}
if ($_FILE[fileupload]) {
	unlink($source); unlink($dest);
}		
//echo 'fichier : '.$dest.'<br />';
$upload = new rapidphp;
if ($rapidpremium) {
	$upload->config("prem",$rapidid,$rapidpw);
}
$retour = $upload -> sendfile($dest);


$adresse= $retour[0];

if ($retour[0] != "")
{
  
	$resultat = $adresse;
  
// on affiche
//**********************************************************
  if (empty($_POST["urlnom"]))
  {
  	$resultat = '<a href=\''.$resultat.'\' ></a>' ;
  }	
  else
  {
	 $resultat = '<a href=\''.$resultat.'\' >'.$_POST["nomurl"].'</a>' ;
  } 
  
  echo '<a href="form.php">uploader un autre fichier</a>' ;
  echo '<br /><script type="text/javascript">document.getElementById(\'upfile_0\').type = "text";</script>' ;
  echo '<script type="text/javascript">document.getElementById(\'upfile_0\').value = "'.$resultat.'";</script>' ;  
  echo '<script type="text/javascript">document.getElementsByName(\'submit\')[0].type = "button";document.getElementsByName(\'submit\')[0].value = "votre lien"</script>' ;
}

?>