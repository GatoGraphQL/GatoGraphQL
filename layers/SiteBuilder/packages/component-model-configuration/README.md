# Component Model Configuration

<!--
[![Build Status][ico-travis]][link-travis]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Software License][ico-license]](LICENSE.md)
[![Latest Version on Packagist][ico-version]][link-packagist]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Total Downloads][ico-downloads]][link-downloads]
-->

Adds the configuration level to the component hierarchy, through which the data API can be extended into an application

## Install

Via Composer

``` bash
composer require getpop/component-model-configuration
```

## Development

The source code is hosted on the [PoP monorepo](https://github.com/leoloso/PoP), under [`SiteBuilder/packages/component-model-configuration`](https://github.com/leoloso/PoP/tree/master/layers/SiteBuilder/packages/component-model-configuration).

## Usage

Initialize the component:

``` php
\PoP\Root\App::stockAndInitializeComponentClasses([([
    \PoP\ConfigurationComponentModel\Component::class,
]);
```

## Architecture Design and Implementation

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

### Client-Side Caching

To cache the configuration for all modules in the client, and keep them available to be reused, we simply deep merge all the responses together. For instance, if the first request brings this response:

```javascript
{
  "module1": {
    configuration: {
      class: "topmodule"
    },
    modules: {
      "module2": {
        configuration: {
          class: "some-class"
        }
      }
    }
  }
}
```

And the second response brings this response:

```javascript
{
  "module3": {
    configuration: {
      class: "topmodule"
    },
    modules: {
      "module4": {
        configuration: {
          class: "another-class"
        }
      }
    }
  }
}
```

Then deep merging the responses together will result in:

```javascript
{
  "module1": {
    configuration: {
      class: "topmodule"
    },
    modules: {
      "module2": {
        configuration: {
          class: "some-class"
        }
      }
    }
  },
  "module3": {
    configuration: {
      class: "topmodule"
    },
    modules: {
      "module4": {
        configuration: {
          class: "another-class"
        }
      }
    }
  }
}
```

And we can perfectly reuse the configuration starting from "module1" to reprint the first request, and the configuration starting from "module3" to reprint the second request.

That was easy, however from now on it gets more complicated. What happens if the module's descendants are not static, but can change depending on the context, such as the requested URL or other inputs? For instance, we could have a module "single-post" which changes its descendant module based on the post type of the requested object, choosing between modules "layout-post" or "layout-event", so that the component hierarchy alternates from this:

```javascript
"single-post"
  modules
    "layout-post"
```

to this:

```javascript
"single-post"
  modules
    "layout-event"
```

Similarly, even within the same component hierarchy, a module could have a property value change for different URLs. For instance, a module "post-layout" can have a property "class" with value "post-{id}", where "{id}" is the id of the requested post, so that we can add styles for specific posts such as `.post-37 { background-color: red; }` and `.post-224 { background-color: green; }`. Then, posts with ids 37 and 224, even though they have the same component hierarchy, their configurations will alternate from this:

```javascript
"single-post"
  modules
    "layout-post"
      configuration
        class: "post-37"
```

to this:

```javascript
"single-post"
  modules
    "layout-post"
      configuration
        class: "post-224"
```

Let's explore what happens in these two situations described above when deep merging the results. In the first case, for instance, if the first request brings this response:

```javascript
{
  "module1": {
    configuration: {
      class: "topmodule"
    },
    modules: {
      "module2": {
        configuration: {
          class: "some-class"
        }
      }
    }
  }
}
```

And the second response brings this response:

```javascript
{
  "module1": {
    configuration: {
      class: "topmodule"
    },
    modules: {
      "module3": {
        configuration: {
          class: "another-class"
        }
      }
    }
  }
}
```

Then deep merging the responses together will result in:

```javascript
{
  "module1": {
    configuration: {
      class: "topmodule"
    },
    modules: {
      "module2": {
        configuration: {
          class: "some-class"
        }
      },
      "module3": {
        configuration: {
          class: "another-class"
        }
      }
    }
  }
}
```

As it can be seen, after merging the response from the second request, the data that was the same (`class: "topmodule"`) didn't affect the merged object, and the new information was appended to the existing object but without overriding any data. However, originally the first response has "module1" with only "module2" as a descendant, but after the merge, "module1" has two descendants, "module2" and "module3". Then, if loading again the URL for the first response and reusing the cached configuration, below "module1" it will print "module2" and "module3" instead of only "module2" as it should be.

To address this issue, the configuration can add a property "descendants" explicitly declaring which are its submodules, to know which modules must be rendered and ignore the rest, even though their data is still part of the merged JSON object. Then, the first and second response will look like this:

```javascript
{
  "module1": {
    configuration: {
      class: "topmodule",
      descendants: ["module2"]
    },
    modules: {
      "module2": {
        configuration: {
          class: "some-class"
        }
      }
    }
  }
}

{
  "module1": {
    configuration: {
      class: "topmodule",
      descendants: ["module3"]
    },
    modules: {
      "module3": {
        configuration: {
          class: "another-class"
        }
      }
    }
  }
}
```

And the merged configuration will look like this:

```javascript
{
  "module1": {
    configuration: {
      class: "topmodule",
      descendants: ["module3"]
    },
    modules: {
      "module2": {
        configuration: {
          class: "some-class"
        }
      },
      "module3": {
        configuration: {
          class: "another-class"
        }
      }
    }
  }
}
```

But now, the value for property "descendants" in the cached object has been overriden with the value from the second response, bringing us to the second issue stated earlier on about differing property values. Then, if loading again the URL for the first response and reusing the cached configuration, below "module1" it will print "module3" instead of "module2" as it should be.

The issue about differing properties arises from the fact that configuration values are set not only according to the component hierarchy, but also to the requested URL. For instance, the following component hierarchy:

```javascript
"single-post"
  modules
    "layout-post"
```

Can produce the following two different configuration outputs:

```javascript
"single-post"
  modules
    "layout-post"
      configuration
        class: "post-37"
```

and 

```javascript
"single-post"
  modules
    "layout-post"
      configuration
        class: "post-224"
```

The solution is to deep merge the configurations from different requests without overriding differing properties, either at the component hierarchy or URL levels, is to have the configuration split into 3 separate subsections: "immutable", "mutableonmodel" (where "model" is equivalent to "component hierarchy") and "mutableonrequest". Every property in the configuration must be placed under exactly 1 of the 3 sections, like this:

- **immutable:** Contains properties which never change, such as `class: "topmodule"`
- **mutableonmodel:** Contains properties which can change based on the component hierarchy, such as `descendants: ["module2"]`
- **mutableonrequest:** Contains properties which can change based on the requested URL, such as `class: "post-37"`

Following this scheme, a first request may produce the following response:

```javascript
{
  immutable: {
    "single-post": {
      configuration: {
        class: "topmodule"
      }
    }
  },
  mutableonmodel: {
    "single-post": {
      configuration: {
        descendants: ["layout-post"]
      }
    }
  },
  mutableonrequest: {
    "single-post": {
      modules: {
        "layout-post": {
          configuration: {
            class: "post-37"
          }
        }
      }
    }
  }
}
```

As it can be observed, because the properties from the 3 sections do not overlap, then merging the 3 sections for the request produces the whole configuration once again:

```javascript
{
  "single-post": {
    configuration: {
      class: "topmodule",
      descendants: ["layout-post"]
    },
    modules: {
      "layout-post": {
        configuration: {
          class: "post-37"
        }
      }
    }
  }
}
```

Next, the cache in the client is kept in 3 separate objects, one for each of the subsections, with sections "mutableonmodel" and "mutableonrequest" storing their data under appropriate keys: "mutableonmodel" under a key called "modelInstanceId", which represents a hash of the component hierarchy, and "mutableonrequest" under the requested URL. The request above will then be cached like this (assuming a "modelInstanceId" with value "bwKtq*8H" and URL "/posts/some-post/"):

```javascript
immutable => 
  {
    "single-post": {
      configuration: {
        class: "topmodule"
      }
    }
  }

mutableonmodel => 
  {
    "bwKtq*8H": {
      "single-post": {
        configuration: {
          descendants: ["layout-post"]
        }
      }
    }
  }

mutableonrequest => 
  {
    "/posts/some-post/": {
      "single-post": {
        modules: {
          "layout-post": {
            configuration: {
              class: "post-37"
            }
          }
        }
      }
    }
  }
```

If then we obtain the response for a second request, the cache is updated like this (assuming a "modelInstanceId" with value "6C7Lu$\3" and URL "/posts/some-event/"):

```javascript
immutable => 
  {
    "single-post": {
      configuration: {
        class: "topmodule"
      }
    }
  }

mutableonmodel => 
  {
    "bwKtq*8H": {
      "single-post": {
        configuration: {
          descendants: ["layout-post"]
        }
      }
    },
    "6C7Lu$\3": {
      "single-post": {
        configuration: {
          descendants: ["layout-event"]
        }
      }
    }
  }

mutableonrequest => 
  {
    "/posts/some-post/": {
      "single-post": {
        modules: {
          "layout-post": {
            configuration: {
              class: "post-37"
            }
          }
        }
      }
    },
    "/posts/some-event/": {
      "single-post": {
        modules: {
          "layout-event": {
            configuration: {
              class: "post-45"
            }
          }
        }
      }
    }
  }
```

As it can be observed, "immutable" holds the common parts of the structure, while "mutableonmodel" and "mutableonrequest" hold the deltas. Hence, this scheme identifies common data and stores it only once, and all dissimilar entries are stored and accessible on their own. If most of the configuration doesn't change within the component hierarchy, then the information stored under "immutable" will make the bulk of the stored information, succeeding in minimizing the amount of data that is cached.

Finally, given the "modelInstanceId" and URL for any request we can obtain the 3 separate branches from the 3 sections, and merge them all together to recreate the whole configuration from the cache. 

The merging can be done in the server-side too: If there is no need to cache the configuration on the client, then we can avoid the added complexity of dealing with the three subsections by adding parameter `dataoutputmode=combined` to the URL.

## PHP versions

Requirements:

- PHP 8.0+ for development
- PHP 7.1+ for production

### Supported PHP features

Check the list of [Supported PHP features in `leoloso/PoP`](https://github.com/leoloso/PoP/blob/master/docs/supported-php-features.md)

### Preview downgrade to PHP 7.1

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

To report a bug or request a new feature please do it on the [PoP monorepo issue tracker](https://github.com/leoloso/PoP/issues).

## Contributing

We welcome contributions for this package on the [PoP monorepo](https://github.com/leoloso/PoP) (where the source code for this package is hosted).

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email leo@getpop.org instead of using the issue tracker.

## Credits

- [Leonardo Losoviz][link-author]
- [All Contributors][link-contributors]

## License

GNU General Public License v2 (or later). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/getpop/component-model-configuration.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-GPLv2-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/getpop/component-model-configuration/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/getpop/component-model-configuration.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/getpop/component-model-configuration.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/getpop/component-model-configuration.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/getpop/component-model-configuration
[link-travis]: https://travis-ci.org/getpop/component-model-configuration
[link-scrutinizer]: https://scrutinizer-ci.com/g/getpop/component-model-configuration/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/getpop/component-model-configuration
[link-downloads]: https://packagist.org/packages/getpop/component-model-configuration
[link-author]: https://github.com/leoloso
[link-contributors]: ../../../../../../contributors
