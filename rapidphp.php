<?php

class rapidphp {


//////////////////////////////////////////////////////////////////
# .________________________________.
# |                                |
# |         www.McSecu.net         |
# |________________________________|
#
#   >> ported from perl to php by rechazz
#
#
#   PHP Class for uploading on Rapidshare.com
#   for non-commercial use only!
#
#   included: upload to free-, collector's and premium-zone, md5-check after upload
#   tested under windows xp only with php 5.2.0 (should work on unix systems too, though)
#
#   Check the "RapidShare AG OpenSource Perl Uploader V1.0." out, too:
#   http://images.rapidshare.com/software/rsapi.pl
#
#
#
#   usage for free-users:
#
#     $upload=new rapidphp;
#     $upload->sendfile("myfile.rar");
#
#
#   usage for premium-zone:
#
#     $upload=new rapidphp;
#     $upload->config("prem","username","password");
#     $upload->sendfile("myfile.zip");
#
#
#   usage for collector's zone:
#
#     $upload=new rapidphp;
#     $upload->config("col","username","password");
#     $upload->sendfile("myfile.tar.gz2");
#
#
#   you can upload several files if you want:
#
#     $upload=new rapidphp;
#     $upload->config("prem","username","password");
#     $upload->sendfile("myfile.part1.rar");
#     $upload->sendfile("myfile.part2.rar");
#     // and so on
#
#   sendfile() returns an array with data of the upload
#    [0]=Download-Link
#    [1]=Delete-Link
#    [2]=Size of the sent file in bytes
#    [3]=md5 hash (hex)
#
//////////////////////////////////////////////////////////////////

         private $maxbuf=64000; // max bytes/packet
         private $uploadpath="l3";
         private $zone,$login,$passwort;

         private function hashfile($filename) { // md5 hash of files
         	return strtoupper(md5_file($filename));
         }
         public function getserver() { // gets server for upload
	         while(empty($server)) {
	         	 $server=file_get_contents("http://rapidshare.com/cgi-bin/rsapi.cgi?sub=nextuploadserver_v1");
 		 }
                 return sprintf("rs%s%s.rapidshare.com",$server,$this->uploadpath);
         }
         public function config($zone,$login="",$passwort="") { // configuration
         	$this->zone=$zone;
                 $this->login=$login;
                 $this->passwort=$passwort;
         }
         public function sendfile($file) { // upload a file
         	if(empty($this->zone)) {
	                 $this->zone="free";
                 }
                 if($this->zone=="prem" OR $this->zone=="col") {
                 	if(empty($this->login) OR empty($this->passwort)) {
                         	$this->zone="free";
                         }
                 }
                 if(!file_exists($file)) {
                 	die("File not found!");
                 }
                 $hash=$this->hashfile($file); // hash of the file
                 $size=filesize($file); // filesize (bytes) of the file
                 $cursize=0; // later needed
                 $server=$this->getserver(); // get server for uploading
                 //print "Using $server:80\n";
          	$sock=	fsockopen($server,80,$errorno,$errormsg,30) or die("Unable to open connection to rapidshare\nError $errorno ($errormsg)");
		stream_set_timeout($sock,3600); // anti timeout
                 $fp=	fopen($file,"r");
                 $boundary = "---------------------632865735RS4EVER5675865";
                 $contentheader="\r\nContent-Disposition: form-data; name=\"rsapi_v1\"\r\n\r\n1\r\n";
                 if($this->zone=="prem") {  // premium
			  $contentheader .= sprintf("%s\r\nContent-Disposition: form-data; name=\"login\"\r\n\r\n%s\r\n",$boundary,$this->login);
			  $contentheader .= sprintf("%s\r\nContent-Disposition: form-data; name=\"password\"\r\n\r\n%s\r\n",$boundary,$this->passwort);
                           print "Upload as a Premium-User\n";
                 }
                 if($this->zone=="col") { // collector
			  $contentheader .= sprintf("%s\r\nContent-Disposition: form-data; name=\"freeaccountid\"\r\n\r\n%s\r\n",$boundary,$this->login);
			  $contentheader .= sprintf("%s\r\nContent-Disposition: form-data; name=\"password\"\r\n\r\n%s\r\n",$boundary,$this->passwort);
                           print "Upload as a Collector\n";
                 }
                 $contentheader .= sprintf("%s\r\nContent-Disposition: form-data; name=\"filecontent\"; filename=\"%s\"\r\n\r\n",$boundary,$file);
		$contenttail = "\r\n".$boundary."--\r\n";
		$contentlength = strlen($contentheader) + $size + strlen($contenttail);
                 $header = "POST /cgi-bin/upload.cgi HTTP/1.0\r\nContent-Type: multipart/form-data; boundary=".$boundary."\r\nContent-Length: ".$contentlength."\r\n\r\n";
                 fwrite($sock,$header.$contentheader);
                 // ok: now we have sent everything except the file!
                 while($cursize < $size) { // If we didn't upload everything, repeat!
                 	$buf=fread($fp,$this->maxbuf) or die(""); // read max bytes from the file
                         $cursize=$cursize+strlen($buf);
                         if(fwrite($sock,$buf)) { // send data
	                         //printf("%d of %d Bytes sent.\n",$cursize,$size);
                         }
                 }
                 fwrite($sock,$contenttail); // finished
                 //printf("All %d bytes sent to the server!\n",$size);
                 $ret=fread($sock,10000); // receive data (links, hash, bytes)
                 preg_match("/\r\n\r\n(.+)/s",$ret,$match); // we don't need the http-header
 		 $ret=explode("\n",$match[1]); // every line gets an entry in an array
		 fclose($sock);
		 fclose($fp);
                 foreach($ret as $id => $cont) {
                 	if($id!=0) { // very boring stuff!
                         	if($id>4) break; // break foreach
                         	$key_val[]=substr($cont,8); // throw away the first eight chars
                         }
                 }
                 if($hash==$key_val[3]) { // if the hash is == hash of the local file
          		return $key_val;
                 } else {  // omg! upload failed!
                 	printf("Upload FAILED! Your hash is %s, while the uploaded file has the hash %s",$hash,$key_val[3]);
                         return FALSE;
                 }
         }
}

?>