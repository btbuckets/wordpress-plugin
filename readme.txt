== BTBuckets plugin for Wordpress ==

  Author: Arnaldo M Pereira - BTBuckets
  Requires at least: 2.7

== Description ==

  This plugin allows one to enable behavioral targeting on his/her Wordpress
  site. Its main goal is to simplify the BTBuckets' installation process, by
  automagically fetching and installing the javascript tag required to use
  the service.

  After installing, activating the plugin and installing your API key, go to
  http://btbuckets.com and create the rules, buckets and targets - they'll
  come to life on your blog, without touching a single line of wordpress code,
  nor from it's templates.

  More info on: 

== Development ==

  If you want to contribute to this plugin, you might send me patches, or
  even fork the project and go your own. We surely prefer to interact with
  anyone who's using the plugin and, even better, extending it.

  Please, let me know your thoughts and send me patches!

== Basic concepts ==

 - Bucket: a container that lies on btbuckets.com, with a set of users and a
           set of rules. Think of it as a profile.
 - Target: the action to take when the user is in a bucket. Valid examples
           are: popup a message, insert code into a div, etc.

== TODO ==

 - List registered buckets.

   This basically requires fetching and presenting the bucket list from 
   btbuckets.com's services.


 - Wizards to create target codes.

   Target codes can run by themselves, on the website, without interaction
   with btbuckets.com. That being said, any javascript code that takes an
   action on profiled (bucketed) users, might be created right from within
   the wordpress instance.

   We have to collect what targets users wants more to define a priority
   list for the wizards, but we could start by creating a shadowbox popup,
   or embed video support.

== ChangeLog ==

  - 2010/03/05 - initial release.
