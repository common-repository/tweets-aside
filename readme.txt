=== Plugin Name ===
Contributors: Sergey.Rozenblat
Tags: twitter
Requires at least: 3.2
Tested up to: 3.2.1
Stable tag: 0.2

Shows your or someones tweets as an aside posts right in your blog line, not in a widget.

== Description ==

If you run a blog and a twitter, you sometimes want a reader to read both. Most plugins would give you a widget with your latest tweets. But, I thought it would be nice to have all your thoughts in one place and in one timeline.

So, this plugin automatically posts your tweets to your blog. As your tweets don't have titles, post format for them is aside. You may want to modify slightly your theme, to allow nice asides without titles.

Posting is done automatically by wordpress built-in cron. You can set it up to run automatically each hour, twice daily or daily. That would depend on how often you blog or tweet.

== Installation ==

To install this plugin, upload a file called `tweets-aside.php` to your `/wp-content/plugins/` folder and then activate plugin from your panel.

== Screenshots ==

1. This is plugin options panel. Pretty simple. Everything else works seamlessly.

== Frequently Asked Questions ==

= How can I delete a post? =

Yes, but never empty your trash or delete post from trash. Trashed posts are used to detect deleted posts.

= Can I modify a tweet-post? =

Yes, you can. Sync would not revert your post. Syncing is done by time. So if you modify your post, don't touch post date and time.

= Why not all my tweets are displayed? =

Sometimes Twitter search returns just a portion of latest tweets. Maybe through few next tries more tweets would be found and posted.

== Changelog ==

= 0.2 =
Now you can delete tweet-posts.

= 0.1 =
Beta version to see if it works or not.