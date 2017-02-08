![PoP](https://assets.getpop.org/wp-content/themes/getpop/img/pop-logo-horizontal.png)

# PoP — “Platform of Platforms”
Framework for building Single-Page Application WordPress websites, decentralized crowd-sourced platforms, and social networks

For more info, visit:

- **GetPoP website:** https://getpop.org
- **Demo:** https://demo.getpop.org

- - -

Below is a summary. For a properly detailed description, please go to https://getpop.org

## What is PoP?

PoP creates Single-Page Application websites, by combining Wordpress and Handlebars into an MVC architecture framework:

- Wordpress is the model/back-end
- Handlebars templates are the view/front-end
- the PoP engine is the controller

## Design principles

1. PoP provides the WordPress website of its own API:

 - Available via HTTP
 - Simply by adding to any URL: output=json

2. Decentralized

 - All PoP websites can communicate among themselves
 - Fetch/process data in real time

## What can be implemented with it?

- Niche/decentralized social network
- Decentralized market-places
- Content aggregators
- Server back-end for mobile apps
- Microservices

## Installation

1. Install the latest version of WordPress
2. Download/Clone the PoP repository on the same folder

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

### Mangling, Minifying and Bundling of .css, .js and .tmpl.js files

PoP allows to include either all registered .css, .js and .tmpl.js files from all plug-ins (suitable for DEV environment),  or 1 .css + 1 .js + 1 .tmpl.js mangled, minified and bundled versions of all those files (suitable for PROD environment):

- **At the plug-in level** (it generates 1.js + 1 .tmpl.js + 1.css files per plug-in): execute `bash -x plugins/PLUGIN-NAME/build/minify.sh` for each plugin
- **At the website level** (it generates 1.js + 1 .tmpl.js + 1.css files for the whole website): execute `bash -x themes/THEME-NAME/build/minify.sh` for the theme

Executing the `minify.sh` scripts requires the following software (_the current procedure, explained below, is not very pretty... I'll welcome anyone proposing a better solution_):
 
1. UglifyJS

 To further reduce the file size of the bundled libraries JS file, I use [UglifyJS](https://github.com/mishoo/UglifyJS2)

2. Google's minimizer Min

 To bundle and minify files, I'm using [Google's minimizer Min](https://github.com/mrclay/minify), deployed under https://min.localhost/, and executing a script that makes a request and saves the output of the minified file to disk.

And configuration:

1. Define the environment variables used in `minify.sh`: `POP_APP_PATH`, `POP_APP_MIN_PATH` and `POP_APP_MIN_FOLDER`.

 For Mac:

      sudo nano ~/.bash_profile, then add:
    
      export POP_APP_PATH=path to your website (eg: "/Users/john/Sites/PoP")
      export POP_APP_MIN_PATH=path to Google's min website (eg: "/Users/john/Sites/min")
      export POP_APP_MIN_FOLDER=path to folder in min, used for copy files to minimize (eg: "PoP", with the folder being /Users/john/Sites/min/PoP/)
    
      and save

2. Create the folder structure needed by `minify.sh`, under `POP_APP_MIN_FOLDER`:

 The .sh scripts copy all files to minimize to this folder, from where it minimizes them. The structure of this folder must be created in advance, under folder defined in `POP_APP_MIN_FOLDER` (eg: /Users/john/Sites/min/PoP/), as follows:
 
 for each theme:
  
      apps/THEME-NAME/css/
      apps/THEME-NAME/js/
      themes/THEME-NAME/css/
      themes/THEME-NAME/js/
     
 for each plug-in:
  
      plugins/PLUGIN-NAME/css/
      plugins/PLUGIN-NAME/js/

## Configuration

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

