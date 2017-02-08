![PoP](https://assets.getpop.org/wp-content/themes/getpop/img/pop-logo-horizontal.png)

# PoP — “Platform of Platforms”
Framework for building Single-Page Application WordPress websites, decentralized crowd-sourced platforms, and social networks

For more info, visit:

- **PoP website:** https://getpop.org
- **PoP demo:** https://demo.getpop.org

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

1. Install the [latest version](https://wordpress.org/latest.zip) of WordPress
2. Download/Clone this repository on the same folder
3. Create all required pages/categories/etc, to be found in each plugin's `config/constants.php` file (eg: [this file](https://github.com/leoloso/PoP/blob/master/wp-content/plugins/pop-coreprocessors/config/constants.php)), and set those constants with their corresponding ID ([there's an issue](https://github.com/leoloso/PoP/issues/38) to do this through scripts)
4. Activate all plug-ins and the theme

## Configuration

PoP allows the configuration of the following properties, done in file wp-config.php:

    Constant: POP_SERVER_USECACHE
    Values: true|false
    Description: Create and re-use a cache of the settings of the requested page.

    Constant: POP_SERVER_USEMINIFIEDFILES
    Values: true|false
    Description: Include the mangled, minified and bundled together version of all .js, .tmpl.js and .css files.

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

    Constant: POP_SERVER_FORCESSL
    Values: true|false
    Description: If true, it will always redirect to HTTPS when accessing over HTTP

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

## Optimization

PoP allows to mangle, minify and bundle together all required .css, .js and .tmpl.js files (suitable for PROD environment), both at the plug-in and website levels:

- **At the plug-in level** (it generates 1.js + 1 .tmpl.js + 1.css files per plug-in): execute `bash -x plugins/PLUGIN-NAME/build/minify.sh` for each plugin
- **At the website level** (it generates 1.js + 1 .tmpl.js + 1.css files for the whole website): execute `bash -x themes/THEME-NAME/build/minify.sh` for the theme

Executing the `minify.sh` scripts requires the following software (_I'll welcome anyone proposing a better way to do this_):
 
1. [UglifyJS](https://github.com/mishoo/UglifyJS2)

 To mangle and so further reduce the file size of the bundled JS file

2. [Google's minimizer Min](https://github.com/mrclay/minify)

 To bundle and minify files. The min webserver must be deployed under https://min.localhost/.

The following environment variables are used in `minify.sh`: `POP_APP_PATH`, `POP_APP_MIN_PATH` and `POP_APP_MIN_FOLDER`. To set their values, for Mac:

      sudo nano ~/.bash_profile, then add:
    
      export POP_APP_PATH=path to your website (eg: "/Users/john/Sites/PoP")
      export POP_APP_MIN_PATH=path to Google's min website (eg: "/Users/john/Sites/min")
      export POP_APP_MIN_FOLDER=path to folder in min, used for copy files to minimize (eg: "PoP", with the folder being /Users/john/Sites/min/PoP/)
    
      and save

The `minify.sh` script copies all files to minimize under folder `POP_APP_MIN_FOLDER`, from where it minimizes them. The structure of this folder must be created in advance, as follows:
 
 for each theme:
  
      apps/THEME-NAME/css/
      apps/THEME-NAME/js/
      themes/THEME-NAME/css/
      themes/THEME-NAME/js/
     
 for each plug-in:
  
      plugins/PLUGIN-NAME/css/
      plugins/PLUGIN-NAME/js/

