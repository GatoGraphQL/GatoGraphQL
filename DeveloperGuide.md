![PoP](https://assets.getpop.org/wp-content/themes/getpop/img/pop-logo-horizontal.png)

# Developer Guide

## Getting Started

PoP is founded on [Object-Oriented Programming](https://en.wikipedia.org/wiki/Object-oriented_programming) (OOP), "a programming paradigm based on the concept of 'objects', which may contain data, in the form of fields, often known as attributes; and code, in the form of procedures, often known as methods". If you know OOP, then you are all set to go! If not, taking [one](https://www.tutorialspoint.com/php/php_object_oriented.htm) [of](https://code.tutsplus.com/tutorials/object-oriented-php-for-beginners--net-12762) [the](https://www.tutorialrepublic.com/php-tutorial/php-classes-and-objects.php) [many](https://www.killerphp.com/tutorials/object-oriented-php/) tutorials on OOP in PHP before starting will greatly help understand all code in this guide.

## Modules

### Definition of a Module

Every module has a unique name that identifies it, defined as a constant:

```php
define ('POP_MODULE_SOMENAME', 'somename'));
```

The name of the module must not necessarily remain fixed: it can be shortened for producing a smaller output, constantly modified to evade bots, or others. Different strategies can be applied, done through function [`\PoP\Engine\DefinitionUtils::get_module_definition`](https://github.com/leoloso/PoP/blob/master/pop-engine/server/definition-manager/utils.php#L16):

```php
define ('POP_MODULE_SOMENAME', \PoP\Engine\DefinitionUtils::get_module_definition('somename'));
```

> Note 1: because the constant is assigned an expression, we must use `define` instead of `const`.

> Note 2: It is important to always target a module through its constant name and not through its defined string, since the latter one may change. In the case above, we must use `POP_MODULE_SOMENAME` instead of `"somename"`.

All the properties of the modules are implemented through objects called [ModuleProcessors](#moduleprocessors).

### ModuleProcessors

A ModuleProcessor is an object class in which to define all the properties of a module. ModuleProcessors are implemented following the [SOLID](https://scotch.io/bar-talk/s-o-l-i-d-the-first-five-principles-of-object-oriented-design) methodology, establishing an object inheritance scheme to progressively add properties to modules. The base class for all ModuleProcessors is [`ModuleProcessorBase`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/processors/pop-processor.php):

```php
namespace PoP\Engine;
abstract class ModuleProcessorBase {

  // ...
}
```

Every ModuleProcessor can handle more than 1 module: Because different modules will naturally share many properties, then having a single ModuleProcessor implement many modules is more legible and reduces the amount of code required compared to having 1 ModuleProcessor per module. What modules are handled by the ModuleProcessor is defined through function `get_modules_to_process`:

```php
define ('POP_MODULE_SOMENAME1', \PoP\Engine\DefinitionUtils::get_module_definition('somename1'));
define ('POP_MODULE_SOMENAME2', \PoP\Engine\DefinitionUtils::get_module_definition('somename2'));
define ('POP_MODULE_SOMENAME3', \PoP\Engine\DefinitionUtils::get_module_definition('somename3'));

class CustomModuleProcessor extends \PoP\Engine\ModuleProcessorBase {

  function get_modules_to_process() {
  
    return array(
      POP_MODULE_SOMENAME1,
      POP_MODULE_SOMENAME2,
      POP_MODULE_SOMENAME3,
    );
  }

  // Implement the modules properties ...
  // ...
}
```

To access the properties of a module, we must obtain its corresponding ModuleProcessor through function `get_processor` from class [`ModuleProcessor_Manager`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/processor-managers/pop-processor-manager.php):

```php
// Retrive the PoP_ModuleProcessor_Manager object from the factory
$moduleprocessor_manager = \PoP\Engine\ModuleProcessor_Manager_Factory::get_instance();

// Obtain the ModuleProcessor for module POP_MODULE_SOMENAME
$processor = $moduleprocessor_manager->get_processor(POP_MODULE_SOMENAME);

// Do something...
// $processor->...
```

### Anatomy of a Module

Currently working on it, coming soon...

### Anatomy of a Module on the Client-side

Currently working on it, coming soon...

### Composition

Currently working on it, coming soon...

### Immutable, Mutable on Model, and Mutable on Request Properties

Currently working on it, coming soon...

### Configuration

Currently working on it, coming soon...

### Props

Modules are most useful when they are generic and enable customization through properties, or "props". For instance, a module can define a prop to change the background color configuration value, define how many objects to fetch from the database, or anything it may need. 

Setting props works in one direction only: modules can set props on any descendant module, but no module can set props on any ancestor module or on any module belonging to a different branch from the component hierarchy. In the structure below, "module1" can set props on "module2", "module3" and "module4", "module2" on "module3", and "module3" and "module4" on nobody:

```json
"module1"
  modules
    "module2"
      modules
        "module3"
    "module4"
```

Modules can set props on descendant modules whichever number of levels below in the component hierarchy, and it is done directly, i.e. without involving the modules in between or affecting their props. In the structure above, "module1" can set a prop directly on "module3" without going through "module2".

Setting props is done through functions [`init_model_props($module, &$props)`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/processors/pop-processor.php#L160) and [`init_request_props($module, &$props)`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/processors/pop-processor.php#L201). A prop must be implemented in either function, but not on both of them. `init_request_props` is used for defining props that depend directly on the requested URL, such as adding a classname `post-{id}` to prop `"class"`, where `{id}` is the ID of the requested post on that URL. `init_model_props` is used for everything else. 

Setting props is done at the very beginning: Immediately after obtaining the component hierarchy, PoP Engine will invoke these 2 functions **before anything else** (i.e. before getting the configuration, fetching database data, etc). Hence, with the exception of the functions to create the component hierarchy (i.e. `get_modules` and those inner functions invoked by `get_modules`), every function in the `ModuleProcessor` can receive `$props`. 

`init_model_props` and `init_request_props` store the props under parameter `$props`, hence it is passed by reference. In all other functions, `$props` may also be passed by reference, but only for performance issues, to not duplicate the object in memory.

Inside these 2 functions, we get to set the props through the following 3 functions:

- [`function add_prop($module_or_modulepath, &$props, $field, $value, $starting_from_modulepath = array())`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/processors/pop-processor.php#L396)
- [`function append_prop($module_or_modulepath, &$props, $field, $value, $starting_from_modulepath = array())`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/processors/pop-processor.php#L404)
- [`function merge_prop($module_or_modulepath, &$props, $field, $value, $starting_from_modulepath = array())`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/processors/pop-processor.php#L412)

These 3 functions are similar to each other, with the following differences: 

`append_prop` is used to append a value to an existing prop, hence it is a cumulative property. It is often used for adding classnames. For instance, having modules "module1" and "module2" both execute `append_prop` on "module3" for property `"class"` with values `"big"` and `"center"` respectively, will make the `"class"` property of "module3" be set as `"big center"`.

`merge_prop` is similar, but concerning arrays. It is often used for adding params to be printed on the element in the DOM. For instance, having modules "module1" and "module2" both execute `merge_prop` on "module3" for property `"params"` with values `["data-target" => "#main"]` and `["data-mode" => "static"]` respectively, will make the `"params"` property of "module3" be set as `["data-target" => "#main", "data-mode" => "static"]`.

`add_prop` is not cumulative, but accepts only 1 value: that one that is set the first. Hence, higher-level modules have priority for setting a property value over lower-level ones along the component hierarchy. For instance, having modules "module1" and "module2" both execute `add_prop` on "module3" for property `"title"` with values `"First title"` and `"Second title"` respectively, will make the `"title"` property of "module3" be set as `"First title"`.

All 3 methods receive the same parameters:

- `$module_or_modulepath`: This value can either be a string with the name of the module on which to set the prop, or an array. If it is the name of the module, there are 2 possibilities: if the target module is the same module setting the prop, then the module is setting a prop on itself (eg: to set the default value for the prop); if not, it will set a prop on the descendant module with that name wherever it is found along the subcomponent hierarchy. If it is an array, then this is already the subpath to the targeted descendant module where to set the prop.
- `&$props`: This is the object storing all the props, a unique object passed across all modules along the component hierarchy to initialize all props
- `$field`: Name of the property
- `$value`: Value to set on the property
- `$starting_from_modulepath`: Array with a module subpath from which to find the target module where to set the prop

Every module first initializes its own props, and only then continues the flow to the parent class, so that inheriting classes have priority over their ancestors in the object inheritance scheme:

```php
function init_model_props($module, &$props) {

  // Set prop...
  // Set prop...
  // Set prop...

  parent::init_model_props($module, $props);
}
```

Accessing the value of the prop is done through [`function get_prop($module, &$props, $field, $starting_from_modulepath = array())`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/processors/pop-processor.php#L420). The signature of the function is similar to the ones above, however without parameter `$value`.

Let's see an example: a component for rendering maps has 2 orientations: `"horizontal"` and `"vertical"`. It is composed by modules "map" => "map-inner", and both these modules need this property. Module "map" will set the value by default to `"vertical"`, obtain the value for this prop just in case an ancestor module had already set the prop, and then set this value on module "map-inner". Function below is implemented for module "map":

```php
function init_model_props($module, &$props) {

  switch ($module) {

    case 'map':

      // Module "map" is setting the default value
      $this->add_prop($module, $props, 'orientation', 'vertical');

      // Obtain the value from the prop
      $orientation = $this->get_prop($module, $props, 'orientation');

      // Set the value on "map-inner"
      $this->add_prop(['map-inner'], $props, 'orientation', $orientation);
      break;
  }

  return parent::init_model_props($module, $props);
}
```

By default, module map will have prop `"orientation"` set with value `"vertical"`. However, parent module "map-wrapper" can set this prop beforehand to `"horizontal"`:

```php
function init_model_props($module, &$props) {

  switch ($module) {

    case 'map-wrapper':

      $this->add_prop(['map'], $props, 'orientation', 'horizontal');      
      break;
  }

  return parent::init_model_props($module, $props);
}
```

### ModuleFilters

Currently working on it, coming soon...

## Data-Loading

Currently working on it, coming soon...

### Dataloading Modules

Currently working on it, coming soon...

### Dataloaders

Currently working on it, coming soon...

### FieldProcessors

Currently working on it, coming soon...

### Relational Successors

Currently working on it, coming soon...

### Lazy-Loading

We can instruct a dataloading module to be lazy-loaded (i.e. instead of fetching its database data immediately, it is fetched on a subsequent request from the client) simply by setting its prop `"lazy-load"` to `true`:

```php
function init_model_props($module, &$props) {

  switch ($module) {

    case 'name-of-module-to-lazy-load':

      // Set the content lazy
      $this->add_prop($module, $props, 'lazy-load', true);
      break;
  }

  parent::init_model_props($module, $props);
}
```

Among others, the following are several uses cases for lazy-loading the data for a module:

- Modules which are displayed on several pages (eg: a "latest posts" widget on a sidebar) can have its data cached in the client (eg: through Service Workers, localStorage, etc) and, by lazy-loading, this data is not fetched again on the server on each request
- Fetching data from a different domain
- Improve apparent loading speed by lazy-loading data for below-the-fold modules (eg: a post's comments)
- Fetching data with user state on a page without user state ([as outlined here](https://www.smashingmagazine.com/2018/12/caching-smartly-gutenberg/))

### DataQueries

Currently working on it, coming soon...

### Filters

Currently working on it, coming soon...

### QueryHandlers

Currently working on it, coming soon...

### Extra URIs

Currently working on it, coming soon...

### Multidomain

By default, a module will fetch its data from the domain where the application is hosted. To change this to a different domain(s) or subdomain(s) is done by setting prop `"dataload-multidomain-sources"` on the module:

```php
function init_model_props($module, &$props) {
    
  switch ($module) {

    case 'name-of-module-to-change-domain-source':

      $this->add_prop(
        $module, 
        $props, 
        'dataload-multidomain-sources', 
        'https://anotherdomain.com'
      );
      break;
  }

  parent::init_model_props($module, $props);
}
```

We can also pass an array of domains, in which case the module will fetch its data from all of them:

```php
function init_model_props($module, &$props) {
    
  switch ($module) {

    case 'name-of-module-to-change-domain-source':

      $this->add_prop(
        $module, 
        $props, 
        'dataload-multidomain-sources', 
        array(
          'https://anotherdomain1.com',
          'https://subdomain.anotherdomain2.com',
          'https://www.anotherdomain3.com',
        );
      break;
  }

  parent::init_model_props($module, $props);
}
```

When fetching data from several sources, each source will keep its own state in the [QueryHandler](#queryhandlers). Then, it is able to query different amounts of data from different domains (eg: 3 results from domain1.com and 6 results from domain2.com), and stop querying from a particular domain when it has no more results.

### DataStructureFormatter

Currently working on it, coming soon...

## Data-Posting and Operations

Currently working on it, coming soon...

### ActionExecuters

Currently working on it, coming soon...

## Validations

Currently working on it, coming soon...

### Checkpoints

Currently working on it, coming soon...

## Context

### Context Vars

It is a global variable, hosted under [`PoP_ModuleManager_Vars::$vars`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/pop-engine-vars.php#L5), accessed through [`PoP_ModuleManager_Vars::get_vars`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/pop-engine-vars.php#L85), and naturally referred as `$vars`, which holds important information needed to process the webpage. Properties in `$vars` are those which are accessed widely throughout the application, and which, upon changing their value, alter the component hierarchy.

**1. Properties which are accessed widely throughout the application**

`$vars` acts as a single, central repository of information, where properties can be calculated just once or initialized with default values, and promoting consistency, by providing a unique place from where to fetch a certain value from anywhere in the application.

For instance, property `output`, which is obtained through `$_REQUEST["output"]` and accepts values `"HTML"` or `"JSON"`, is accessed through `$vars['output']`, and is initialized to value `"HTML"` if `$_REQUEST["output"]` is empty.

**2. Properties which, upon changing their value, alter the component hierarchy**

Changing the values of certain properties will alter the component hierarchy. For instance, passing parameter `modulefilter=modulepaths` in the URL will make the component hierarchy only include those modules specified under parameter `modulepaths[]`. Hence, property `modulefilter` must be in `$vars`.

Keeping these properties in `$vars` is needed for the following reasons:

_1. To calculate the `modelInstanceId`:_ the `modelInstanceId` is the unique identifier representing the particular instance of the component hierarchy. This id is calculated by function [`ModelInstanceProcessor_Utils::get_model_instance_id()`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/vars/pop-modelinstance-processor-utils.php#L11), which simply calculates a hash of the values of all properties which alter the component hierarchy. Because not all properties in `$vars` alter the component hierarchy, these ones must be defined by implementing hook [`"ModelInstanceProcessor:model_instance_components"`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/vars/pop-modelinstance-processor-utils.php#L17)

_2. To determine the entry module_: The component hierarchy's top-most module is called the entry module. Every potential entry module must define a list of conditions, to be evaluated against `$vars`, that need be satisfied to be chosen the entry module  (more on this under [PageModuleProcessors](#pagemoduleprocessors)).

_3. To decouple processed page from requested page_: Storing all properties which modify the component hierarchy under `$vars`, making sure that these properties are only accessed through `$vars` all throughout the application, and then modifying these values directly in `$vars`, makes it possible to manipulate the response, for instance adding more data. This way, it is possible to fetch more than one page's content on a single request (for preloading views to cache on the client or other use cases), or send personalized transactional emails to many users on a single request, among other use cases.

#### Setting properties in `$vars`

When first accessed, `$vars` is initialized with certain current request values, such as:

- Hierarchy (home, single, page, author, etc)
- Output (HTML, JSON, etc)
- Module filter, if any
- Mangled output?
- The queried object (the post object in single hierarchy, the user object in author hierarchy, etc)
- Others

Plugins must add their own properties and corresponding values in `$vars` by implementing hook [`"\PoP\Engine\Engine_Vars:add_vars"`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/pop-engine-vars.php#L242). `$vars` can be [`reset`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/pop-engine-vars.php#L8) at any moment and filled with different values, for instance to process a different request.

### PageModuleProcessors

Currently working on it, coming soon...

### User State

Currently working on it, coming soon...

## Client-side Rendering

Currently working on it, coming soon...

### JavaScript templates through Handlebars

Currently working on it, coming soon...

### Executing JavaScript functions

Currently working on it, coming soon...

### Resources

Currently working on it, coming soon...

### Asset-bundling

Currently working on it, coming soon...

### Progressive Booting

Currently working on it, coming soon...

### Links in body

Currently working on it, coming soon...

### State Management

Currently working on it, coming soon...

### Data Cache, Configuration Cache and Replication

Currently working on it, coming soon...

### Reactivity

Currently working on it, coming soon...

## Server-Side Rendering

Currently working on it, coming soon...

### Isomorphism

Currently working on it, coming soon...

### JavaScript templates into PHP through LightnCandy

Currently working on it, coming soon...

### Rendering a Webpage as a Transactional Email

Currently working on it, coming soon...

## Caching

Currently working on it, coming soon...

### Configuration Cache

Currently working on it, coming soon...

## Coding Peculiarities

### WordPress Legacy

Being first built as a WordPress website, PoP has taken several concepts from this CMS, such as: 

- The hierarchy model (home, single, author, page, 404 and tag, and category to be added in the future)
- Pages, Posts and Custom Post Types
- Hooks: actions and filters
- Meta (post meta, user meta, comment meta and tag meta)
- Plugins for extending functionality

## Technology Stack

### CMS-Agnosticism

We are currently attempting to make the existing implementation of PoP be CMS-agnostic, so that it can be used with other PHP-based Content Management Systems (such as Joomla or Drupal) in addition to WordPress. We have already re-architected the codebase to use interfaces to access the CMS-provided functionality, and are migrating all code to use these interfaces.

Making functionality become CMS-agnostic is accomplished by splitting plugins into 2 entities: a generic one containing definitions of interfaces for all the required functionalities, and the implementation of the interfaces for the specific CMS.

For instance, plugin [PoP CMS Model](https://github.com/leoloso/PoP/tree/master/pop-cmsmodel) is a generic implementation, declaring all the functionalities it requires through [`interface \PoP\CMSModel\FunctionAPI`](https://github.com/leoloso/PoP/blob/master/pop-cmsmodel/kernel/cms-apis/cms-functionapi-interface.php). This interface is implemented by plugin [PoP CMS Model for WordPress](https://github.com/leoloso/PoP/tree/master/pop-cmsmodel-wp) through [`class \PoP\CMSModel\WP\FunctionAPI`](https://github.com/leoloso/PoP/blob/master/pop-cmsmodel-wp/kernel/cms-apis/cms-functionapi.php).

Then, all throughout the application, we must access the functionality through the interface and never directly through the CMS library. For instance, function `get_posts`, which is defined in the interface mentioned above, must be invoked like this:

```php
$cmsapi = \PoP\CMS\FunctionAPI_Factory::get_instance();
$query = array(
  // Query parameters here...
);
return $cmsapi->get_posts($query);
```

Currently, PoP's code adopts several concepts from WordPress, namely:

1. Hierarchy
2. Pages, Posts and Custom Post Types
3. Hooks
4. Meta
5. Plugins

Let's analyze, for each of these items, how they can be made CMS-agnostic.

**1. Hierarchy**

PoP implements the same concept of hierarchies as in WordPress, even though with a subset of its implementations: home, single, author, page, tag and 404 (category will be supported in the future too). 

Deducing the hierarchy from the URL is done through interface's [`function query_is_hierarchy($query, $hierarchy)`](https://github.com/leoloso/PoP/blob/master/pop-cms/kernel/cms-apis/cms-functionapi-interface.php), which is implemented for WordPress [here](https://github.com/leoloso/PoP/blob/master/pop-cms-wp/kernel/cms-apis/cms-functionapi.php#L69).

The signature of this function receives a `$query` object, which is based on the WordPress `WP_Query` class, and which is in charge of calculating the hierarchy from either the current URL or from the properties in `$vars`. Hence, an implementation of the `$query` object, [through an interface `Query`](https://github.com/leoloso/PoP/issues/78), must be implemented for the other CMSs. This should be relatively easy to accomplish.

However, if hierarchies could not be deduced from the URL by other CMSs, PoP should still work, even though limited to treating every URL as the "page" hierarchy (i.e. mydomain.com/ will be treated as "page" instead of "home", mydomain.com/author/leo will be treated as "page" instead of "author", etc). This is because if the hierarchy is not provided then it defaults to "page", and treating URLs as pages should work for any CMS

**2. Pages, Posts and Custom Post Types**

PoP is based on the entity model introduced in WordPress, based on the concept of "Pages", "Posts" and "Custom Post Types". Each CMS needs to validate if it supports this entity model, or fall back to a lower entity if not, as follows:

- "Pages" should be supported by every CMS, since, at its most basic, a URL can be treated as a page
- "Posts" should be supported by any blogging platform (such as Joomla or Drupal), and if it were not supported, it can be treated as a page
- "Custom Post Types" can be treated as "Posts" or, if not available, as "Pages"

Hence, PoP can be considered a progressive enhancement, in which WordPress unlocks all the features and capabilities, and other CMSs may have restricted features and capabilities but without breaking.

**3. Hooks**

PoP uses [hooks](https://codex.wordpress.org/Plugin_API) everywhere, through both functions `do_action` and `apply_filters`, allowing any piece of code to be overridable by any 3rd party, or be injected extra functionality.

The code for these 2 functions can be copied from WordPress into PoP so they will always work, on any CMS. [This task must still be done](https://github.com/leoloso/PoP/issues/79).

An issue for the other CMSs is to execute each appropriate hook whenever it is expected by PoP. For instance, PoP uses hook "plugins_loaded" to initialize plugins, and hook "init" when the system is ready. These 2 hooks, and possibly several others, must also be executed at the corresponding stages in the other CMSs. The names themselves ("plugins_loaded", "init", etc) can be abstracted through functions in the interface, if it is not desired to follow the naming by WordPress.

**4. Meta**

Many functionalities depend on post/user/comment/tag meta data (for instance, Recommending a post, Following a user, and many others). CMSs supporting meta for these entities will have no issues, but those which do not may not be able to access these functionalities, as in the progressive enhancement situation described above.

**5. Plugins**

The codebase is split into plugins, similar to WordPress. Other CMSs will have their own way to install new functionalities, which may or may not work like plugins, so they will need to wrap the plugin-provided code into their own required packaging and activate it using their own methodology.

### Handlebars

Currently working on it, coming soon...

### LightnCandy

Currently working on it, coming soon...

## Extending and installing functionality

### Plugin Dependencies and Initialization Order

Currently working on it, coming soon...

### Progressive Plugins

Currently working on it, coming soon...

### Plugin Architecture

Currently working on it, coming soon...

### Themes

Currently working on it, coming soon...

## Packaging and Deployment

Currently working on it, coming soon...

### Custom Tooling

Currently working on it, coming soon...

### Code Splitting

Currently working on it, coming soon...

## Enabling Pre-built Application Features

### Progressive-Web App

Currently working on it, coming soon...

### Single-Page Application

Currently working on it, coming soon...

### Content CDN

Currently working on it, coming soon...

### A/B Testing

Currently working on it, coming soon...
