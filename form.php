<?php

require_once("rapidphp.php");

echo '<form name="form_upload" method="post" enctype="multipart/form-data"
		<input type="hidden" name="recu" value="votre lien" "style:/><input type="file" name="fileupload" id="upfile_0" class="myinput-file" size="25" tabindex="1" />
		<input type="checkbox" name="urlnom" /> lien avec un nom  <input type="text" name="nomurl" />
		<input type="submit" name="submit" value="Upload" />
	</form>';

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
