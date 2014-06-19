<?php

/*
Plugin Name: Tagline Rotator
Plugin URI: http://neverblog.net/tagline-rotator-plugin-for-wordpress
Description: Displays a random tagline from a database list. You can manage taglines through Settings->Tagline Rotator.
Version: 2.3 
Author: Vasken Hauri
Author URI: http://neverblog.net
*/

/*  
Copyright 2008-2011  Vasken Hauri  (email : vhauri (at) gmail dot com)

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

if( !class_exists('WP_Tagline_Rotator') ){
	class WP_Tagline_Rotator{

		public function __construct(){
			if(is_admin())
				add_action('admin_menu', array($this, 'add_pages'));
			else
				add_filter('bloginfo', array($this, 'filter_bloginfo'),11,2);
			
			register_activation_hook(__FILE__,array('WP_Tagline_Rotator', 'tagline_upgrade_check'));
		}
	
		public static function add_pages() {
				// Add a new submenu under Options:
				add_options_page('Tagline Rotator', 'Tagline Rotator', 'manage_options', 'taglineoptions', array('WP_Tagline_Rotator', 'tagline_options_page'));
		}

		public static function tagline_upgrade_check(){
			global $wpdb;
			$old_taglines = $wpdb->get_results( "SELECT * FROM " . $wpdb->prefix . "tagline_rotator" );
			$taglines = get_option('tagline_rotator_taglines');
			
			foreach($old_taglines as $old_tagline){
				$taglines[] = $old_tagline->random_tagline;		
			}

			update_option('tagline_rotator_taglines', $taglines);
		}

		public static function tagline_options_page() {
			$taglines = get_option('tagline_rotator_taglines');

			if( !empty($_POST) && check_admin_referer('tagline-rotator-update-options', 'tagline-rotator-nonce') )
			{
			
				if(isset($_POST['text'])){
					$text = $_POST['text'];
					foreach ($text as $key => $tagline){
						if($tagline !== '')
							$taglines[$key] = wp_kses_post($tagline);
						}
					}
				
				//loop through the checkboxes to see what to delete
				if(isset($_POST['box'])){
					$box = $_POST['box'];
					foreach ($box as $x){
						unset($taglines[$x]);
					}	
				}  
				
				// check for new tagline and insert if found
				if(isset($_POST['new_tagline']) && $_POST['new_tagline'] !== ''){
					$new_tagline = wp_kses_post($_POST['new_tagline']);
					$taglines[] = $new_tagline;
				}
				
				update_option('tagline_rotator_taglines', $taglines);
			}
			?>

			<!-- begin options page form -->
			<div class="wrap">
			<h2>Tagline Rotator Options</h2>
			<p><strong>PLEASE NOTE:</strong> You must click 'Save Changes' in order to permanently add or delete a tagline.</p>
			<form method="post">
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
			<hr>
			<h3>Delete Taglines</h3>
			<table class="form-table">
			<?php wp_nonce_field('tagline-rotator-update-options', 'tagline-rotator-nonce'); ?>

			<?php
			foreach($taglines as $key => $tagline){
			?>

			<tr valign="top">
			<th scope="row">Delete this tagline</th>
			<?php $sanitized_tagline = preg_replace('/"/','&quot;',$tagline); ?>
			<td><input type="checkbox" name="box[<?php echo $key; ?>]" value="<?php echo $key;?>" /><input type="text" size="80" name="text[<?php echo $key; ?>]" value="<?php echo stripslashes($sanitized_tagline);?>" /><input type="hidden" name="vhtr_ids[]" value="<?php echo $key;?>" /></td></tr>

			<?php
			}
			?>
			</table>
			<br>
			<p class="submit">
			<input type="submit" name="Submit" value="<?php _e('Save Changes') ?>" />
			</p>
			<?php
		}

		public static function filter_bloginfo($output = '', $show){
			if($show == 'description'){
				$taglines = get_option('tagline_rotator_taglines');
				foreach($taglines as $tagline){
					$tagline_array[] = $tagline;
				}
				if($tagline_array !== FALSE){
					$tagline_counter = count($tagline_array);
					$key = rand(0, $tagline_counter - 1);	
					$output = $tagline_array[$key];
				}
			}
			return stripslashes($output);
		}	

	}
}//end WP_Tagline_Rotator class

if( class_exists('WP_Tagline_Rotator')){
	new WP_Tagline_Rotator;
}
