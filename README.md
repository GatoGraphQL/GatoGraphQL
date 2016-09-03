# PoP — “Platform of Platforms”
Framework for building Single-Page Application WordPress websites, decentralized crowd-sourced platforms, and social networks

For more info, visit:

- **GetPoP website:** https://getpop.org
- **Demo:** https://demo.getpop.org

## Intro

This section is a summary. For a properly detailed description, please go to https://getpop.org

### What is PoP?

PoP creates Single-Page Application websites, by combining Wordpress and Handlebars into an MVC architecture framework:

- Wordpress is the model/back-end
- Handlebars templates are the view/front-end
- the PoP engine is the controller

### How does it work?

1. PoP intercepts the user request in the front-end and delivers it to the webserver using AJAX.
2. Wordpress processes the request, and provides the results to the PoP controller.
3. PoP generates a response in JSON, and feeds this JSON code back to the front-end
4. Handlebars templates transform the JSON code into HTML, and passes it back to PoP
5. PoP appends the HTML into the DOM and executes user-defined javascript functions on the new elements.

### Design principles

1. PoP provides the WordPress website of its own API:

 - Available via HTTP
 - Simply by adding to any URL: output=json

2. Decentralized

 - All PoP websites can communicate among themselves
 - Fetch/process data in real time

### What can be implemented with it?

- Niche/decentralized social network
- Decentralized market-places
- Content aggregators
- Server back-end for mobile apps
- Microservices

## Themes in WP and in PoP

Themes are preserved in PoP, however in a different implementation than in WordPress.

To create a clearcut MVC separation of layers, PoP sends a JSON code to the front-end, which is then transformed into HTML. As such, PoP does not use WordPress' hierarchy templates (archive.php, single.php, home.php, etc), and replaces these with Handlebars templates in the front-end. 

Additionaly, themes are actually implemented as plug-ins, instead of actual themes. This is because PoP enables WordPress websites to export their data to aggregators, following the format required by the aggregator. For this, the theme from the aggregator must also be installed in the aggregated websites, making an aggregated WordPress website require at least 2 active themes: one for its own website, and one for exporting data to its aggregator. Since a WordPress website cannot have more than 1 active theme, then in PoP themes are installed as plug-ins.

That doesn't mean that there are no actual themes. Yes, there are, however these barely implement the configuration and styles for the website, and almost no functionality.

In this repository, PoP ships with a theme called “Wassup”, under plug-in "poptheme-wassup". All themes provided (mesym, tppdebate and getpop-demo) are all implementations of “Wassup”.

## Installation

1. Install the latest version of WordPress
2. Download/Clone the PoP repository on the same folder

### Installing your first website

Install the following DB dump, created from the PoP Demo (https://demo.getpop.org), to have your your first PoP website up and running quickly:

  wp-content/plugins/pop-demo-environment/install/pop_demo.sql

The script will: create the DB, create the user and assign the permissions, and populate the tables with demo data. 

This demo website is configured to run under http://popdemo.localhost

wp-config configuration:

    /** MySQL database */
    define('DB_NAME', 'pop_demo');

    /** MySQL database username */
    define('DB_USER', 'pop_demo');

    /** MySQL database password */
    define('DB_PASSWORD', 'pop_demo');

Admin user: 

    Log in: http://popdemo.localhost/en/log-in/
    Username: pop_demo
    Password: pop_demo

### Decentralization: enabling crossdomain

To have a website consume data coming from other domains, crossdomain access must be allowed. For this, edit your .htaccess file like this:

    <IfModule mod_headers.c>
      SetEnvIf Origin "http(s)?://(.+\.)?(source-website.localhost|aggregator-website.localhost)$" AccessControlAllowOrigin=$0
      Header add Access-Control-Allow-Origin %{AccessControlAllowOrigin}e env=AccessControlAllowOrigin

      # Allow for cross-domain setting of cookies, so decentralized log-in also works
      Header set Access-Control-Allow-Credentials true
      Header add Access-Control-Allow-Methods GET
      Header add Access-Control-Allow-Methods POST
    </IfModule>

### Hacks: WordPress

Theme "wassup" implements some features for which some files from WordPress have had to be hacked. The corresponding issues will be added to TRAC soon, asking core developers to implement needed changes. But for the time being, add the following hacks, to make everything work.

1. Do not allow users to access wp-login.php

 Hack: added `do_action('gd_wp_login');` just below `require( dirname(__FILE__) . '/wp-load.php' );` in file wp-login.php

2. Since there is no page reload when the user logs in, the button to open the media manager above the editor must always be there, independently of the user being logged in or not, and make it hidden when user is not logged in.

 Hack: added `$set = apply_filters('gd_wp_editor_set', $set);` just above `if ( self::$this_tinymce ) {` in file wp-includes/class-wp-editor.php

3. Show 2 buttons above the editor to open the media manager: "Add File/Image", and "Add Image/Gallery"
Why hack: there is a bug currently in WordPress: 1) click on "Add Image/Gallery" works fine; 2) click on "Add File/Image" works fine; 3) click on "Add Image/Gallery" does not switch the tab to the Image Gallery.

 Hack: added `if (options.state) { workflow.setState(options.state); }` just above `wp.media.frame = workflow;` in file wp-includes/js/media-editor.js, and the corresponding `,(b.state)&&(c.setState(b.state))` after `&&(c=this.add(a,b))` in the minified version the file, wp-includes/js/media-editor.min.js

