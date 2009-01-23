<?php
/*
Plugin Name: upload rapidshare
Plugin URI: http://jessai.fr.nf/archives/6
Description: just upload file to rapidshare and get code to put in your post
Version: 1.0.0
Author: salledewordpress
Author URI: http://jessai.fr.nf
*/
// This function tells WP to add a new "meta box"
function add_rapid_box() {
	add_meta_box(
		'rapid',  // id of the <div> we'll add
		'Upload vers rapidshare', //title
		'add_rapid', // callback function that will echo the box content
		'post', // where to add the box: on "post", "page", or "link" page
		'normal',
		'high'
	);
}
// This function echoes the content of our meta box
function add_rapid() {
	echo '<div class="inside"><object type="text/html" id="rapidshare" data="../wp-content/plugins/upload-rapidshare/form.php" width="800px" height="135px"></object></div>';
}
// Hook things in, late enough so that add_meta_box() is defined
if (is_admin())
	add_action('admin_menu', 'add_rapid_box');
?>