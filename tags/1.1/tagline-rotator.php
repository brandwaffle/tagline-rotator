<?php

/*
Plugin Name: Tagline Rotator
Plugin URI: http://neverblog.net/tagline-rotator-plugin-for-wordpress
Description: Displays a random tagline from a database list. You can manage taglines through Settings->Tagline Rotator.
Version: 1.1
Author: Vasken Hauri
Author URI: http://neverblog.net
*/

/*  
Copyright 2008  Vasken Hauri  (email : vhauri (at) gmail dot com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/


//new in version 1.1 to call the upgrade function
register_activation_hook(__FILE__,'tagline_upgrade_check');

// set the option for the tables to false until created
add_option('tagline_tables','false');

// check to make sure the tables exist
$tagline_set = get_option('tagline_tables');

filter_bloginfo();

if($tagline_set != 'true')
{
update_option('tagline_tables','false');
}

// create the tables
createtables();

// Hook for adding admin menus
add_action('admin_menu', 'tagline_add_pages');

// action function for above hook
function tagline_add_pages() {
    // Add a new submenu under Options:
    add_options_page('Tagline Rotator', 'Tagline Rotator', 8, 'taglineoptions', 'tagline_options_page');
}

// tagline_options_page() displays the page content for the Tagline Rotator submenu
function tagline_options_page() {

	//new in version 1.1. sets the database value to the wordpress database prefix
	global $wpdb;
	$table_name = $wpdb->prefix . "tagline_rotator";

//loop through the checkboxes to see what to delete
if(isset($_POST['box']))
{
	$box = $_POST['box'];

	foreach ($box as $x) 
	{

        $query = "DELETE FROM " . $table_name ." WHERE id='" . $x . "' LIMIT 1";
        $result = mysql_query($query) or die('Query did not work');
  
	}
	
}  

if(isset($_POST['text']))
			{
			$text = $_POST['text'];
			$id_numbers = $_POST['vhtr_ids'];		
			foreach ($text as $key => $y)
				{
					$query = "UPDATE " . $table_name . " SET random_tagline='" . $y . "' WHERE id='" . $id_numbers[$key] . "' LIMIT 1";
					$result = mysql_query($query) or die ('Could not update tagline');									
				}
			}

// check for new tagline and insert if found
if(isset($_POST['new_tagline']))
{
	//new in version 1.1. sets the database value to the wordpress database prefix
	global $wpdb;
	$table_name = $wpdb->prefix . "tagline_rotator";

	$new_tagline = $_POST['new_tagline'];

	$query = "INSERT INTO " . $table_name . " SET random_tagline=\"" . $new_tagline . "\"";
	mysql_query($query) or die ('Unable to add tagline.');

}

?>

<!-- begin options page form -->
<div class="wrap">
<h2>Tagline Rotator Options</h2>
<p><strong>PLEASE NOTE:</strong> You must click 'Save Changes' in order to permanently add or delete a tagline.</p>
<hr>
<form method="post">
<h3>Delete Taglines</h3>
<table class="form-table">
<?php wp_nonce_field('update-options'); ?>

<?php
	//new in version 1.1. sets the database value to the wordpress database prefix
	global $wpdb;
	$table_name = $wpdb->prefix . "tagline_rotator";

// delete any empty rows in the form
$query = "DELETE FROM " . $table_name . " WHERE random_tagline = ''";
mysql_query($query);

// and select the non-empty ones to populate our checkboxes
$query = "SELECT random_tagline,id FROM " . $table_name . " WHERE random_tagline!=''";
$result = mysql_query($query);
while($row = mysql_fetch_array($result, MYSQL_ASSOC))
{
?>

<tr valign="top">
<th scope="row">Delete this tagline</th>
<?php $sanitized_tagline = preg_replace('/"/','&quot;',$row['random_tagline']); ?>
<td><input type="checkbox" name="box[]" value="<?php echo $row['id'];?>" /><input type="text" size="80" name="text[]" value="<?php echo $sanitized_tagline;?>" /><input type="hidden" name="vhtr_ids[]" value="<?php echo $row['id'];?>" /></td></tr>

<?php
}
?>
</table>
<br>
<hr>
<h3>Add New Taglines</h3>
<table class="form-table">
<tr valign="top">
<th scope="row">Add a new tagline</th>
<td><input type="text" name="new_tagline" size="80" /></td></tr>
</table>

<p class="submit">
<input type="submit" name="Submit" value="<?php _e('Save Changes') ?>" />
</p>
<?php
}

// add a filter to replace the default bloginfo function with our own

function filter_bloginfo(){
          add_filter('option_blogdescription','mybloginfo',1,2);

}



// main function to create tables
function createtables ()
{

	//new in version 1.1. sets the database value to the wordpress database prefix
	global $wpdb;
	$table_name = $wpdb->prefix . "tagline_rotator";
	
	$tagline_tables = get_option('tagline_tables');


	if($tagline_tables == 'false')
	{
		$query = "CREATE TABLE " . $table_name . " (id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,random_tagline VARCHAR( 200 ) NOT NULL)";
		echo $query;
		mysql_query($query) or die ('Could not create tagline table');
		update_option('tagline_tables','true');
	}

}

// edited bloginfo function. pay special attention to description case
function mybloginfo($output = '') {
	
	//new in version 1.1. sets the database value to the wordpress database prefix
	global $wpdb;
	$table_name = $wpdb->prefix . "tagline_rotator";

	$query = "SELECT random_tagline FROM " . $table_name . " ORDER BY rand() limit 1";
   	$result = mysql_query($query);
        while($row = mysql_fetch_array($result, MYSQL_ASSOC))
	{
		$random_tagline = $row['random_tagline'];
	} 
	return $random_tagline;
}	

//new to version 1.1 function to add version info and check if a table needs to be renamed

function tagline_upgrade_check(){

	//new in version 1.1. sets the database value to the wordpress database prefix
	global $wpdb;
	$table_name = $wpdb->prefix . "tagline_rotator";

	$installed_ver = get_option( "tagline_db_version" );

    if( $installed_ver != "1.1" ) {
		
		if($wpdb->get_var("SHOW TABLES LIKE 'wp_tagline_rotator'") != $table_name) {
			
			$query = "RENAME TABLE wp_tagline_rotator TO " . $table_name;
			mysql_query($query) or die('The query failed');
		}

		add_option("tagline_db_version", "1.1");

   }
}
?>