### Hacks: Plug-ins

Different plug-ins also have a few hacks, mainly to make them compatible with other plug-ins. Reports to the plug-in owners are on their way.

Because the required plug-ins have been included in the repository, these hacks are already taken care of. To see what hacks there are, search for string "Hack PoP Plug-in" everywhere.

### Required commercial plug-ins

The following plug-ins have been left out from the repository, since they are commercial. Some functionalities have been developed for these, so if you have these plug-ins, add them to your local repository.

1. Gravity Forms

 Currently, all the forms in the website (Contact user, Contact us, Newsletter, Flag content, Volunteer, and Share by email) have been implemented using Gravity Forms. 

 It is possible to avoid having to install this plug-in, by adding the code to send the email to the recipient, however this must be implemented.

2. Advanced Custom Fields Pro

 Some functionalities have been implemented, in the wp-admin dashboard, using Gravity Custom Fields, and in some cases using their commercial extensions, ACF Pro. These functionalities can always be executed from the front-end, as they are implemented using PoP. However, if you don't have ACF Pro, they are not available from the wp-admin dashboard. Examples: adding locations to a user, adding communities to a user, adding recommended posts to a user, adding relationships among posts, etc.

### Improvements for deploying to PROD

PoP allows to include either all registered .css and .js files, suitable for DEV environment,  or 1 .css + 1 .js + 1 .tmpl.js bundled and minified versions of those files, suitable for PROD environment, making the website load faster with less http connections. 

The bundling and minifying of files is done in 2 places:

- At the plug-in level => it generates 1.js + 1 .tmpl.js + 1.css files per plug-in. Input files are defined in plugins/PLUGIN-NAME/build/minify.sh
- At the website level => it generates 1.js + 1 .tmpl.js + 1.css files in total. Input files are defined in themes/THEME-NAME/build/minify.sh

#### Configuration:

Configure constant in wp-config.php, specifying to use minified files:
`define('POP_SERVER_USEMINIFIEDFILES', true);`

(More info on configuration constants in section below)

#### Bundle and minify .js and .css files

_**Notice:** the procedure below is quite ugly, it works for me but I see how it's troublesome to implement. I'll welcome anyone proposing, and implementing, a better solution (using RequireJS? other?)_

1. Install Google's minimizer Min in your webserver

 To bundle and minify files, I'm using Google's minimizer Min (), deployed under https://min.localhost/, and executing a script that makes a request and saves the output of the minified file to disk.

2. Define the environment variables used in minify.sh: POP_APP_PATH, POP_APP_MIN_PATH and POP_APP_MIN_FOLDER

 For Mac:

      sudo nano ~/.bash_profile, then add:
    
      export POP_APP_PATH=path to your website (eg: /Users/john/Sites/PoP)
      export POP_APP_MIN_PATH=path to Google's min website (eg: /Users/john/Sites/min)
      export POP_APP_MIN_FOLDER=path to folder in min, used for copy files to minimize (eg: PoP, with the folder being /Users/john/Sites/min)
    
      and save

3. Create the folder structure needed by minify.sh, under POP_APP_MIN_FOLDER:

 The .sh scripts copy all files to minimize to this folder, from where it minimizes them. The structure of this folder must be created in advance, under folder defined in POP_APP_MIN_FOLDER (eg: /Users/john/Sites/min/PoP/), as follows:
 
 for each theme:
	
		 apps/THEME-NAME/css/
		 apps/THEME-NAME/js/
		 themes/THEME-NAME/css/
		 themes/THEME-NAME/js/
		 
 for each plug-in:
	
		 plugins/PLUGIN-NAME/css/
		 plugins/PLUGIN-NAME/js/

#### Generating minified files

