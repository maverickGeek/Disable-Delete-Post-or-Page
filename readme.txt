=== Disable Delete Post or Page ===
Contributors: jeremyselph
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=RESFMU9LDAEDQ&source=url
Tags: delete post, delete, delete page
Requires at least: 3.1.1
Tested up to: 4.9
Requires PHP: 5.6
License: GPL v3
License URI: http://www.gnu.org/licenses/gpl-3.0.en.html

A WordPress Plugin that allows the administrator to remove the delete post functionality from the wp-admin area.

== Description ==

When handing over a WordPress installation to the end-client, there are always certain pages that you may not want them to delete. It could be pages with custom templates, pages with HTML in the WYSWIG editor or for some reason a page that is hooked in and is not dynamic.

Whatever the reason is the Disable Delete Post or Page Link Plugin removes the ability to delete a post if its option has been previously set. The &quot;Delete&quot; links are removed from the following areas:

1. When viewing the list of All Posts or All Pages.
1. When editing a post in the Publish meta box.

== Important notes ==

* This plugin does not add anything to your current theme.
* It will stop users from deleting posts, pages or other custom posts types if the option has been set.
* The wp_trash_post() or wp_post_delete() functions are not affected and when used posts can and will be deleted.
* The screen options panel is required to use the disable functionality. See [https://make.wordpress.org/support/user-manual/getting-to-know-wordpress/screen-options/](https://make.wordpress.org/support/user-manual/getting-to-know-wordpress/screen-options/).

== Installation ==

1. Visit 'Plugins > Add New'
1. Search for 'Disable Delete Post or Page'
1. Click on 'Install Now'
1. Activate the 'Disable Delete Post or Page' plugin.
1. Go to 'Settings > Disable delete link' and modify.

== How to ==

When using this &quot;Disable delete posts or pages&quot; plugin you can hide/remove the delete links and delete functionality from the areas shown above. Each post, page or custom post needs to be edited and its option set. To do this follow these steps:

1. When editing the post click on &quot;Screen Options&quot; in the upper right-hand corner.
1. After the &quot;Screen Options&quot; panel has opened look for the label &quot;Remove the ability to delete this post&quot;. Then check the checkbox that says &quot;Remove trash link&quot;.

== Help and support ==

For custom WordPress plugin and theme development requests email us at [info@reactivedevelopment.net](mailto:info@reactivedevelopment.net) or go to [https://www.reactivedevelopment.net/](https://www.reactivedevelopment.net/). If you have questions or requests for this plugin go to [http://wordpress.org/support/plugin/featured-users-wordpress-plugin](http://wordpress.org/support/plugin/featured-users-wordpress-plugin) or for quick and paid support go to [https://www.reactivedevelopment.net/contact/project-mind/?plugin=featured-users](https://www.reactivedevelopment.net/contact/project-mind/?plugin=featured-users) to message us.

== Frequently Asked Questions ==

Let us know what questions you have!

== Screenshots ==

1. screenshot-1.jpg
1. screenshot-2.jpg
1. screenshot-3.jpg

== Changelog ==

= 3.0 =

Release Date: 2019/01/15

* Updated documentation, readmes, comments and phpDOC comments.

= 2.5 =

Release Date: 2019/01/14

* Re-developed by, Jeremy Selph http://www.reactivedevelopment.net/

= 2.0 =

* Released to WordPress.

= 0.2 =

Release Date: 2014/01/04

* updated public function reference

= 0.1 =

* initial plugin development by, Jeremy Selph http://www.reactivedevelopment.net/