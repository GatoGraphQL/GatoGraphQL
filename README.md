![PoP](https://assets.getpop.org/wp-content/themes/getpop/img/pop-logo-horizontal.png)

# PoP — “Platform of Platforms”

Framework for building component-based websites

## Examples

**Demos to play with PoP:**

- Social Network: https://demo.getpop.org
- Decentralized Social Network: https://sukipop.com

**Production sites:**

- MESYM: https://www.mesym.com
- Agenda Urbana: https://agendaurbana.org

## Current focus: Release in stages of the new PoP API

[We are re-architecting PoP](https://getpop.org/en/blog/describing-the-foundations-of-the-new-pop/) to make it much simpler to code with it, faster, and more versatile. The new PoP will feature an architecture based on the following foundations:

- Everything is a module
- The module is its own API
- Reactivity

At the core of these changes is an upgraded API model, which will feature server-based components. Following the example set by GraphQL, which owes its success in part to being a specification instead of an implementation, PoP will be composed of the following layers:

1. The API (JSON response) specification
2. PoP Server, to serve content based on the API specification
3. PoP.js, to consume the content in the client

Being built around an open specification, we hope that different implementations for different technologies will emerge, so that any PoP website can communicate with any other PoP website no matter its underlying technology. The current PoP site will immediately provide the first implementation of PoP Server and PoP.js:

- PoP Server for WordPress, based on PoP Server for PHP
- PoP.js through vanilla JS and Handlebars templates

We started releasing the new PoP on early December 2018, and the process should take several months until complete. We expect all code to be released by 2nd quarter of 2019.

<!-- 
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

We are currently creating scripts to automate the installation process, we expect them to be ready around mid-October 2018.

Until then, we provide a zip file including all code (PoP, WordPress and plugins), and a database dump from the [GetPoP Demo website](https://demo.getpop.org/), to set-up this same site in a quick-and-dirty manner in your localhost. Download the files and read the installation instructions [here](https://github.com/leoloso/PoP/blob/master/install/getpop-demo/install.md).

## Configuration

PoP allows the configuration of the following properties, done in file wp-config.php:

- `POP_SERVER_USEAPPSHELL` (_true_|_false_): Load an empty Application Shell (or appshell), which loads the page content after loading.

- `POP_SERVER_USESERVERSIDERENDERING` (_true_|_false_): Produce HTML on the server-side for the first-loaded page.

- `POP_SERVER_USECODESPLITTING` (_true_|_false_): Load only the .js and .css that is needed on each page and nothing more.

- `POP_SERVER_USEPROGRESSIVEBOOTING` (_true_|_false_): If doing code splitting, load JS resources on 2 stages: critical ones immediately, and non-critical ones deferred, to lower down the Time to Interactive of the application.

- `POP_SERVER_GENERATEBUNDLEGROUPFILES` and `POP_SERVER_GENERATEBUNDLEFILES` (_true_|_false_): (Only if doing code-splitting) When executing the `/generate-theme/` build script, generate a single bundlegroup and/or a series of bundle files for each page on the website containing all resources it needs.

- `POP_SERVER_GENERATEBUNDLEFILESONRUNTIME` (_true_|_false_): (Only if doing code-splitting) Generate the bundlegroup or bundle files on runtime, so no need to pre-generate these.

- `POP_SERVER_GENERATELOADINGFRAMERESOURCEMAPPING` (_true_|_false_): (Only if doing code-splitting) Pre-generate the mapping listing what resources are needed for each route in the application, created when executing the `/generate-theme/` build script.

- `POP_SERVER_ENQUEUEFILESTYPE` (_resource_|_bundle_|_bundlegroup_): (Only if doing code-splitting) Choose how the initial-page resources are loaded:

    - "resource": Load the required resources straight
    - "bundle": through a series of bundle files, each of them comprising up to x resources (defined through constant `POP_SERVER_BUNDLECHUNKSIZE`)
    - "bundlegroup": through a unique bundlegroup file

- `POP_SERVER_BUNDLECHUNKSIZE` (_int_): (Only if doing code-splitting) How many resources to pack inside a bundle file. Default: 4.

- `POP_SERVER_TEMPLATERESOURCESINCLUDETYPE` (_header_|_body_|_body-inline_): (Only if doing server-side rendering, code-splitting and enqueue type = "resource") Choose how to include those resources depended by a module (mainly CSS styles):

    - "header": Link in the header
    - "body": Link in the body, right before the module HTML
    - "body-inline": Inline in the body, right before the module HTML

- `POP_SERVER_GENERATERESOURCESONRUNTIME` (_true_|_false_): Allow to extract configuration code from the HTML output and into Javascript files on runtime.

- `POP_SERVER_USEMINIFIEDRESOURCES` (_true_|_false_): Include the minified version of .js and .css files.

- `POP_SERVER_USEBUNDLEDRESOURCES` (_true_|_false_): (Only if not doing code-splitting) Insert script and style assets from a single bundled file.

- `POP_SERVER_USECDNRESOURCES` (_true_|_false_): Whenever available, use resources from a public CDN.

- `POP_SERVER_SCRIPTSAFTERHTML` (_true_|_false_): If doing server-side rendering, re-order script tags so that they are included only after rendering all HTML.

- `POP_SERVER_REMOVEDATABASEFROMOUTPUT` (_true_|_false_): If doing server-side rendering, remove all database data from the HTML output.

- `POP_SERVER_TEMPLATEDEFINITION_TYPE` (_0_|_1_|_2_): Allows to replace the name of each module with a base36 number instead, to generate a smaller response (around 40%).

    - 0: Use the original name of each module
    - 1: Use both
    - 2: Use the base36 counter number

- `POP_SERVER_TEMPLATEDEFINITION_CONSTANTOVERTIME` (_true_|_false_): When mangling the template names (template definition type is set to 2), use a database of all template definitions, which will be constant over time and shared among different plugins, to avoid errors in the website from accessed pages (localStorage, Service Workers) with an out-of-date configuration.

- `POP_SERVER_TEMPLATEDEFINITION_USENAMESPACES` (_true_|_false_): If the template definition type is set to 2, then we can set namespaces for each plugin, to add before each template definition. It is needed for decentralization, so that different websites can communicate with each other without conflict, mangling all template definitions the same way. (Otherwise, having different plugins activated will alter the mangling counter, and produce different template definitions).

- `POP_SERVER_USECACHE` (_true_|_false_): Create and re-use a cache of the settings of the requested page.

- `POP_SERVER_COMPACTJSKEYS` (_true_|_false_): Common keys from the JSON code sent to the front-end are replaced with a compact string. Output response will be smaller.

- `POP_SERVER_USELOCALSTORAGE` (_true_|_false_): Save special loaded-in-the-background pages in localStorage, to not have to retrieve them again (until software version changes).

- `POP_SERVER_ENABLECONFIGBYPARAMS` (_true_|_false_): Enable to set the application configuration through URL param "config".

- `POP_SERVER_DISABLEJS` (_true_|_false_): Strip the output of all Javascript code.

- `POP_SERVER_USEGENERATETHEMEOUTPUTFILES` (_true_|_false_): Indicates that we are using all the output files produced from running `/generate-theme/` in this environment, namely:

    - resourceloader-bundle-mapping.json
    - resourceloader-generatedfiles.json
    - All `pop-memory/` files

- `POP_SERVER_SKIPLOADINGFRAMERESOURCES` (_true_|_false_): When generating file `resources.js`, with the list of resources to dynamically load on the client, do not include those resources initially loaded in the website (through "loading-frame").

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

 To minify (as to reduce the file size of) JS files

2. [UglifyCSS](https://github.com/fmarcia/UglifyCSS)

 To minify (as to reduce the file size of) CSS files

3. [Google's minimizer Min](https://github.com/mrclay/minify)

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

-->

## Want to help?

We are looking for developers who want to become involved. Check here the issues we need your help with:

https://github.com/leoloso/PoP/issues?q=is%3Aissue+is%3Aopen+label%3A%22help+wanted%22

### Many thanks to BrowserStack!

Open to Open Source projects for free, PoP uses the Live, Web-Based Browser Testing provided by [BrowserStack](https://www.browserstack.com/).

![BrowserStack](http://www.softcrylic.com/wp-content/uploads/2017/03/browser-stack.png)