Before deploying the website to PROD, run the corresponding .sh script files to re-generate the bundled and minified .js/.css files:

    bash -x $POP_APP_PATH/wp-content/plugins/PLUGIN-NAME/build/minify.sh
    bash -x $POP_APP_PATH/wp-content/themes/THEME-NAME/build/minify.sh
    bash -x $POP_APP_PATH/wp-content/apps/THEME-NAME/build/minify.sh

(You can take a look at file wp-content/plugins/pop-demo-environment/install/minify.sh, which itself executs all minify.sh files from all plug-ins and its theme)

In addition, increase the version number of the affected:

- plug-ins (in file wp-content/plugins/PLUGIN-NAME/PLUGIN-NAME.php, eg: constant `POP_FRONTENDENGINE_VERSION` in file wp-content/plugins/pop-frontendengine/popfrontend-engine.php)
- theme (in file wp-content/themes/THEME-NAME/THEME-NAME.php, eg: constant `GETPOP_VERSION` in file wp-content/themes/getpop/getpop.php)

Increasing the version number will guarantee that the latest version of the file will be requested, and not a previous, cached version.

## Configuration constants

PoP allows the configuration of the following properties, done in file wp-config.php:

    Constant: POP_SERVER_USECACHE
    Values: true|false
    Description: Create and re-use a cache of the settings of the requested page.

    Constant: POP_SERVER_USEMINIFIEDFILES
    Values: true|false
    Description: Include all .js and .css files, or the bundled and minified version.

    Constant: POP_SERVER_TEMPLATEDEFINITION_TYPE
    Values: 0|1|2
    Description: Allows to replace the name of each module with a base36 number instead, to generate a smaller response (around 40%).
      0: Use the original name of each module
      1: Use both
      2: Use the base36 counter number

    Constant: POP_SERVER_COMPACTJSKEYS
    Values: true|false
    Description: Common keys from the JSON code sent to the front-end are replaced with a compact string. Output response will be smaller.

    Constant: POP_SERVER_USELOCALSTORAGE
    Values: true|false
    Description: Save special loaded-in-the-background pages in localStorage, to not have to retrieve them again (until software version changes).

## Integration with AWS

The framework is shipped ready to be deployed to Amazon Web Services, through the use of the following plug-ins:

Third-party plug-ins:

- amazon-s3-and-cloudfront
- amazon-web-services

PoP plug-ins:

- pop-aws
- pop-useravatar-aws

If you are not interested in using AWS, you can safely ignore these plug-ins.

## File naming conventions

When having different environments (DEV, STAGING, PROD), implementing the naming conventions allow to ignore copying these files from environment to environment (DEV => STAGING => PROD).

- All constants related to the application environment must be defined in files named "environment-constants.php" 
- All logic related to the application environment must be defined in files named "environment-config.php"

Please check in plug-in "pop-demo-environment" for an example of this.

## Context (or how and why it was born)

Some 4 years ago, I started using WordPress, and I immediately fell in love with it. I particularly loved the idea of installing plug-ins, these pieces of software contributed by the community, allowing me to not have to implement a given functionality; instead, I'd search for it among the many available plug-ins, and most likely than not I would find what I neededd.

This worked very well for some time, however after installing many different plug-ins from different authors, there arose a few problems:

1. Same data may be retrieved many times from the DB, eg: post's author data, retrieved by different widgets 
2. The website became bloated, having each plug-in require its own .css/.js files. Even worse, sometimes they would load the same resources, such as jQuery. (There are plug-ins to minify these files, but since they operate on runtime, they consume extra server resources, so it's not an optimal solution.)
3. Each plug-in would implement its own front-end styles, forcing me to fix it to have a coherent look and feel:

 - Some plug-ins allow to customize the styles, in which case I would end up implementing the same styles many times, each time for a different plug-in
 - If not, I would deregister the styles and register them again with the proper formatting
 - If too much effort, I would just hack the plug-in

Issue #1 shows that there is an efficiency problem in the back-end. Issue #2 shows that there is an efficiency problem in the front-end. Issue #3 shows that there is an efficiency problem in development efforts. It was from all these conflicts that I started dreaming of a different way to implement plug-ins. 

PoP was designed to fix these issues. It has managed to solve them as follows:

1. Widgets do not retrieve their required database data, but only the object IDs. Then, all required database data, from all widgets, is retrieved at the end of the page request, just once. And it is sent to the front-end also only once.
2. Modularity and reusability of components allows different plug-ins to re-use what is provided by others, and build on top of that, reducing the duplication of code.
3. Back-end and front-end have been separated. PoP re-uses the back-end functionality from plug-ins, but completely ignores their front-end. PoP themes implement their own, clean front-end.
