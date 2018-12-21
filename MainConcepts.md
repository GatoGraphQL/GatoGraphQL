![PoP](https://assets.getpop.org/wp-content/themes/getpop/img/pop-logo-horizontal.png)

# Main Concepts

## What is PoP?

PoP — “Platform of Platforms” is a framework for building component-based websites, which spans both frontend and backend. It provides a data + configuration API, on top of which can stack layers adding rendering and functionalities, progressively building complex sites. 

PoP can be divided into 2 entities:

1. **The component-based API open specification**, which can be implemented through any platform or technology
2. **The current implementation of the specification**, done in PHP, wrapping WordPress

## Stacked on top of a CMS

PoP handles the application logic and rendering, but relies on a Content Management System for system logic, such as routing, saving and loading data to/from the database, user management, etc. The application can be made CMS-agnostic by accessing all functionality through interfaces, and deployed on top of any supported CMS.

![PoP is a layer wrapping a CMS](https://uploads.getpop.org/wp-content/uploads/2018/12/cms-wrapper.png)

## Everything is a Module

The term "component" is often used to describe the concept of [encapsulating a set of related functions](https://en.wikipedia.org/wiki/Component-based_software_engineering#Software_component) for building modular applications. In PoP, a component is called a "module", so from now on the names "component" and "module" are used interchangeably. 

A module is simply a set of pieces of HTML, JavaScript and CSS code put all together to create an autonomous entity. Each module can be an atomic functionality, a composition of other modules, or a combination of the two. Each module has a purpose, which can range from something very basic, such as a link or a button, to something very elaborate, such as a carousel or a drag-and-drop image uploader. 

The relationship among modules is defined on a strictly top-down fashion: a module wraps other modules and knows who they are, but it doesn't know, and doesn't care, which modules are wrapping him. Ever more complex modules are created by iteratively wrapping simpler modules, until reaching the top-most module representing the webpage:

![Sequence of modules wrapping modules wrapping modules, from an avatar all the way up to the webpage](https://uploads.getpop.org/wp-content/uploads/2018/12/module-sequence.gif)

In PoP, everything is a module:

![In PoP, everything is a module](https://uploads.getpop.org/wp-content/uploads/2018/12/everything-is-a-module.jpg)

The relationship of all modules wrapping each other, from the top-most module all the way down to the last level, is called the component hierarchy. The PoP API has the component hierarchy at its core, implemented as an associative array on the server-side, in which each module states its name as the key attribute and whatever properties it needs as values, and then nests its descendant modules under property "modules", iteratively adding their own data and that of their own descendant modules. Finally, this associative array is returned as a JSON object for comsumption through the API:

```
{
  "topmost-module": {
    someprop: {...},
    modules: {
      "module-level1": {
        someprop: {...},
        someprop: {...},
        modules: {
          "module-level11": {
            ...
          }
        },
        modules: {
          "module-level12": {
            someprop: {...},
            modules: {
              "module-level121": {
                ...
              }
            }
          }
        }
      },
      "module-level2": {
        someprop: {...},
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

## Relational database data

PoP represents the database data in a relational manner, organized under each object type, object ID and object properties, mirroring the structure of the data in the database. This way, all data is normalized, fetched only once from the database, and printed only once in the output. It is added under entry `databases` in the API response:

```
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

```
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

Each module knows which are its queried objects from section `datasetmoduledata`, which provides the IDs of the queried objects under property `dbobjectids` (IDs 4 and 9 for the blog posts), and knows from where to retrieve the database object data from under section `databases` through section `modulesettings`, which indicates to what type each object belongs under property `dbkeys` (then, it knows that the post's author data, corresponding to the author with the ID given under property "author", is found under object type "users"):

```
{
  modulesettings: {
    "page" {
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
  datasetmoduledata: {
    "page" {
      modules: {
        "post-feed": {
          dbobjectids: [4, 9]
        }
      }
    }
  }
}
```

## The engine infers how to retrieve database data already from the component hierarchy

When a module displays a property from a DB object, the module may not know, or care, what object it is; all it cares about is defining what properties from the loaded object are required. For instance, consider the image below: a module loads an object from the database (in this case, a single post), and then its descendant modules will show certain properties from the object, such as "title" and "content":

![While some modules load the database object, others load properties](https://uploads.getpop.org/wp-content/uploads/2018/12/loading-data-at-intervals.jpg)

Hence, along the component hierarchy, some modules will be in charge of loading the queried objects (the module loading the single post, in this case), and its descendant modules will define what properties from the DB object are required ("title" and "content", in this case). 

Fetching all the required properties for the DB object can be done automatically by traversing the component hierarchy: starting from the data loading module, we iterate all its descendant modules all the way down until reaching a new data loading module, or until the end of the tree; at each level we obtain all required properties, and then merge all properties together and query them from the database, all of them only once. In the structure below, module "single-post" fetches the results from the DB, and submodules "post-title" and "post-content" define properties to be loaded for the queried DB object ("title" and "content" respectively); submodule "post-layout" does not require any data fields. Please notice how the executed query, which is calculated automatically from the component hierarchy and their required data fields, will contain all the properties needed by all the modules and their submodules:

```
"single-post"
  => Load objects from domain "post"
  modules
    "post-layout"
      modules
        "post-title"
          => Load property "title"
        "post-content"
          => Load property "content"
```

Which will result in the following (pseudo-)query:

```
SELECT 
  title, content 
FROM 
  posts 
WHERE 
  id = 37 
```

The query to fetch data from the database is automatically updated whenever the component hierarchy changes. If we then add submodule "post-thumbnail", which requires data field "thumbnail", under "single-post":

```
"single-post"
  => Load objects from domain "post"
  modules
    "post-layout"
      modules
        "post-title"
          => Load property "title"
        "post-content"
          => Load property "content"
        "post-thumbnail"
          => Load property "thumbnail"
```

Then the query is automatically updated with the new data:

```
SELECT 
  title, content, thumbnail 
FROM 
  posts 
WHERE 
  id = 37 
```

This strategy also applies to relational objects. Consider the image below: Starting from the object domain "post", we need to change the DB object domain to entities "user" and "comment", corresponding to the post's author and each of the post's comments respectively, and then, for each comment, it must change the domain once again to "user", for the comment's author. After changing from one to another domain, from that level at the component hierarchy downwards, all required properties will be subjected to the new domain: Property "name" is fetched from the "user" object representing the post's author, "content" from the "comment" object representing each of the post's comments, and then "name" from the "user" object representing the author of each comment:

![Changing the DB object from one domain to another](https://uploads.getpop.org/wp-content/uploads/2018/12/loading-data-at-intervals-relational.jpg)

Going back to our previous example, if we need to show data from the post's author, stacking submodule "post-author" will change the domain at that level from "post" to the corresponding "user", and from this level downwards the DB object loaded into the context passed to the module is the user. Then, submodules "user-name" and "user-avatar" under "post-author" will load properties "name" and "avatar" under the user object:

```
"single-post"
  => Load objects from domain "post"
  modules
    "post-layout"
      modules
        "post-title"
          => Load property "title"
        "post-content"
          => Load property "content"
        "post-author"
          => Change object domain from "post" to "user", based on property "author"
          modules
            "user-layout"
              modules
                "user-name"
                  => Load property "name"
                "user-avatar"
                  => Load property "avatar"

```

Resulting in the following query:

```
SELECT 
  p.title, p.content, p.author, u.name, u.avatar 
FROM 
  posts p 
INNER JOIN 
  users u 
WHERE 
  p.id = 37 AND p.author = u.id 
```

## Configuration values included, overridable through props

Instead of hardcoding classnames or other properties such as a title's HTML tag or an avatar max width inside of JavaScript files for rendering in the client, we can pass configuration values already through the API, so that then these can be directly updated on the server and without the need to redeploy JavaScript files:

```
{
  modulesettings: {
    "module1" {
      modules: {
        "module2": {
          configuration: {
            class: "whoweare text-center",
            title: "Who we are",
            titletag: "h3"
          },
          modules: {
            "module3": {
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
              modules: {
                "module4": {
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

Configuration values can be set through props, defined across the component hierarchy so that modules can modify the behavior of their descendant modules, and where higher-level modules have priority for setting a prop. Setting props works in one direction only: parent modules can set props on any descendant module, but no module can set props on any ancestor module or on any module belonging to a different branch from the component hierarchy. In the example below, "module1" can set props on "module2", "module3" and "module4", "module2" on "module3", and "module3" and "module4" on nobody:

```
"module1"
  modules
    "module2"
      modules
        "module3"
    "module4"
```

Let's say we have a module "module3" with property "color", set as "red" by default. By not specifying a module target of the prop, the module in the pseudo-code below is setting a prop on itself:

```
Module("module3")->set_prop({
  prop: "color",
  value: "red"
});
```

Through property "modulepath", a module can target any of its descendant modules any number of levels deep from itself. In the pseudo-code below, parent module "module2" adds property "modulepath" pointing to submodule "module3", overriding its value for property "color" to "green":

```
Module("module2")->set_prop({
  modulepath: ["module3"],
  prop: "color",
  value: "green"
});
```

And this can go on for any number of ancestor modules. For instance, in the pseudo-code below, the "module1" module, which is parent to "module2", can further override the value for property "color" applied to module "module3":

```
Module("module1")->set_prop({
  modulepath: ["module2", "module3"],
  prop: "color",
  value: "blue"
});
```

Module "module1" can set a property on module "module3", which is 2 levels below ("module1" => "module2" => "module3"), directly, without having to retransmit the information through the modules in between (in this case through "module2"). Hence, the API allows to set props by skipping the modules in between, i.e. not every module down a path must convey the prop value until it reaches its destination, and the prop value will be set on the context of the destination module and not pollute the contexts of the modules in between.

Armed with these capabilities, the API allows for powerful customization of components, which enables to produce a wide array of layouts and functionalities for different use cases. For instance, in the image below a component `<ShareButtons>` is embedded twice, printing descriptions ("Facebook", "Twitter", etc) or not just by setting property "show-description" as `true` or `false`:

![A module can be customized through props](https://uploads.getpop.org/wp-content/uploads/2018/12/configuration-props.jpg)

And in the image below, a module can be rendered in three different fashions simply by overriding what classes are printed in the module: 

```
// Layout on the left uses default configuration of thumbnail on top of the text
Module("post-layout")->set_prop({
  prop: "classes",
  value: {
    wrapper: "",
    thumb: "",
    contentbody: ""
  }
});

// Layout on the center display a big thumnail to the left of the text
Module("central-section")->set_prop({
  modulepath: ["post-layout"],
  prop: "classes",
  value: {
    wrapper: "row",
    thumb: "col-sm-4",
    contentbody: "col-sm-8"
  }
});

// Layout on the floating window display a small thumnail to the left of the text
Module("floating-window")->set_prop({
  modulepath: ["post-layout"],
  prop: "classes",
  value: {
    wrapper: "media",
    thumb: "media-left",
    contentbody: "media-body"
  }
});
```

![A module can be rendered in multiple fashions](https://uploads.getpop.org/wp-content/uploads/2018/12/configuration-layouts.jpg)

## The webpage is its own API endpoint

PoP will issue only one request to fetch all the data for all components in the page, normalizing the database data for all results. The API endpoint to be called is simply the same as the webpage URL for which we are fetching the data, just adding an additional parameter `output=json` to indicate to bring the data in JSON format instead of printing it as HTML:

```
GET - /url-of-the-page/?output=json
```

## The module is its own API

Instead of fetching the data for all modules in a page, we can also fetch the data for a module, for any module at any level of the component hierarchy: wherever the module is placed, it can interact with itself from client to server just by adding its module path to the page URL in which it has been included. This way, when creating a component, we don't need to create an API to go alongside with it (such as REST or GraphQL), because the module is already able to talk to itself in the server and load its own data: it is completely autonomous and self-serving. 

This is accomplished by allowing to select what module paths (i.e. the path to a specific module starting from the top-most module) will be included in the response, so as to load data only starting from that level, and ignore anything above that level. This is done through adding parameters `modulefilter=modulepaths` and `modulepaths[]=path-to-the-module` to the URL (we use `modulepaths[]` instead of `modulepaths` for versatility, so that we can include more than one module path in a single request). The value for the `modulepaths[]` parameter is a list of modules separated by dots. Hence, fetching data for module "module5", located under `module1 => module2 => module5`, is done by adding parameter `modulepaths[]=module1.module2.module5` to the URL. 

For instance, in the following module hierarchy every module is loading data, hence every level has an entry `dbobjectids`:

```
"module1"
  dbobjectids: [...]
  modules
    "module2"
      dbobjectids: [...]
      modules
        "module3"
          dbobjectids: [...]
        "module4"
          dbobjectids: [...]
        "module5"
          dbobjectids: [...]
          modules
            "module6"
              dbobjectids: [...]
```

Then requesting the webpage URL adding parameters `modulefilter=modulepaths` and `modulepaths[]=module1.module2.module5` will produce the following response:

```
"module1"
  modules
    "module2"
      modules
        "module5"
          dbobjectids: [...]
          modules
            "module6"
              dbobjectids: [...]
```

In essence, the API starts loading data starting from module1 => module2 => module5, that's why "module6", which comes under "module5", also brings its data, but "module3" and "module4" do not.

Each module that loads data exports the URL to interact with it under entry `dataloadsource` from under section `datasetmodulemeta`:

```
{
  datasetmodulemeta: {
    "module1" {
      modules: {
        "module2": {
          modules: {
            "module5":  {
              meta: {
                dataloadsource: "https://page-url/?modulefilter=modulepaths&modulepaths[]=module1.module2.module5"
              },
              modules: {
                "module6": {
                  meta: {
                    dataloadsource: "https://page-url/?modulefilter=modulepaths&modulepaths[]=module1.module2.module5.module6"
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

## Multidomain

PoP has been built to support decentralization: modules can fetch their data from a different domain/subdomain from which the application is hosted. For instance, an application can have its components retrieved from subdomains:

![Modules can have their data fetched from different domains and subdomains](https://uploads.getpop.org/wp-content/uploads/2017/02/site-wireframe.png)

A single component is also able to have many sources of data, each of them coming from a different domain/subdomain. For instance, the [events calendar in SukiPoP.com](https://sukipop.com/en/calendar/) displays events from several external sites in a unique calendar, painting events with a different color according to the source domain:

![Multidomain events calendar](https://uploads.getpop.org/wp-content/uploads/2018/12/multidomain-events-calendar.png)

## Reactivity

Coming soon...

