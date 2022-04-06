=== Gravity Forms Helper ===

Contributors: gblessylva
Tags: gravityforms
Requires at least: 4.6
Tested up to: 5.8
Stable tag: 0.3.1
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Misc Gravity Forms Settings/etc Improvements

== Description ==

Various minor changes to GF behavior that I was tired of encorporating into various other plugins I've written, so I decided to build them into a single GFAddOn.

The most useful of these changes provides a UI for adding scripts and styles (from other plugins) to GF's  non-conflict lists.  See `Forms > Settings > GF Helper`.

We also add Yes/No pre-defined choices.  That is, when editing a form, for any field type that has choices (e.g., `radio`, `select`, `list`), when you click on `Bulk Add / Predefined Choices`, you can add easily add 'Yes' and 'No' choices.

== Installation ==

From your WordPress dashboard

1. Go to _Plugins > Add New_ and click on _Upload Plugin_
2. Upload the zip file
3. Activate the plugin

= Build from sources =

1. clone the global repo to your local machine
2. install node.js and npm ([instructions](https://docs.npmjs.com/downloading-and-installing-node-js-and-npm))
3. install composer ([instructions](https://getcomposer.org/download/))
4. run `npm install`
5. run `composer install`
6. run `grunt build`

Useful tasks that are defined in Gruntfile.js

1. `grunt precomit`
    * runs phpcs (with modified WPCS) and jshint
    * always run this...and correct any errors...before committing anything
2. `grunt package`
    * builds a new release package
    * this will minify CSS/JS, update the plugin version number/description/name/etc from the info in package.js and then produce a zip package
3. `grunt autoload`
    * rebuilds the Composer autoloader.  Useful if/when you add a new PHP class to the plugin

== Changelog ==

= 0.3.1 (2021-11-06) =

* Enhancments
    * Added an uninstall.php.  Currently it's a no-op, it's just to bring this plugin in conformance with other plugins we write that do need it 
    * i18n: added full localization support (i.e., load our text domain for `__()` et al)
    * Minor WPCS changes, including a few DocBlock updates

* Misc
    * Improved this readme.txt

= 0.3.0 (2021-07-30) =

* Enhancements
    * Added CSS that allows `<ol type='a'>` and `<ol type='i'>` in field descriptions to render correctly in the form editor
    * Improve i18n aspects of form settings strings

* Misc
    * Removed unused code

= 0.2.1 (2021-03-29) =

Unknown changes.

= 0.1.0 =

* init commit
