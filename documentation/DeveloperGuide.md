![PoP](https://assets.getpop.org/wp-content/themes/getpop/img/pop-logo-horizontal.png)

# Developer Guide

## Getting Started

PoP is founded on [Object-Oriented Programming](https://en.wikipedia.org/wiki/Object-oriented_programming) (OOP). If you know OOP, then you are all set to go! If not, taking [one](https://www.tutorialspoint.com/php/php_object_oriented.htm) [of](https://code.tutsplus.com/tutorials/object-oriented-php-for-beginners--net-12762) [the](https://www.tutorialrepublic.com/php-tutorial/php-classes-and-objects.php) [many](https://www.killerphp.com/tutorials/object-oriented-php/) tutorials on OOP in PHP before starting will greatly help understand all code in this guide.

## Foundations

### Components

PoP is framework for building sites using [components](https://en.wikipedia.org/wiki/Component-based_software_engineering). In PoP, a component is called a "module", so from now on the terms "component" and "module" are used interchangeably. 

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

Module properties (configuration values, what database data to fetch, etc) and descendant modules are not added manually to the associative array. Instead, they are defined through an object called a [ModuleProcessor](#moduleprocessor) on a module by module basis. The PoP engine will traverse all modules in the component hierarchy, starting from the entry module, fetch the properties for each from the corresponding ModuleProcessor, and create the nested associative array with all properties for all modules. A ModuleProcessor for a module called `POP_MODULE_SOMENAME` looks like this:

```php
define ('POP_MODULE_SOMENAME', \PoP\Engine\DefinitionUtils::get_module_definition('somename'));

class CustomModuleProcessor extends \PoP\Engine\ModuleProcessorBase {

  function get_modules_to_process() {
  
    return array(
      POP_MODULE_SOMENAME,
    );
  }

  function get_modules($module) {

    $ret = parent::get_modules($module);

    switch ($module) {
      
      case POP_MODULE_SOMENAME:
        
        $ret[] = POP_MODULE_SOMELAYOUT1;
        $ret[] = POP_MODULE_SOMELAYOUT2;
        break;
    }

    return $ret;
  }

  // Implement the modules properties ...
  function get_immutable_configuration($module, &$props) {

    $ret = parent::get_immutable_configuration($module, $props);

    switch ($module) {
      
      case POP_MODULE_SOMENAME:
        
        $ret['description'] = __('Some description');
        $ret['showmore'] = $this->get_prop($module, $props, 'showmore');
        $ret['class'] = $this->get_prop($module, $props, 'class');
        break;
    }

    return $ret;
  }
  
  function init_model_props($module, &$props) {

    switch ($module) {
        
      case POP_MODULE_SOMENAME:
        
        $this->set_prop($module, $props, 'showmore', false);
        $this->append_prop($module, $props, 'class', 'text-center');
        break;
    }

    parent::init_model_props($module, $props);
  }
  // ...
}

// Initialize
new CustomModuleProcessor();
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
define ('POP_MODULE_SOMENAME', 'somename');
```

The name of the module must not necessarily remain fixed: it can be shortened for producing a smaller output, constantly modified to evade bots, or others. Different strategies can be applied through function [`DefinitionUtils::get_module_definition`](https://github.com/leoloso/PoP/blob/master/pop-engine/server/definition-manager/utils.php#L16):

```php
define ('POP_MODULE_SOMENAME', \PoP\Engine\DefinitionUtils::get_module_definition('somename'));
```

> Note: It is important to always target a module through its constant name and not through its defined string, since the latter one may change. In the case above, we must use `POP_MODULE_SOMENAME` instead of `"somename"`.

All the properties of the modules are implemented through objects called [ModuleProcessor](#moduleprocessor).

### ModuleProcessor

A ModuleProcessor is an object class in which to define all the properties of a module. ModuleProcessors are implemented following the [SOLID](https://scotch.io/bar-talk/s-o-l-i-d-the-first-five-principles-of-object-oriented-design) methodology, establishing an object inheritance scheme to progressively add properties to modules. The base class for all ModuleProcessors is [`ModuleProcessorBase`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/moduleprocessors/pop-moduleprocessor.php):

```php
namespace PoP\Engine;
abstract class ModuleProcessorBase {

  // ...
}
```

In practice, because a module is implemented through a ModuleProcessor object, describing a module equals to describing how the ModuleProcessor implements all functions to define the properties of the module.

Every ModuleProcessor can handle more than 1 module: Because different modules will naturally share many properties, then having a single ModuleProcessor implement many modules is more legible and reduces the amount of code required compared to having 1 ModuleProcessor per module. Which modules are handled by the ModuleProcessor is defined through function `get_modules_to_process`:

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

Once the ModuleProcessor class is intantiated, all of its defined modules become available to be added to the component hirarchy.

```php
// Initialize
new CustomModuleProcessor();
```

> Note: if a module is stray, i.e. no ModuleProcessor handles it, and it is added to the component hierarchy, the engine will throw an exception and terminate the execution of the request. This error happens on runtime, not on compilation time.

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

Because a ModuleProcessor can handle several modules, then each of its functions will receive a parameter `$module` indicating which is the module being processed. Please notice how, inside the function, we can conveniently use `switch` statements to operate accordingly (modules with shared properties can easily share the logic) and, according to [SOLID](https://scotch.io/bar-talk/s-o-l-i-d-the-first-five-principles-of-object-oriented-design), we first obtain the results of the parent class and then the ModuleProcessor adds its own properties:

```php
class CustomModuleProcessor extends \PoP\Engine\ModuleProcessorBase {

  function some_function($module) {

    // First obtain the value from the parent class
    $ret = parent::some_function($module);

    // Add properties to the module
    switch ($module) {

      case POP_MODULE_SOMENAME1:
        
        // Do something with $ret
        // ...
        break;

      // These modules share the same properties
      case POP_MODULE_SOMENAME2:
      case POP_MODULE_SOMENAME3:
        
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
class CustomModuleProcessor extends \PoP\Engine\ModuleProcessorBase {

  function some_function($module, &$atts) {

    $ret = parent::some_function($module, &$atts);

    // ...

    return $ret;
  }
}
```

### Composition

Modules are composed of other modules through function [`get_modules`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/moduleprocessors/pop-moduleprocessor.php#L22):

```php
class CustomModuleProcessor extends \PoP\Engine\ModuleProcessorBase {

  function get_modules($module) {

    $ret = parent::get_modules($module);

    switch ($module) {

      case POP_MODULE_SOMENAME1:
        
        $ret[] = POP_MODULE_SOMENAME2;
        break;

      case POP_MODULE_SOMENAME2:
      case POP_MODULE_SOMENAME3:
        
        $ret[] = POP_MODULE_LAYOUT1;
        $ret[] = POP_MODULE_LAYOUT2;
        $ret[] = POP_MODULE_LAYOUT3;
        break;
    }

    return $ret;
  }
}
```

> Note: the component hierarchy is created by calling `get_modules` on the entry-module and then repeating the process, iteratively, for its descendant modules.

Abstract ModuleProcessors can define what descendant modules will be required through placeholder functions, to be implemented by an inheriting ModuleProcessor:

```php
abstract class PostLayoutModuleProcessorBase extends \PoP\Engine\ModuleProcessorBase {

  function get_modules($module) {
  
    $ret = parent::get_modules($module);

    if ($thumbnail_module = $this->get_thumbnail_module($module)) {

      $ret[] = $thumbnail_module;
    }
    if ($content_module = $this->get_content_module($module)) {

      $ret[] = $content_module;
    }
    if ($aftercontent_modules = $this->get_aftercontent_modules($module)) {

      $ret = array_merge(
        $ret,
        $aftercontent_modules
      );
    }

    return $ret;
  }

  protected function get_content_module($module) {

    // Must implement
    return null;
  }
  protected function get_thumbnail_module($module) {

    // Default value
    return POP_MODULE_LAYOUT_THUMBNAILSMALL;
  }
  protected function get_aftercontent_modules($module) {

    return array();
  }
}

class PostLayoutModuleProcessor extends PostLayoutModuleProcessorBase {

  protected function get_content_module($module) {

    switch ($module) {

      case POP_MODULE_SOMENAME1:
        
        return POP_MODULE_LAYOUT_POSTCONTENT;

      case POP_MODULE_SOMENAME2:
      case POP_MODULE_SOMENAME3:
        
        return POP_MODULE_LAYOUT_POSTEXCERPT;
    }

    return parent::get_content_module($module);
  }
  protected function get_thumbnail_module($module) {

    switch ($module) {

      case POP_MODULE_SOMENAME1:
        
        return POP_MODULE_LAYOUT_THUMBNAILBIG;

      case POP_MODULE_SOMENAME3:
        
        return POP_MODULE_LAYOUT_THUMBNAILMEDIUM;
    }

    return parent::get_thumbnail_module($module);
  }
  protected function get_aftercontent_modules($module) {

    $ret = parent::get_aftercontent_modules($module);

    switch ($module) {

      case POP_MODULE_SOMENAME2:
        
        $ret[] = POP_MODULE_LAYOUT_POSTLIKES;
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

Setting props is done through functions [`init_model_props($module, &$props)`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/moduleprocessors/pop-moduleprocessor.php#L160) and [`init_request_props($module, &$props)`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/moduleprocessors/pop-moduleprocessor.php#L201). A prop must be implemented in either function, but not on both of them. `init_request_props` is used for defining props that depend directly on the requested URL, such as adding a classname `post-{id}` to prop `"class"`, where `{id}` is the ID of the requested post on that URL. `init_model_props` is used for everything else. 

Setting props is done at the very beginning: Immediately after obtaining the component hierarchy, PoP Engine will invoke these 2 functions **before anything else** (i.e. before getting the configuration, fetching database data, etc). Hence, with the exception of the functions to create the component hierarchy (i.e. `get_modules` and those inner functions invoked by `get_modules`), every function in the `ModuleProcessor` can receive `$props`. 

`init_model_props` and `init_request_props` store the props under parameter `$props`, hence it is passed by reference. In all other functions, `$props` may also be passed by reference, but only for performance issues, to not duplicate the object in memory.

Inside these 2 functions, we get to set the props through the following 3 functions:

- [`function set_prop($module_or_modulepath, &$props, $field, $value, $starting_from_modulepath = array())`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/moduleprocessors/pop-moduleprocessor.php#L396)
- [`function append_prop($module_or_modulepath, &$props, $field, $value, $starting_from_modulepath = array())`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/moduleprocessors/pop-moduleprocessor.php#L404)
- [`function merge_prop($module_or_modulepath, &$props, $field, $value, $starting_from_modulepath = array())`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/moduleprocessors/pop-moduleprocessor.php#L412)

These 3 functions are similar to each other, with the following differences: 

`append_prop` is used to append a value to an existing prop, hence it is a cumulative property. It is often used for adding classnames. For instance, having modules "module1" and "module2" both execute `append_prop` on "module3" for property `"class"` with values `"big"` and `"center"` respectively, will make the `"class"` property of "module3" be set as `"big center"`.

`merge_prop` is similar, but concerning arrays. It is often used for adding params to be printed on the element in the DOM. For instance, having modules "module1" and "module2" both execute `merge_prop` on "module3" for property `"params"` with values `["data-target" => "#main"]` and `["data-mode" => "static"]` respectively, will make the `"params"` property of "module3" be set as `["data-target" => "#main", "data-mode" => "static"]`.

`set_prop` is not cumulative, but accepts only 1 value: that one that is set the first. Hence, higher-level modules have priority for setting a property value over lower-level ones along the component hierarchy. For instance, having modules "module1" and "module2" both execute `set_prop` on "module3" for property `"title"` with values `"First title"` and `"Second title"` respectively, will make the `"title"` property of "module3" be set as `"First title"`.

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

Accessing the value of the prop is done through [`function get_prop($module, &$props, $field, $starting_from_modulepath = array())`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/moduleprocessors/pop-moduleprocessor.php#L420). The signature of the function is similar to the ones above, however without parameter `$value`.

Let's see an example: a component for rendering maps has 2 orientations: `"horizontal"` and `"vertical"`. It is composed by modules `"map" => "map-inner"`, and both these modules need this property. Module `"map"` will set the value by default to `"vertical"`, obtain the value for this prop just in case an ancestor module had already set the prop, and then set this value on module `"map-inner"`. Function below is implemented for module `"map"`:

```php
function init_model_props($module, &$props) {

  switch ($module) {

    case POP_MODULE_MAP:

      // Module "map" is setting the default value
      $this->set_prop($module, $props, 'orientation', 'vertical');

      // Obtain the value from the prop
      $orientation = $this->get_prop($module, $props, 'orientation');

      // Set the value on "map-inner"
      $this->set_prop([POP_MODULE_MAPINNER], $props, 'orientation', $orientation);
      break;
  }

  return parent::init_model_props($module, $props);
}
```

By default, module map will have prop `"orientation"` set with value `"vertical"`. However, parent module `"map-wrapper"` can set this prop beforehand to `"horizontal"`:

```php
function init_model_props($module, &$props) {

  switch ($module) {

    case POP_MODULE_MAPWRAPPER:

      $this->set_prop([POP_MODULE_MAP], $props, 'orientation', 'horizontal');      
      break;
  }

  return parent::init_model_props($module, $props);
}
```

### Configuration

Configuration values are added under functions:

- [`function get_immutable_configuration($module, &$props)`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/moduleprocessors/pop-moduleprocessor.php#L459)
- [`function get_mutableonmodel_configuration($module, &$props)`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/moduleprocessors/pop-moduleprocessor.php#L529)
- [`function get_mutableonrequest_configuration($module, &$props)`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/moduleprocessors/pop-moduleprocessor.php#L554)

For instance:

```php
// Implement the modules properties ...
function get_immutable_configuration($module, &$props) {

  $ret = parent::get_immutable_configuration($module, $props);

  switch ($module) {
    
    case POP_MODULE_SOMENAME:
      
      $ret['description'] = __('Some description');
      $ret['classes']['description'] = 'jumbotron';
      break;
  }

  return $ret;
}
```

Please notice that the configuration receives the `$props` parameter, hence it can print configuration values set through props. `immutable` and `mutable on model` configuration values are initialized through `init_model_props`, and `mutable on request` ones are initialized through `init_request_props`:

```php
// Implement the modules properties ...
function get_immutable_configuration($module, &$props) {

  $ret = parent::get_immutable_configuration($module, $props);

  switch ($module) {
    
    case POP_MODULE_SOMENAME:
      
      $ret['showmore'] = $this->get_prop($module, $props, 'showmore');
      $ret['class'] = $this->get_prop($module, $props, 'class');
      break;
  }

  return $ret;
}

function init_model_props($module, &$props) {

  switch ($module) {
      
    case POP_MODULE_SOMENAME:
      
      $this->set_prop($module, $props, 'showmore', false);
      $this->append_prop($module, $props, 'class', 'text-center');
      break;
  }

  parent::init_model_props($module, $props);
}
```

### Data-Loading

Along the component hierarchy, certain modules will define what objects from the database are required, and their descendants will indicate what properties the database object must have. Consider the image below, in which a module `"singlepost"` defines to what DB object to load, and its descendant modules `"post-title"` and `"post-content"` indicate that the object must have properties `"title"` and `"content"` loaded:

![While some modules load the database object, others load properties](https://uploads.getpop.org/wp-content/uploads/2018/12/loading-data-at-intervals.jpg)

#### Dataloading Modules

Those modules indicating what DB objects must be loaded are called "dataloading" modules. To do this, dataloading modules must define the functions and properties below.

##### DataSource

Indicate if the results are `immutable` (eg: results which never change and are cacheable) or `mutable on request`, through function [`get_datasource`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/moduleprocessors/pop-moduleprocessor.php#L563). By default results are set as `mutable on request` (through constant `POP_DATALOAD_DATASOURCE_MUTABLEONREQUEST`), so only when results are `immutable` this function must be implemented:

```php
function get_datasource($module, &$props) {

  switch ($module) {
      
    case POP_MODULE_WHOWEARE:

      return POP_DATALOAD_DATASOURCE_IMMUTABLE;
  }

  return parent::get_datasource($module, $props);
}
```

##### Database Object IDs

Define the IDs of the objects to be retrieved from the database, through function [`get_dbobjectids`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/moduleprocessors/pop-moduleprocessor.php#L570). If the module already knows what database objects are required, it can simply return them:

```php
function get_dbobject_ids($module, &$props, $data_properties) {

  switch ($module) {
      
    case POP_MODULE_WHOWEARE:

      return [13, 54, 998];
  }

  return parent::get_dbobject_ids($module, $props, $data_properties);
}
```

However, most likely, the objects are not known in advance, and must be found through a query. In this case, the ModuleProcessor must inherit from class [`QueryDataModuleProcessorBase`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/moduleprocessors/pop-querydatamoduleprocessor.php), which implements [`get_dbobject_ids`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/moduleprocessors/pop-querydatamoduleprocessor.php#L48) transferring the responsibility of finding the database object IDs to function [`get_dbobject_ids`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/dataload/querydata-dataloader.php#L9) from the corresponding [Dataloader](#dataloader).

##### Dataloader

Define what [Dataloader](#dataloader) to use, which is the object in charge of fetching data from the database, through function [`get_dataloader`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/moduleprocessors/pop-moduleprocessor.php#L575):

```php
function get_dataloader($module) {

  switch ($module) {

    case POP_MODULE_AUTHORARTICLES:
    
      return GD_DATALOADER_POSTLIST;
  }
    
  return parent::get_dataloader($module);
}
```

##### Query Args

Customize a query to filter data, which is passed to the Dataloader, through functions [`get_immutable_dataload_query_args`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/moduleprocessors/pop-querydataprocessor-trait.php#L6) and [`get_mutableonrequest_dataload_query_args`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/moduleprocessors/pop-querydataprocessor-trait.php#L10):

```php
protected function get_immutable_dataload_query_args($module, $props) {

  $ret = parent::get_immutable_dataload_query_args($module, $props);
  
  switch ($module) {

    case POP_MODULE_AUTHORARTICLES:

      // 55: id of "Articles" category
      $ret['cat'] = 55;
      break;
  }

  return $ret;
}

protected function get_mutableonrequest_dataload_query_args($module, $props) {

  $ret = parent::get_mutableonrequest_dataload_query_args($module, $props);
  
  switch ($module) {

    case POP_MODULE_AUTHORARTICLES:
    
      // Set the logged-in user id
      $cmsapi = \PoP\CMS\FunctionAPI_Factory::get_instance();
      $ret['author'] = $cmsapi->get_current_user_id();
      break;
  }

  return $ret;
}
```

##### Filter

The fetched data can be filtered through [Filter](#filter) objects, defined through function [`get_filter`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/moduleprocessors/pop-querydataprocessor-trait.php#L18):

```php
function get_filter($module) {

  switch ($module) {

    case POP_MODULE_AUTHORARTICLES:
          
      return GD_FILTER_AUTHORARTICLES;
  }
  
  return parent::get_filter($module);
}
```

##### QueryHandler

After fetching data, we can communicate state (eg: are there more results? what's the next paging number? etc) through [QueryHandler](#queryhandler) objects, defined through function [`get_queryhandler`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/moduleprocessors/pop-querydataprocessor-trait.php#L14). By default, it returns object with name `GD_DATALOAD_QUERYHANDLER_ACTIONEXECUTION`, needed when executing an operation (see section [Data-Posting and Operations](#data-posting-and-operations)):

```php
function get_filter($module) {

  switch ($module) {

    case POP_MODULE_AUTHORARTICLES:
          
      return GD_DATALOAD_QUERYHANDLER_LIST;
  }
  
  return parent::get_filter($module);
}
```

##### Data Properties

If the module needs to pass a variable to any other object involved in fetching/processing data ([Dataloader](#dataloader), [QueryHandler](#queryhandler), [ActionExecuter](#actionexecuter), etc), it can do so through "data properties", set through functions `get_immutable_headdatasetmodule_data_properties` and `get_mutableonrequest_headdatasetmodule_data_properties`:

```php
function get_immutable_headdatasetmodule_data_properties($module, &$props) {

  $ret = parent::get_immutable_headdatasetmodule_data_properties($module, $props);

  switch ($module) {

    case POP_MODULE_AUTHORARTICLES:
          
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
function init_model_props($module, &$props) {

  switch ($module) {

    case POP_MODULE_AUTHORARTICLES:

      // Set the content lazy
      $this->set_prop($module, $props, 'lazy-load', true);
      break;
  }

  parent::init_model_props($module, $props);
}
```

Being a prop, this value can be set either by the dataloading module itself, or by any of its ancestor modules:

```php
function init_model_props($module, &$props) {

  switch ($module) {

    case POP_MODULE_AUTHORARTICLESWRAPPER:

      // Set the content lazy
      $this->set_prop([POP_MODULE_AUTHORARTICLES], $props, 'lazy-load', true);
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

##### Conditionally Loading Database Data

We can instruct a dataloading module to not load its data simply by setting its prop `"skip-data-load"` to `true`:

```php
function init_model_props($module, &$props) {

  switch ($module) {

    case POP_MODULE_AUTHORARTICLES:

      // Set the content lazy
      $this->set_prop($module, $props, 'skip-data-load', true);
      break;
  }

  parent::init_model_props($module, $props);
}
```

> Note: Being a prop, this value can be set either by the dataloading module itself, or by any of its ancestor modules.

Among others, the following are several uses cases for not loading the data for a module:

- Loading the Search page without any search parameter
- Validate that the logged-in user has the required permissions
- Load data not when loading the site, but only when loading a page in an SPA

#### Dataloading + Descendant Modules

Starting from a dataloading module, and including itself, any descendant module can execute the functions described below: loading properties or "data fields" on the database object, and "switching domain" from the current database object to another one. 

##### Data-Fields

"Data fields", which are the properties to be required from the loaded database object, are defined through function [`get_data_fields`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/moduleprocessors/pop-moduleprocessor.php*590):

```php
function get_data_fields($module, $props) {

  $ret = parent::get_data_fields($module, $props);

  switch ($module) {

    case POP_MODULE_AUTHORARTICLES:
    
      $ret[] = 'title';
      $ret[] = 'content';
      break;
  }

  return $ret;
}
```

The value for "data-fields" is resolved through an object called a [FieldProcessor](#fieldprocessor), described below.

##### Switching domain to a relational object

When defining what data to retrieve, we can also "switch domain", which is changing from the current database object to another one, defined as a relationship. 

Consider the image below: Starting from the object type "post", and moving down the component hierarchy, we will need to shift the DB object type to "user" and "comment", corresponding to the post's author and each of the post's comments respectively, and then, for each comment, it must change the object type once again to "user" corresponding to the comment's author. After switching to a new domain, from that level at the component hierarchy downwards, all required properties, or data-fields, will be subjected to the new domain: Property "name" is fetched from the "user" object representing the post's author, "content" from the "comment" object representing each of the post's comments, and then "name" from the "user" object representing the author of each comment:

![Changing the DB object from one domain to another](https://uploads.getpop.org/wp-content/uploads/2018/12/loading-data-at-intervals-relational.jpg)

Switching domins is accomplished through function [`get_dbobject_relational_successors`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/moduleprocessors/pop-moduleprocessor.php#L595). It must return an array, in which each key is the property, or "data-field", containing the ID of the object to switch to, and its value is another array, in which the key is the [Dataloader](#dataloader) to use to load this object, and its values are the modules to use:

```php
function get_dbobject_relational_successors($module) {

  $ret = parent::get_dbobject_relational_successors($module);

  switch ($module) {

    case POP_MODULE_AUTHORARTICLES:
    
      $ret['author'] = [
        GD_DATALOADER_USERLIST => [
          POP_MODULE_AUTHORNAME,
        ]
      ];
      $ret['comments'] = [
        GD_DATALOADER_COMMENTLIST => [
          POP_MODULE_COMMENTLAYOUT,
        ]
      ];
      break;
  }

  return $ret;
}
```

> Note: Similar to `get_modules`, this method also loads modules into the component hierarchy, hence it cannot receive parameter `$props`.

## Data-Loading Objects

The following objects below are involved in fetching data from the database, filtering it, processing it and describing the new state of the application.

### Dataloader

The dataloader object is in charge of fetching database data. It knows what type of data it must fetch (posts, users, comments, etc) and how to do it. All dataloaders inherit from class [`Dataloader`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/dataload/dataloader.php). Given an array of IDs, it must fetch the corresponding objects from the database, through function [`execute_get_data`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/dataload/dataloader.php#L42):

```php
function execute_get_data($ids) {
  
  $objects = array();

  // Fetch all objects with IDs $ids
  // ...
  
  return $objects;
}
```

For instance, a [dataloader fetching posts](https://github.com/leoloso/PoP/blob/master/pop-cmsmodel/library/dataload/dataloaders/dataloader-post-base.php) will implement the function like this:

```php
function execute_get_data($ids) {
  
  $cmsapi = \PoP\CMS\FunctionAPI_Factory::get_instance();
  $query = array(
    'include' => $ids,
  );
  return $cmsapi->get_posts($query);
}
```

The dataloader must also implement the following functions:

- [`get_fieldprocessor`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/dataload/dataloader.php#L25): return the name of the [FieldProcessor](#fieldprocessor) that will handle the data-fields for all objects returned by the dataloader
- [`get_database_key`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/dataload/dataloader.php#L17): return the object type under which objects returned by the dataloader will be stored under `databases` in the JSON response
- [`get_dataquery`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/dataload/dataloader.php#L33): return the name of the [DataQuery](#dataquery) object that will handle the objects returned by the dataloader

For instance, a [dataloader fetching posts](https://github.com/leoloso/PoP/blob/master/pop-cmsmodel/library/dataload/dataloaders/dataloader-post-base.php) will implement these functions like this:

```php
function get_database_key() {

  return GD_DATABASE_KEY_POSTS;
}  

function get_fieldprocessor() {

  return GD_DATALOAD_FIELDPROCESSOR_POSTS;
}

function get_dataquery() {

  return GD_DATAQUERY_POST;
}
```

In addition, most likely dataloader will also be in charge of obtaining the `$ids` to fetch from the databse. In this case, it must inherit from class [`QueryDataDataloader`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/dataload/querydata-dataloader.php), and implement function [`get_dbobject_ids`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/dataload/querydata-dataloader.php#L9):

```php
function get_dbobject_ids($data_properties) {
  
  $ids = array();

  // Find the IDs of the objects to be fetched
  // ...
  
  return $ids;
}
```

For instance, a [dataloader fetching the single post](https://github.com/leoloso/PoP/blob/master/pop-cmsmodel/library/dataload/dataloaders/dataloader-single.php) will simply return the object ID of the post in the [Context Vars](#context-vars):

```php
function get_dbobject_ids($data_properties) {
  
  // Simply return the global $post ID. 
  $vars = \PoP\Engine\Engine_Vars::get_vars();
  return array($vars['global-state']['queried-object-id']);
}
```

Dataloaders fetching lists of results (eg: a list of posts, a list of users, etc) will need to execute a query and filter the results. This logic has been implemented in trait [`Dataloader_ListTrait`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/dataload/dataloader-list-trait.php#L42), which requires to implement functions [`get_query`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/dataload/dataloader-list-trait.php#L138) to generate the query from the `$query_args` provided through [Data Properties](#data-properties), and [`execute_query_ids`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/dataload/dataloader-list-trait.php#L16) to, given the generated `$query`, return the list of object IDs:

```php
function get_query($query_args) {

  $query = array();

  // Add all the conditions in $query, taking values from $query_args
  // ...

  return $query;
}

function execute_query_ids($query) {
    
  $ids = array();

  // Find the IDs of the objects to be fetched
  // ...
  
  return $ids;
}
```

For instance, a [dataloader fetching a list of posts](https://github.com/leoloso/PoP/blob/master/pop-cmsmodel/library/dataload/dataloaders/dataloader-postlist-base.php) will implement these functions like this:

```php
function get_query($query_args) {
    
  $query = array();

  if ($limit = $query_args['limit']) {
    $query['posts_per_page'] = $limit;
  }
  
  if ($author = $query_args['author']) {
    $query['author'] = $author;
  }

  if ($cat = $query_args['cat']) {
    $query['cat'] = $cat;
  }

  // Add more args to the query
  // ...

  return $query;
}

function execute_query_ids($query) {
    
  // Retrieve only ids
  $query['fields'] = 'ids';
  $cmsapi = \PoP\CMS\FunctionAPI_Factory::get_instance();
  return $cmsapi->get_posts($query);
}  
```

### FieldProcessor

The FieldProcessor is the object resolving "data-fields" to their corresponding value. It must inherit from class [`FieldProcessorBase`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/dataload/dataload-fieldprocessor.php), and implement function [`get_value`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/dataload/dataload-fieldprocessor.php#L17), which receives two parameters, `$resultitem` which is the database object, and `$field` which is the data-field to resolve, and must return the value for that property applied to the database object. 

For instance, a [FieldProcessor for posts](https://github.com/leoloso/PoP/blob/master/pop-cmsmodel/library/dataload/fieldprocessors/fieldprocessor-posts.php) looks like this:

```php
class FieldProcessor_Posts extends \PoP\Engine\FieldProcessorBase {

  function get_name() {
  
    return GD_DATALOAD_FIELDPROCESSOR_POSTS;
  }
  
  function get_value($resultitem, $field) {
  
    // First Check if there's a FieldProcessorHook to implement this field
    $hook_value = $this->get_hook_value(GD_DATALOAD_FIELDPROCESSOR_POSTS, $resultitem, $field);
    if (!is_wp_error($hook_value)) {
      return $hook_value;
    }    

    $cmsresolver = \PoP\CMS\ObjectPropertyResolver_Factory::get_instance();
    $cmsapi = \PoP\CMS\FunctionAPI_Factory::get_instance();
    $post = $resultitem;
    switch ($field) {

      case 'tags' :
        $value = $cmsapi->wp_get_post_tags($this->get_id($post), array('fields' => 'ids'));
        break;

      case 'title' :
        $value = apply_filters('the_title', $cmsresolver->get_post_title($post), $this->get_id($post));
        break;
      
      case 'content' :
        $value = $cmsresolver->get_post_content($post);
        $value = apply_filters('pop_content_pre', $value, $this->get_id($post));
        $value = apply_filters('the_content', $value);
        $value = apply_filters('pop_content', $value, $this->get_id($post));
        break;
    
      case 'url' :

        $value = $cmsapi->get_permalink($this->get_id($post));
        break;

      case 'excerpt' :
        $value = $cmsapi->get_the_excerpt($this->get_id($post));
        break;

      case 'comments' :
        $query = array(
          'status' => 'approve',
          'type' => 'comment', 
          'post_id' => $this->get_id($post),
          'order' =>  'ASC',
          'orderby' => 'comment_date_gmt',
        );
        $comments = $cmsapi->get_comments($query);
        $value = array();
        foreach ($comments as $comment) {
          $value[] = $cmsresolver->get_comment_id($comment);
        }
        break;
  
      case 'author' :
        $value = $cmsresolver->get_post_author($post);
        break;  
      
      default:
        $value = parent::get_value($resultitem, $field);
        break;
    }

    return $value;
  }
}

// Initialize
new GD_DataLoad_FieldProcessor_Posts();
```

#### FieldProcessorHook

A FieldProcessorHook is an object that allows to resolve data-fields for specific FieldProcessors, either to override their value or to extend them. For instance, it can be implemented at the application level, resolving those application-specific data fields. It must inherit from class [`FieldProcessor_HookBase`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/dataload/dataload-fieldprocessor-hookbase.php) and implement function [`get_value`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/dataload/dataload-fieldprocessor-hookbase.php#L38), which receives three parameters, `$resultitem` which is the database object, `$field` which is the data-field to resolve, and `$fieldprocessor` which is the FieldProcessor object hooked into, and must return the value for that property applied to the database object. 

For instance, a FieldProcessorHook for posts might add a custom "disclaimer" message, and it looks like this:

```php
class FieldProcessor_Posts_Hook extends \PoP\Engine\FieldProcessor_HookBase {

  function get_fieldprocessors_to_hook() {
    
    return array(
      GD_DATALOAD_FIELDPROCESSOR_POSTS,
    );
  }

  function get_value($resultitem, $field, $fieldprocessor) {

    $post = $resultitem;
    switch ($field) {

      case 'disclaimer':
        return \PoP\Engine\MetaManager::get_post_meta($fieldprocessor->get_id($post), "disclaimer", true);
    }

    return parent::get_value($resultitem, $field, $fieldprocessor);
  }  
}

// Initialize
new FieldProcessor_Posts_Hook();
```

### Filter and FilterComponent

The Filter is an object that modifies the query to filter data when fetching data from the database. It must inherit from class [`FilterBase`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/filter/filter-base.php) and implement functions [`get_filter_args_override_values`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/filter/filter-base.php#L25) and [`get_filter_args`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/filter/filter-base.php#L30), returning an array of property => value for the query. The difference between these 2 functions is that, while `get_filter_args` doesn't replace values existing under the same property in the query but merges the existing and new values together, `get_filter_args_override_values` overrides existing values under the same property with the new one.

The filter object delegates filtering of each field to FilterComponent objects. The filter must then implement function [`get_filtercomponents`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/filter/filter-base.php#L14) to know each FilterComponent objects it is composed of. Each FilterComponent is an object which inherits from class [`FilterComponentBase`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/filter/components/filtercomponent.php), and which knows how to filter a specific field (eg: filter by search, by category, by author, etc). The FilterComponent is assigned an input module through function [`get_filterinput`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/filter/components/filtercomponent.php#L20), from which it gets the value to filter. Then, when doing the filtering, the Filter makes each of its FilterComponents filter its corresponding field with the values filled in its corresponding input. 

For instance, a [filter for posts](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/filter/postfilter-base.php) looks like this:

```php
abstract class PostFilterBase extends FilterBase {

  function get_filter_args_override_values() {
  
    $args = parent::get_filter_args_override_values();

    if (!$this->get_filtercomponents()) {
      return $args;
    }
    
    // Search
    if ($search = $this->get_search()) {
      $args['is_search'] = true;
      $args['s'] = $search;
    }

    // Categories
    if ($categories = $this->get_categories()) {
      $args['cat'] = implode(',', $categories);
    }

    // Author
    if ($author = $this->get_author()) {
      $args['author'] = implode(",", $author);
    }

    // Order / Orderby
    if ($order = $this->get_order()) {
      $args['orderby'] = $order['orderby'];
      $args['order'] = $order['order'];
    }
    
    return $args;
  }

  function get_filter_args() {
  
    $args = parent::get_filter_args();
    
    if (!$this->get_filtercomponents()) {
      return $args;
    }
                
    // Meta query
    if ($meta_query = $this->get_metaquery()) {
      $args['meta_query'] = $meta_query;
    }
    
    return $args;
  }
  
  function get_metaquery() {
  
    $meta_query = array();
    foreach ($this->get_filtercomponents() as $filtercomponent) {
    
      if ($filtercomponent_metaquery = $filtercomponent->get_metaquery($this)) {
      
        $meta_query = array_merge($meta_query, $filtercomponent_metaquery);
      }
    }
    
    if ($meta_query) {
      $meta_query['relation'] = 'AND';
    }
    return $meta_query;  
  }
  
  function get_author() {
  
    $author = array();
    foreach ($this->get_filtercomponents() as $filtercomponent) {

      if ($filtercomponent_author = $filtercomponent->get_author($this)) {

        $author = array_merge($author, $filtercomponent_author);
      }
    }
    
    return $author;  
  }

  function get_categories() {
  
    $categories = array();
    foreach ($this->get_filtercomponents() as $filtercomponent) {
    
      if ($filtercomponent_categories = $filtercomponent->get_categories($this)) {
      
        $categories = array_merge($categories, $filtercomponent_categories);
      }
    }
    
    return $categories;  
  }
  
  function get_search() {
  
    $search = '';
    foreach ($this->get_filtercomponents() as $filtercomponent) {
    
      if ($search = $filtercomponent->get_search($this)) {
        
        // Only 1 filter can do the Search, so already break
        break;
      }    
    }
    
    return $search;  
  }

  function get_order() {
  
    $order = array();
    foreach ($this->get_filtercomponents() as $filtercomponent) {

      if ($order = $filtercomponent->get_order($this)) {
        
        // Only 1 filter can define the Order, so already break
        break;
      }
    }
    
    return $order;  
  }
}
```

Each FilterComponent object can filter only 1 field. It does so by implementing the corresponding function, returning the value of the input through function [`get_filterinput_value`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/filter/components/filtercomponent.php#L6). For instance, a FilterComponent to [filter by the "search" field](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/filter/components/filtercomponent-search.php) is implemented like this:

```php
abstract class FilterComponent_SearchBase extends FilterComponentBase {
  
  function get_search($filter) {
  
    return $this->get_filterinput_value($filter);
  }  
}
```

### QueryHandler

The QueryHandler is an object that synchronizes the state of the query between client and server. It must inherit from class [`QueryHandlerBase`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/dataload/dataload-queryhandler.php) and implement the following functions:

Before fetching data from the database, function [`prepare_query_args`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/dataload/dataload-queryhandler.php#L14) populates the `$query_args` object used passed to the dataloader to fetch data. It can get values from the request (eg: set through the application in the client) or define default values.

After fecthing data from the database, functions [`get_query_state`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/dataload/dataload-queryhandler.php#L17), [`get_query_params`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/dataload/dataload-queryhandler.php#L21) and [`get_query_result`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/dataload/dataload-queryhandler.php#L25), all of them receiving parameters `$data_properties, $checkpoint_validation, $executed, $dbobjectids`, send information about the executed query back to the client: state values (eg: are there more results?), parameter values (eg: how many results to bring each time) and result values (eg: was execution successful?) correspondingly.


### DataQuery

When loading data, it is possible to mark data-fields as noncacheable to make sure that their value is always fresh from the database (eg: a post's "Recommend" count), and modules as lazy to load them on a subsequent request (eg: a post's comments). A DataQuery is an object which allows to define these values. It must inherit from class [`DataQueryBase`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/dataload/dataquery.php). 

In plugin `pop-cmsmodel` there are already basic implementations of DataQueries for post, user, comment and tag, on which can be injected the data-fields to mark as noncacheable and modules to mark as lazy through a corresponding [DataQueryHook](#dataqueryhook) object:

- [`DataQuery_Post`](https://github.com/leoloso/PoP/blob/master/pop-cmsmodel/library/dataload/dataqueries/dataquery-post.php)
- [`DataQuery_User`](https://github.com/leoloso/PoP/blob/master/pop-cmsmodel/library/dataload/dataqueries/dataquery-user.php)
- [`DataQuery_Comment`](https://github.com/leoloso/PoP/blob/master/pop-cmsmodel/library/dataload/dataqueries/dataquery-comment.php)
- [`DataQuery_Tag`](https://github.com/leoloso/PoP/blob/master/pop-cmsmodel/library/dataload/dataqueries/dataquery-tag.php)

#### DataQueryHook

The DataQueryHooks injects data-fields to mark as noncacheable and modules to mark as lazy on a DataQuery object. It must inherit from class [`DataQuery_HookBase`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/dataload/dataquery-hook-base.php). 

## Data-Posting and Operations

In addition to loading data, "dataloading" modules can also post data, or execute any operation supported by the underlying CMS (log in/out the user, send emails, logging, etc).

To achieve this, the ModuleProcessor must define the [ActionExecuter](#actionexecuter) object for the module through function [`get_actionexecuter`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/moduleprocessors/pop-moduleprocessor.php#L580):

```php
function get_actionexecuter($module) {
	
  switch ($module) {

    case POP_MODULE_SOMENAME:
  
      return GD_DATALOAD_ACTIONEXECUTER_SOMENAME;
  }

  return parent::get_actionexecuter($module);
}
```

### ActionExecuter

The ActionExecuter is an object to execute actions or operations. It must inherit from class [`ActionExecuterBase`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/dataload/dataload-actionexecuter.php), and implement function [`execute`](https://github.com/leoloso/PoP/blob/master/pop-engine/kernel/dataload/dataload-actionexecuter.php#L14):

```php
function execute(&$data_properties) {
  
  // Execute some operation and return the results
  // ...
  return $results;
}
```

For instance, an ActionExecuter to log the user out will look like this:

```php
define ('GD_DATALOAD_ACTIONEXECUTER_LOGOUT', 'logout');

class ActionExecuter_Logout extends \PoP\Engine\ActionExecuterBase {

  function get_name() {
    
		return GD_DATALOAD_ACTIONEXECUTER_LOGOUT;
	}

  function execute(&$data_properties) {

		if ('POST' == $_SERVER['REQUEST_METHOD']) { 

			// If the user is not logged in, then return the error
			$vars = \PoP\Engine\Engine_Vars::get_vars();
			if (!$vars['global-state']['is-user-logged-in']) {

				$error = __('You are not logged in.');
			
				// Return error string
				return array(
					GD_DATALOAD_QUERYHANDLERRESPONSE_ERRORSTRINGS => array($error)
				);
			}

			$cmsapi = \PoP\CMS\FunctionAPI_Factory::get_instance();
			$cmsapi->logout();

			return array(
				GD_DATALOAD_QUERYHANDLERRESPONSE_SUCCESS => true
			);
		}

		return parent::execute($data_properties);
	}
}

//Initialize
new ActionExecuter_Logout();
```

#### Storing and reusing the results from an execution

The results obtained in function `execute` can be stored for other objects (ModuleProcessors, ActionExecuters) to use and base their logic upon them. For instance, a module is able to load data or not depending on the success or not of an execution.

Storing and accessing the execution results is done through function `set_result` and `get_result` from the [`ActionExecution_Manager`](https://github.com/leoloso/PoP/blob/masterpop-engine/kernel/dataload/dataload-actionexecution-manager.php) object. For instance, an ActionExecuter to create a comment will store the new comment ID:

```php
function execute(&$data_properties) {

  if ('POST' == $_SERVER['REQUEST_METHOD']) {

    // Function get_form_data obtains the filled-in values in the form
    $form_data = $this->get_form_data($data_properties);

    $errors = array();
    if (empty($form_data['post_id'])) {
			$errors[] = __('We don\'t know what post the comment is for.');
		}
		if (empty($form_data['comment'])) {
			$errors[] = __('The comment is empty.');
    }    
    if ($errors) {

      return array(
        GD_DATALOAD_QUERYHANDLERRESPONSE_ERRORSTRINGS => $errors
      );
    }

    $cmsapi = \PoP\CMS\FunctionAPI_Factory::get_instance();
    $comment_id = $cmsapi->insert_comment($form_data);

    // Save the result
    $gd_dataload_actionexecution_manager = \PoP\Engine\ActionExecution_Manager_Factory::get_instance();
    $gd_dataload_actionexecution_manager->set_result($this->get_name(), $comment_id);

    // No errors => success
    return array(
      GD_DATALOAD_QUERYHANDLERRESPONSE_SUCCESS => true
    );			
  }

  return parent::execute($data_properties);
}
```

A ModuleProcessor can modify what data it will fetch from the database through function `prepare_data_properties_after_actionexecution`, which is invoked after executing the module's corresponding ActionExecuter. For instance, after creating a comment, we can load it immediately or, if the creation was not successful, state to skip loading any database object:

```php
function prepare_data_properties_after_actionexecution($module, &$props, &$data_properties) {
    
  parent::prepare_data_properties_after_actionexecution($module, $props, $data_properties);

  switch ($module) {

    case POP_MODULE_ADDCOMMENT:

      $gd_dataload_actionexecution_manager = \PoP\Engine\ActionExecution_Manager_Factory::get_instance();
      if ($comment_id = $gd_dataload_actionexecution_manager->get_result(GD_DATALOAD_ACTIONEXECUTER_ADDCOMMENT)) {

        $data_properties[GD_DATALOAD_QUERYARGS]['include'] = array($comment_id);
      }
      else {

        $data_properties[GD_DATALOAD_SKIPDATALOAD] = true;
      }
      break;
  }
}
```

## Validations

Will be added soon...

### Checkpoints

Will be added soon...

## Extending and Formatting Data

### Extra URIs

Will be added soon...

### Multidomain

By default, a module will fetch its data from the domain where the application is hosted. To change this to a different domain(s) or subdomain(s) is done by setting prop `"dataload-multidomain-sources"` on the module:

```php
function init_model_props($module, &$props) {
    
  switch ($module) {

    case POP_MODULE_SOMENAME:

      $this->set_prop(
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

    case POP_MODULE_SOMENAME:

      $this->set_prop(
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

When fetching data from several sources, each source will keep its own state in the [QueryHandler](#queryhandler). Then, it is able to query different amounts of data from different domains (eg: 3 results from domain1.com and 6 results from domain2.com), and stop querying from a particular domain when it has no more results.

### DataStructureFormatter

Will be added soon...

## Extending and Filtering Modules

### ModuleDecoratorProcessor

Will be added soon...

### ModuleFilter

Will be added soon...

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

Will be added soon...

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