# Component Model

<!--
[![Build Status][ico-travis]][link-travis]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Software License][ico-license]](LICENSE.md)
[![Latest Version on Packagist][ico-version]][link-packagist]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Total Downloads][ico-downloads]][link-downloads]
-->

Component model for PoP, over which the component-based architecture is based. 

## Install

Via Composer

``` bash
composer require getpop/component-model
```

## Development

The source code is hosted on the [GatoGraphQL monorepo](https://github.com/GatoGraphQL/GatoGraphQL), under [`Engine/packages/component-model`](https://github.com/GatoGraphQL/GatoGraphQL/tree/master/layers/Engine/packages/component-model).

## Usage

Initialize the component:

``` php
\PoP\Root\App::stockAndInitializeModuleClasses([([
    \PoP\ComponentModel\Component::class,
]);
```

## Main Concepts

### Everything is a Component

The term "component" is often used to describe the concept of [encapsulating a set of related functions](https://en.wikipedia.org/wiki/Component-based_software_engineering#Software_component) for building modular applications. In PoP, a component is called a "component", so from now on the names "component" and "component" are used interchangeably. 

A component is simply a set of pieces of HTML, JavaScript and CSS code put all together to create an autonomous entity. Each component can be an atomic functionality, a composition of other components, or a combination of the two. Each component has a purpose, which can range from something very basic, such as a link or a button, to something very elaborate, such as a carousel or a drag-and-drop image uploader. 

The relationship among components is defined on a strictly top-down fashion: a component wraps other components and knows who they are, but it doesn't know, and doesn't care, which components are wrapping him. Ever more complex components are created by iteratively wrapping simpler components, until reaching the top-most component representing the webpage:

![Sequence of components wrapping components wrapping components, from an avatar all the way up to the webpage](https://uploads.getpop.org/wp-content/uploads/2018/12/module-sequence.gif)

In PoP, everything is a component:

![In PoP, everything is a component](https://uploads.getpop.org/wp-content/uploads/2018/12/everything-is-a-module.jpg)

The relationship of all components wrapping each other, from the top-most component all the way down to the last level, is called the component hierarchy. The PoP API has the component hierarchy at its core, implemented as an associative array on the server-side, in which each component states its name as the key attribute and whatever properties it needs as values, and then nests its descendant components under property "components", iteratively adding their own data and that of their own descendant components. Finally, this associative array is returned as a JSON object for comsumption through the API:

```javascript
{
  "topmost-component": {
    someprop: {...},
    components: {
      "component-level1": {
        someprop: {...},
        someprop: {...},
        components: {
          "component-level11": {
            ...
          }
        },
        components: {
          "component-level12": {
            someprop: {...},
            components: {
              "component-level121": {
                ...
              }
            }
          }
        }
      },
      "component-level2": {
        someprop: {...},
        components: {
          "component-level21": {
            ...
          }
        }
      }
    }
  }
}
```

### Relational database data

PoP represents the database data in a relational manner, organized under each object type, object ID and object properties, mirroring the structure of the data in the database. This way, all data is normalized, fetched only once from the database, and printed only once in the output. It is added under entry `databases` in the API response:

```javascript
{
  databases: {
    primary: {
      db_key_1: {
        dbobject_1_id: {
          property_1: ...,
          ...
          property_n: ...,
        },
        ...
        dbobject_n_id: {
          property_1: ...,
          ...
          property_n: ...,
        },
      },
      ...
      db_key_n: {...},
    }
  }
}
```

For instance, if fetching the data for blog posts with titles "Hello World!" and "Everything fine?" and author "Leo" both of them, then PoP brings the response below; please notice how property "author" contains the ID to the author object instead of printing the author data directly:

```javascript
{
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

Each component knows which are its queried objects from section `datasetcomponentdata`, which provides the IDs of the queried objects under property `objectIDs` (IDs 4 and 9 for the blog posts), and knows from where to retrieve the database object data from under section `databases` through section `componentsettings`, which indicates to what type each object belongs under property `outputKeys` (then, it knows that the post's author data, corresponding to the author with the ID given under property "author", is found under object type "users"):

```javascript
{
  componentsettings: {
    "page": {
      components: {
        "post-feed": {
          outputKeys: {
            id: "posts",
            author: "users"
          }
        }
      }
    }
  },
  datasetcomponentdata: {
    "page": {
      components: {
        "post-feed": {
          objectIDs: [4, 9]
        }
      }
    }
  }
}
```

### The engine infers how to retrieve database data already from the component hierarchy

When a component displays a property from a DB object, the component may not know, or care, what object it is; all it cares about is defining what properties from the loaded object are required. For instance, consider the image below: a component loads an object from the database (in this case, a single post), and then its descendant components will show certain properties from the object, such as "title" and "content":

![While some components load the database object, others load properties](https://uploads.getpop.org/wp-content/uploads/2018/12/loading-data-at-intervals.jpg)

Hence, along the component hierarchy, some components will be in charge of loading the queried objects (the component loading the single post, in this case), and its descendant components will define what properties from the DB object are required ("title" and "content", in this case). 

Fetching all the required properties for the DB object can be done automatically by traversing the component hierarchy: starting from the data loading component, we iterate all its descendant components all the way down until reaching a new data loading component, or until the end of the tree; at each level we obtain all required properties, and then merge all properties together and query them from the database, all of them only once. In the structure below, component "single-post" fetches the results from the DB, and subcomponents "post-title" and "post-content" define properties to be loaded for the queried DB object ("title" and "content" respectively); subcomponent "post-layout" does not require any data fields. Please notice how the executed query, which is calculated automatically from the component hierarchy and their required data fields, will contain all the properties needed by all the components and their subcomponents:

```javascript
"single-post"
  => Load objects from domain "post" where ID = 37
  components
    "post-layout"
      components
        "post-title"
          => Load property "title"
        "post-content"
          => Load property "content"
```

Which will result in the following (pseudo-)query:

```sql
SELECT 
  title, content 
FROM 
  posts 
WHERE 
  id = 37 
```

The query to fetch data from the database is automatically updated whenever the component hierarchy changes. If we then add subcomponent "post-thumbnail", which requires data field "thumbnail", under "single-post":

```javascript
"single-post"
  => Load objects from domain "post" where ID = 37
  components
    "post-layout"
      components
        "post-title"
          => Load property "title"
        "post-content"
          => Load property "content"
        "post-thumbnail"
          => Load property "thumbnail"
```

Then the query is automatically updated with the new data:

```sql
SELECT 
  title, content, thumbnail 
FROM 
  posts 
WHERE 
  id = 37 
```

This strategy also applies to relational objects. Consider the image below: Starting from the object domain "post", we need to change the DB object domain to entities "user" and "comment", corresponding to the post's author and each of the post's comments respectively, and then, for each comment, it must change the domain once again to "user", for the comment's author. After changing from one to another domain, from that level at the component hierarchy downwards, all required properties will be subjected to the new domain: Property "name" is fetched from the "user" object representing the post's author, "content" from the "comment" object representing each of the post's comments, and then "name" from the "user" object representing the author of each comment:

![Changing the DB object from one domain to another](https://uploads.getpop.org/wp-content/uploads/2018/12/loading-data-at-intervals-relational.jpg)

Going back to our previous example, if we need to show data from the post's author, stacking subcomponent "post-author" will change the domain at that level from "post" to the corresponding "user", and from this level downwards the DB object loaded into the context passed to the component is the user. Then, subcomponents "user-name" and "user-avatar" under "post-author" will load properties "name" and "avatar" under the user object:

```javascript
"single-post"
  => Load objects from domain "post" where ID = 37
  components
    "post-layout"
      components
        "post-title"
          => Load property "title"
        "post-content"
          => Load property "content"
        "post-author"
          => Change object domain from "post" to "user", based on property "author"
          components
            "user-layout"
              components
                "user-name"
                  => Load property "name"
                "user-avatar"
                  => Load property "avatar"

```

Resulting in the following query:

```sql
SELECT 
  p.title, p.content, p.author, u.name, u.avatar 
FROM 
  posts p 
INNER JOIN 
  users u 
WHERE 
  p.id = 37 AND p.author = u.id 
```

### Configuration values included, overridable through props

> Note: the configuration layer must be added through [another package](https://github.com/getpop/component-model-configuration)

Instead of hardcoding classnames or other properties such as a title's HTML tag or an avatar max width inside of JavaScript files for rendering in the client, we can pass configuration values already through the API, so that then these can be directly updated on the server and without the need to redeploy JavaScript files:

```javascript
{
  componentsettings: {
    "component1": {
      components: {
        "component2": {
          configuration: {
            class: "whoweare text-center",
            title: "Who we are",
            titletag: "h3"
          },
          components: {
            "component3": {
              configuration: {
                classes: {
                  wrapper: "media",
                  avatar: "mr-3",
                  body: "media-body",
                  items: "row",
                  item: "col-sm-6"
                },
                avatarmaxsize: "100px"
              },
              components: {
                "component4": {
                  configuration: {
                    classes: {
                      wrapper: "card",
                      image: "card-img-top",
                      body: "card-body",
                      title: "card-title",
                      avatar: "img-thumbnail"
                    }
                  }
                }
              }
            }
          }
        }
      }
    }
  }
}
```

Configuration values can be set through props, defined across the component hierarchy so that components can modify the behavior of their descendant components, and where higher-level components have priority for setting a prop. Setting props works in one direction only: parent components can set props on any descendant component, but no component can set props on any ancestor component or on any component belonging to a different branch from the component hierarchy. In the example below, "component1" can set props on "component2", "component3" and "component4", "component2" on "component3", and "component3" and "component4" on nobody:

```javascript
"component1"
  components
    "component2"
      components
        "component3"
    "component4"
```

Let's say we have a component "component3" with property "color", set as "red" by default. By not specifying a component target of the prop, the component in the pseudo-code below is setting a prop on itself:

```php
Component("component3")->setProp({
  prop: "color",
  value: "red"
});
```

Through property "componentpath", a component can target any of its descendant components any number of levels deep from itself. In the pseudo-code below, parent component "component2" adds property "componentpath" pointing to subcomponent "component3", overriding its value for property "color" to "green":

```php
Component("component2")->setProp({
  componentpath: ["component3"],
  prop: "color",
  value: "green"
});
```

And this can go on for any number of ancestor components. For instance, in the pseudo-code below, the "component1" component, which is parent to "component2", can further override the value for property "color" applied to component "component3":

```php
Component("component1")->setProp({
  componentpath: ["component2", "component3"],
  prop: "color",
  value: "blue"
});
```

Component "component1" can set a property on component "component3", which is 2 levels below ("component1" => "component2" => "component3"), directly, without having to retransmit the information through the components in between (in this case through "component2"). Hence, the API allows to set props by skipping the components in between, i.e. not every component down a path must convey the prop value until it reaches its destination, and the prop value will be set on the context of the destination component and not pollute the contexts of the components in between.

Armed with these capabilities, the API allows for powerful customization of components, which enables to produce a wide array of layouts and functionalities for different use cases. For instance, in the image below a component `<ShareButtons>` is embedded twice, printing descriptions ("Facebook", "Twitter", etc) or not just by setting property "show-description" as `true` or `false`:

![A component can be customized through props](https://uploads.getpop.org/wp-content/uploads/2018/12/configuration-props.jpg)

And in the image below, a component can be rendered in three different fashions simply by overriding what classes are printed in the component: 

```php
// Layout on the left uses default configuration of thumbnail on top of the text
Component("post-layout")->setProp({
  prop: "classes",
  value: {
    wrapper: "",
    thumb: "",
    contentbody: ""
  }
});

// Layout on the center display a big thumnail to the left of the text
Component("central-section")->setProp({
  componentpath: ["post-layout"],
  prop: "classes",
  value: {
    wrapper: "row",
    thumb: "col-sm-4",
    contentbody: "col-sm-8"
  }
});

// Layout on the floating window display a small thumnail to the left of the text
Component("floating-window")->setProp({
  componentpath: ["post-layout"],
  prop: "classes",
  value: {
    wrapper: "media",
    thumb: "media-left",
    contentbody: "media-body"
  }
});
```

![A component can be rendered in multiple fashions](https://uploads.getpop.org/wp-content/uploads/2018/12/configuration-layouts.jpg)

### The webpage is its own API endpoint

PoP will issue only one request to fetch all the data for all components in the page, normalizing the database data for all results. The API endpoint to be called is simply the same as the webpage URL for which we are fetching the data, just adding an additional parameter `output=json` to indicate to bring the data in JSON format instead of printing it as HTML:

```bash
GET - /url-of-the-page/?output=json
```

### The component is its own API

Every component can interact with itself from client to server just by adding its component path to the webpage URL in which it has been included. This way, when creating a component, we don't need to create an API to go alongside with it (such as REST or GraphQL), because the component is already able to talk to itself in the server and load its own data: it is completely autonomous and self-serving. 

This is accomplished by allowing to select what component paths (i.e. the path to a specific component starting from the top-most component) will be included in the response, so as to load data only starting from that level, and ignore anything above that level. This is done through adding parameters `componentFilter=componentpaths` and `componentpaths[]=path-to-the-component` to the URL (we use `componentpaths[]` instead of `componentpaths` for versatility, so that we can include more than one component path in a single request). The value for the `componentpaths[]` parameter is a list of components separated by dots. Hence, fetching data for component "component5", located under `component1 => component2 => component5`, is done by adding parameter `componentpaths[]=component1.component2.component5` to the URL. 

For instance, in the following component hierarchy every component is loading data, hence every level has an entry `objectIDs`:

```javascript
"component1"
  objectIDs: [...]
  components
    "component2"
      objectIDs: [...]
      components
        "component3"
          objectIDs: [...]
        "component4"
          objectIDs: [...]
        "component5"
          objectIDs: [...]
          components
            "component6"
              objectIDs: [...]
```

Then requesting the webpage URL adding parameters `componentFilter=componentpaths` and `componentpaths[]=component1.component2.component5` will produce the following response:

```javascript
"component1"
  components
    "component2"
      components
        "component5"
          objectIDs: [...]
          components
            "component6"
              objectIDs: [...]
```

In essence, the API starts loading data starting from component1 => component2 => component5, that's why "component6", which comes under "component5", also brings its data, but "component3" and "component4" do not.

Each component that loads data exports the URL to interact with it under entry `dataloadsource` from under section `datasetcomponentmeta`:

```javascript
{
  datasetcomponentmeta: {
    "component1": {
      components: {
        "component2": {
          components: {
            "component5":  {
              meta: {
                dataloadsource: "https://page-url/?componentFilter=componentpaths&componentpaths[]=component1.component2.component5"
              },
              components: {
                "component6": {
                  meta: {
                    dataloadsource: "https://page-url/?componentFilter=componentpaths&componentpaths[]=component1.component2.component5.component6"
                  }
                }
              }
            }
          }
        }
      }
    }
  }
}
```

## Architecture Design and Implementation

In PoP, a component is called a "component", so from now on the terms "component" and "component" are used interchangeably.

### Component Hierarchy and JSON Output

The relationship of all components wrapping each other, from the top-most component all the way down to the last level, is called the component hierarchy. This relationship can be expressed through an associative array (an array of key => property) on the server-side, in which each component states its name as the key attribute and its inner components under property "components":

```php
// Component hierarchy on server-side, eg: through PHP:
[
  "top-component" => [
    "components" => [
      "component-level1" => [
        "components" => [
          "component-level11" => [
            "components" => [...]
          ],
          "component-level12" => [
            "components" => [
              "component-level121" => [
                "components" => [...]
              ]
            ]
          ]
        ]
      ],
      "component-level2" => [
        "components" => [
          "component-level21" => [
            "components" => [...]
          ]
        ]
      ]
    ]
  ]
]
```

Please notice how components are nested. This way, component properties will never collide with each other if having the same name, avoiding having to add namespaces for components. For instance, the property `"class"` from one component's configuration will not override property `"class"` from another component's configuration.

The API then simply encodes this array as a JSON object for consumption. Its format is a specification all by itself: As long as the server returns the JSON response in its required format, the client can consume the API independently of how it is implemented:

```javascript
// Component hierarchy encoded as JSON:
{
  "top-component": {
    components: {
      "component-level1": {
        components: {
          "component-level11": {
            ...
          },
          "component-level12": {
            components: {
              "component-level121": {
                ...
              }
            }
          }
        }
      },
      "component-level2": {
        components: {
          "component-level21": {
            ...
          }
        }
      }
    }
  }
}
```

The relationship among components is defined on a strictly top-down fashion: a component wraps other components and knows who they are, but it doesn't know, and doesn't care, which components are wrapping him. For instance, in the JSON code above, component `"component-level1"` knows it wraps components `"component-level11"`` and "component-level12"`, and, transitively, it also knows it wraps `"component-level121"`; but component `"component-level11"` doesn't care who is wrapping him, consequently is unaware of `"component-level1"`.

Having the component-based structure, we can now add the actual information required by each component, which is categorized into either settings (such as configuration values and other properties) and data (such as the IDs of the queried database objects and other properties), and placed accordingly under entries `componentsettings` and `datasetcomponentdata`:

```javascript
{
  componentsettings: {
    "top-component": {
      configuration: {...},
      ...,
      components: {
        "component-level1": {
          configuration: {...},
          ...,
          components: {
            "component-level11": {
              repeat...
            },
            "component-level12": {
              configuration: {...},
              ...,
              components: {
                "component-level121": {
                  repeat...
                }
              }
            }
          }
        },
        "component-level2": {
          configuration: {...},
          ...,
          components: {
            "component-level21": {
              repeat...
            }
          }
        }
      }
    }
  },
  datasetcomponentdata: {
    "top-component": {
      objectIDs: [...],
      ...,
      components: {
        "component-level1": {
          objectIDs: [...],
          ...,
          components: {
            "component-level11": {
              repeat...
            },
            "component-level12": {
              objectIDs: [...],
              ...,
              components: {
                "component-level121": {
                  repeat...
                }
              }
            }
          }
        },
        "component-level2": {
          objectIDs: [...],
          ...,
          components: {
            "component-level21": {
              repeat...
            }
          }
        }
      }
    }
  }
}
```

Component properties (configuration values, what database data to fetch, etc) and descendant components are not added manually to the associative array. Instead, they are defined through an object called a [ComponentProcessor](#componentprocessor) on a component by component basis. The PoP engine will traverse all components in the component hierarchy, starting from the entry component, fetch the properties for each from the corresponding ComponentProcessor, and create the nested associative array with all properties for all components. A ComponentProcessor for a component called `COMPONENT_SOMENAME` looks like this:

```php
class SomeComponentProcessor extends AbstractComponentProcessor {

  const COMPONENT_SOMENAME = 'somename';

  function getSubcomponentsToProcess() {
  
    return array(
      COMPONENT_SOMENAME,
    );
  }

  function getSubcomponents($component) 
  {
    $ret = parent::getSubcomponents($component);

    switch ($component->name) {
      
      case self::COMPONENT_SOMENAME:
        
        $ret[] = self::COMPONENT_SOMELAYOUT1;
        $ret[] = self::COMPONENT_SOMELAYOUT2;
        break;
    }

    return $ret;
  }

  function getImmutableConfiguration($component, &$props) 
  {
    $ret = parent::getImmutableConfiguration($component, $props);

    // Print the components properties ...
    switch ($component->name) {
      case self::COMPONENT_SOMENAME:        
        $ret['description'] = __('Some description');
        $ret['showmore'] = $this->getProp($component, $props, 'showmore');
        $ret['class'] = $this->getProp($component, $props, 'class');
        break;
    }

    return $ret;
  }
  
  function initModelProps($component, &$props) 
  {
    // Implement the components properties ...
    switch ($component->name) {
      case self::COMPONENT_SOMENAME:
        $this->setProp($component, $props, 'showmore', false);
        $this->appendProp($component, $props, 'class', 'text-center');
        break;
    }

    parent::initModelProps($component, $props);
  }
  // ...
}
```

Database object data is retrieved and placed under a shared section called `databases`, to avoid duplicating information when 2 or more different components fetch the same objects from the database. In addition, it is added in a relational manner to the associative array and printed in the JSON response, to avoid duplicating information when 2 or more different database objects are related to a common object (such as 2 posts having the same author). In other words, database object data is normalized. The structure is a dictionary, organized under each object type first and object ID second, from which we can obtain the object properties: 

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

For instance, the API response below contains a component hierarchy with two components, `"page" => "post-feed"`, where component `"post-feed"` fetches blog posts. Please notice the following:

- Each component knows which are its queried objects from property `objectIDs` (IDs 4 and 9 for the blog posts)
- Each component knows the object type for its queried objects from property `outputKeys` (each post's data is found under "posts", and the post's author data, corresponding to the author with the ID given under the post's property "author", is found under "users"):
- Because the database object data is relational, property "author" contains the ID to the author object instead of printing the author data directly

```javascript
{
  datasetcomponentdata: {
    "page": {
      components: {
        "post-feed": {
          objectIDs: [4, 9]
        }
      }
    }
  },
  componentsettings: {
    "page": {
      components: {
        "post-feed": {
          outputKeys: {
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

### Definition of a Component

Every component has a unique name that identifies it, defined as a constant:

```php
const COMPONENT_SOMENAME = 'somename';
```

All the properties of the components are implemented through objects called [ComponentProcessor](#componentprocessor).
<!--
> Note: the name of a component cannot include the special character "|" (`POP_CONSTANT_VIRTUALCOMPONENTATTS_SEPARATOR`), as will be explained below
-->
### Virtual Components

Virtual components are "dynamically-generated" components: components with a base personality and dynamic behaviour. For instance, the [custom-querying capabilities of the API](ArchitectureDesignAndImplementation.md#API-Custom-Querying-Capabilities) create the component hierarchy based on the value of URL parameter `query`, creating a virtual component along its path for each of the nested relationships. 
<!--
Another example (yet to be implemented) involves the integration of PoP with [WordPress Gutenberg](https://wordpress.org/gutenberg/): Gutenberg allows to drag-and-drop blocks to the page and customize them through properties; then, to have Gutenberg input components and PoP save them, two blocks from the same block/component must be made unique, hence they can be dynamically created by selecting a base component for its personality (a scroll of posts, a calendar of events, etc) and then assigning a random id to each, or a serialization of their properties, for its dynamic behaviour.
-->
Virtual components cannot depend on props for defining their behaviour, because at the time of creating the component hierarchy we don't have the `$props` available (otherwise it's a chicken or egg situation). Hence, the particular properties given to a virtual component are coded into the component name itself<!-- as a serialized array separated from the component name with a `"|"` (which is represented under constant `POP_CONSTANT_VIRTUALCOMPONENTATTS_SEPARATOR`): `componentname|virtualcomponentatts`-->. Then, the personality of the component is given by the component with name `"componentname"`, and the virtual component attributes <!--(`"virtualcomponentatts"`)-->are the runtime element which define its dynamic behaviour.
<!--
Extracting the pair of component name and virtual component atts from the component is done through function `extract_virtualcomponent`, like this:

```php
list($component, $virtualcomponentatts) = \PoP\Engine\VirtualComponentUtils::extractVirtualcomponent($component);
```

To generate a virtual component is done through function `create_virtualcomponent`, like this:

```php
$virtualcomponent = \PoP\Engine\VirtualComponentUtils::createVirtualcomponent($component, $virtualcomponentatts),
```
-->
### ComponentProcessor

A ComponentProcessor is an object class in which to define all the properties of a component. ComponentProcessors are implemented following the [SOLID](https://scotch.io/bar-talk/s-o-l-i-d-the-first-five-principles-of-object-oriented-design) methodology, establishing an object inheritance scheme to progressively add properties to components. The base class for all ComponentProcessors is `AbstractComponentProcessor`:

```php
namespace PoP\Engine;
abstract class AbstractComponentProcessor {

  // ...
}
```

In practice, because a component is implemented through a ComponentProcessor object, describing a component equals to describing how the ComponentProcessor implements all functions to define the properties of the component.

Every ComponentProcessor can handle more than 1 component: Because different components will naturally share many properties, then having a single ComponentProcessor implement many components is more legible and reduces the amount of code required compared to having 1 ComponentProcessor per component. Which components are handled by the ComponentProcessor is defined through function `getSubcomponentsToProcess`:

```php
class SomeComponentProcessor extends \PoP\Engine\AbstractComponentProcessor {

  const COMPONENT_SOMENAME1 = 'somename1';
  const COMPONENT_SOMENAME2 = 'somename2';
  const COMPONENT_SOMENAME3 = 'somename3';

  function getSubcomponentsToProcess() {
  
    return array(
      COMPONENT_SOMENAME1,
      COMPONENT_SOMENAME2,
      COMPONENT_SOMENAME3,
    );
  }

  // Implement the components properties ...
  // ...
}
```

Once the ComponentProcessor class is instantiated, all of its defined components become available to be added to the component hirarchy.

To access the properties of a component, we must reference its corresponding ComponentProcessor through function `getComponentProcessor` from class `ComponentProcessor_Manager`:

```php
// Retrive the PoP_ComponentProcessor_Manager object from the factory
$componentprocessor_manager = \PoP\Engine\ComponentProcessor_Manager_Factory::getInstance();

// Obtain the ComponentProcessor for component COMPONENT_SOMENAME
$processor = $componentprocessor_manager->getComponentProcessor(new \PoP\ComponentModel\Component\Component(SomeComponentProcessor::class, SomeComponentProcessor::COMPONENT_SOMENAME));

// Do something...
// $processor->...
```

### Anatomy of a Component

Because a ComponentProcessor can handle several components, then each of its functions will receive a parameter `$component` indicating which is the component being processed. Please notice how, inside the function, we can conveniently use `switch` statements to operate accordingly (components with shared properties can easily share the logic) and, according to [SOLID](https://scotch.io/bar-talk/s-o-l-i-d-the-first-five-principles-of-object-oriented-design), we first obtain the results of the parent class and then the ComponentProcessor adds its own properties:

```php
class SomeComponentProcessor extends \PoP\Engine\AbstractComponentProcessor {

  function foo($component) 
  {
    // First obtain the value from the parent class
    $ret = parent::foo($component);

    // Add properties to the component
    switch ($component->name) 
    {
      case self::COMPONENT_SOMENAME1:
        
        // Do something with $ret
        // ...
        break;

      // These components share the same properties
      case self::COMPONENT_SOMENAME2:
      case self::COMPONENT_SOMENAME3:
        
        // Do something with $ret
        // ...
        break;
    }

    return $ret;
  }
}
```

In addition to parameter `$component`, most functions will also receive a `$props` parameter, with the value of the "props" set on the component (more on section [Props](#props)):

```php
class SomeComponentProcessor extends \PoP\Engine\AbstractComponentProcessor {

  function foo($component, &$atts) 
  {
    $ret = parent::foo($component, &$atts);

    // ...

    return $ret;
  }
}
```

### Composition

Components are composed of other components through function `getSubcomponents`:

```php
class SomeComponentProcessor extends \PoP\Engine\AbstractComponentProcessor {

  function getSubcomponents($component) 
  {
    $ret = parent::getSubcomponents($component);

    switch ($component->name) 
    {
      case self::COMPONENT_SOMENAME1:
        
        $ret[] = self::COMPONENT_SOMENAME2;
        break;

      case self::COMPONENT_SOMENAME2:
      case self::COMPONENT_SOMENAME3:
        
        $ret[] = new \PoP\ComponentModel\Component\Component(LayoutComponentProcessor::class, LayoutComponentProcessor::COMPONENT_LAYOUT1);
        $ret[] = new \PoP\ComponentModel\Component\Component(LayoutComponentProcessor::class, LayoutComponentProcessor::COMPONENT_LAYOUT2);
        $ret[] = new \PoP\ComponentModel\Component\Component(LayoutComponentProcessor::class, LayoutComponentProcessor::COMPONENT_LAYOUT3);
        break;
    }

    return $ret;
  }
}
```

> Note: the component hierarchy is created by calling `getSubcomponents` on the entry-component and then repeating the process, iteratively, for its descendant components.

Abstract ComponentProcessors can define what descendant components will be required through placeholder functions, to be implemented by an inheriting ComponentProcessor:

```php
abstract class PostLayoutAbstractComponentProcessor extends \PoP\Engine\AbstractComponentProcessor {

  function getSubcomponents($component) {
  
    $ret = parent::getSubcomponents($component);

    if ($thumbnail_component = $this->getThumbnailComponent($component)) 
    {
      $ret[] = $thumbnail_component;
    }
    if ($content_component = $this->getContentComponent($component)) 
    {
      $ret[] = $content_component;
    }
    if ($aftercontent_components = $this->getAftercontentComponents($component)) 
    {
      $ret = array_merge(
        $ret,
        $aftercontent_components
      );
    }

    return $ret;
  }

  protected function getContentComponent($component) 
  {
    // Must implement
    return null;
  }
  protected function getThumbnailComponent($component) 
  {
    // Default value
    return self::COMPONENT_LAYOUT_THUMBNAILSMALL;
  }
  protected function getAftercontentComponents($component) 
  {
    return array();
  }
}

class PostLayoutComponentProcessor extends PostLayoutAbstractComponentProcessor {

  protected function getContentComponent($component) 
  {
    switch ($component->name) 
    {
      case self::COMPONENT_SOMENAME1:
        
        return self::COMPONENT_LAYOUT_POSTCONTENT;

      case self::COMPONENT_SOMENAME2:
      case self::COMPONENT_SOMENAME3:
        
        return self::COMPONENT_LAYOUT_POSTEXCERPT;
    }

    return parent::getContentComponent($component);
  }
  protected function getThumbnailComponent($component) 
  {
    switch ($component->name) 
    {
      case self::COMPONENT_SOMENAME1:
        
        return self::COMPONENT_LAYOUT_THUMBNAILBIG;

      case self::COMPONENT_SOMENAME3:
        
        return self::COMPONENT_LAYOUT_THUMBNAILMEDIUM;
    }

    return parent::getThumbnailComponent($component);
  }
  protected function getAftercontentComponents($component) 
  {
    $ret = parent::getAftercontentComponents($component);

    switch ($component->name) 
    {
      case self::COMPONENT_SOMENAME2:
        
        $ret[] = self::COMPONENT_LAYOUT_POSTLIKES;
        break
    }

    return $ret;
  }
}

// Initialize
new PostLayoutComponentProcessor();
```

### Function Names and Caching

The component hierarchy depends not on the URL, but on what components are needed in that URL. Hence, a component hierarchy included in different URLs can be cached and reused across them. For instance, requesting `/events/1/` `/events/2/` will most like have the same component hierarchy. Then, the 2nd request can reuse the cached component hierarchy from the first request, avoiding to calculate all required properties again and thus optimizing performance.

Most properties can be cached, however a number of them cannot be cached. For instance, adding configuration property `"classname"` with value `post-{id}`, where ID is the id of the requested post, cannot be cached, since the ID depends directly on the URL. To address this and maximize how much caching can be achieved, PoP has split component properties into non-overlapping segments: "cacheable" and "non-cacheable", and implemented through appropriate functions.

Caching is carried out on two different areas: the server-side and the client-side. All functionality will be required on the server, however not everything will necessarily reach the client. For instance, "props" are used to modify configuration values; while configuration values are sent to the client, the "props" themselves are not. Caching for these two cases is different, and as such the functions to define them will be different, as explained next.

#### Server-side only Properties: Model and Request

Properties that will only required on the server-side of the application, and never reach the client, can be divided into "model" and "request":

- **model:** it is a synonym of "component hierarchy". It represents all those component properties which are fixed to the component hierarchy. Hence, when caching the component hierarchy on the server, these properties can be included in the cache.
- **request:** properties which can change based on the requested URL, hence they cannot be included when caching the component hierarchy.

For instance, a prop `"description"` setting value `"Welcome to my site!"` is immutable within the component hierarchy, hence it can be set in a `model` function. Prop `"classname"` with value `post-{id}`, where ID is the id of the requested post, which depends directly on the URL, must be set under a `request` function.

#### Client-side Properties: Immutable, Mutable on Model, and Mutable on Request

Properties which are sent to the client-side (eg: configuration values) can also be cached on the client-side application, so that once a component hierarchy has been loaded, we can avoid fetching this information again and, if we already have all the data to satisfy the request, we can render a layout without even having to communicate with the server.

Caching on the server-side is easy: each component hierarchy is cached on its own file, so these can be handled independently. In the client-side, however, we will be caching together the JSON response from different requests, involving different component hierarchies which have shared properties, and we want to cache these shared properties only once in total instead of once per request. For instance, requesting `/posts/1` may have component hierarchy `"singlepost" => "postlayout"`, while `/events/1` may have component hierarchy `"singlepost" => "eventlayout"`. In this case, the first level, `"singlepost"`, is shared across different requests.

To deal with this situation, we need to make the naming of functions a bit more granular: the term `model` explained above has been further split into two, `immutable` and `mutable on model`, and the term `request` has been renamed as `mutable on request`. How and why this works is explained in detail in section [Client-side caching](ArchitectureDesignAndImplementation.md#client-side-caching) of the "Architecture Design and Implementation" document.

The difference between `immutable` and `mutable on model` is that properties on `mutable on model` can change their value depending on the component's descentant components:

- **immutable:** properties which are fixed
- **mutableonmodel:** properties which can change based on the descendant components
- **mutableonrequest:** properties which can change based on the requested URL

For instance, we could have a configuration property `"descendants"` explicitly declaring the names of its descendant components. In the example below, component `"singlepost"` has descendant component `"postlayout"` and configuration property `"descendants"` with value `["postlayout"]`:

```javascript
{
  "singlepost": {
    configuration: {
      class: "text-center",
      descendants: ["postlayout"]
    },
    components: {
      "postlayout": {
        configuration: {
          class: "post-37",
        }
      }
    }
  }
}
```

Then, properties are filled like this: `class: "text-center"` is `immutable`, `descendants: ["component2"]` is `mutable on model`, and `class: "post-37"`, which corresponds to `post-{id}`, is `mutable on request`.

### Props

Components are most useful when they are generic and enable customization through properties, or "props". For instance, a component can define a prop to change the background color configuration value, define how many objects to fetch from the database, or anything it may need. 

Setting props works in one direction only: components can set props on any descendant component, but no component can set props on any ancestor component or on any component belonging to a different branch from the component hierarchy. In the structure below, "component1" can set props on "component2", "component3" and "component4", "component2" on "component3", and "component3" and "component4" on nobody:

```javascript
"component1"
  components
    "component2"
      components
        "component3"
    "component4"
```

Components can set props on descendant components whichever number of levels below in the component hierarchy, and it is done directly, i.e. without involving the components in between or affecting their props. In the structure above, "component1" can set a prop directly on "component3" without going through "component2".

Setting props is done through functions `initModelProps($component, &$props)` and `initRequestProps($component, &$props)`. A prop must be implemented in either function, but not on both of them. `initRequestProps` is used for defining props that depend directly on the requested URL, such as adding a classname `post-{id}` to prop `"class"`, where `{id}` is the ID of the requested post on that URL. `initModelProps` is used for everything else. 

Setting props is done at the very beginning: Immediately after obtaining the component hierarchy, PoP Engine will invoke these 2 functions **before anything else** (i.e. before getting the configuration, fetching database data, etc). Hence, with the exception of the functions to create the component hierarchy (i.e. `getSubcomponents` and those inner functions invoked by `getSubcomponents`), every function in the `ComponentProcessor` can receive `$props`. 

`initModelProps` and `initRequestProps` store the props under parameter `$props`, hence it is passed by reference. In all other functions, `$props` may also be passed by reference, but only for performance issues, to not duplicate the object in memory.

Inside these 2 functions, we get to set the props through the following 3 functions:

- `function setProp($component_or_componentpath, &$props, $field, $value, $starting_from_componentpath = array())`
- `function appendProp($component_or_componentpath, &$props, $field, $value, $starting_from_componentpath = array())`
- `function mergeProp($component_or_componentpath, &$props, $field, $value, $starting_from_componentpath = array())`

These 3 functions are similar to each other, with the following differences: 

`appendProp` is used to append a value to an existing prop, hence it is a cumulative property. It is often used for adding classnames. For instance, having components "component1" and "component2" both execute `appendProp` on "component3" for property `"class"` with values `"big"` and `"center"` respectively, will make the `"class"` property of "component3" be set as `"big center"`.

`mergeProp` is similar, but concerning arrays. It is often used for adding params to be printed on the element in the DOM. For instance, having components "component1" and "component2" both execute `mergeProp` on "component3" for property `"params"` with values `["data-target" => "#main"]` and `["data-mode" => "static"]` respectively, will make the `"params"` property of "component3" be set as `["data-target" => "#main", "data-mode" => "static"]`.

`setProp` is not cumulative, but accepts only 1 value: that one that is set the first. Hence, higher-level components have priority for setting a property value over lower-level ones along the component hierarchy. For instance, having components "component1" and "component2" both execute `setProp` on "component3" for property `"title"` with values `"First title"` and `"Second title"` respectively, will make the `"title"` property of "component3" be set as `"First title"`.

All 3 methods receive the same parameters:

- `$component_or_componentpath`: This value can either be a string with the name of the component on which to set the prop, or an array. If it is the name of the component, there are 2 possibilities: if the target component is the same component setting the prop, then the component is setting a prop on itself (eg: to set the default value for the prop); if not, it will set a prop on the descendant component with that name wherever it is found along the subcomponent hierarchy. If it is an array, then this is already the subpath to the targeted descendant component where to set the prop.
- `&$props`: This is the object storing all the props, a unique object passed across all components along the component hierarchy to initialize all props
- `$field`: Name of the property
- `$value`: Value to set on the property
- `$starting_from_componentpath`: Array with a component subpath from which to find the target component where to set the prop

Every component first initializes its own props, and only then continues the flow to the parent class, so that inheriting classes have priority over their ancestors in the object inheritance scheme:

```php
function initModelProps($component, &$props) 
{
  // Set prop...
  // Set prop...
  // Set prop...

  parent::initModelProps($component, $props);
}
```

Accessing the value of the prop is done through `function getProp($component, &$props, $field, $starting_from_componentpath = array())`. The signature of the function is similar to the ones above, however without parameter `$value`.

Let's see an example: a component for rendering maps has 2 orientations: `"horizontal"` and `"vertical"`. It is composed by components `"map" => "map-inner"`, and both these components need this property. Component `"map"` will set the value by default to `"vertical"`, obtain the value for this prop just in case an ancestor component had already set the prop, and then set this value on component `"map-inner"`. Function below is implemented for component `"map"`:

```php
function initModelProps($component, &$props) 
{
  switch ($component->name) {
    case self::COMPONENT_MAP:
      // Component "map" is setting the default value
      $this->setProp($component, $props, 'orientation', 'vertical');

      // Obtain the value from the prop
      $orientation = $this->getProp($component, $props, 'orientation');

      // Set the value on "map-inner"
      $this->setProp([[SomeComponent::class, SomeComponent::COMPONENT_MAPINNER]], $props, 'orientation', $orientation);
      break;
  }

  parent::initModelProps($component, $props);
}
```

By default, component map will have prop `"orientation"` set with value `"vertical"`. However, parent component `"map-wrapper"` can set this prop beforehand to `"horizontal"`:

```php
function initModelProps($component, &$props) 
{
  switch ($component->name) {
    case self::COMPONENT_MAPWRAPPER:
      $this->setProp([[SomeComponent::class, SomeComponent::COMPONENT_MAP]], $props, 'orientation', 'horizontal');      
      break;
  }

  parent::initModelProps($component, $props);
}
```

### Data-Loading

Along the component hierarchy, certain components will define what objects from the database are required, and their descendants will indicate what properties the database object must have. Consider the image below, in which a component `"singlepost"` defines to what DB object to load, and its descendant components `"post-title"` and `"post-content"` indicate that the object must have properties `"title"` and `"content"` loaded:

![While some components load the database object, others load properties](https://uploads.getpop.org/wp-content/uploads/2018/12/loading-data-at-intervals.jpg)

#### Dataloading Components

Those components indicating what DB objects must be loaded are called "dataloading" components. To do this, dataloading components must define the functions and properties below.

##### Defining the DataSource

Indicate if the results are `immutable` (eg: results which never change and are cacheable) or `mutable on request`, through function `getDatasource`. By default results are set as `mutable on request` (through constant `\PoP\ComponentModel\Constants\DataSources::MUTABLEONREQUEST`), so only when results are `immutable` this function must be implemented:

```php
function getDatasource($component, &$props) 
{
  switch ($component->name) {
    case self::COMPONENT_WHOWEARE:
      return \PoP\ComponentModel\Constants\DataSources::IMMUTABLE;
  }

  return parent::getDatasource($component, $props);
}
```

##### Defining the Database Object IDs

Define the IDs of the objects to be retrieved from the database, through function `getDbobjectIds`. If the component already knows what database objects are required, it can simply return them:

```php
function getDbobjectIds($component, &$props, $data_properties) 
{
  switch ($component->name) {
    case self::COMPONENT_WHOWEARE:
      return [13, 54, 998];
  }

  return parent::getDbobjectIds($component, $props, $data_properties);
}
```

However, most likely, the objects are not known in advance, and must be found through a query. In this case, the ComponentProcessor must inherit from class `QueryDataAbstractComponentProcessor`, which implements `getDbobjectIds` transferring the responsibility of finding the database object IDs to function `getDbobjectIds` from the corresponding [Dataloader](#dataloader).

##### Defining the Dataloader

Define what [Dataloader](#dataloader) to use, which is the object in charge of fetching data from the database, through function `getDataloader`:

```php
function getDataloader($component) 
{
  switch ($component->name) {
    case self::COMPONENT_AUTHORARTICLES:
      return [Dataloader::class, Dataloader::DATALOADER_POSTLIST];
  }
    
  return parent::getDataloader($component);
}
```

##### Defining the Query Args

Customize a query to filter data, which is passed to the Dataloader, through functions `getImmutableDataloadQueryArgs` and `getMutableonrequestDataloadQueryArgs`:

```php
protected function getImmutableDataloadQueryArgs($component, $props) 
{
  $ret = parent::getImmutableDataloadQueryArgs($component, $props);
  
  switch ($component->name) {
    case self::COMPONENT_AUTHORARTICLES:
      // 55: id of "Articles" category
      $ret['cat'] = 55;
      break;
  }

  return $ret;
}

protected function getMutableonrequestDataloadQueryArgs($component, $props) 
{
  $ret = parent::getMutableonrequestDataloadQueryArgs($component, $props);
  
  switch ($component->name) {
    case self::COMPONENT_AUTHORARTICLES:
    
      // Set the logged-in user id
      $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
      $ret['author'] = $cmsapi->getCurrentUserID();
      break;
  }

  return $ret;
}
```

<!--
##### Defining the Filter

The fetched data can be filtered through [Filter](#filter) objects, defined through function `getFilter`:

```php
function getFilter($component) 
{
  switch ($component->name) {
    case self::COMPONENT_AUTHORARTICLES:
          
      return GD_FILTER_AUTHORARTICLES;
  }
  
  return parent::getFilter($component);
}
```
-->

##### Defining the QueryInputOutputHandler

After fetching data, we can communicate state (eg: are there more results? what's the next paging number? etc) through [QueryInputOutputHandler](#queryhandler) objects, defined through function `getQueryhandler`. By default, it returns object with name `GD_DATALOAD_QUERYHANDLER_ACTIONEXECUTION`, needed when executing an operation (see section [Data-Posting and Operations](#data-posting-and-operations)):

```php
function getQueryhandler($component) 
{
  switch ($component->name) {
    case self::COMPONENT_AUTHORARTICLES:
      return GD_DATALOAD_QUERYHANDLER_LIST;
  }
  
  return parent::getQueryhandler($component);
}
```

##### Defining the Data Properties

If the component needs to pass a variable to any other object involved in fetching/processing data ([Dataloader](#dataloader), [QueryInputOutputHandler](#queryhandler), [ActionExecuter](#actionexecuter), etc), it can do so through "data properties", set through functions `getImmutableHeaddatasetcomponentDataProperties` and `getMutableonrequestHeaddatasetcomponentDataProperties`:

```php
function getImmutableHeaddatasetcomponentDataProperties($component, &$props) 
{
  $ret = parent::getImmutableHeaddatasetcomponentDataProperties($component, $props);

  switch ($component->name) {
    case self::COMPONENT_AUTHORARTICLES:
      // Make it not fetch more results
      $ret[GD_DATALOAD_QUERYHANDLERPROPERTY_LIST_STOPFETCHING] = true;
      break;
  }
  
  return $ret;
}
```

##### Conditionally Loading Database Data

We can instruct a dataloading component to not load its data simply by setting its prop `"skip-data-load"` to `true`:

```php
function initModelProps($component, &$props) 
{
  switch ($component->name) {
    case self::COMPONENT_AUTHORARTICLES:

      // Set the content lazy
      $this->setProp($component, $props, 'skip-data-load', true);
      break;
  }

  parent::initModelProps($component, $props);
}
```

> Note: Being a prop, this value can be set either by the dataloading component itself, or by any of its ancestor components.

Among others, the following are several uses cases for not loading the data for a component:

- Loading the Search page without any search parameter
- Validate that the logged-in user has the required permissions
- Load data not when loading the site, but only when loading a page in an SPA

#### Dataloading + Descendant Components

Starting from a dataloading component, and including itself, any descendant component can execute the functions described below: loading properties or "data fields" on the database object, and "switching domain" from the current database object to another one. 

##### Defining the Data-Fields

"Data fields", which are the properties to be required from the loaded database object, are defined through function `getLeafComponentFieldNodes`:

```php
function getLeafComponentFieldNodes($component, $props) 
{
  $ret = parent::getLeafComponentFieldNodes($component, $props);

  switch ($component->name) {
    case self::COMPONENT_AUTHORARTICLES:
      $ret[] = 'title';
      $ret[] = 'content';
      break;
  }

  return $ret;
}
```

The value for "data-fields" is resolved through an object called a [ObjectTypeFieldResolver](#fieldprocessor), described below.

##### Switching domain to a relational object

When defining what data to retrieve, we can also "switch domain", which is changing from the current database object to another one, defined as a relationship. 

Consider the image below: Starting from the object type "post", and moving down the component hierarchy, we will need to shift the DB object type to "user" and "comment", corresponding to the post's author and each of the post's comments respectively, and then, for each comment, it must change the object type once again to "user" corresponding to the comment's author. After switching to a new domain, from that level at the component hierarchy downwards, all required properties, or data-fields, will be subjected to the new domain: Property "name" is fetched from the "user" object representing the post's author, "content" from the "comment" object representing each of the post's comments, and then "name" from the "user" object representing the author of each comment:

![Changing the DB object from one domain to another](https://uploads.getpop.org/wp-content/uploads/2018/12/loading-data-at-intervals-relational.jpg)

Switching domins is accomplished through function `getRelationalComponentFieldNodes`. It must return an array, in which each key is the property, or "data-field", containing the ID of the object to switch to, and its value is another array, in which the key is the [Dataloader](#dataloader) to use to load this object, and its values are the components to use:

```php
function getRelationalComponentFieldNodes($component) 
{
  $ret = parent::getRelationalComponentFieldNodes($component);

  switch ($component->name) {
    case self::COMPONENT_AUTHORARTICLES:
    
      $ret['author'] = [
        GD_DATALOADER_USERLIST => [
          COMPONENT_AUTHORNAME,
        ]
      ];
      $ret['comments'] = [
        GD_DATALOADER_COMMENTLIST => [
          COMPONENT_COMMENTLAYOUT,
        ]
      ];
      break;
  }

  return $ret;
}
```

> Note: Similar to `getComponents`, this method also loads components into the component hierarchy, hence it cannot receive parameter `$props`.

Alternatively, instead of explicitly defining the name of the dataloader, we can also select the default dataloader defined for that field through constant `POP_CONSTANT_SUBCOMPONENTDATALOADER_DEFAULTFROMFIELD`, which are defined through the [ObjectTypeFieldResolver](#ObjectTypeFieldResolver). In the example below, the default dataloaders for fields `"author"` and `"comments"` will be automatically selected:

```php
function getRelationalComponentFieldNodes($component) 
{
  $ret = parent::getRelationalComponentFieldNodes($component);

  switch ($component->name) {
    case self::COMPONENT_AUTHORARTICLES:
    
      $ret['author'] = [
        POP_CONSTANT_SUBCOMPONENTDATALOADER_DEFAULTFROMFIELD => [
          new \PoP\ComponentModel\Component\Component(SomeComponentProcessor::class, SomeComponentProcessor::COMPONENT_AUTHORNAME),
        ]
      ];
      $ret['comments'] = [
        POP_CONSTANT_SUBCOMPONENTDATALOADER_DEFAULTFROMFIELD => [
          new \PoP\ComponentModel\Component\Component(SomeComponentProcessor::class, SomeComponentProcessor::COMPONENT_COMMENTLAYOUT),
        ]
      ];
      break;
  }

  return $ret;
}
```

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

- `getFieldprocessor`: return the name of the [ObjectTypeFieldResolver](#fieldprocessor) that will handle the data-fields for all objects returned by the dataloader
- `getDatabaseKey`: return the object type under which objects returned by the dataloader will be stored under `databases` in the JSON response

For instance, a [dataloader fetching posts](https://github.com/GatoGraphQL/GatoGraphQL/blob/master/pop-cmsmodel/library/dataload/dataloaders/dataloader-post-base.php) will implement these functions like this:

```php
function getDatabaseKey() 
{
  return GD_DATABASE_KEY_POSTS;
}  

function getFieldprocessor() 
{
  return GD_DATALOAD_FIELDPROCESSOR_POSTS;
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

Dataloaders fetching lists of results (eg: a list of posts, a list of users, etc) will need to execute a query and filter the results. This logic has been implemented in trait `Dataloader_ListTrait`, which requires to implement functions `getQuery` to generate the query from the `$query_args` provided through [Data Properties](#data-properties), and `executeQueryIDs` to, given the generated `$query`, return the list of object IDs:

```php
function getQuery($query_args) 
{
  $query = array();

  // Add all the conditions in $query, taking values from $query_args
  // ...

  return $query;
}

function executeQueryIDs($query) {
    
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

function executeQueryIDs($query) {
    
  $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
  return $cmsapi->getPosts($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
}  
```

### ObjectTypeFieldResolver

The ObjectTypeFieldResolver is the object resolving "data-fields" to their corresponding value. It must inherit from class `AbstractObjectTypeFieldResolver`, and implement function `getValue`, which receives two parameters, `$resultitem` which is the database object, and `$field` which is the data-field to resolve, and must return the value for that property applied to the database object. 

> Note: the names of fields cannot include the following special characters: "," (`\PoP\ComponentModel\Tokens\Param::VALUE_SEPARATOR`), "." (`POP_CONSTANT_DOTSYNTAX_DOT`) or "|" (`POP_CONSTANT_PARAMFIELD_SEPARATOR`)

For instance, a ObjectTypeFieldResolver for posts looks like this:

```php
class ObjectTypeFieldResolver_Posts extends \PoP\Engine\AbstractObjectTypeFieldResolver {

  function getValue($resultitem, $field) {
  
    // First Check if there's a ObjectTypeFieldResolverExtension to implement this field
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
        $value = $cmsapi->getCustomPostTags($this->getId($post), [], array('fields' => 'ids'));
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
          $value[] = $cmsresolver->getCommentID($comment);
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

The ObjectTypeFieldResolver also allows to select the default dataloader to process a specific field through function `getFieldDefaultDataloader`. This feature is required for [switching domain](#Switching-domain-to-a-relational-object) through function `getRelationalComponentFieldNodes` and deciding to not explicitly indicate the dataloader to use to load relationships, but use the default one for that field instead. For instance, for the fieldprocessor for posts, it is implemented like this:

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

#### ObjectTypeFieldResolverExtension

A ObjectTypeFieldResolverExtension is an object that allows to resolve data-fields for specific ObjectTypeFieldResolvers, either to override their value or to extend them. For instance, it can be implemented at the application level, resolving those application-specific data fields. It must inherit from class `ObjectTypeFieldResolver_HookBase` and implement function `getValue`, which receives three parameters, `$resultitem` which is the database object, `$field` which is the data-field to resolve, and `$fieldprocessor` which is the ObjectTypeFieldResolver object hooked into, and must return the value for that property applied to the database object. 

For instance, a ObjectTypeFieldResolverExtension for posts might add a custom "disclaimer" message, and it looks like this:

```php
class ObjectTypeFieldResolver_Posts_Hook extends \PoP\Engine\ObjectTypeFieldResolver_HookBase {

  function getClassesToAttachTo() {
    
    return array(
      [ObjectTypeFieldResolver::class, ObjectTypeFieldResolver::FIELDPROCESSOR_POSTS],
    );
  }

  function getValue($resultitem, $field, $fieldprocessor) 
  {
    $post = $resultitem;
    switch ($field) 
    {
      case 'disclaimer':
        return \PoP\Engine\MetaManager::getCustomPostMeta($fieldprocessor->getId($post), "disclaimer", true);
    }

    return parent::getValue($resultitem, $field, $fieldprocessor);
  }  
}
```

### Filtering Data

By implementing the interface `DataloadQueryArgsFilter` components can also filter the data fetched by the ancestor dataloading component. For that, they must implement functions `filterDataloadQueryArgs`, to filter the query for some property, and `getValue`, to provide the corresponding value. For instance, a component that performs a search of content looks like this (notice that since it extends from `TextFormInputsBase`, its `getValue` function is already implemented by class `FormInputsBase`):

```php
class TextFilterInputs extends TextFormInputsBase implements \PoP\ComponentModel\DataloadQueryArgsFilter
{
  public function filterDataloadQueryArgs(array &$query, $component, $value)
  {
    switch ($component->name) 
    {
      case self::COMPONENT_FILTERINPUT_SEARCH:
        $query['search'] = $value;
        break;
    }
  }
}
```

### QueryInputOutputHandler

The QueryInputOutputHandler is an object that synchronizes the state of the query between client and server. It must inherit from class `QueryInputOutputHandlerBase` and implement the following functions:

Before fetching data from the database, function `prepareQueryArgs` populates the `$query_args` object used passed to the dataloader to fetch data. It can get values from the request (eg: set through the application in the client) or define default values.

After fecthing data from the database, functions `getQueryState`, `getQueryParams` and `getQueryResult`, all of them receiving parameters `$data_properties, $checkpoint_validation, $executed, $objectIDs`, send information about the executed query back to the client: state values (eg: are there more results?), parameter values (eg: how many results to bring each time) and result values (eg: was execution successful?) correspondingly.

### ActionExecuter

In addition to loading data, "dataloading" components can also post data, or execute any operation supported by the underlying CMS (log in/out the user, send emails, logging, etc).

To achieve this, the ComponentProcessor must define the ActionExecuter object for the component through function `getActionExecuterClass`:

```php
function getActionExecuterClass($component) {
  
  switch ($component->name) {
    case self::COMPONENT_SOMENAME:
  
      return SomeActionExecuter::class;
  }

  return parent::getActionExecuterClass($component);
}
```

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
    if ('POST' == \PoP\Root\App::server('REQUEST_METHOD')) { 

      // If the user is not logged in, then return the error
      $vars = \PoP\ComponentModel\Engine_Vars::getVars();
      if (!$vars['global-userstate']['is-user-logged-in']) 
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

The results obtained in function `execute` can be stored for other objects (ComponentProcessors, ActionExecuters) to use and base their logic upon them. For instance, a component is able to load data or not depending on the success or not of an execution.

Storing and accessing the execution results is done through function `setResult` and `getResult` from the `ActionExecution_Manager` object. For instance, an ActionExecuter to create a comment will store the new comment ID:

```php
function execute(&$data_properties) 
{
  if ('POST' == \PoP\Root\App::server('REQUEST_METHOD')) 
  {
    // Function getFormData obtains the filled-in values in the form
    $form_data = $this->getFormData();

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

A ComponentProcessor can modify what data it will fetch from the database through function `prepareDataPropertiesAfterMutationExecution`, which is invoked after executing the component's corresponding ActionExecuter. For instance, after creating a comment, we can load it immediately or, if the creation was not successful, state to skip loading any database object:

```php
function prepareDataPropertiesAfterMutationExecution($component, &$props, &$data_properties) {
    
  parent::prepareDataPropertiesAfterMutationExecution($component, $props, $data_properties);

  switch ($component->name) {
    case self::COMPONENT_ADDCOMMENT:

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

### Checkpoints

A "checkpoint" is a condition that must be satisfied when performing an operation-access validation. These validations do not include content validations, such as checking if the user has filled-in the form correctly; instead, they are used to find out if the user can access a certain page or functionality, such as checking if the user is logged in to access the user account page, checking if the user IP has been whitelisted to execute special scripts, etc.

Components can specify their checkpoints through 2 functions in the ComponentProcessor:

- `getDataAccessCheckpoints`: Define the checkpoints to access data for the component: both load data or execute the component's actionexecuter
- `getActionExecutionCheckpoints`: Define the checkpoints to execute the component's actionexecuter

The reason why these 2 functions are split like is, is to allow a page perform the validation only when posting data. Then, an "Add Post" page can require no checkpoints when first loaded, which enables to cache it, and only perform the validation (eg: is user logged in?) when executing the POST operation and triggering the actionexecuter.

For instance, a component that needs to validate that the user's IP is whitelisted can do it like this:

```php
function getDataAccessCheckpoints($component, &$props) 
{
  switch ($component->name) {
    case self::COMPONENT_SOMECOMPONENT:
    
      return [CHECKPOINT_WHITELISTEDIP];
  }
  
  return parent::getDataAccessCheckpoints($component, $props);
}
```

Pages can also be assigned checkpoints through their [SettingsProcessor](#settingsprocessor). Whenever a component is directly associated with a page (eg: component `COMPONENT_MYPOSTS_SCROLL` is directly associated to `POP_PAGE_MYPOSTS`) then it is assigned the checkpoints associated with that page. Associating a component with a page is done through function `getRelevantPage` from the ComponentProcessor, like this:

```php
function getRelevantPage($component, &$props) {
    
  switch ($component->name) {
    case self::COMPONENT_MYPOSTS_SCROLL:
    case self::COMPONENT_MYPOSTS_CAROUSEL:
    case self::COMPONENT_MYPOSTS_TABLE:

      return POP_PAGE_MYPOSTS;
  }

  return parent::getRelevantPage($component, $props);
}
```

A checkpoint is resolved through a [Checkpoint](#checkpoint).

#### Checkpoint

A Checkpoint is an object inheriting from class `AbstractCheckpoint`, which handles checkpoints, resolving if a checkpoint is satisfied or not through function `process`. When a checkpoint is not satisfied, it must thrown an error. Otherwise, the base class will eventually return `true`, signalling that the validation is satisfied.

For instance, to validate if the user IP is whitelisted can be implemented like this:

```php

class WhitelistedIPCheckpoint extends \PoP\Engine\AbstractCheckpoint {

  function validateCheckpoint() 
  {
    // Validate the user's IP
    $ip = get_client_ip();
    if (!$ip) {          
      return new \PoP\ComponentModel\Error\Error('ipempty');
    }

    $whitelisted_ips = array(...);
    if (!in_array($ip, $whitelisted_ips)) {      
      return new \PoP\ComponentModel\Error\Error('ipincorrect');
    }
  
    return parent::validateCheckpoint();
  }
}
```

### Extra URIs

Will be added soon...

### DataStructureFormatter

Will be added soon...

### ComponentDecoratorProcessor

Will be added soon...

### ComponentFilter

Will be added soon...

### Context Vars

It is a global variable, hosted under `PoP_ComponentManager_Vars::$vars`, accessed through `PoP_ComponentManager_Vars::getVars`, and naturally referred as `$vars`, which holds important information needed to process the webpage. Properties in `$vars` are those which are accessed widely throughout the application, and which, upon changing their value, alter the component hierarchy.

**1. Properties which are accessed widely throughout the application**

`$vars` acts as a single, central repository of information, where properties can be calculated just once or initialized with default values, and promoting consistency, by providing a unique place from where to fetch a certain value from anywhere in the application.

For instance, property `output`, which is obtained through `$_GET["output"]` and accepts values `"HTML"` or `"JSON"`, is accessed through `$vars['output']`, and is initialized to value `"HTML"` if `$_GET["output"]` is empty.

**2. Properties which, upon changing their value, alter the component hierarchy**

Changing the values of certain properties will alter the component hierarchy. For instance, passing parameter `componentFilter=componentpaths` in the URL will make the component hierarchy only include those components specified under parameter `componentpaths[]`. Hence, property `componentFilter` must be in `$vars`.

Keeping these properties in `$vars` is needed for the following reasons:

_1. To calculate the `modelInstanceId`:_ the `modelInstanceId` is the unique identifier representing the particular instance of the component hierarchy. This id is calculated by function `ModelInstanceProcessor_Utils::getModelInstanceID()`, which simply calculates a hash of the values of all properties which alter the component hierarchy. Because not all properties in `$vars` alter the component hierarchy, these ones must be defined by implementing hook `"ModelInstanceProcessor:model_instance_components"`.

_2. To determine the entry component_: The component hierarchy's top-most component is called the entry component. Every potential entry component must define a list of conditions, to be evaluated against `$vars`, that need be satisfied to be chosen the entry component (more on this under [PageComponentProcessors](#pagecomponentprocessor)).

_3. To decouple processed page from requested page_: Storing all properties which modify the component hierarchy under `$vars`, making sure that these properties are only accessed through `$vars` all throughout the application, and then modifying these values directly in `$vars`, makes it possible to manipulate the response, for instance adding more data. This way, it is possible to fetch more than one page's content on a single request (for preloading views to cache on the client or other use cases), or send personalized transactional emails to many users on a single request, among other use cases.

#### Setting properties in `$vars`

When first accessed, `$vars` is initialized with certain current request values, such as:

- Hierarchy (home, single, page, author, etc)
- Output (HTML, JSON, etc)
- Component filter, if any
- Mangled output?
- The queried object (the post object in single hierarchy, the user object in author hierarchy, etc)
- Others

Plugins must add their own properties and corresponding values in `$vars` by implementing hook `"\PoP\ComponentModel\Engine_Vars:add_vars"`. `$vars` can be `reset` at any moment and filled with different values, for instance to process a different request.

### PageComponentProcessor

Will be added soon...

### SettingsProcessor

Will be added soon...

### Component Model Cache

Will be added soon...

## PHP versions

Requirements:

- PHP 8.1+ for development
- PHP 7.2+ for production

### Supported PHP features

Check the list of [Supported PHP features in `GatoGraphQL/GatoGraphQL`](https://github.com/GatoGraphQL/GatoGraphQL/blob/master/docs/supported-php-features.md)

### Preview downgrade to PHP 7.2

Via [Rector](https://github.com/rectorphp/rector) (dry-run mode):

```bash
composer preview-code-downgrade
```

## Standards

[PSR-1](https://www.php-fig.org/psr/psr-1), [PSR-4](https://www.php-fig.org/psr/psr-4) and [PSR-12](https://www.php-fig.org/psr/psr-12).

To check the coding standards via [PHP CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer), run:

``` bash
composer check-style
```

To automatically fix issues, run:

``` bash
composer fix-style
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

To execute [PHPUnit](https://phpunit.de/), run:

``` bash
composer test
```

## Static Analysis

To execute [PHPStan](https://github.com/phpstan/phpstan), run:

``` bash
composer analyse
```

## Report issues

To report a bug or request a new feature please do it on the [GatoGraphQL monorepo issue tracker](https://github.com/GatoGraphQL/GatoGraphQL/issues).

## Contributing

We welcome contributions for this package on the [GatoGraphQL monorepo](https://github.com/GatoGraphQL/GatoGraphQL) (where the source code for this package is hosted).

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email leo@getpop.org instead of using the issue tracker.

## Credits

- [Leonardo Losoviz][link-author]
- [All Contributors][link-contributors]

## License

GNU General Public License v2 (or later). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/getpop/component-model.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-GPLv2-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/getpop/component-model/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/getpop/component-model.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/getpop/component-model.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/getpop/component-model.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/getpop/component-model
[link-travis]: https://travis-ci.org/getpop/component-model
[link-scrutinizer]: https://scrutinizer-ci.com/g/getpop/component-model/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/getpop/component-model
[link-downloads]: https://packagist.org/packages/getpop/component-model
[link-author]: https://github.com/leoloso
[link-contributors]: ../../../../../../contributors
