=== Plugin Name ===
Contributors: Combrite
Tags: events, eventbrite, event, calendar, ticket, tickets, ticketing
Requires at least: 3.4
Tested up to: 3.9
Stable tag: 1.0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Turn your visitors into attendees. Everything you need to sell tickets and manage registration on WordPress! 

== Description ==

Combrite is an Eventbrite integration plugin for WordPress. 
It lets you import any Eventbrite event as a post, and publish it on your site complete with ticket widget, map and event details.

This lets you showcase your events on your site and also take advantage of the features that Eventbrite provides you to create, promote and manage events, such as:

- Simple tools let you create an event webpage with logos, images and send barcoded tickets.
- Online or mobile, it's quick and easy for people to buy tickets and register for your event.
- Easily collect money online with credit card processor, PayPal, and more.
- Email personalized invitations to your contact lists. Automatically list your public events on search engines and more.
- Increase your turnout by letting attendees promote your event on Facebook, Twitter, and LinkedIn to their friends and network.
- See how many people are coming to your event, view your ticket and registration sales, and ramp up your promotional activity as needed.
- Print a guest list or use the app to check in people and scan barcoded tickets with your phone.
- Attendees can access your event info and tickets on the go from their phone
- Sell tickets and collect customer info with the box office app for the iPad.

.. and much more.

== Frequently Asked Questions ==

### How does this work?
From our administrative interface, you search for an event on Eventbrite. Once you have chosen an event to post on your site, you'll go on to import it as a post. Combrite will prefill the event information, and you'll have the opportunity to fine tune the text, delete or add something to the post.
Once published, the post will show the event information, a Google map to point the location, and the Eventbrite ticketing widget, so people can directly go and purchase tickets on the Eventbrite ticket checkout page.

### How to customize the page/post created by Combrite

Articles generated by Combrite have a structure and markup that's defined by a template, found in `wp-content/plugins/combrite/front/template/default.html`. 
To customize it, don't edit this file, but copy that in `wp-content/uploads/combrite/custom.html` so that future updates will not overwrite your customization. If Combrite finds that file, will pick it up instead of the default one. This ensures that when you update Combrite, your custom template is not overwritten.

### How to add some custom CSS

Combrite loads its own CSS file, located in `wp-content/plugins/combrite/front/template/default.css`. 
To customize it or add your own CSS rules, don't edit this file, but create your own CSS file in `wp-content/uploads/combrite/custom.css`. This ensures that when you update Combrite, your custom CSS file is not overwritten.

### How to customize the ticket widget height

Once a page/post has been created, add the height, in pixels, to the `{combrite_tickets}` snippet, for example to set a ticket widget in an article to 200px height, add 200 to the end of it:

`{combrite_tickets 238188321838;1;200}` 

You can also customize the template so ALL ticket widgets generated inherit the specified height. In this case, you'll need to create a custom Combrite template (see question)

### Does this require me to have Eventbrite API keys or a developer account? 
No. The plugin works without any API key needed.

### Where is my event data?
The event information and ticketing is not stored inside your site, but it's all referenced from the Eventbrite website. Your WordPress site will copy the event information inside a post, and use a widget to show the tickets available, in real time, to your users. This means that you can take advantage of the best event platform on the web and still promote your events on your own website.

== Installation ==

Install using the WP Plugin installer, or unzip the plugin zip file in the `/wp-content/plugins/` directory and activate it through the 'Plugins' menu in WordPress.

== Screenshots ==

1. Track your ticket sales
2. Sell multiple tickets
3. Fast checkout
4. Easy to use
5. Mobile ready

== Changelog ==

= 1.0.2 =
* Fix Google maps URL
* Extract functions for map and ticket widget generation

= 1.0.1 =
* Fixed some warnings

= 1.0 =
* First release
