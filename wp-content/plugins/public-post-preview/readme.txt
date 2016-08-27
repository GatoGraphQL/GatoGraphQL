=== Public Post Preview ===
Contributors: ocean90
Tags: public, post, preview, posts, custom post types, draft
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=VR8YU922B7K46
Requires at least: 3.5
Tested up to: 4.4
Stable tag: 2.4.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Enables you to give a link to anonymous users for public preview of a post before it is published.

== Description ==

Enables you to give a link to anonymous users for public preview of a post (or any other public post type) before it is published.

Have you ever been writing a post with the help of someone who does not have access to your blog and needed to give them the ability to preview it before publishing? This plugin takes care of that by generating an URL with an expiring nonce that can be given out for public preview.

**Sounds pretty good? Install now!**

*Previously this plugin was maintained by [Matt Martz](http://profiles.wordpress.org/sivel/) and was an idea of [Jonathan Dingman](http://profiles.wordpress.org/jdingman/).*

= Feedback =
If you want, you can drop me a line @[ocean90](http://twitter.com/ocean90) on Twitter or @[Dominik Schilling](https://plus.google.com/+DominikSchilling/) on Google+.

= More =
Try also some of my [other plugins](http://profiles.wordpress.org/users/ocean90) or visit my site [wpGrafie.de](http://wpgrafie.de/).

*Thanks to Hans Dinkelberg for his [photo](http://www.flickr.com/photos/uitdragerij/7516234430/).*

== Installation ==

Note: There will be NO settings page.

For an automatic installation through WordPress:

1. Go to the 'Add New' plugins screen in your WordPress admin area
1. Search for 'Public Post Preview'
1. Click 'Install Now' and activate the plugin


For a manual installation via FTP:

1. Upload the `public-post-preview` directory to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' screen in your WordPress admin area


To upload the plugin through WordPress, instead of FTP:

1. Upload the downloaded zip file on the 'Add New' plugins screen (see the 'Upload' tab) in your WordPress admin area and activate.

== Screenshots ==

1. Edit Posts Page

== Usage ==
* To enable a public post preview check the box below the edit post box.
* The link will be displayed if the checkbox is checked, just copy and share the link with your friends.
* To disable a preview just uncheck the box.

== Frequently Asked Questions ==

**After some time the preview link returns the message "The link has been expired!". Why?**

The plugin generates an URL with an expiring nonce. By default a link "lives" 48 hours. After 48 hours the link is expired and you need to copy and share a new link which is automatically generated on the same place under the editor.


**48 hours are not enough to me. Can I extend the nonce time?**

Yes, of course. You can use the filter `ppp_nonce_life`. Example for 5 days:

`add_filter( 'ppp_nonce_life', 'my_nonce_life' );
function my_nonce_life() {
	return 60 * 60 * 24 * 5; // 5 days
}`

Or use the [Public Post Preview Configurator](https://wordpress.org/plugins/public-post-preview-configurator/).

== Change Log ==
= 2.4.1 (2015-10-13): =
* Update text domain to support language packs. Translations are now managed via http://translate.wordpress.org/projects/wp-plugins/public-post-preview.

= 2.4 (2014-08-21): =
* Supports EditFlow and custom statuses
* Disables comments and pings during public post preview
* Adds **Public Preview** to the list of display states used in the Posts list table
* Prevents flickering of link box on Post edit while loading

= 2.3 (2013-11-18): =
* Introduces a filter `ppp_preview_link`. With the filter you can adjust the preview link.
* If a post has gone live, redirect to it's proper permalink.
* Adds the query var `_ppp` to WordPress SEO by Yoast whitelist.

= 2.2 (2013-03-15): =
* Based on feedback I have removed the extra metabox and added the preview link to the main Publish metabox.
* Only show the checkbox if the post status/post type is good.
* Requires WordPress 3.5.

= 2.1.1 (2012-09-19): =
* Sorry for the new update. Through a change in 2.1 a filter was applied to each query. The misplaced "The link has been expired!" message is now gone. Props Aki Björklund and Jonathan Channon.

= 2.1 (2012-09-16): =
* Introduces a filter `ppp_nonce_life`. With the filter you can adjust the expiration of a link. By default a link has a lifetime of 48 hours.
* In some situations (still not sure when) the preview link is rewritten as a permalink which results in an error. The plugin now works in this situations too.

= 2.0.1 (2012-07-25): =
* Makes the preview link copyable again

= 2.0 (2012-07-23): =
* Support for all public post types
* Saves public preview status via an AJAX request
* I18n
* Requires at least WordPress 3.3

= 1.3 (2009-06-30): =
* Hook in earlier in the post selection process to fix PHP notices
* Add uninstall functionality to remove options from the options table

= 1.2 (2009-03-30): =
* Fix preview URL for scheduled posts on sites with a permalink other than default activated.

= 1.1 (2009-03-11): =
* Don't limit public previews to posts in draft or pending status.  Just exclude posts in publish status.

= 1.0 (2009-02-20): =
* Initial Public Release
