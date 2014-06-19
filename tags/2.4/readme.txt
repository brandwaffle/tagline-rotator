=== Tagline Rotator ===
Contributors: vhauri
Donate link: http://neverblog.net/tagline-rotator-plugin-for-wordpress/
Tags: taglines, random, header
Requires at least: 2.9
Tested up to: 3.3
Stable tag: trunk

Tagline Rotator plugin randomly selects a tagline from a list of user-entered taglines.

== Description ==

**Update 12-28-10: If you're running an updated version of WP (i.e. 3.0 or later), the plugin may not show your taglines at first. If you deactivate and reactivate the plugin, your taglines should appear. This is due to a change in WordPress that you can read about [here](http://wpdevel.wordpress.com/2010/10/27/plugin-activation-hooks-no-longer-fire-for-updates/).** The Tagline Rotator plugin does pretty much what it sounds like it would do: it randomly selects a tagline 
(that’s the description of your blog beneath the title) from a list of user-compiled taglines, then displays 
it within your blog. It offers a couple of advantages over some of the other similar plugins I found, most 
importantly that it uses the mySQL database within WordPress, and therefore should not slow down page loads 
significantly. As of version 1.1, it is now WP database prefix compliant, meaning it will use the database prefix stored in your WordPress settings, and should be WPMU compliant.

== Installation ==

Installation of the plugin is a simple three-step process:

   1. Download the plugin and unzip it.
   2. Copy the tagline_rotator.php file to the plugins directory of your WordPress install.
   3. Activate the plugin in the Plugins menu within your admin pages. Taglines can be added through Settings -> Tagline Rotator.

== Frequently Asked Questions ==

= Can I use this plugin with WP Super Cache or a similar caching plugin? =

Sure, but right now it's not using any cached data. That should be included in the next version.

= Can I use this plugin to rotate any other content on my blog? =

Not right now, but if you ask for something by posting a comment [here](http://neverblog.net/tagline-rotator-plugin-for-wordpress "here"), I'd be happy to work on 
it. I just can't think of anything I need off-hand.

== Known Bugs/Limitations ==
Of course, the current version of this plugin is 1.1, so there are a 
couple of bugs/limitations that you should know about.

    * You must choose ‘Save Changes’ to commit any deletions or additions to the tagline database. Hopefully, this will soon be automated
    * The plugin currently does not delete the tables it creates upon de-activation (that way you don’t lose your taglines). However,
      if you manually delete the table, WordPress will throw an error upon re-activation of the plugin. The easiest way to fix this is to change the option 
      ‘tagline_tables’ in wp_options to be ‘false’ NB: If you’re not comfortable manually editing your mySQL database, don’t worry. The table will just sit there 
      and work if you re-activate the plugin.
    * It still won’t do the dishes.

== Updates ==

= 0.2 = 
* Fixes an issue where double quotes in a tagline prevent it from being deleted. Thanks to Thorsten for pointing out this bug.
= 0.3 =
* Allows you to edit taglines without deleting and recreating them.
= 0.4b = 
* Allows compatibility with themes which use either the bloginfo() or get bloginfo() function to display the tagline.
= 1.0 = 
* Allows compatibility with themes which use either the bloginfo() or get bloginfo() function to display the tagline. Has been tested good.
= 1.1 = 
* Plugin database table is now compliant with WordPress database prefix (i.e. it will use the database prefix set by WP, not the previous wp_tagline_rotator). This should make it WPMU compatible as well, although this is still untested. Upgrading automatically through WordPress from a previous =  will also rename the database table to use the correct prefix.
= 1.2 = 
* Updated UI in Admin Settings to put Save button and add functionality at the top.
= 1.3 = 
* DO NOT DOWNLOAD: fixed an issue with taglines not displaying in headers, but included tagline from Settings=>General by mistake. Use 1.4 instead.
= 1.4 =
* Fixed me shutting my brain off on =  1.3. Displays tagline in header, but does not duplicate taglines.
= 2.0 =
* Changed plugin to class=based structure and cleaned up a lot of the logic. 
* Removed all MySQL queries and switched to using options for storing taglines. 
* Added an upgrade function to pull old taglines from table=based = s of the plugin that is called on activation.
= 2.1 = 
* Fixes an issue with slashes not being properly escaped, and an issue with sorting that could cause a duplicate tagline to appear when changing an existing tagline. If you have =  2.0, you should upgrade immediately.
= 2.2 =
* Fixes a bug that could cause taglines not to display.
= 2.3 =
* Allow HTML tags permitted in posts to appear in taglines
* Adds nonce verification and admin referer check
