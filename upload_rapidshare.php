<?php
/*
Plugin Name: upload rapidshare
Plugin URI: http://jessai.fr.nf
Description: just upload file to rapidshare and get code to put in your post
Version: 1.3.2
Author: jessai
Author URI: http://jessai.fr.nf
*/

if(get_option('upload_rapid_OK') == '' || get_option('upload_rapid_OK') == 'NO') {
	delete_option('upload_rapid_premium');
	delete_option('upload_rapid_id');
	delete_option('upload_rapid_mp');
		
	add_option('upload_rapid_premium', false, 'Compte Premium', false);
	add_option('upload_rapid_id', '', 'Identifiant', false);
	add_option('upload_rapid_mp', '', 'Mot de passe', false);
}

function upload_rapid_subpanel() {
	if($_POST['Submit']) {
		if(get_option('upload_rapid_OK') == '')	add_option('upload_rapid_OK', 'YES', "Setting indicating", false);
			update_option('upload_rapid_premium',trim($_POST['upload_rapid_premium']));
			update_option('upload_rapid_id',trim($_POST['upload_rapid_id']));
			update_option('upload_rapid_mp',trim($_POST['upload_rapid_mp']));
		}
?>
	<div class="wrap">
	<h2>Param&egrave;tres du plugin : Upload Rapidshare</h2>
	<form method="post">
	<p class="submit">
		<input type="submit" name="Submit" value="Mettre &agrave; jour les options &raquo;" />
	</p>
	<fieldset class="options">
	        <table class="editform optiontable">
        <tr valign="middle">
			<th scope="row">Compte Premium :</th>
			<td><input type="checkbox" id="upload_rapid_premium" name="upload_rapid_premium"  <?php if (get_option("upload_rapid_premium")) { ?> checked="checked" <?php } ?> /> Compte Premium</td>
		</tr>
        <tr valign="middle">
			<th scope="row">Identifiant :</th>
			<td><input type="text" id="upload_rapid_id" name="upload_rapid_id" value="<?PHP echo get_option("upload_rapid_id"); ?>" size="10" /></td>
		</tr>
		<tr valign="middle">
			<th scope="row">Mot de passe :</th>
			<td><input type="text" id="upload_rapid_mp" name="upload_rapid_mp" value="<?PHP echo get_option("upload_rapid_mp"); ?>" /></td>
		</tr>
		</table>
	</fieldset>
    <p class="submit">
        <input type="submit" name="Submit" value="Mettre &agrave; jour les options &raquo;" />
    </p>
    </form>
    </div>
<?PHP
}

function upload_rapid_add_options() {
	add_options_page('upload rapid', 'Rapidshare', 8, basename(__FILE__), 'upload_rapid_subpanel');
}

add_action('admin_menu', 'upload_rapid_add_options');

// This function tells WP to add a new "meta box"
function add_rapid_box() {
	add_meta_box(
		'rapid',  // id of the <div> we'll add
		'Upload vers RapidShare', //title
		'add_rapid', // callback function that will echo the box content
		'post', // where to add the box: on "post", "page", or "link" page
		'advanced',
		'high'
	);
}
// This function echoes the content of our meta box
function add_rapid() {
	echo '<div class="inside"><object type="text/html" id="rapidshare" data="../wp-content/plugins/upload-rapidshare/form.php?rapidpremium='.get_option("upload_rapid_prenium").'&rapidid='.get_option("upload_rapid_id").'&rapidpw='.get_option("upload_rapid_mp").'" width="100%"';if (get_option(upload_rapid_id)=='') { echo 'height="160px">'; } else { echo 'height="100px">'; } echo '</object></div>';
}

// Hook things in, late enough so that add_meta_box() is defined
	add_action('admin_menu', 'add_rapid_box');
add_action('admin_menu', 'upload_rapid_add_options');

?>