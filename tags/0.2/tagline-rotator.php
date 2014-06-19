<?php

/*
Plugin Name: Tagline Rotator
Plugin URI: http://neverblog.net/tagline-rotator-plugin-for-wordpress
Description: Displays a random tagline from a database list. You can manage taglines through Settings->Tagline Rotator.
Version: 0.2
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

// tagline_options_page() displays the page content for the Test Options submenu
function tagline_options_page() {


//loop through the checkboxes to see what to delete
if(isset($_POST['box']))
{
$box = $_POST['box'];

foreach ($box as $x) {

        $query = "DELETE FROM wp_tagline_rotator WHERE random_tagline='" . $x . "' LIMIT 1";


 
 

        $result = mysql_query($query) or die('Query did not work');
  
}

}  

// check for new tagline and insert if found
if(isset($_POST['new_tagline']))
{

$new_tagline = $_POST['new_tagline'];

$query = "INSERT INTO wp_tagline_rotator SET random_tagline=\"" . $new_tagline . "\"";
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
// delete any empty rows in the form
$query = "DELETE FROM wp_tagline_rotator WHERE random_tagline = ''";
mysql_query($query);

// and select the non-empty ones to populate our checkboxes
$query = "SELECT random_tagline FROM wp_tagline_rotator WHERE random_tagline!=''";
$result = mysql_query($query);
while($row = mysql_fetch_array($result, MYSQL_ASSOC))
{
?>

<tr valign="top">
<th scope="row">Delete this tagline</th>
<?php $sanitized_tagline = preg_replace('/"/','&quot;',$row['random_tagline']); ?>
<td><input type="checkbox" name="box[]" value="<?php echo $sanitized_tagline;?>" /><?php echo " " . $row['random_tagline'];?></td></tr>

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
          add_filter('bloginfo','mybloginfo',1,2);
}



// main function to create tables
function createtables ()
{
$tagline_tables = get_option('tagline_tables');


if($tagline_tables == 'false')
{
$query = "CREATE TABLE wp_tagline_rotator (id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,random_tagline VARCHAR( 200 ) NOT NULL)";
echo $query;
mysql_query($query) or die ('Could not create tagline table');
update_option('tagline_tables','true');
}

}

// edited bloginfo function. pay special attention to description case
function mybloginfo($output='', $show='') {
        
	$query = "SELECT random_tagline FROM wp_tagline_rotator ORDER BY rand() limit 1";
   	$result = mysql_query($query);
        while($row = mysql_fetch_array($result, MYSQL_ASSOC))
	{
		$random_tagline = $row['random_tagline'];
	} 
	
	switch($show) {
                case 'url' :
                case 'home' : // DEPRECATED
                case 'siteurl' : // DEPRECATED
                        $output = get_option('home');
                        break;
                case 'wpurl' :
                        $output = get_option('siteurl');
                        break;
                case 'description':
                        $output = $random_tagline; //here is where we removed the existing function
                        break;
                case 'rdf_url':
                        $output = get_feed_link('rdf');
                        break;
                case 'rss_url':
                        $output = get_feed_link('rss');
                        break;
                case 'rss2_url':
                        $output = get_feed_link('rss2');
                        break;  
                case 'atom_url':
                        $output = get_feed_link('atom');
                        break;
					 case 'comments_atom_url':
                        $output = get_feed_link('comments_atom');
                        break;
                case 'comments_rss2_url':  
                        $output = get_feed_link('comments_rss2');
                        break;
                case 'pingback_url':
                        $output = get_option('siteurl') .'/xmlrpc.php';
                        break;
                case 'stylesheet_url':
                        $output = get_stylesheet_uri();
                        break;
                case 'stylesheet_directory':
                        $output = get_stylesheet_directory_uri();
                        break;
                case 'template_directory':
                case 'template_url':
                        $output = get_template_directory_uri();
                        break;
                case 'admin_email':
                        $output = get_option('admin_email');
                        break;  
                case 'charset': 
                        $output = get_option('blog_charset');
                        if ('' == $output) $output = 'UTF-8';
			break;
		case 'html_type' :
                        $output = get_option('html_type');
                        break;
                case 'version':
                        global $wp_version;
                        $output = $wp_version;
                        break;
                case 'language':
                        $output = get_locale();
                        $output = str_replace('_', '-', $output);
                        break;
                case 'text_direction':
                        global $wp_locale;
                        $output = $wp_locale->text_direction;
                        break;
                case 'name':
                default:
                        $output = get_option('blogname');
                        break;
        }
        
        $url = true;
        if (strpos($show, 'url') === false && 
         strpos($show, 'directory') === false &&
                strpos($show, 'home') === false)
                $url = false;
                        
        if ( 'display' == $filter ) {
                if ( $url )
                        $output = apply_filters('bloginfo_url', $output, $show);
                else
                        $output = apply_filters('bloginfo', $output, $show);
        }
                        
        return $output;
}
?>
