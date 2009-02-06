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
	echo '<span name="recu" style="display:none;">votre lien : </span><input type="file" name="fileupload" id="upfile_0" class="myinput-file" size="25" tabindex="1" onclick="copie_lien()" />
		<span name="url"> nom du lien <input type="text" name="nomurl" />

	<input type="submit" name="submit" value="Upload" /></span>
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
  /*if (empty($_POST["urlnom"]))
  {
  	$resultat = '<a href=\''.$resultat.'\' ></a>' ;
  }	
  else
  {*/
	 $resultat = '<a href=\''.$resultat.'\' >'.$_POST["nomurl"].'</a>' ;
  //} 
  
  echo '<a href="form.php">uploader un autre fichier</a>' ;
  echo '<br /><script type="text/javascript">document.getElementById(\'upfile_0\').type = "text";</script>' ;
  echo '<script type="text/javascript">document.getElementById(\'upfile_0\').value="'.$resultat.'";</script>' ;  
  echo '<script type="text/javascript">document.getElementsByName(\'url\')[0].style.display = "none";document.getElementsByName(\'recu\')[0].style.display = ""</script>' ;

}
?>
<script type="text/javascript">
function copie_lien() {
	var lien = document.getElementsByName("fileupload");
	var lien1 = " "+ lien[0].value+" ";
	parent.tinyMCE.execCommand('mceInsertContent',false,lien1);
}
</script>
<?php
?>