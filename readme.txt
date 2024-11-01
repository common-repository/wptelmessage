=== WPTelMessage ===

Contributors: hrcode
Tags: message, telegram, notifications, group, token
Requires at least: 4.7
Tested up to: 6.6
Stable tag: 1.0
Requires PHP: 7.4
License: GPLv2 or later

With the WPTelMessage plugin you can receive messages from your website and WooCommerce feedback forms in Telegram.

== Description ==

With the WpTelMessage plugin, you can quickly receive messages from a website to a Telegram group and respond to them promptly.<br>
You can receive messages from feedback forms such as __Contact Form 7__, __WpForms__, __NinjaForms__.<br>
In order to receive messages from a website in Telegram, you do not need to configure the forms, the plugin will do everything itself.
You will only need to create a Telegram bot that will publish these messages in a group, which will also need to be created.<br>
For the feedback form __Contact Form 7__, the plugin allows you to create your own tags for the form fields, if they have names such as __your-name__, __your-message__ ..., then the plugin itself will convert them into tags such as __Name__, __Message__ ..., which will have a good effect on the readability of the message in Telegram.


== Additional Info ==

= Resources used by the WPTelMessage plugin =

The WPTelMessage plugin uses the Telegram API (https://core.telegram.org/api) for its operation.
The token number and chat ID you specified are required for the plugin to work and will be sent to the Telegram servers.
Messages from contact forms and the WooCommerce plugin will also be sent to the Telegram servers.

You can read the Telegram API terms of use and Telegram privacy policy here: https://telegram.org/privacy/ru

== Frequently Asked Questions ==

= Is the plugin ready to work immediately after activation? =

Yes, the plugin is ready to work immediately after activation.<br> 
Once the plugin is activated, it is ready to start receiving messages from your website contact forms and sending them to the specified Telegram group.<br> 
Just make sure that you have configured the plugin settings correctly, such as the bot token (__Bot Token__) and group ID (__Group ID__).<br> 
Once the plugin is activated and the settings are configured, it is ready to work immediately and will start sending messages to your Telegram group when it receives data from contact forms and WooCommerce.


== Screenshots ==

1. Telegram bot token and group ID input fields.
2. Setting up feedback forms.
3. Setting up WooCommerce messages.


== Changelog ==

= 1.0 =


== Upgrade Notice ==

= 1.0 =
