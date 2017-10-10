![PoP](https://assets.getpop.org/wp-content/themes/getpop/img/pop-logo-horizontal.png)

# PoP — “Platform of Platforms”
Framework for building Single-Page Application WordPress websites, decentralized crowd-sourced platforms, and social networks

**PoP website:** https://getpop.org

**Demos to play with PoP:**

- Social Network: https://demo.getpop.org
- Decentralized Social Network: https://sukipop.com

**Production sites:**

- MESYM: https://www.mesym.com
- Agenda Urbana: https://agendaurbana.org

Below is a technical summary. For a more in-depth description, please visit [PoP's documentation page](https://getpop.org/en/documentation/overview/).

## What is PoP?

PoP creates [Single-Page Application](https://en.wikipedia.org/wiki/Single-page_application) websites, by combining [Wordpress](https://wordpress.org) and [Handlebars](http://handlebarsjs.com/) into an [MVC architecture](https://en.wikipedia.org/wiki/Model-view-controller) framework:

- Wordpress is the model/back-end
- Handlebars templates are the view/front-end
- the PoP engine is the controller

![How it works](https://uploads.getpop.org/wp-content/uploads/2016/10/Step-5-640x301.png)

## Design principles

1. PoP provides the WordPress website of its own API:

 - Available via HTTP
 - By adding parameter `output=json` to any URL

2. Decentralized

 - All PoP websites can communicate among themselves
 - Fetch/process data in real time

## What can be implemented with it?

- Niche social networks
- Decentralized websites
- Content aggregators
- Server back-end for mobile apps
- Microservices
- APIs for Wordpress websites

## Installation

_**Important:** PoP is currently not very smooth to install, it's a slightly lengthy manual process. We hope soon we will have scripts automating the process. If you would like to help us, that would be very welcome! [More info here](https://github.com/leoloso/PoP/issues/49)._

1. Install the [latest version](https://wordpress.org/latest.zip) of WordPress
2. Download/clone this repository on the same folder
3. Create all required pages/categories/etc, to be found in each plugin's `config/constants.php` file (eg: [this file](https://github.com/leoloso/PoP/blob/master/wp-content/plugins/pop-coreprocessors/config/constants.php)), and set those constants with their corresponding ID
4. Activate all plug-ins and the theme

### Required 3rd-party plugins

Install and activate the following plugins, which are integrated with PoP (_not all of them are mandatory, however **PoP has yet not been tested without them**_):
 - [Events Manager](https://wordpress.org/plugins/events-manager/): for the events and locations
 - [User Role Editor](https://wordpress.org/plugins/user-role-editor/): for the organization/individual account types
 - [Co-Authors Plus](https://wordpress.org/plugins/co-authors-plus/): for adding co-authors to the posts
 - [Public Post Preview](https://wordpress.org/plugins/public-post-preview/): for previewing draft posts
 - [Aryo Activity Log (Forked for PoP)](https://github.com/leoloso/aryo-activity-log): for the notifications (_please notice: this is a forked version of the plugin [Aryo Activity Log](https://wordpress.org/plugins/aryo-activity-log/), which doesn't work with PoP._)
 - [WordPress Social Login](https://wordpress.org/plugins/wordpress-social-login/): for allowing users to log-in with Facebook, Twitter, etc accounts
 - [qTranslate X](https://wordpress.org/plugins/qtranslate-x/): multi-language
 - [WP Super Cache](https://wordpress.org/plugins/wp-super-cache/): for caching the website
 - (Commercial) [Gravity Forms](http://www.gravityforms.com/): for sending messages, newsletter, flagging, volunteering, etc

### Required 3rd-party libraries

#### LightnCandy

[LightnCandy](https://github.com/zordius/lightncandy) is used for producing server-side HTML code. It must be installed in order to compile Handlebars javascript templates into PHP code. (There is no need to install it just to run the website as it is, since all PHP-compiled Handlebars javascript templates have been uploaded to this repository).

To install it, follow the instructions [here](https://zordius.github.io/HandlebarsCookbook/9000-quickstart.html). Run composer under folder [wp-content/plugins/pop-frontendengine/php-templates/cli](https://github.com/leoloso/PoP/tree/master/wp-content/plugins/pop-frontendengine/php-templates/cli).

#### PHP CSS Parser

[PHP CSS Parser](https://github.com/sabberworm/PHP-CSS-Parser) is used for parsing css files to extract their styles. It must be installed in order to send the automated emails, which, instead of using classes, have inline styles printed in the HTML code.

To install it, open a Terminal window, go to folder [wp-content/plugins/pop-frontendengine/library/css-to-style-conversion/](https://github.com/leoloso/PoP/tree/master/wp-content/plugins/pop-frontendengine/library/css-to-style-conversion), and run `composer install` (make sure to have [Composer](https://getcomposer.org/) installed first).

### Installing the demo

Because the [scripts automating the installation process](https://github.com/leoloso/PoP/issues/49) are not done yet, we provide file [getpop-demo/install.zip](https://github.com/leoloso/PoP/tree/master/install/getpop-demo/install.zip) to quickly install a copy of [GetPoP Demo website](https://demo.getpop.org/) in your localhost. Read the installation instructions [here](https://github.com/leoloso/PoP/blob/master/install/getpop-demo/install.md).

## Configuration

PoP allows the configuration of the following properties, done in file wp-config.php:

- `POP_SERVER_USECODESPLITTING` (_true|false_): If true, load only the .js and .css that is needed on each page and nothing more, if false, load a big application bundle.

- `POP_SERVER_USESERVERSIDERENDERING` (_true|false_): Produce HTML on the server-side for the first-loaded page.

- `POP_SERVER_USECACHE` (_true|false_): Create and re-use a cache of the settings of the requested page.

- `POP_SERVER_USEMINIFIEDRESOURCES` (_true|false_): Include the mangled, minified and bundled together version of all .js, .tmpl.js and .css files.

- `POP_SERVER_USECDNRESOURCES` (_true|false_): Whenever available, use resources from a public CDN.

- `POP_SERVER_TEMPLATEDEFINITION_TYPE` (_0|1|2_): Allows to replace the name of each module with a base36 number instead, to generate a smaller response (around 40%).

  0: Use the original name of each module. 1: Use both. 2: Use the base36 counter number.

- `POP_SERVER_TEMPLATEDEFINITION_USENAMESPACES` (_true|false_): If the template definition type is set to 2, then we can set namespaces for each plugin, to add before each template definition. It is needed for decentralization, so that different websites can communicate with each other without conflict, mangling all template definitions the same way. (Otherwise, having different plugins activated will alter the mangling counter, and produce different template definitions).

- `POP_SERVER_COMPACTJSKEYS` (_true|false_): Common keys from the JSON code sent to the front-end are replaced with a compact string. Output response will be smaller.

- `POP_SERVER_USELOCALSTORAGE` (_true|false_): Save special loaded-in-the-background pages in localStorage, to not have to retrieve them again (until software version changes).

### Decentralization: enabling crossdomain

To have a website consume data coming from other domains, crossdomain access must be allowed. For this, edit your .htaccess file like this:

    <IfModule mod_headers.c>
      SetEnvIf Origin "http(s)?://(.+\.)?(source-website.com|aggregator-website.com)$" AccessControlAllowOrigin=$0
      Header add Access-Control-Allow-Origin %{AccessControlAllowOrigin}e env=AccessControlAllowOrigin

      # Allow for cross-domain setting of cookies, so decentralized log-in also works
      Header set Access-Control-Allow-Credentials true
      Header add Access-Control-Allow-Methods GET
      Header add Access-Control-Allow-Methods POST
    </IfModule>

#### Important

For POST operations to work, we need to make sure the user's browser isn't blocking third-party cookies, otherwise [cross-origin credentialed requests will not work](https://stackoverflow.com/questions/24687313/what-exactly-does-the-access-control-allow-credentials-header-do#24689738). In Chrome, this configuration is set under Settings > Advanced Settings > Privacy > Content Settings > Block third-party cookies and site data.

### Integration between the Content CDN and Service Workers

To allow the website's service-worker.js be able to cache content coming from the content CDN, access to reading the ETag header must be granted:

    <IfModule mod_headers.c>
      Header add Access-Control-Allow-Headers ETag
      Header add Access-Control-Expose-Headers ETag
    </IfModule>

## Optimization

_**Important:** Similar to the installation process, there is room for improvement for the optimization process. If you would like to help us, please [check here](https://github.com/leoloso/PoP/issues/49)._

PoP allows to mangle, minify and bundle together all required .css, .js and .tmpl.js files (suitable for PROD environment), both at the plug-in and website levels:

- **At the plug-in level** (it generates 1.js + 1 .tmpl.js + 1.css files per plug-in): execute `bash -x plugins/PLUGIN-NAME/build/minify.sh` for each plugin
- **At the website level** (it generates 1.js + 1 .tmpl.js + 1.css files for the whole website): execute `bash -x themes/THEME-NAME/build/minify.sh` for the theme

Executing the `minify.sh` scripts requires the following software (_I'll welcome anyone proposing a better way to do this_):
 
1. [UglifyJS](https://github.com/mishoo/UglifyJS2)

 To mangle and so further reduce the file size of the bundled JS file

2. [Google's minimizer Min](https://github.com/mrclay/minify)

 To bundle and minify files. The min webserver must be deployed under http://min.localhost/.

The following environment variables are used in `minify.sh`: `POP_APP_PATH`, `POP_APP_MIN_PATH` and `POP_APP_MIN_FOLDER`. To set their values, for Mac, execute `sudo nano ~/.bash_profile`, then add and save:
    
      export POP_APP_PATH=path to your website (eg: "/Users/john/Sites/PoP")
      export POP_APP_MIN_PATH=path to Google's min website (eg: "/Users/john/Sites/min")
      export POP_APP_MIN_FOLDER=path to folder in min, used for copy files to minimize (eg: "PoP", with the folder being /Users/john/Sites/min/PoP/)

The `minify.sh` script copies all files to minimize under folder `POP_APP_MIN_FOLDER`, from where it minimizes them. The structure of this folder must be created in advance, as follows:
 
 for each theme:
  
      apps/THEME-NAME/css/
      apps/THEME-NAME/js/
      themes/THEME-NAME/css/
      themes/THEME-NAME/js/
     
 for each plug-in:
  
      plugins/PLUGIN-NAME/css/
      plugins/PLUGIN-NAME/js/

## Want to help?

We are looking for developers who want to become involved. Check here the issues we need your help with:

https://github.com/leoloso/PoP/issues?q=is%3Aissue+is%3Aopen+label%3A%22help+wanted%22

### Many thanks to BrowserStack!

Open to Open Source projects for free, PoP uses the Live, Web-Based Browser Testing provided by [BrowserStack](https://www.browserstack.com/).

![BrowserStack](http://www.softcrylic.com/wp-content/uploads/2017/03/browser-stack.png)
