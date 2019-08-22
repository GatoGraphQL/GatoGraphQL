![PoP](https://assets.getpop.org/wp-content/themes/getpop/img/pop-logo-horizontal.png)

# Developer Guide

> Note: Because PoP is currently under development and its internals have changed significantly, this document has become out of date! So please don't use it as a proper reference yet. We will update it once we are approaching the release of version 1.0 of PoP.

## Getting Started

<!-- PoP is founded on [Object-Oriented Programming](https://en.wikipedia.org/wiki/Object-oriented_programming) (OOP). If you know OOP, then you are all set to go! If not, taking [one](https://www.tutorialspoint.com/php/php_object_oriented.htm) [of](https://code.tutsplus.com/tutorials/object-oriented-php-for-beginners--net-12762) [the](https://www.tutorialrepublic.com/php-tutorial/php-classes-and-objects.php) [many](https://www.killerphp.com/tutorials/object-oriented-php/) tutorials on OOP in PHP before starting will greatly help understand all code in this guide. -->

## Foundations

### Components

PoP is set of PHP components for building sites using [components](https://en.wikipedia.org/wiki/Component-based_software_engineering) (i.e. the component as a conceptual unit for building an application). In PoP, a component is called a "module", so from now on the terms "component" and "module" are used interchangeably. 

### Component Hierarchy and JSON Output

The relationship of all modules wrapping each other, from the top-most module all the way down to the last level, is called the component hierarchy. This relationship can be expressed through an associative array (an array of key => property) on the server-side, in which each module states its name as the key attribute and its inner modules under property "modules":

```php
// Component hierarchy on server-side, eg: through PHP:
[
  "top-module" => [
    "modules" => [
      "module-level1" => [
        "modules" => [
          "module-level11" => [
            "modules" => [...]
          ],
          "module-level12" => [
            "modules" => [
              "module-level121" => [
                "modules" => [...]
              ]
            ]
          ]
        ]
      ],
      "module-level2" => [
        "modules" => [
          "module-level21" => [
            "modules" => [...]
          ]
        ]
      ]
    ]
  ]
]
```

Please notice how modules are nested. This way, module properties will never collide with each other if having the same name, avoiding having to add namespaces for modules. For instance, the property `"class"` from one module's configuration will not override property `"class"` from another module's configuration.

The API then simply encodes this array as a JSON object for consumption. Its format is a specification all by itself: As long as the server returns the JSON response in its required format, the client can consume the API independently of how it is implemented:

```javascript
// Component hierarchy encoded as JSON:
{
  "top-module": {
    modules: {
      "module-level1": {
        modules: {
          "module-level11": {
            ...
          },
          "module-level12": {
            modules: {
              "module-level121": {
                ...
              }
            }
          }
        }
      },
      "module-level2": {
        modules: {
          "module-level21": {
            ...
          }
        }
      }
    }
  }
}
```

The relationship among modules is defined on a strictly top-down fashion: a module wraps other modules and knows who they are, but it doesn't know, and doesn't care, which modules are wrapping him. For instance, in the JSON code above, module `"module-level1"` knows it wraps modules `"module-level11"`` and "module-level12"`, and, transitively, it also knows it wraps `"module-level121"`; but module `"module-level11"` doesn't care who is wrapping him, consequently is unaware of `"module-level1"`.

Having the component-based structure, we can now add the actual information required by each module, which is categorized into either settings (such as configuration values and other properties) and data (such as the IDs of the queried database objects and other properties), and placed accordingly under entries `modulesettings` and `datasetmoduledata`:

```javascript
{
  modulesettings: {
    "top-module": {
      configuration: {...},
      ...,
      modules: {
        "module-level1": {
          configuration: {...},
          ...,
          modules: {
            "module-level11": {
              repeat...
            },
            "module-level12": {
              configuration: {...},
              ...,
              modules: {
                "module-level121": {
                  repeat...
                }
              }
            }
          }
        },
        "module-level2": {
          configuration: {...},
          ...,
          modules: {
            "module-level21": {
              repeat...
            }
          }
        }
      }
    }
  },
  datasetmoduledata: {
    "top-module": {
      dbobjectids: [...],
      ...,
      modules: {
        "module-level1": {
          dbobjectids: [...],
          ...,
          modules: {
            "module-level11": {
              repeat...
            },
            "module-level12": {
              dbobjectids: [...],
              ...,
              modules: {
                "module-level121": {
                  repeat...
                }
              }
            }
          }
        },
        "module-level2": {
          dbobjectids: [...],
          ...,
          modules: {
            "module-level21": {
              repeat...
            }
          }
        }
      }
    }
  }
}
```

Module properties (configuration values, what database data to fetch, etc) and descendant modules are not added manually to the associative array. Instead, they are defined through an object called a [ModuleProcessor](#moduleprocessor) on a module by module basis. The PoP engine will traverse all modules in the component hierarchy, starting from the entry module, fetch the properties for each from the corresponding ModuleProcessor, and create the nested associative array with all properties for all modules. A ModuleProcessor for a module called `MODULE_SOMENAME` looks like this:

```php
class SomeModuleProcessor extends AbstractModuleProcessor {

  const MODULE_SOMENAME = 'somename';

  function getSubmodulesToProcess() {
  
    return array(
      MODULE_SOMENAME,
    );
  }

  function getSubmodules($module) 
  {
    $ret = parent::getSubmodules($module);

    switch ($module[1]) {
      
      case self::MODULE_SOMENAME:
        
        $ret[] = self::MODULE_SOMELAYOUT1;
        $ret[] = self::MODULE_SOMELAYOUT2;
        break;
    }

    return $ret;
  }

  function getImmutableConfiguration($module, &$props) 
  {
    $ret = parent::getImmutableConfiguration($module, $props);

    // Print the modules properties ...
    switch ($module[1]) {
      case self::MODULE_SOMENAME:        
        $ret['description'] = __('Some description');
        $ret['showmore'] = $this->getProp($module, $props, 'showmore');
        $ret['class'] = $this->getProp($module, $props, 'class');
        break;
    }

    return $ret;
  }
  
  function initModelProps($module, &$props) 
  {
    // Implement the modules properties ...
    switch ($module[1]) {
      case self::MODULE_SOMENAME:
        $this->setProp($module, $props, 'showmore', false);
        $this->appendProp($module, $props, 'class', 'text-center');
        break;
    }

    parent::initModelProps($module, $props);
  }
  // ...
}
```

Database object data is retrieved and placed under a shared section called `databases`, to avoid duplicating information when 2 or more different modules fetch the same objects from the database. In addition, it is added in a relational manner to the associative array and printed in the JSON response, to avoid duplicating information when 2 or more different database objects are related to a common object (such as 2 posts having the same author). In other words, database object data is normalized. The structure is a dictionary, organized under each object type first and object ID second, from which we can obtain the object properties: 

```javascript
{
  databases: {
    primary: {
      dbobject_type: {
        dbobject_id: {
          property: ...,
          ...
        },
        ...
      },
      ...
    }
  }
}
```

### API response example

For instance, the API response below contains a component hierarchy with two modules, `"page" => "post-feed"`, where module `"post-feed"` fetches blog posts. Please notice the following:

- Each module knows which are its queried objects from property `dbobjectids` (IDs 4 and 9 for the blog posts)
- Each module knows the object type for its queried objects from property `dbkeys` (each post's data is found under "posts", and the post's author data, corresponding to the author with the ID given under the post's property "author", is found under "users"):
- Because the database object data is relational, property "author" contains the ID to the author object instead of printing the author data directly

```javascript
{
  datasetmoduledata: {
    "page": {
      modules: {
        "post-feed": {
          dbobjectids: [4, 9]
        }
      }
    }
  },
  modulesettings: {
    "page": {
      modules: {
        "post-feed": {
          dbkeys: {
            id: "posts",
            author: "users"
          }
        }
      }
    }
  },
  databases: {
    primary: {
      posts: {
        4: {
          title: "Hello World!",
          author: 7
        },
        9: {
          title: "Everything fine?",
          author: 7
        }
      },
      users: {
        7: {
          name: "Leo"
        }
      }
    }
  }
}
```

## Modules

### Definition of a Module

Every module has a unique name that identifies it, defined as a constant:

```php
const MODULE_SOMENAME = 'somename';
```

All the properties of the modules are implemented through objects called [ModuleProcessor](#moduleprocessor).
<!--
> Note: the name of a module cannot include the special character "|" (`POP_CONSTANT_VIRTUALMODULEATTS_SEPARATOR`), as will be explained below
-->
### Virtual Modules

Virtual modules are "dynamically-generated" modules: modules with a base personality and dynamic behaviour. For instance, the [custom-querying capabilities of the API](ArchitectureDesignAndImplementation.md#API-Custom-Querying-Capabilities) create the component hierarchy based on the value of URL parameter `fields`, creating a virtual module along its path for each of the nested relationships. 
<!--
Another example (yet to be implemented) involves the integration of PoP with [WordPress Gutenberg](https://wordpress.org/gutenberg/): Gutenberg allows to drag-and-drop blocks to the page and customize them through properties; then, to have Gutenberg input modules and PoP save them, two blocks from the same block/component must be made unique, hence they can be dynamically created by selecting a base module for its personality (a scroll of posts, a calendar of events, etc) and then assigning a random id to each, or a serialization of their properties, for its dynamic behaviour.
-->
Virtual modules cannot depend on props for defining their behaviour, because at the time of creating the component hierarchy we don't have the `$props` available (otherwise it's a chicken or egg situation). Hence, the particular properties given to a virtual module are coded into the module name itself<!-- as a serialized array separated from the module name with a `"|"` (which is represented under constant `POP_CONSTANT_VIRTUALMODULEATTS_SEPARATOR`): `modulename|virtualmoduleatts`-->. Then, the personality of the module is given by the module with name `"modulename"`, and the virtual module attributes <!--(`"virtualmoduleatts"`)-->are the runtime element which define its dynamic behaviour.
<!--
Extracting the pair of module name and virtual module atts from the module is done through function `extract_virtualmodule`, like this:

```php
list($module, $virtualmoduleatts) = \PoP\Engine\VirtualModuleUtils::extractVirtualmodule($module);
```

To generate a virtual module is done through function `create_virtualmodule`, like this:

```php
$virtualmodule = \PoP\Engine\VirtualModuleUtils::createVirtualmodule($module, $virtualmoduleatts),
```
-->
### ModuleProcessor

A ModuleProcessor is an object class in which to define all the properties of a module. ModuleProcessors are implemented following the [SOLID](https://scotch.io/bar-talk/s-o-l-i-d-the-first-five-principles-of-object-oriented-design) methodology, establishing an object inheritance scheme to progressively add properties to modules. The base class for all ModuleProcessors is `AbstractModuleProcessor`:

```php
namespace PoP\Engine;
abstract class AbstractModuleProcessor {

  // ...
}
```

In practice, because a module is implemented through a ModuleProcessor object, describing a module equals to describing how the ModuleProcessor implements all functions to define the properties of the module.

Every ModuleProcessor can handle more than 1 module: Because different modules will naturally share many properties, then having a single ModuleProcessor implement many modules is more legible and reduces the amount of code required compared to having 1 ModuleProcessor per module. Which modules are handled by the ModuleProcessor is defined through function `getSubmodulesToProcess`:

```php
class SomeModuleProcessor extends \PoP\Engine\AbstractModuleProcessor {

  const MODULE_SOMENAME1 = 'somename1';
  const MODULE_SOMENAME2 = 'somename2';
  const MODULE_SOMENAME3 = 'somename3';

  function getSubmodulesToProcess() {
  
    return array(
      MODULE_SOMENAME1,
      MODULE_SOMENAME2,
      MODULE_SOMENAME3,
    );
  }

  // Implement the modules properties ...
  // ...
}
```

Once the ModuleProcessor class is instantiated, all of its defined modules become available to be added to the component hirarchy.

To access the properties of a module, we must reference its corresponding ModuleProcessor through function `getProcessor` from class `ModuleProcessor_Manager`:

```php
// Retrive the PoP_ModuleProcessor_Manager object from the factory
$moduleprocessor_manager = \PoP\Engine\ModuleProcessor_Manager_Factory::getInstance();

// Obtain the ModuleProcessor for module MODULE_SOMENAME
$processor = $moduleprocessor_manager->getProcessor([SomeModuleProcessor::class, SomeModuleProcessor::MODULE_SOMENAME]);

// Do something...
// $processor->...
```

### Anatomy of a Module

Because a ModuleProcessor can handle several modules, then each of its functions will receive a parameter `$module` indicating which is the module being processed. Please notice how, inside the function, we can conveniently use `switch` statements to operate accordingly (modules with shared properties can easily share the logic) and, according to [SOLID](https://scotch.io/bar-talk/s-o-l-i-d-the-first-five-principles-of-object-oriented-design), we first obtain the results of the parent class and then the ModuleProcessor adds its own properties:

```php
class SomeModuleProcessor extends \PoP\Engine\AbstractModuleProcessor {

  function foo($module) 
  {
    // First obtain the value from the parent class
    $ret = parent::foo($module);

    // Add properties to the module
    switch ($module[1]) 
    {
      case self::MODULE_SOMENAME1:
        
        // Do something with $ret
        // ...
        break;

      // These modules share the same properties
      case self::MODULE_SOMENAME2:
      case self::MODULE_SOMENAME3:
        
        // Do something with $ret
        // ...
        break;
    }

    return $ret;
  }
}
```

In addition to parameter `$module`, most functions will also receive a `$props` parameter, with the value of the "props" set on the module (more on section [Props](#props)):

```php
class SomeModuleProcessor extends \PoP\Engine\AbstractModuleProcessor {

  function foo($module, &$atts) 
  {
    $ret = parent::foo($module, &$atts);

    // ...

    return $ret;
  }
}
```

### Composition

Modules are composed of other modules through function `getSubmodules`:

```php
class SomeModuleProcessor extends \PoP\Engine\AbstractModuleProcessor {

  function getSubmodules($module) 
  {
    $ret = parent::getSubmodules($module);

    switch ($module[1]) 
    {
      case self::MODULE_SOMENAME1:
        
        $ret[] = [self::class, self::MODULE_SOMENAME2];
        break;

      case self::MODULE_SOMENAME2:
      case self::MODULE_SOMENAME3:
        
        $ret[] = [LayoutModuleProcessor::class, LayoutModuleProcessor::MODULE_LAYOUT1];
        $ret[] = [LayoutModuleProcessor::class, LayoutModuleProcessor::MODULE_LAYOUT2];
        $ret[] = [LayoutModuleProcessor::class, LayoutModuleProcessor::MODULE_LAYOUT3];
        break;
    }

    return $ret;
  }
}
```

> Note: the component hierarchy is created by calling `getSubmodules` on the entry-module and then repeating the process, iteratively, for its descendant modules.

Abstract ModuleProcessors can define what descendant modules will be required through placeholder functions, to be implemented by an inheriting ModuleProcessor:

```php
abstract class PostLayoutAbstractModuleProcessor extends \PoP\Engine\AbstractModuleProcessor {

  function getSubmodules($module) {
  
    $ret = parent::getSubmodules($module);

    if ($thumbnail_module = $this->getThumbnailModule($module)) 
    {
      $ret[] = $thumbnail_module;
    }
    if ($content_module = $this->getContentModule($module)) 
    {
      $ret[] = $content_module;
    }
    if ($aftercontent_modules = $this->getAftercontentModules($module)) 
    {
      $ret = array_merge(
        $ret,
        $aftercontent_modules
      );
    }

    return $ret;
  }

  protected function getContentModule($module) 
  {
    // Must implement
    return null;
  }
  protected function getThumbnailModule($module) 
  {
    // Default value
    return [self::class, self::MODULE_LAYOUT_THUMBNAILSMALL];
  }
  protected function getAftercontentModules($module) 
  {
    return array();
  }
}

class PostLayoutModuleProcessor extends PostLayoutAbstractModuleProcessor {

  protected function getContentModule($module) 
  {
    switch ($module[1]) 
    {
      case self::MODULE_SOMENAME1:
        
        return [self::class, self::MODULE_LAYOUT_POSTCONTENT];

      case self::MODULE_SOMENAME2:
      case self::MODULE_SOMENAME3:
        
        return [self::class, self::MODULE_LAYOUT_POSTEXCERPT];
    }

    return parent::getContentModule($module);
  }
  protected function getThumbnailModule($module) 
  {
    switch ($module[1]) 
    {
      case self::MODULE_SOMENAME1:
        
        return [self::class, self::MODULE_LAYOUT_THUMBNAILBIG];

      case self::MODULE_SOMENAME3:
        
        return [self::class, self::MODULE_LAYOUT_THUMBNAILMEDIUM];
    }

    return parent::getThumbnailModule($module);
  }
  protected function getAftercontentModules($module) 
  {
    $ret = parent::getAftercontentModules($module);

    switch ($module[1]) 
    {
      case self::MODULE_SOMENAME2:
        
        $ret[] = self::MODULE_LAYOUT_POSTLIKES;
        break
    }

    return $ret;
  }
}

// Initialize
new PostLayoutModuleProcessor();
```

### Function Names and Caching

The component hierarchy depends not on the URL, but on what components are needed in that URL. Hence, a component hierarchy included in different URLs can be cached and reused across them. For instance, requesting `/events/1/` `/events/2/` will most like have the same component hierarchy. Then, the 2nd request can reuse the cached component hierarchy from the first request, avoiding to calculate all required properties again and thus optimizing performance.

Most properties can be cached, however a number of them cannot be cached. For instance, adding configuration property `"classname"` with value `post-{id}`, where ID is the id of the requested post, cannot be cached, since the ID depends directly on the URL. To address this and maximize how much caching can be achieved, PoP has split module properties into non-overlapping segments: "cacheable" and "non-cacheable", and implemented through appropriate functions.

Caching is carried out on two different areas: the server-side and the client-side. All functionality will be required on the server, however not everything will necessarily reach the client. For instance, "props" are used to modify configuration values; while configuration values are sent to the client, the "props" themselves are not. Caching for these two cases is different, and as such the functions to define them will be different, as explained next.

#### Server-side only Properties: Model and Request

Properties that will only required on the server-side of the application, and never reach the client, can be divided into "model" and "request":

- **model:** it is a synonym of "component hierarchy". It represents all those module properties which are fixed to the component hierarchy. Hence, when caching the component hierarchy on the server, these properties can be included in the cache.
- **request:** properties which can change based on the requested URL, hence they cannot be included when caching the component hierarchy.

For instance, a prop `"description"` setting value `"Welcome to my site!"` is immutable within the component hierarchy, hence it can be set in a `model` function. Prop `"classname"` with value `post-{id}`, where ID is the id of the requested post, which depends directly on the URL, must be set under a `request` function.

#### Client-side Properties: Immutable, Mutable on Model, and Mutable on Request

Properties which are sent to the client-side (eg: configuration values) can also be cached on the client-side application, so that once a component hierarchy has been loaded, we can avoid fetching this information again and, if we already have all the data to satisfy the request, we can render a layout without even having to communicate with the server.

Caching on the server-side is easy: each component hierarchy is cached on its own file, so these can be handled independently. In the client-side, however, we will be caching together the JSON response from different requests, involving different component hierarchies which have shared properties, and we want to cache these shared properties only once in total instead of once per request. For instance, requesting `/posts/1` may have component hierarchy `"singlepost" => "postlayout"`, while `/events/1` may have component hierarchy `"singlepost" => "eventlayout"`. In this case, the first level, `"singlepost"`, is shared across different requests.

To deal with this situation, we need to make the naming of functions a bit more granular: the term `model` explained above has been further split into two, `immutable` and `mutable on model`, and the term `request` has been renamed as `mutable on request`. How and why this works is explained in detail in section [Client-side caching](ArchitectureDesignAndImplementation.md#client-side-caching) of the "Architecture Design and Implementation" document.

The difference between `immutable` and `mutable on model` is that properties on `mutable on model` can change their value depending on the module's descentant modules:

- **immutable:** properties which are fixed
- **mutableonmodel:** properties which can change based on the descendant modules
- **mutableonrequest:** properties which can change based on the requested URL

For instance, we could have a configuration property `"descendants"` explicitly declaring the names of its descendant modules. In the example below, module `"singlepost"` has descendant module `"postlayout"` and configuration property `"descendants"` with value `["postlayout"]`:

```javascript
{
  "singlepost": {
    configuration: {
      class: "text-center",
      descendants: ["postlayout"]
    },
    modules: {
      "postlayout": {
        configuration: {
          class: "post-37",
        }
      }
    }
  }
}
```

Then, properties are filled like this: `class: "text-center"` is `immutable`, `descendants: ["module2"]` is `mutable on model`, and `class: "post-37"`, which corresponds to `post-{id}`, is `mutable on request`.

### Props

Modules are most useful when they are generic and enable customization through properties, or "props". For instance, a module can define a prop to change the background color configuration value, define how many objects to fetch from the database, or anything it may need. 

Setting props works in one direction only: modules can set props on any descendant module, but no module can set props on any ancestor module or on any module belonging to a different branch from the component hierarchy. In the structure below, "module1" can set props on "module2", "module3" and "module4", "module2" on "module3", and "module3" and "module4" on nobody:

```javascript
"module1"
  modules
    "module2"
      modules
        "module3"
    "module4"
```

Modules can set props on descendant modules whichever number of levels below in the component hierarchy, and it is done directly, i.e. without involving the modules in between or affecting their props. In the structure above, "module1" can set a prop directly on "module3" without going through "module2".

Setting props is done through functions `initModelProps($module, &$props)` and `initRequestProps($module, &$props)`. A prop must be implemented in either function, but not on both of them. `initRequestProps` is used for defining props that depend directly on the requested URL, such as adding a classname `post-{id}` to prop `"class"`, where `{id}` is the ID of the requested post on that URL. `initModelProps` is used for everything else. 

Setting props is done at the very beginning: Immediately after obtaining the component hierarchy, PoP Engine will invoke these 2 functions **before anything else** (i.e. before getting the configuration, fetching database data, etc). Hence, with the exception of the functions to create the component hierarchy (i.e. `getSubmodules` and those inner functions invoked by `getSubmodules`), every function in the `ModuleProcessor` can receive `$props`. 

`initModelProps` and `initRequestProps` store the props under parameter `$props`, hence it is passed by reference. In all other functions, `$props` may also be passed by reference, but only for performance issues, to not duplicate the object in memory.

Inside these 2 functions, we get to set the props through the following 3 functions:

- `function setProp($module_or_modulepath, &$props, $field, $value, $starting_from_modulepath = array())`
- `function appendProp($module_or_modulepath, &$props, $field, $value, $starting_from_modulepath = array())`
- `function mergeProp($module_or_modulepath, &$props, $field, $value, $starting_from_modulepath = array())`

These 3 functions are similar to each other, with the following differences: 

`appendProp` is used to append a value to an existing prop, hence it is a cumulative property. It is often used for adding classnames. For instance, having modules "module1" and "module2" both execute `appendProp` on "module3" for property `"class"` with values `"big"` and `"center"` respectively, will make the `"class"` property of "module3" be set as `"big center"`.

`mergeProp` is similar, but concerning arrays. It is often used for adding params to be printed on the element in the DOM. For instance, having modules "module1" and "module2" both execute `mergeProp` on "module3" for property `"params"` with values `["data-target" => "#main"]` and `["data-mode" => "static"]` respectively, will make the `"params"` property of "module3" be set as `["data-target" => "#main", "data-mode" => "static"]`.

`setProp` is not cumulative, but accepts only 1 value: that one that is set the first. Hence, higher-level modules have priority for setting a property value over lower-level ones along the component hierarchy. For instance, having modules "module1" and "module2" both execute `setProp` on "module3" for property `"title"` with values `"First title"` and `"Second title"` respectively, will make the `"title"` property of "module3" be set as `"First title"`.

All 3 methods receive the same parameters:

- `$module_or_modulepath`: This value can either be a string with the name of the module on which to set the prop, or an array. If it is the name of the module, there are 2 possibilities: if the target module is the same module setting the prop, then the module is setting a prop on itself (eg: to set the default value for the prop); if not, it will set a prop on the descendant module with that name wherever it is found along the subcomponent hierarchy. If it is an array, then this is already the subpath to the targeted descendant module where to set the prop.
- `&$props`: This is the object storing all the props, a unique object passed across all modules along the component hierarchy to initialize all props
- `$field`: Name of the property
- `$value`: Value to set on the property
- `$starting_from_modulepath`: Array with a module subpath from which to find the target module where to set the prop

Every module first initializes its own props, and only then continues the flow to the parent class, so that inheriting classes have priority over their ancestors in the object inheritance scheme:

```php
function initModelProps($module, &$props) 
{
  // Set prop...
  // Set prop...
  // Set prop...

  parent::initModelProps($module, $props);
}
```

Accessing the value of the prop is done through `function getProp($module, &$props, $field, $starting_from_modulepath = array())`. The signature of the function is similar to the ones above, however without parameter `$value`.

Let's see an example: a component for rendering maps has 2 orientations: `"horizontal"` and `"vertical"`. It is composed by modules `"map" => "map-inner"`, and both these modules need this property. Module `"map"` will set the value by default to `"vertical"`, obtain the value for this prop just in case an ancestor module had already set the prop, and then set this value on module `"map-inner"`. Function below is implemented for module `"map"`:

```php
function initModelProps($module, &$props) 
{
  switch ($module[1]) {
    case self::MODULE_MAP:
      // Module "map" is setting the default value
      $this->setProp($module, $props, 'orientation', 'vertical');

      // Obtain the value from the prop
      $orientation = $this->getProp($module, $props, 'orientation');

      // Set the value on "map-inner"
      $this->setProp([[SomeModule::class, SomeModule::MODULE_MAPINNER]], $props, 'orientation', $orientation);
      break;
  }

  return parent::initModelProps($module, $props);
}
```

By default, module map will have prop `"orientation"` set with value `"vertical"`. However, parent module `"map-wrapper"` can set this prop beforehand to `"horizontal"`:

```php
function initModelProps($module, &$props) 
{
  switch ($module[1]) {
    case self::MODULE_MAPWRAPPER:
      $this->setProp([[SomeModule::class, SomeModule::MODULE_MAP]], $props, 'orientation', 'horizontal');      
      break;
  }

  return parent::initModelProps($module, $props);
}
```

### Configuration

Configuration values are added under functions:

- `function getImmutableConfiguration($module, &$props)`
- `function getMutableonmodelConfiguration($module, &$props)`
- `function getMutableonrequestConfiguration($module, &$props)`

For instance:

```php
// Implement the modules properties ...
function getImmutableConfiguration($module, &$props) 
{
  $ret = parent::getImmutableConfiguration($module, $props);

  switch ($module[1]) {
    case self::MODULE_SOMENAME:
      $ret['description'] = __('Some description');
      $ret['classes']['description'] = 'jumbotron';
      break;
  }

  return $ret;
}
```

Please notice that the configuration receives the `$props` parameter, hence it can print configuration values set through props. `immutable` and `mutable on model` configuration values are initialized through `initModelProps`, and `mutable on request` ones are initialized through `initRequestProps`:

```php
// Implement the modules properties ...
function getImmutableConfiguration($module, &$props) 
{
  $ret = parent::getImmutableConfiguration($module, $props);

  switch ($module[1]) {
    case self::MODULE_SOMENAME:
      $ret['showmore'] = $this->getProp($module, $props, 'showmore');
      $ret['class'] = $this->getProp($module, $props, 'class');
      break;
  }

  return $ret;
}

function initModelProps($module, &$props) 
{
  switch ($module[1]) {
    case self::MODULE_SOMENAME:      
      $this->setProp($module, $props, 'showmore', false);
      $this->appendProp($module, $props, 'class', 'text-center');
      break;
  }

  parent::initModelProps($module, $props);
}
```

### Data-Loading

Along the component hierarchy, certain modules will define what objects from the database are required, and their descendants will indicate what properties the database object must have. Consider the image below, in which a module `"singlepost"` defines to what DB object to load, and its descendant modules `"post-title"` and `"post-content"` indicate that the object must have properties `"title"` and `"content"` loaded:

![While some modules load the database object, others load properties](https://uploads.getpop.org/wp-content/uploads/2018/12/loading-data-at-intervals.jpg)

#### Dataloading Modules

Those modules indicating what DB objects must be loaded are called "dataloading" modules. To do this, dataloading modules must define the functions and properties below.

##### Defining the DataSource

Indicate if the results are `immutable` (eg: results which never change and are cacheable) or `mutable on request`, through function `getDatasource`. By default results are set as `mutable on request` (through constant `POP_DATALOAD_DATASOURCE_MUTABLEONREQUEST`), so only when results are `immutable` this function must be implemented:

```php
function getDatasource($module, &$props) 
{
  switch ($module[1]) {
    case self::MODULE_WHOWEARE:
      return POP_DATALOAD_DATASOURCE_IMMUTABLE;
  }

  return parent::getDatasource($module, $props);
}
```

##### Defining the Database Object IDs

Define the IDs of the objects to be retrieved from the database, through function `getDbobjectIds`. If the module already knows what database objects are required, it can simply return them:

```php
function getDbobjectIds($module, &$props, $data_properties) 
{
  switch ($module[1]) {
    case self::MODULE_WHOWEARE:
      return [13, 54, 998];
  }

  return parent::getDbobjectIds($module, $props, $data_properties);
}
```

However, most likely, the objects are not known in advance, and must be found through a query. In this case, the ModuleProcessor must inherit from class `QueryDataAbstractModuleProcessor`, which implements `getDbobjectIds` transferring the responsibility of finding the database object IDs to function `getDbobjectIds` from the corresponding [Dataloader](#dataloader).

##### Defining the Dataloader

Define what [Dataloader](#dataloader) to use, which is the object in charge of fetching data from the database, through function `getDataloader`:

```php
function getDataloader($module) 
{
  switch ($module[1]) {
    case self::MODULE_AUTHORARTICLES:
      return [Dataloader::class, Dataloader::DATALOADER_POSTLIST];
  }
    
  return parent::getDataloader($module);
}
```

##### Defining the Query Args

Customize a query to filter data, which is passed to the Dataloader, through functions `getImmutableDataloadQueryArgs` and `getMutableonrequestDataloadQueryArgs`:

```php
protected function getImmutableDataloadQueryArgs($module, $props) 
{
  $ret = parent::getImmutableDataloadQueryArgs($module, $props);
  
  switch ($module[1]) {
    case self::MODULE_AUTHORARTICLES:
      // 55: id of "Articles" category
      $ret['cat'] = 55;
      break;
  }

  return $ret;
}

protected function getMutableonrequestDataloadQueryArgs($module, $props) 
{
  $ret = parent::getMutableonrequestDataloadQueryArgs($module, $props);
  
  switch ($module[1]) {
    case self::MODULE_AUTHORARTICLES:
    
      // Set the logged-in user id
      $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
      $ret['author'] = $cmsapi->getCurrentUserId();
      break;
  }

  return $ret;
}
```
<!--
##### Defining the Filter

The fetched data can be filtered through [Filter](#filter) objects, defined through function `getFilter`:

```php
function getFilter($module) 
{
  switch ($module[1]) {
    case self::MODULE_AUTHORARTICLES:
          
      return GD_FILTER_AUTHORARTICLES;
  }
  
  return parent::getFilter($module);
}
```
-->
##### Defining the QueryHandler

After fetching data, we can communicate state (eg: are there more results? what's the next paging number? etc) through [QueryHandler](#queryhandler) objects, defined through function `getQueryhandler`. By default, it returns object with name `GD_DATALOAD_QUERYHANDLER_ACTIONEXECUTION`, needed when executing an operation (see section [Data-Posting and Operations](#data-posting-and-operations)):

```php
function getQueryhandler($module) 
{
  switch ($module[1]) {
    case self::MODULE_AUTHORARTICLES:
      return GD_DATALOAD_QUERYHANDLER_LIST;
  }
  
  return parent::getQueryhandler($module);
}
```

##### Defining the Data Properties

If the module needs to pass a variable to any other object involved in fetching/processing data ([Dataloader](#dataloader), [QueryHandler](#queryhandler), [ActionExecuter](#actionexecuter), etc), it can do so through "data properties", set through functions `getImmutableHeaddatasetmoduleDataProperties` and `getMutableonrequestHeaddatasetmoduleDataProperties`:

```php
function getImmutableHeaddatasetmoduleDataProperties($module, &$props) 
{
  $ret = parent::getImmutableHeaddatasetmoduleDataProperties($module, $props);

  switch ($module[1]) {
    case self::MODULE_AUTHORARTICLES:
      // Make it not fetch more results
      $ret[GD_DATALOAD_QUERYHANDLERPROPERTY_LIST_STOPFETCHING] = true;
      break;
  }
  
  return $ret;
}
```

##### Lazy-Loading

We can instruct a dataloading module to be lazy-loaded (i.e. instead of fetching its database data immediately, it is fetched on a subsequent request from the client) simply by setting its prop `"lazy-load"` to `true`:

```php
function initModelProps($module, &$props) 
{
  switch ($module[1]) {
    case self::MODULE_AUTHORARTICLES:

      // Set the content lazy
      $this->setProp($module, $props, 'lazy-load', true);
      break;
  }

  parent::initModelProps($module, $props);
}
```

Being a prop, this value can be set either by the dataloading module itself, or by any of its ancestor modules:

```php
function initModelProps($module, &$props) 
{
  switch ($module[1]) {
    case self::MODULE_AUTHORARTICLESWRAPPER:

      // Set the content lazy
      $this->setProp([MODULE_AUTHORARTICLES], $props, 'lazy-load', true);
      break;
  }

  parent::initModelProps($module, $props);
}
```

Among others, the following are several uses cases for lazy-loading the data for a module:

- Modules which are displayed on several pages (eg: a "latest posts" widget on a sidebar) can have its data cached in the client (eg: through Service Workers, localStorage, etc) and, by lazy-loading, this data is not fetched again on the server on each request
- Fetching data from a different domain
- Improve apparent loading speed by lazy-loading data for below-the-fold modules (eg: a post's comments)
- Fetching data with user state on a page without user state ([as outlined here](https://www.smashingmagazine.com/2018/12/caching-smartly-gutenberg/))

##### Conditionally Loading Database Data

We can instruct a dataloading module to not load its data simply by setting its prop `"skip-data-load"` to `true`:

```php
function initModelProps($module, &$props) 
{
  switch ($module[1]) {
    case self::MODULE_AUTHORARTICLES:

      // Set the content lazy
      $this->setProp($module, $props, 'skip-data-load', true);
      break;
  }

  parent::initModelProps($module, $props);
}
```

> Note: Being a prop, this value can be set either by the dataloading module itself, or by any of its ancestor modules.

Among others, the following are several uses cases for not loading the data for a module:

- Loading the Search page without any search parameter
- Validate that the logged-in user has the required permissions
- Load data not when loading the site, but only when loading a page in an SPA

#### Dataloading + Descendant Modules

Starting from a dataloading module, and including itself, any descendant module can execute the functions described below: loading properties or "data fields" on the database object, and "switching domain" from the current database object to another one. 

##### Defining the Data-Fields

"Data fields", which are the properties to be required from the loaded database object, are defined through function `getDataFields`:

```php
function getDataFields($module, $props) 
{
  $ret = parent::getDataFields($module, $props);

  switch ($module[1]) {
    case self::MODULE_AUTHORARTICLES:
      $ret[] = 'title';
      $ret[] = 'content';
      break;
  }

  return $ret;
}
```

The value for "data-fields" is resolved through an object called a [FieldValueResolver](#fieldprocessor), described below.

##### Switching domain to a relational object

When defining what data to retrieve, we can also "switch domain", which is changing from the current database object to another one, defined as a relationship. 

Consider the image below: Starting from the object type "post", and moving down the component hierarchy, we will need to shift the DB object type to "user" and "comment", corresponding to the post's author and each of the post's comments respectively, and then, for each comment, it must change the object type once again to "user" corresponding to the comment's author. After switching to a new domain, from that level at the component hierarchy downwards, all required properties, or data-fields, will be subjected to the new domain: Property "name" is fetched from the "user" object representing the post's author, "content" from the "comment" object representing each of the post's comments, and then "name" from the "user" object representing the author of each comment:

![Changing the DB object from one domain to another](https://uploads.getpop.org/wp-content/uploads/2018/12/loading-data-at-intervals-relational.jpg)

Switching domins is accomplished through function `getDomainSwitchingSubmodules`. It must return an array, in which each key is the property, or "data-field", containing the ID of the object to switch to, and its value is another array, in which the key is the [Dataloader](#dataloader) to use to load this object, and its values are the modules to use:

```php
function getDomainSwitchingSubmodules($module) 
{
  $ret = parent::getDomainSwitchingSubmodules($module);

  switch ($module[1]) {
    case self::MODULE_AUTHORARTICLES:
    
      $ret['author'] = [
        GD_DATALOADER_USERLIST => [
          MODULE_AUTHORNAME,
        ]
      ];
      $ret['comments'] = [
        GD_DATALOADER_COMMENTLIST => [
          MODULE_COMMENTLAYOUT,
        ]
      ];
      break;
  }

  return $ret;
}
```

> Note: Similar to `getModules`, this method also loads modules into the component hierarchy, hence it cannot receive parameter `$props`.

Alternatively, instead of explicitly defining the name of the dataloader, we can also select the default dataloader defined for that field through constant `POP_CONSTANT_SUBCOMPONENTDATALOADER_DEFAULTFROMFIELD`, which are defined through the [FieldValueResolver](#FieldValueResolver). In the example below, the default dataloaders for fields `"author"` and `"comments"` will be automatically selected:

```php
function getDomainSwitchingSubmodules($module) 
{
  $ret = parent::getDomainSwitchingSubmodules($module);

  switch ($module[1]) {
    case self::MODULE_AUTHORARTICLES:
    
      $ret['author'] = [
        POP_CONSTANT_SUBCOMPONENTDATALOADER_DEFAULTFROMFIELD => [
          [SomeModuleProcessor::class, SomeModuleProcessor::MODULE_AUTHORNAME],
        ]
      ];
      $ret['comments'] = [
        POP_CONSTANT_SUBCOMPONENTDATALOADER_DEFAULTFROMFIELD => [
          [SomeModuleProcessor::class, SomeModuleProcessor::MODULE_COMMENTLAYOUT],
        ]
      ];
      break;
  }

  return $ret;
}
```

## Data-Loading Objects

The following objects below are involved in fetching data from the database, filtering it, processing it and describing the new state of the application.

### Dataloader

The dataloader object is in charge of fetching database data. It knows what type of data it must fetch (posts, users, comments, etc) and how to do it. All dataloaders inherit from class `Dataloader`. Given an array of IDs, it must fetch the corresponding objects from the database, through function `executeGetData`:

```php
function executeGetData($ids) {
  
  $objects = array();

  // Fetch all objects with IDs $ids
  // ...
  
  return $objects;
}
```

For instance, a dataloader fetching posts will implement the function like this:

```php
function executeGetData($ids) {
  
  $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
  $query = array(
    'include' => $ids,
  );
  return $cmsapi->getPosts($query);
}
```

The dataloader must also implement the following functions:

- `getFieldprocessor`: return the name of the [FieldValueResolver](#fieldprocessor) that will handle the data-fields for all objects returned by the dataloader
- `getDatabaseKey`: return the object type under which objects returned by the dataloader will be stored under `databases` in the JSON response
- `getDataquery`: return the name of the [DataQuery](#dataquery) object that will handle the objects returned by the dataloader

For instance, a [dataloader fetching posts](https://github.com/leoloso/PoP/blob/master/pop-cmsmodel/library/dataload/dataloaders/dataloader-post-base.php) will implement these functions like this:

```php
function getDatabaseKey() 
{
  return GD_DATABASE_KEY_POSTS;
}  

function getFieldprocessor() 
{
  return GD_DATALOAD_FIELDPROCESSOR_POSTS;
}

function getDataquery() 
{
  return GD_DATAQUERY_POST;
}
```

In addition, most likely dataloader will also be in charge of obtaining the `$ids` to fetch from the databse. In this case, it must inherit from class `QueryDataDataloader`, and implement function `getDbobjectIds`:

```php
function getDbobjectIds($data_properties) {
  
  $ids = array();

  // Find the IDs of the objects to be fetched
  // ...
  
  return $ids;
}
```

For instance, a dataloader fetching the single post will simply return the object ID of the post in the [Context Vars](#context-vars):

```php
function getDbobjectIds($data_properties) {
  
  // Simply return the global $post ID. 
  $vars = \PoP\ComponentModel\Engine_Vars::getVars();
  return array($vars['global-state']['queried-object-id']);
}
```

Dataloaders fetching lists of results (eg: a list of posts, a list of users, etc) will need to execute a query and filter the results. This logic has been implemented in trait `Dataloader_ListTrait`, which requires to implement functions `getQuery` to generate the query from the `$query_args` provided through [Data Properties](#data-properties), and `executeQueryIds` to, given the generated `$query`, return the list of object IDs:

```php
function getQuery($query_args) 
{
  $query = array();

  // Add all the conditions in $query, taking values from $query_args
  // ...

  return $query;
}

function executeQueryIds($query) {
    
  $ids = array();

  // Find the IDs of the objects to be fetched
  // ...
  
  return $ids;
}
```

For instance, a dataloader fetching a list of posts will be implemented like this:

```php
function getQuery($query_args) {
    
  return $query_args;
}

function executeQueryIds($query) {
    
  $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
  return $cmsapi->getPosts($query, ['return-type' => POP_RETURNTYPE_IDS]);
}  
```

### FieldValueResolver

The FieldValueResolver is the object resolving "data-fields" to their corresponding value. It must inherit from class `AbstractFieldValueResolver`, and implement function `getValue`, which receives two parameters, `$resultitem` which is the database object, and `$field` which is the data-field to resolve, and must return the value for that property applied to the database object. 

> Note: the names of fields cannot include the following special characters: "," (`POP_CONSTANT_PARAMVALUE_SEPARATOR`), "." (`POP_CONSTANT_DOTSYNTAX_DOT`) or "|" (`POP_CONSTANT_PARAMFIELD_SEPARATOR`)

For instance, a FieldValueResolver for posts looks like this:

```php
class FieldValueResolver_Posts extends \PoP\Engine\AbstractFieldValueResolver {

  function getValue($resultitem, $field) {
  
    // First Check if there's a FieldValueResolverExtension to implement this field
    $hook_value = $this->getHookValue(GD_DATALOAD_FIELDPROCESSOR_POSTS, $resultitem, $field);
    if (!\PoP\ComponentModel\GeneralUtils::isError($hook_value)) {
      return $hook_value;
    }    

    $cmsresolver = \PoP\CMS\ObjectPropertyResolver_Factory::getInstance();
    $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
    $post = $resultitem;
    switch ($field) 
    {
      case 'tags' :
        $value = $cmsapi->getPostTags($this->getId($post), array('fields' => 'ids'));
        break;

      case 'title' :
        $value = \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters('the_title', $cmsresolver->getPostTitle($post), $this->getId($post));
        break;
      
      case 'content' :
        $value = $cmsresolver->getPostContent($post);
        $value = \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters('pop_content_pre', $value, $this->getId($post));
        $value = \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters('the_content', $value);
        $value = \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters('pop_content', $value, $this->getId($post));
        break;
    
      case 'url' :

        $value = $cmsapi->getPermalink($this->getId($post));
        break;

      case 'excerpt' :
        $value = $cmsapi->getTheExcerpt($this->getId($post));
        break;

      case 'comments' :
        $query = array(
          'status' => 'approve',
          'type' => 'comment', 
          'post_id' => $this->getId($post),
          'order' =>  'ASC',
          'orderby' => 'comment_date_gmt',
        );
        $comments = $cmsapi->getComments($query);
        $value = array();
        foreach ($comments as $comment) {
          $value[] = $cmsresolver->getCommentId($comment);
        }
        break;
  
      case 'author' :
        $value = $cmsresolver->getPostAuthor($post);
        break;  
      
      default:
        $value = parent::getValue($resultitem, $field);
        break;
    }

    return $value;
  }
}
```

The FieldValueResolver also allows to select the default dataloader to process a specific field through function `getFieldDefaultDataloader`. This feature is required for [switching domain](#Switching-domain-to-a-relational-object) through function `getDomainSwitchingSubmodules` and deciding to not explicitly indicate the dataloader to use to load relationships, but use the default one for that field instead. For instance, for the fieldprocessor for posts, it is implemented like this:

```php
function getFieldDefaultDataloader($field) 
{
  switch ($field) 
  {
    case 'tags' :
      return GD_DATALOADER_TAGLIST;

    case 'comments' :
      return GD_DATALOADER_COMMENTLIST;

    case 'author' :
      return GD_DATALOADER_CONVERTIBLEUSERLIST;																													
  }

  return parent::getFieldDefaultDataloader($field);
}
```

#### FieldValueResolverExtension

A FieldValueResolverExtension is an object that allows to resolve data-fields for specific FieldValueResolvers, either to override their value or to extend them. For instance, it can be implemented at the application level, resolving those application-specific data fields. It must inherit from class `FieldValueResolver_HookBase` and implement function `getValue`, which receives three parameters, `$resultitem` which is the database object, `$field` which is the data-field to resolve, and `$fieldprocessor` which is the FieldValueResolver object hooked into, and must return the value for that property applied to the database object. 

For instance, a FieldValueResolverExtension for posts might add a custom "disclaimer" message, and it looks like this:

```php
class FieldValueResolver_Posts_Hook extends \PoP\Engine\FieldValueResolver_HookBase {

  function getClassesToAttachTo() {
    
    return array(
      [FieldValueResolver::class, FieldValueResolver::FIELDPROCESSOR_POSTS],
    );
  }

  function getValue($resultitem, $field, $fieldprocessor) 
  {
    $post = $resultitem;
    switch ($field) 
    {
      case 'disclaimer':
        return \PoP\Engine\MetaManager::getPostMeta($fieldprocessor->getId($post), "disclaimer", true);
    }

    return parent::getValue($resultitem, $field, $fieldprocessor);
  }  
}
```

### Filtering Data

By implementing the interface `DataloadQueryArgsFilter` modules can also filter the data fetched by the ancestor dataloading module. For that, they must implement functions `filterDataloadQueryArgs`, to filter the query for some property, and `getValue`, to provide the corresponding value. For instance, a module that performs a search of content looks like this (notice that since it extends from `TextFormInputsBase`, its `getValue` function is already implemented by class `FormInputsBase`):

```php
class TextFilterInputs extends TextFormInputsBase implements \PoP\ComponentModel\DataloadQueryArgsFilter
{
  public function filterDataloadQueryArgs(array &$query, $module, $value)
  {
    switch ($module[1]) 
    {
      case self::MODULE_FILTERINPUT_SEARCH:
        $query['search'] = $value;
        break;
    }
  }
}
```

### QueryHandler

The QueryHandler is an object that synchronizes the state of the query between client and server. It must inherit from class `QueryHandlerBase` and implement the following functions:

Before fetching data from the database, function `prepareQueryArgs` populates the `$query_args` object used passed to the dataloader to fetch data. It can get values from the request (eg: set through the application in the client) or define default values.

After fecthing data from the database, functions `getQueryState`, `getQueryParams` and `getQueryResult`, all of them receiving parameters `$data_properties, $checkpoint_validation, $executed, $dbobjectids`, send information about the executed query back to the client: state values (eg: are there more results?), parameter values (eg: how many results to bring each time) and result values (eg: was execution successful?) correspondingly.

### DataQuery

When loading data, it is possible to mark data-fields as noncacheable to make sure that their value is always fresh from the database (eg: a post's "Recommend" count), and modules as lazy to load them on a subsequent request (eg: a post's comments). A DataQuery is an object which allows to define these values. It must inherit from class `DataQueryBase`. 

In plugin `pop-cmsmodel` there are already basic implementations of DataQueries for post, user, comment and tag, on which can be injected the data-fields to mark as noncacheable and modules to mark as lazy through a corresponding [DataQueryHook](#dataqueryhook) object:

- `DataQuery_Post`
- `DataQuery_User`
- `DataQuery_Comment`
- `DataQuery_Tag`

#### DataQueryHook

The DataQueryHooks injects data-fields to mark as noncacheable and modules to mark as lazy on a DataQuery object. It must inherit from class `DataQuery_HookBase`. 

## Data-Posting and Operations

In addition to loading data, "dataloading" modules can also post data, or execute any operation supported by the underlying CMS (log in/out the user, send emails, logging, etc).

To achieve this, the ModuleProcessor must define the [ActionExecuter](#actionexecuter) object for the module through function `getActionExecuterClass`:

```php
function getActionExecuterClass($module) {
  
  switch ($module[1]) {
    case self::MODULE_SOMENAME:
  
      return SomeActionExecuter::class;
  }

  return parent::getActionExecuterClass($module);
}
```

### ActionExecuter

The ActionExecuter is an object to execute actions or operations. It must inherit from class `AbstractActionExecuter`, and implement function `execute`:

```php
function execute(&$data_properties) {
  
  // Execute some operation and return the results
  // ...
  return $results;
}
```

For instance, an ActionExecuter to log the user out will look like this:

```php
class ActionExecuter_Logout extends \PoP\Engine\AbstractActionExecuter {

  function execute(&$data_properties) 
  {
    if ('POST' == $_SERVER['REQUEST_METHOD']) { 

      // If the user is not logged in, then return the error
      $vars = \PoP\ComponentModel\Engine_Vars::getVars();
      if (!$vars['global-state']['is-user-logged-in']) 
      {
        $error = __('You are not logged in.');
      
        // Return error string
        return array(
          GD_DATALOAD_QUERYHANDLERRESPONSE_ERRORSTRINGS => array($error)
        );
      }

      $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
      $cmsapi->logout();

      return array(
        GD_DATALOAD_QUERYHANDLERRESPONSE_SUCCESS => true
      );
    }

    return parent::execute($data_properties);
  }
}
```

#### Storing and reusing the results from an execution

The results obtained in function `execute` can be stored for other objects (ModuleProcessors, ActionExecuters) to use and base their logic upon them. For instance, a module is able to load data or not depending on the success or not of an execution.

Storing and accessing the execution results is done through function `setResult` and `getResult` from the `ActionExecution_Manager` object. For instance, an ActionExecuter to create a comment will store the new comment ID:

```php
function execute(&$data_properties) 
{
  if ('POST' == $_SERVER['REQUEST_METHOD']) 
  {
    // Function getFormData obtains the filled-in values in the form
    $form_data = $this->getFormData($data_properties);

    $errors = array();
    if (empty($form_data['post_id'])) {
      $errors[] = __('We don\'t know what post the comment is for.');
    }
    if (empty($form_data['comment'])) {
      $errors[] = __('The comment is empty.');
    }    
    if ($errors) 
    {
      return array(
        GD_DATALOAD_QUERYHANDLERRESPONSE_ERRORSTRINGS => $errors
      );
    }

    $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
    $comment_id = $cmsapi->insertComment($form_data);

    // Save the result
    $actionexecution_manager = \PoP\Engine\ActionExecution_Manager_Factory::getInstance();
    $actionexecution_manager->setResult($this->get_name(), $comment_id);

    // No errors => success
    return array(
      GD_DATALOAD_QUERYHANDLERRESPONSE_SUCCESS => true
    );      
  }

  return parent::execute($data_properties);
}
```

A ModuleProcessor can modify what data it will fetch from the database through function `prepareDataPropertiesAfterActionexecution`, which is invoked after executing the module's corresponding ActionExecuter. For instance, after creating a comment, we can load it immediately or, if the creation was not successful, state to skip loading any database object:

```php
function prepareDataPropertiesAfterActionexecution($module, &$props, &$data_properties) {
    
  parent::prepareDataPropertiesAfterActionexecution($module, $props, $data_properties);

  switch ($module[1]) {
    case self::MODULE_ADDCOMMENT:

      $actionexecution_manager = \PoP\Engine\ActionExecution_Manager_Factory::getInstance();
      if ($comment_id = $actionexecution_manager->getResult(GD_DATALOAD_ACTIONEXECUTER_ADDCOMMENT)) 
      {
        $data_properties[GD_DATALOAD_QUERYARGS]['include'] = array($comment_id);
      }
      else {

        $data_properties[GD_DATALOAD_SKIPDATALOAD] = true;
      }
      break;
  }
}
```

## Custom-Querying API

Similar to GraphQL, PoP also provides an API which can be queried from the client, which retrieves exactly the data fields which are requested and nothing more. The custom-querying API is accessed by appending `/api` to the URL and adding parameter `fields` with the list of fields to retrieve from the queried resources. 

For instance, the following link fetches a collection of posts. By adding `fields=title,content,datetime` we retrieve only these items:

- Original: https://nextapi.getpop.org/posts/?output=json
- Custom-querying: https://nextapi.getpop.org/posts/api/?fields=id|title|content|datetime

The links above demonstrate fetching data only for the queried resources. What about their relationships? For instance, let’s say that we want to retrieve a list of posts with fields "title" and "content", each post’s comments with fields "content" and "date", and the author of each comment with fields "name" and "url". To achieve this in GraphQL we would implement the following query:

```graph
query {
  post {
    title
    content
    comments {
      content
      date
      author {
        name
        url
      }
    }
  }
}
```

PoP, instead, uses a query translated into its corresponding “dot syntax” expression, which can then be supplied through parameter fields. Querying on a “post” resource, this value is:

```properties
fields=title,content,comments.content,comments.date,comments.author.name,comments.author.url
```

Or it can be simplified, using | to group all fields applied to the same resource:

```properties
fields=title|content,comments.content|date,comments.author.name|url
```

When executing this query on a [single post](https://nextapi.getpop.org/posts/a-lovely-tango/api/?fields=id|title|content,comments.content|date,comments.author.name|url) we obtain exactly the required data for all involved resources:

```javascript
{
  "datasetmodulesettings": {
    "dataload-dataquery-singlepost-fields": {
      "dbkeys": {
        "id": "posts",
        "comments": "comments",
        "comments.author": "users"
      }
    }
  },
  "datasetmoduledata": {
    "dataload-dataquery-singlepost-fields": {
      "dbobjectids": [
        23691
      ]
    }
  },
  "databases": {
    "posts": {
      "23691": {
        "id": 23691,
        "title": "A lovely tango",
        "content": "<div class=\"responsiveembed-container\"><iframe width=\"480\" height=\"270\" src=\"https:\\/\\/www.youtube.com\\/embed\\/sxm3Xyutc1s?feature=oembed\" frameborder=\"0\" allowfullscreen><\\/iframe><\\/div>\n",
        "comments": [
          "25094",
          "25164"
        ]
      }
    },
    "comments": {
      "25094": {
        "id": "25094",
        "content": "<p><a class=\"hashtagger-tag\" href=\"https:\\/\\/newapi.getpop.org\\/tags\\/videos\\/\">#videos<\\/a>\\u00a0<a class=\"hashtagger-tag\" href=\"https:\\/\\/newapi.getpop.org\\/tags\\/tango\\/\">#tango<\\/a><\\/p>\n",
        "date": "4 Aug 2016",
        "author": "851"
      },
      "25164": {
        "id": "25164",
        "content": "<p>fjlasdjf;dlsfjdfsj<\\/p>\n",
        "date": "19 Jun 2017",
        "author": "1924"
      }
    },
    "users": {
      "851": {
        "id": 851,
        "name": "Leonardo Losoviz",
        "url": "https:\\/\\/newapi.getpop.org\\/u\\/leo\\/"
      },
      "1924": {
        "id": 1924,
        "name": "leo2",
        "url": "https:\\/\\/newapi.getpop.org\\/u\\/leo2\\/"
      }
    }
  }
}
```

Hence, PoP can query resources in a REST fashion, and specify schema-based queries in a GraphQL fashion, and we will obtain exactly what is required, without over or underfetching data, and normalizing data in the database so that no data is duplicated. The query can include any number of nested relationships, and these are resolved with linear complexity time: worst case of O(n+m), where n is the number of nodes that switch domain (in this case 2: `comments` and `comments.author`) and m is the number of retrieved results (in this case 5: 1 post + 2 comments + 2 users), and average case of O(n).

## Validations

### Checkpoints

A "checkpoint" is a condition that must be satisfied when performing an operation-access validation. These validations do not include content validations, such as checking if the user has filled-in the form correctly; instead, they are used to find out if the user can access a certain page or functionality, such as checking if the user is logged in to access the user account page, checking if the user IP has been whitelisted to execute special scripts, etc.

Modules can specify their checkpoints through 2 functions in the ModuleProcessor:

- `getDataaccessCheckpoints`: Define the checkpoints to access data for the module: both load data or execute the module's actionexecuter
- `getActionexecutionCheckpoints`: Define the checkpoints to execute the module's actionexecuter

The reason why these 2 functions are split like is, is to allow a page perform the validation only when posting data. Then, an "Add Post" page can require no checkpoints when first loaded, which enables to cache it, and only perform the validation (eg: is user logged in?) when executing the POST operation and triggering the actionexecuter.

For instance, a module that needs to validate that the user's IP is whitelisted can do it like this:

```php
function getDataaccessCheckpoints($module, &$props) 
{
  switch ($module[1]) {
    case self::MODULE_SOMEMODULE:
    
      return [CHECKPOINT_WHITELISTEDIP];
  }
  
  return parent::getDataaccessCheckpoints($module, $props);
}
```

Pages can also be assigned checkpoints through their [SettingsProcessor](#settingsprocessor). Whenever a module is directly associated with a page (eg: module `MODULE_MYPOSTS_SCROLL` is directly associated to `POP_PAGE_MYPOSTS`) then it is assigned the checkpoints associated with that page. Associating a module with a page is done through function `getRelevantPage` from the ModuleProcessor, like this:

```php
function getRelevantPage($module, &$props) {
    
  switch ($module[1]) {
    case self::MODULE_MYPOSTS_SCROLL:
    case self::MODULE_MYPOSTS_CAROUSEL:
    case self::MODULE_MYPOSTS_TABLE:

      return POP_PAGE_MYPOSTS;
  }

  return parent::getRelevantPage($module, $props);
}
```

A checkpoint is resolved through a [CheckpointProcessor](#checkpointprocessor).

#### CheckpointProcessor

A CheckpointProcessor is an object inheriting from class `AbstractCheckpointProcessor`, which handles checkpoints, resolving if a checkpoint is satisfied or not through function `process`. When a checkpoint is not satisfied, it must thrown an error. Otherwise, the base class will eventually return `true`, signalling that the validation is satisfied.

For instance, to validate if the user IP is whitelisted can be implemented like this:

```php

class CheckpointProcessor extends \PoP\Engine\AbstractCheckpointProcessor {

  const CHECKPOINT_WHITELISTEDIP = 'checkpoint-whitelistedip';

  function getCheckpointsToProcess() 
  {
    return array(
      [self::class, self::CHECKPOINT_WHITELISTEDIP],
    );
  }

  function process($checkpoint) 
  {
    switch ($checkpoint) 
    {
      case self::CHECKPOINT_WHITELISTEDIP:

        // Validate the user's IP
        $ip = get_client_ip();
        if (!$ip) {
          
          return new \PoP\ComponentModel\Error('ipempty');
        }

        $whitelisted_ips = array(...);
        if (!in_array($ip, $whitelisted_ips)) {
          
          return new \PoP\ComponentModel\Error('ipincorrect');
        }
        break;
    }
  
    return parent::process($checkpoint, $module);
  }
}
```

## Extending and Formatting Data

### Extra URIs

Will be added soon...

### Multidomain

By default, a module will fetch its data from the domain where the application is hosted. To change this to a different domain(s) or subdomain(s) is done by setting prop `"dataload-multidomain-sources"` on the module:

```php
function initModelProps($module, &$props) {
    
  switch ($module[1]) {
    case self::MODULE_SOMENAME:

      $this->setProp(
        $module, 
        $props, 
        'dataload-multidomain-sources', 
        'https://anotherdomain.com'
      );
      break;
  }

  parent::initModelProps($module, $props);
}
```

We can also pass an array of domains, in which case the module will fetch its data from all of them:

```php
function initModelProps($module, &$props) {
    
  switch ($module[1]) {
    case self::MODULE_SOMENAME:

      $this->setProp(
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

  parent::initModelProps($module, $props);
}
```

When fetching data from several sources, each source will keep its own state in the [QueryHandler](#queryhandler). Then, it is able to query different amounts of data from different domains (eg: 3 results from domain1.com and 6 results from domain2.com), and stop querying from a particular domain when it has no more results.

Because the external site may have different components installed, it is not guaranteed that fetching data from the external site by simply adding `?output=json` will bring the data required by the origin site. To solve this issue, when querying data from an external site, PoP will use the [custom-querying API](#Custom-Querying-API) to fetch exactly the required data fields (this works for fetching database data, not configuration). If we have control on the external site and we can guarantee that both sites have the same components installed, then we can define constant `EXTERNAL_SITES_RUN_SAME_SOFTWARE` as true, which will allow to fetch database and configuration data through the regular `?output=json` request.


### DataStructureFormatter

Will be added soon...

## Extending and Filtering Modules

### ModuleDecoratorProcessor

Will be added soon...

### ModuleFilter

Will be added soon...

## Context

### Context Vars

It is a global variable, hosted under `PoP_ModuleManager_Vars::$vars`, accessed through `PoP_ModuleManager_Vars::getVars`, and naturally referred as `$vars`, which holds important information needed to process the webpage. Properties in `$vars` are those which are accessed widely throughout the application, and which, upon changing their value, alter the component hierarchy.

**1. Properties which are accessed widely throughout the application**

`$vars` acts as a single, central repository of information, where properties can be calculated just once or initialized with default values, and promoting consistency, by providing a unique place from where to fetch a certain value from anywhere in the application.

For instance, property `output`, which is obtained through `$_REQUEST["output"]` and accepts values `"HTML"` or `"JSON"`, is accessed through `$vars['output']`, and is initialized to value `"HTML"` if `$_REQUEST["output"]` is empty.

**2. Properties which, upon changing their value, alter the component hierarchy**

Changing the values of certain properties will alter the component hierarchy. For instance, passing parameter `modulefilter=modulepaths` in the URL will make the component hierarchy only include those modules specified under parameter `modulepaths[]`. Hence, property `modulefilter` must be in `$vars`.

Keeping these properties in `$vars` is needed for the following reasons:

_1. To calculate the `modelInstanceId`:_ the `modelInstanceId` is the unique identifier representing the particular instance of the component hierarchy. This id is calculated by function `ModelInstanceProcessor_Utils::getModelInstanceId()`, which simply calculates a hash of the values of all properties which alter the component hierarchy. Because not all properties in `$vars` alter the component hierarchy, these ones must be defined by implementing hook `"ModelInstanceProcessor:model_instance_components"`.

_2. To determine the entry module_: The component hierarchy's top-most module is called the entry module. Every potential entry module must define a list of conditions, to be evaluated against `$vars`, that need be satisfied to be chosen the entry module (more on this under [PageModuleProcessors](#pagemoduleprocessor)).

_3. To decouple processed page from requested page_: Storing all properties which modify the component hierarchy under `$vars`, making sure that these properties are only accessed through `$vars` all throughout the application, and then modifying these values directly in `$vars`, makes it possible to manipulate the response, for instance adding more data. This way, it is possible to fetch more than one page's content on a single request (for preloading views to cache on the client or other use cases), or send personalized transactional emails to many users on a single request, among other use cases.

#### Setting properties in `$vars`

When first accessed, `$vars` is initialized with certain current request values, such as:

- Hierarchy (home, single, page, author, etc)
- Output (HTML, JSON, etc)
- Module filter, if any
- Mangled output?
- The queried object (the post object in single hierarchy, the user object in author hierarchy, etc)
- Others

Plugins must add their own properties and corresponding values in `$vars` by implementing hook `"\PoP\ComponentModel\Engine_Vars:add_vars"`. `$vars` can be `reset` at any moment and filled with different values, for instance to process a different request.

### PageModuleProcessor

Will be added soon...

### SettingsProcessor

Will be added soon...

<!-- 
Explain `get_checkpoint_configuration`, etc
-->

### User State

Will be added soon...

## Form Input Modules

Will be added soon...

## Client-side Rendering

Will be added soon...

### JavaScript templates through Handlebars

Will be added soon...

### Executing JavaScript functions

Will be added soon...

### Resources

Will be added soon...

### Asset-bundling

Will be added soon...

### Progressive Booting

Will be added soon...

### Links in body

Will be added soon...

### State Management

Will be added soon...

### Data Cache, Configuration Cache and Replication

Will be added soon...

### Reactivity

Will be added soon...

## Server-Side Rendering

Will be added soon...

### Isomorphism

Will be added soon...

### JavaScript templates into PHP through LightnCandy

Will be added soon...

### Rendering a Webpage as a Transactional Email

Will be added soon...

## Caching

Will be added soon...

### Configuration Cache

Will be added soon...

## Coding Peculiarities
<!--
### WordPress Legacy

Being first built as a WordPress website, PoP has taken several concepts from this CMS, such as: 

- The hierarchy model (home, single, author, page, 404 and tag, and category to be added in the future)
- Pages, Posts and Custom Post Types
- Meta (post meta, user meta, comment meta and tag meta)
-->
## Technology Stack
<!--
### CMS-Agnosticism

We are currently attempting to make the existing implementation of PoP be CMS-agnostic, so that it can be used with other PHP-based Content Management Systems (such as Joomla or Drupal) in addition to WordPress. We have already re-architected the codebase to use interfaces to access the CMS-provided functionality, and are migrating all code to use these interfaces.

Making functionality become CMS-agnostic is accomplished by splitting plugins into 2 entities: a generic one containing definitions of interfaces for all the required functionalities, and the implementation of the interfaces for the specific CMS.

For instance, plugin [PoP CMS Model](https://github.com/leoloso/PoP/tree/master/pop-cmsmodel) is a generic implementation, declaring all the functionalities it requires through `interface \PoP\CMSModel\FunctionAPI`. This interface is implemented by plugin [PoP CMS Model for WordPress](https://github.com/leoloso/PoP/tree/master/pop-cmsmodel-wp) through class `\PoP\CMSModel\WP\FunctionAPI`.

Then, all throughout the application, we must access the functionality through the interface and never directly through the CMS library. For instance, function `getPosts`, which is defined in the interface mentioned above, must be invoked like this:

```php
$cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
$query = array(
  // Query parameters here...
);
return $cmsapi->getPosts($query);
```

Currently, PoP's code adopts several concepts from WordPress, namely:

1. Hierarchy
2. Pages, Posts and Custom Post Types
3. Meta

Let's analyze, for each of these items, how they can be made CMS-agnostic.

**1. Hierarchy**

PoP implements the same concept of hierarchies as in WordPress, even though with a subset of its implementations: home, single, author, page, tag and 404 (category will be supported in the future too). 

Deducing the hierarchy from the URL is done through interface `RoutingStateFacade`.

**2. Pages, Posts and Custom Post Types**

PoP is based on the entity model introduced in WordPress, based on the concept of "Pages", "Posts" and "Custom Post Types". Each CMS needs to validate if it supports this entity model, or fall back to a lower entity if not, as follows:

- "Pages" should be supported by every CMS, since, at its most basic, a URL can be treated as a page
- "Posts" should be supported by any blogging platform (such as Joomla or Drupal), and if it were not supported, it can be treated as a page
- "Custom Post Types" can be treated as "Posts" or, if not available, as "Pages"

Hence, PoP can be considered a progressive enhancement, in which WordPress unlocks all the features and capabilities, and other CMSs may have restricted features and capabilities but without breaking.

**3. Meta**

Many functionalities depend on post/user/comment/tag meta data (for instance, Recommending a post, Following a user, and many others). CMSs supporting meta for these entities will have no issues, but those which do not may not be able to access these functionalities, as in the progressive enhancement situation described above.
-->
### Hooks

PoP uses hooks (as pioneered by [WordPress](https://codex.wordpress.org/Plugin_API)) everywhere, through both functions `doAction` and `applyFilters` as defined through interface `HooksAPI`, allowing any piece of code to be overridable by any 3rd party, or be injected extra functionality. For WordPress, the implementation of the interface is trivial. Other systems can rely on packages to implement this functionality (eg: [this one](https://github.com/tormjens/eventy) or [this one](https://github.com/voku/php-hooks)).

### Handlebars

Will be added soon...

### LightnCandy

Will be added soon...

## Extending and installing functionality

### Plugin Dependencies and Initialization Order

Will be added soon...

### Progressive Plugins

Will be added soon...

### Plugin Architecture

Will be added soon...

### Themes

Will be added soon...

## Packaging and Deployment

Will be added soon...

### Custom Tooling

Will be added soon...

### Code Splitting

Will be added soon...

## Enabling Pre-built Application Features

### Progressive-Web App

Will be added soon...

### Single-Page Application

Will be added soon...

### Content CDN

Will be added soon...

### A/B Testing

Will be added soon...

<!-- Notes:

### QueryHandlers (query params, query state, query result)

### Progressive Plugins (Frontend, SSR, CodeSplitting, SPA, PWA, Themes, User State, Content CDN)

### Plugin Architecture (API, Frontend, Processors, Implementations)

### CMS-Agnosticism (pop-cms, pop-cms-wp)

### Custom Tooling (pop-system)

-->