=== Tagline Rotator ===
Contributors: vhauri
Donate link: http://neverblog.net/tagline-rotator-plugin-for-wordpress/
Tags: taglines, random, header
Requires at least: 2.0.2
Tested up to: 2.6

Tagline Rotator plugin randomly selects a tagline from a list of user-entered taglines.

== Description ==

The Tagline Rotator plugin does pretty much what it sounds like it would do: it randomly selects a tagline 
(that’s the description of your blog beneath the title) from a list of user-compiled taglines, then displays 
it within your blog. It offers a couple of advantages over some of the other similar plugins I found, most 
importantly that it uses the mySQL database within WordPress, and therefore should not slow down page loads 
significantly.

== Installation ==

Installation of the plugin is a simple three-step process:

   1. Download the plugin and unzip it.
   2. Copy the tagline_rotator.php file to the plugins directory of your WordPress install.
   3. Activate the plugin in the Plugins menu within your admin pages. Taglines can be added through Settings -> Tagline Rotator.

== Frequently Asked Questions ==

= Can I use this plugin with wp-cache or a similar caching plugin? =

I have spent some time investigating this question, and while I believe it is possible to maintain a dynamic 
header while using a cache module, it requires including so many headers that it renders the cache relatively 
meaningless. Instead, consider that users will probably see a random tagline each time they visit a new page 
regardless of who cached the tagline.

= Can I use this plugin to rotate any other content on my blog? =

Not right now, but if you ask for something by posting a comment [here](http://neverblog.net/tagline-rotator-plugin-for-wordpress "here"), I'd be happy to work on 
it. I just can't think of anything I need off-hand.

== Known Bugs/Limitations ==
Of course, the current version of this plugin is 0.2, so there are a couple of bugs/limitations that you should know about.

    * You must choose ‘Save Changes’ to commit any deletions or additions to the tagline database. Hopefully, this will soon be automated
    * The plugin currently does not delete the tables it creates upon de-activation (that way you don’t lose your taglines). However, if you manually delete the 
table, WordPress will throw an error upon re-activation of the plugin. The easiest way to fix this is to change the option ‘tagline_tables’ in wp_options to be 
‘false’ NB: If you’re not comfortable manually editing your mySQL database, don’t worry. The table will just sit there and work if you re-activate the plugin.
    * It still won’t do the dishes.

== Updates ==

Version 0.2 - 7-26-2008 - Fixes an issue where double quotes in a tagline prevent it from being deleted. Thanks to Thorsten for pointing out this bug.
