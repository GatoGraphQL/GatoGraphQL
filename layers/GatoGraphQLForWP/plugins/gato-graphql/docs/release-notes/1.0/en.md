# Release Notes: 1.0

After a reaaaaaaally long time (spanning several years), development of the Gato GraphQL plugin has finally reached v1.0! Yaaaaaaaaaaay 🎉🍾🎊🥳🍻👏🥂🎈🙌

Starting from this version, the plugin will be released in the [WordPress plugins directory](https://wordpress.org/plugins/) (submission process currently ongoing), so you'll be able to install the plugin directly from your WordPress dashboard.

Version 1.0 brings plenty of improvements, including the **integration with the (Gutenberg) block editor**, and the **availability of (commercial) extensions** to extend the GraphQL schema and provide further functionality.

Here's a description of all the changes.

## Integration of Gutenberg blocks into the GraphQL schema

_This integration is heavily based on <a href="https://github.com/Automattic/vip-block-data-api/" target="_blank">`Automattic/vip-block-data-api`</a>. My deepest gratitude to this project's contributors, as their contribution has also benefitted this plugin. ❤️_

The newly-added "Blocks" module adds `Block` types to the GraphQL schema, retrieved via the following fields added to all `CustomPost` types (such as `Post` and `Page`):

- `blocks`
- `blockDataItems`
- `blockFlattenedDataItems`

This module is disabled if the [Classic Editor](https://wordpress.org/plugins/classic-editor/) plugin is active.

Below is a short summary of the 3 fields. Please check the [Blocks module documentation](https://github.com/leoloso/PoP/blob/351a79c7fb3fcdabf78c679e02661f4750b0f34d/layers/GatoGraphQLForWP/plugins/gato-graphql/docs/modules/schema-blocks/en.md) for a more thorough description.

### `blocks`

Field `CustomPost.blocks: [BlockUnion!]` retrieves the list of all the blocks contained in the custom post.

`blocks` returns a List of the Block types that have been mapped to the GraphQL schema. These Block types are all part of the `BlockUnion` type, and implement the `Block` interface.

The plugin implements one Block type, `GenericBlock`, which is already sufficient to retrieve the data for any block (via field `attributes: JSONObject`).

This query:

```graphql
{
  post(by: { id: 1 }) {
    blocks {
      ...on Block {
        name
        attributes
        innerBlocks {
          ...on Block {
              name
              attributes
              innerBlocks {
                ...on Block {
                  name
                  attributes
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

...will produce this response:

```json
{
  "data": {
    "post": {
      "blocks": [
        {
          "name": "core/gallery",
          "attributes": {
            "linkTo": "none",
            "className": "alignnone",
            "images": [
              {
                "url": "https://d.pr/i/zd7Ehu+",
                "alt": "",
                "id": "1706"
              },
              {
                "url": "https://d.pr/i/jXLtzZ+",
                "alt": "",
                "id": "1705"
              }
            ],
            "ids": [],
            "shortCodeTransforms": [],
            "imageCrop": true,
            "fixedHeight": true,
            "sizeSlug": "large",
            "allowResize": false
          },
          "innerBlocks": null
        },
        {
          "name": "core/heading",
          "attributes": {
            "content": "List Block",
            "level": 2
          },
          "innerBlocks": null
        },
        {
          "name": "core/list",
          "attributes": {
            "ordered": false,
            "values": "<li>List item 1</li><li>List item 2</li><li>List item 3</li><li>List item 4</li>"
          },
          "innerBlocks": null
        },
        {
          "name": "core/heading",
          "attributes": {
            "className": "has-top-margin",
            "content": "Columns Block",
            "level": 2
          },
          "innerBlocks": null
        },
        {
          "name": "core/columns",
          "attributes": {
            "isStackedOnMobile": true
          },
          "innerBlocks": [
            {
              "name": "core/column",
              "attributes": {},
              "innerBlocks": [
                {
                  "name": "core/image",
                  "attributes": {
                    "id": 1701,
                    "className": "layout-column-1",
                    "url": "https://d.pr/i/fW6V3V+",
                    "alt": ""
                  },
                  "innerBlocks": null
                }
              ]
            },
            {
              "name": "core/column",
              "attributes": {},
              "innerBlocks": [
                {
                  "name": "core/paragraph",
                  "attributes": {
                    "className": "layout-column-2",
                    "content": "Phosfluorescently morph intuitive relationships rather than customer directed human capital.",
                    "dropCap": false
                  },
                  "innerBlocks": null
                }
              ]
            }
          ]
        },
        {
          "name": "core/heading",
          "attributes": {
            "content": "Columns inside Columns (nested inner blocks)",
            "level": 2
          },
          "innerBlocks": null
        },
        {
          "name": "core/columns",
          "attributes": {
            "isStackedOnMobile": true
          },
          "innerBlocks": [
            {
              "name": "core/column",
              "attributes": {},
              "innerBlocks": [
                {
                  "name": "core/image",
                  "attributes": {
                    "id": 1701,
                    "className": "layout-column-1",
                    "url": "https://d.pr/i/fW6V3V+",
                    "alt": ""
                  },
                  "innerBlocks": null
                },
                {
                  "name": "core/columns",
                  "attributes": {
                    "isStackedOnMobile": true
                  },
                  "innerBlocks": [
                    {
                      "name": "core/column",
                      "attributes": {
                        "width": "33.33%"
                      },
                      "innerBlocks": [
                        {
                          "name": "core/heading",
                          "attributes": {
                            "fontSize": "large",
                            "content": "Life is so rich",
                            "level": 2
                          },
                          "innerBlocks": null
                        },
                        {
                          "name": "core/heading",
                          "attributes": {
                            "level": 3,
                            "content": "Life is so dynamic"
                          },
                          "innerBlocks": null
                        }
                      ]
                    },
                    {
                      "name": "core/column",
                      "attributes": {
                        "width": "66.66%"
                      },
                      "innerBlocks": [
                        {
                          "name": "core/paragraph",
                          "attributes": {
                            "content": "This rhyming poem is the spark that can reignite the fires within you. It challenges you to go out and live your life in the present moment as a \u201chero\u201d and leave your mark on this world.",
                            "dropCap": false
                          },
                          "innerBlocks": null
                        },
                        {
                          "name": "core/columns",
                          "attributes": {
                            "isStackedOnMobile": true
                          },
                          "innerBlocks": [
                            {
                              "name": "core/column",
                              "attributes": {},
                              "innerBlocks": [
                                {
                                  "name": "core/image",
                                  "attributes": {
                                    "id": 361,
                                    "sizeSlug": "large",
                                    "linkDestination": "none",
                                    "url": "https://gato-graphql.lndo.site/wp-content/uploads/2022/05/graphql-voyager-public-1024x622.jpg",
                                    "alt": ""
                                  },
                                  "innerBlocks": null
                                }
                              ]
                            },
                            {
                              "name": "core/column",
                              "attributes": {},
                              "innerBlocks": null
                            },
                            {
                              "name": "core/column",
                              "attributes": {},
                              "innerBlocks": [
                                {
                                  "name": "core/image",
                                  "attributes": {
                                    "id": 362,
                                    "sizeSlug": "large",
                                    "linkDestination": "none",
                                    "url": "https://gato-graphql.lndo.site/wp-content/uploads/2022/05/namespaced-interactive-schema-1024x598.png",
                                    "alt": ""
                                  },
                                  "innerBlocks": null
                                }
                              ]
                            }
                          ]
                        }
                      ]
                    }
                  ]
                }
              ]
            }
          ]
        }
      ]
    }
  }
}
```

### `blockDataItems`

This field differs from `blocks` in that it returns `[JSONObject!]` (instead of `[BlockUnion!]`):

```graphql
type CustomPost {
  blockDataItems: [JSONObject!]
}
```

In other words, instead of following the typical GraphQL way of having entities relate to entities and navigate across them, every Block entity at the top level already produces the whole block data for itself and all of its children, within a single `JSONObject` result.

The JSON object contains the properties for the block (under entries `name` and `attributes`) and for its inner blocks (under entry `innerBlocks`), recursively.

For instance, the following query:

```graphql
{
  post(by: { id: 1 }) {
    blockDataItems
  }
}
```

...will produce:

```json
{
  "data": {
    "post": {
      "blockDataItems": [
        {
          "name": "core/gallery",
          "attributes": {
            "linkTo": "none",
            "className": "alignnone",
            "images": [
              {
                "url": "https://d.pr/i/zd7Ehu+",
                "alt": "",
                "id": "1706"
              },
              {
                "url": "https://d.pr/i/jXLtzZ+",
                "alt": "",
                "id": "1705"
              }
            ],
            "ids": [],
            "shortCodeTransforms": [],
            "imageCrop": true,
            "fixedHeight": true,
            "sizeSlug": "large",
            "allowResize": false
          }
        },
        {
          "name": "core/heading",
          "attributes": {
            "content": "List Block",
            "level": 2
          }
        },
        {
          "name": "core/list",
          "attributes": {
            "ordered": false,
            "values": "<li>List item 1</li><li>List item 2</li><li>List item 3</li><li>List item 4</li>"
          }
        },
        {
          "name": "core/heading",
          "attributes": {
            "className": "has-top-margin",
            "content": "Columns Block",
            "level": 2
          }
        },
        {
          "name": "core/columns",
          "attributes": {
            "isStackedOnMobile": true
          },
          "innerBlocks": [
            {
              "name": "core/column",
              "attributes": {},
              "innerBlocks": [
                {
                  "name": "core/image",
                  "attributes": {
                    "id": 1701,
                    "className": "layout-column-1",
                    "url": "https://d.pr/i/fW6V3V+",
                    "alt": ""
                  }
                }
              ]
            },
            {
              "name": "core/column",
              "attributes": {},
              "innerBlocks": [
                {
                  "name": "core/paragraph",
                  "attributes": {
                    "className": "layout-column-2",
                    "content": "Phosfluorescently morph intuitive relationships rather than customer directed human capital.",
                    "dropCap": false
                  }
                }
              ]
            }
          ]
        },
        {
          "name": "core/heading",
          "attributes": {
            "content": "Columns inside Columns (nested inner blocks)",
            "level": 2
          }
        },
        {
          "name": "core/columns",
          "attributes": {
            "isStackedOnMobile": true
          },
          "innerBlocks": [
            {
              "name": "core/column",
              "attributes": {},
              "innerBlocks": [
                {
                  "name": "core/image",
                  "attributes": {
                    "id": 1701,
                    "className": "layout-column-1",
                    "url": "https://d.pr/i/fW6V3V+",
                    "alt": ""
                  }
                },
                {
                  "name": "core/columns",
                  "attributes": {
                    "isStackedOnMobile": true
                  },
                  "innerBlocks": [
                    {
                      "name": "core/column",
                      "attributes": {
                        "width": "33.33%"
                      },
                      "innerBlocks": [
                        {
                          "name": "core/heading",
                          "attributes": {
                            "fontSize": "large",
                            "content": "Life is so rich",
                            "level": 2
                          }
                        },
                        {
                          "name": "core/heading",
                          "attributes": {
                            "level": 3,
                            "content": "Life is so dynamic"
                          }
                        }
                      ]
                    },
                    {
                      "name": "core/column",
                      "attributes": {
                        "width": "66.66%"
                      },
                      "innerBlocks": [
                        {
                          "name": "core/paragraph",
                          "attributes": {
                            "content": "This rhyming poem is the spark that can reignite the fires within you. It challenges you to go out and live your life in the present moment as a \u201chero\u201d and leave your mark on this world.",
                            "dropCap": false
                          }
                        },
                        {
                          "name": "core/columns",
                          "attributes": {
                            "isStackedOnMobile": true
                          },
                          "innerBlocks": [
                            {
                              "name": "core/column",
                              "attributes": {},
                              "innerBlocks": [
                                {
                                  "name": "core/image",
                                  "attributes": {
                                    "id": 361,
                                    "sizeSlug": "large",
                                    "linkDestination": "none",
                                    "url": "https://gato-graphql.lndo.site/wp-content/uploads/2022/05/graphql-voyager-public-1024x622.jpg",
                                    "alt": ""
                                  }
                                }
                              ]
                            },
                            {
                              "name": "core/column",
                              "attributes": {}
                            },
                            {
                              "name": "core/column",
                              "attributes": {},
                              "innerBlocks": [
                                {
                                  "name": "core/image",
                                  "attributes": {
                                    "id": 362,
                                    "sizeSlug": "large",
                                    "linkDestination": "none",
                                    "url": "https://gato-graphql.lndo.site/wp-content/uploads/2022/05/namespaced-interactive-schema-1024x598.png",
                                    "alt": ""
                                  }
                                }
                              ]
                            }
                          ]
                        }
                      ]
                    }
                  ]
                }
              ]
            }
          ]
        }
      ]
    }
  }
}
```

### `blockFlattenedDataItems`

Both fields `blocks` and `blockDataItems` allow to filter what blocks are retrieved, via the `filterBy` argument. In both cases, if a block satisfies the inclusion condition, but is nested within a block that does not, then it will be excluded.

For instance, this query:

```graphql
{
  post(by: { id: 1 }) {
    id
    blockDataItems(
      filterBy: {
        include: [
          "core/heading"
        ]
      }
    )
  }
}
```

...will produce:

```json
{
  "data": {
    "post": {
      "blockDataItems": [
        {
          "name": "core/heading",
          "attributes": {
            "content": "List Block",
            "level": 2
          },
          "innerBlocks": null
        },
        {
          "name": "core/heading",
          "attributes": {
            "className": "has-top-margin",
            "content": "Columns Block",
            "level": 2
          },
          "innerBlocks": null
        },
        {
          "name": "core/heading",
          "attributes": {
            "content": "Columns inside Columns (nested inner blocks)",
            "level": 2
          },
          "innerBlocks": null
        }
      ]
    }
  }
}
```

Please notice that not all blocks of type `core/heading` have been included: Those which are nested under `core/column` have been excluded, as there is no way to reach them (since blocks `core/columns` and `core/column` are themselves excluded).

There are occasions, though, when we need to retrieve all blocks of a certain type from the custom post, independently of where these blocks are located within the hierarchy. For instance, we may want to include all blocks of type `core/image`, to retrieve all images included in a blog post.

It is to satisfy this need that there is field `CustomPost.blockFlattenedDataItems`. Unlike fields `blocks` and `blockDataItems`, it flattens the block hierarchy into a single level.

This query:

```graphql
{
  post(by: { id: 1 }) {
    id
    blockFlattenedDataItems(
      filterBy: {
        include: [
          "core/heading"
        ]
      }
    )
  }
}
```

...will produce:

```json
{
  "data": {
    "post": {
      "blockFlattenedDataItems": [
        {
          "name": "core/heading",
          "attributes": {
            "content": "List Block",
            "level": 2
          }
        },
        {
          "name": "core/heading",
          "attributes": {
            "className": "has-top-margin",
            "content": "Columns Block",
            "level": 2
          }
        },
        {
          "name": "core/heading",
          "attributes": {
            "content": "Columns inside Columns (nested inner blocks)",
            "level": 2
          }
        },
        {
          "name": "core/heading",
          "attributes": {
            "fontSize": "large",
            "content": "Life is so rich",
            "level": 2
          }
        },
        {
          "name": "core/heading",
          "attributes": {
            "level": 3,
            "content": "Life is so dynamic"
          }
        }
      ]
    }
  }
}
```

## Browse and install Gato GraphQL extensions

@todo Complete Extensions page changelog!

## Browse "Additional Documentation" when editing a Schema Configuration

Documentation for additional features in the Gato GraphQL can now be browsed when editing a Schema Configuration CPT, on the editor's sidebar:

![Additional Documentation in Schema Configuration CPT](../../images/releases/v1.0/schema-configuration-additional-documentation.png)

When clicking on any of the links, a modal window is displayed with the corresponding documentation:

![Modal window with documentation](../../images/releases/v1.0/schema-configuration-additional-documentation-modal.png)

## Inspect properties when editing Custom Endpoints and Persisted Query Endpoints

A sidebar component has been added to the editor for Custom Endpoints and Persisted Query Endpoints, displaying links to:

- The endpoint URL
- Its source configuration
- Its GraphiQL client (for the Custom Endpoint)
- Its interactive schema client (for the Custom Endpoint)

![Custom Endpoint Overview](../../images/custom-endpoint-overview.png)

## The Settings page has been re-designed

Due to the great number of modules in the plugin, the Settings page required several rows to display all tabs, which was not very polished.

Now, the Settings page organizes all modules into 2 levels, and displays tabs vertically, making it easier to browse them:

![Settings page](../../images/releases/v1.0/settings-page.png)

## Reset settings, and choose to use the restrictive or non-restrictive default settings

A Gato GraphQL may be exposed publicly or only privately, and depending on which is the case there are options and features that need to be restricted or not.

For instance, querying values from the `wp_options` table can be unrestrained whenever building a static site (in which case the WordPress site may be on the developer's laptop, not exposed to the Internet), but must be limited to a handful of options (or even none at all) for a public API, for security reasons.

The plugin has provided restrictive and non-restrictive default behaviors for the Settings since v0.8, but it required to define a PHP constant in `wp-config.php` to switch from one to the other, and then manually delete all Settings values that had to be regenerated.

Now it is possible to do the switch directly via the UI: The new "Plugin Management" tab in the Settings Page has an item "Reset Settings" which restores the default settings values, and allows to decide if to use the restrictive or the non-restrictive default behaviors.

![Reset Settings page](../../images/releases/v1.0/reset-settings-page.png)

## Added the Recipes page, providing use examples of GraphQL queries for different use cases

A new Recipes page has been added to the menu.

It provides examples of GraphQL queries for plenty of use cases. These can help us visualize the possibilities of what's doable with Gato GraphQL, and we can copy/paste these queries into our sites to achieve our goals faster.

![Recipes page](../../images/releases/v1.0/recipes-page.png)

## Configuration blocks in the the Schema Configuration CPT editor can be removed (and added again)

When creating a Schema Configuration, the new entry contains the whole list of options to configure:

![New Schema Configuration](../../images/new-schema-configuration.png)

If we need to configure only a handful of items, displaying all blocks in the editor makes it difficult to visualize.

Now, the Gutenberg template (containing the blocks) is not locked anymore, then we can delete the blocks that we don't need. And if we need to add a block again, it can be done from the inserter (notice that every block can only be added once):

![Removing and adding blocks in the Schema Configuration](../../images/schema-configuration-removing-and-adding-blocks.gif)

## Implementation of standard custom scalar types

Several standard custom scalar types have been implemented, so they are readily-available to be used in your GraphQL schema:

- `Domain`
- `IP`
- `IPv4`
- `IPv6`
- `MACAddress`
- `PhoneNumber`
- `PositiveInt`
- `StrictlyPositiveInt`
- `PositiveFloat`
- `StrictlyPositiveFloat`
- `UUID`

Please notice that these do not currently appear in the Interactive Schema client, as they are not being referenced anywhere within the WordPress model (as [defined by the spec](https://spec.graphql.org/October2021/#sec-Scalars.Built-in-Scalars), only types referenced by another type are reachable via introspection).

## Sort the Schema Configuration entries by name

In the Custom Endpoint and Persisted Query editors, the Schema Configuration entries are now sorted by name:

![Selecting a Schema Configuration](../../images/releases/v1.0/select-schema-configuration.png)

## Configure returning a payload object or the mutated entity for mutations

Mutation fields can be configured to return either of these 2 different entities:

- A payload object type
- Directly the mutated entity

### Payload object type

A payload object type contains all the data concerning the mutation:

- The status of the mutation (success or failure)
- The errors (if any) using distinctive GraphQL types, or
- The successfully mutated entity

For instance, mutation `updatePost` returns an object of type `PostUpdateMutationPayload` (please notice that we still need to query its field `post` to retrieve the updated post entity):

```graphql
mutation UpdatePost {
  updatePost(input: {
    id: 1724,
    title: "New title",
    status: publish
  }) {
    # This is the status of the mutation: SUCCESS or FAILURE
    status
    errors {
      __typename
      ...on ErrorPayload {
        message
      }
    }
    post {
      id
      title
      # This is the status of the post: publish, pending, trash, etc
      status
    }
  }
}
```

The payload object allows us to represent better the errors, even having a unique GraphQL type per kind of error. This allows us to present different reactions for different errors in the application, thus improving the user experience.

In the example above, the `PostUpdateMutationPayload` type contains field `errors`, which returns a list of `CustomPostUpdateMutationErrorPayloadUnion`. This is a union type which includes the list of all possible errors that can happen when modifying a custom post (to be queried via introspection field `__typename`):

- `CustomPostDoesNotExistErrorPayload`
- `GenericErrorPayload`
- `LoggedInUserHasNoEditingCustomPostCapabilityErrorPayload`
- `LoggedInUserHasNoPermissionToEditCustomPostErrorPayload`
- `LoggedInUserHasNoPublishingCustomPostCapabilityErrorPayload`
- `UserIsNotLoggedInErrorPayload`

If the operation was successful, we will receive:

```json
{
  "data": {
    "updatePost": {
      "status": "SUCCESS",
      "errors": null,
      "post": {
        "id": 1724,
        "title": "Some title",
        "status": "publish"
      }
    }
  }
}
```

If the user is not logged in, we will receive:

```json
{
  "data": {
    "updatePost": {
      "status": "FAILURE",
      "errors": [
        {
          "__typename": "UserIsNotLoggedInErrorPayload",
          "message": "You must be logged in to create or update custom posts"
        }
      ],
      "post": null
    }
  }
}
```

If the user doesn't have the permission to edit posts, we will receive:

```json
{
  "data": {
    "updatePost": {
      "status": "FAILURE",
      "errors": [
        {
          "__typename": "LoggedInUserHasNoEditingCustomPostCapabilityErrorPayload",
          "message": "Your user doesn't have permission for editing custom posts."
        }
      ],
      "post": null
    }
  }
}
```

As a consequence of all the additional `MutationPayload`, `MutationErrorPayloadUnion` and `ErrorPayload` types added, the GraphQL schema will have a bigger size:

![GraphQL schema with payload object types for mutations](../../images/mutations-using-payload-object-types.png "GraphQL schema with payload object types for mutations")

### Mutated entity

The mutation will directly return the mutated entity in case of success, or <code>null</code> in case of failure, and any error message will be displayed in the JSON response's top-level <code>errors</code> entry.

For instance, mutation `updatePost` will return the object of type `Post`:

```graphql
mutation UpdatePost {
  updatePost(input: {
    id: 1724,
    title: "New title",
    status: publish
  }) {
    id
    title
    status
  }
}
```

If the operation was successful, we will receive:

```json
{
  "data": {
    "updatePost": {
      "id": 1724,
      "title": "Some title",
      "status": "publish"
    }
  }
}
```

In case of errors, these will appear under the `errors` entry of the response. For instance, if the user is not logged in, we will receive:

```json
{
    "errors": [
      {
        "message": "You must be logged in to create or update custom posts'",
        "locations": [
          {
            "line": 2,
            "column": 3
          }
        ]
      }
  ],
  "data": {
    "updatePost": null
  }
}
```

We must notice that, as a result, the top-level `errors` entry will contain not only syntax, schema validation and logic errors (eg: not passing a field argument's name, requesting a non-existing field, or calling `_sendHTTPRequest` and the network is down respectively), but also "content validation" errors (eg: "you're not authorized to modify this post").

Because there are no additional types added, the GraphQL schema will look leaner:

![GraphQL schema without payload object types for mutations](../../images/mutations-not-using-payload-object-types.png "GraphQL schema without payload object types for mutations")

## Added "state" column to tables for Custom Endpoints and Persisted Queries

The tables for the Custom Endpoint and Persisted Query CPTs now display the "state" column, showing if entries are enabled or disabled:

![State column in CPTs table](../../images/releases/v1.0/state-column-in-cpts-table.png)

## Saving the Settings is faster, as it does not regenerate the service container anymore

The Plugin Settings has been completely decoupled from the services registered in the container. As such, the container does not need to be regenerated when updating the Settings, leading to a performance boost.

## Only activating/deactivating Gato GraphQL extension plugins will regenerate the service container

Before, the service container (upon which the GraphQL schema is based) was regenerated whenever any plugin (whether it was related to Gato GraphQL or not) was activated or deactivated.

Now, only Gato GraphQL extension plugins trigger this process.

## Fixed

- Made field `Comment.type` of type `CommentTypeEnum` (previously was `String`)
- Avoid error from loading non-existing translation files
- Updated all documentation images

## Breaking changes

- Non-restrictive Settings values are used by default
- Env var `ENABLE_UNSAFE_DEFAULTS` has been removed and `SETTINGS_OPTION_ENABLE_RESTRICTIVE_DEFAULT_BEHAVIOR` added in its place, to indicate to use the restrictive Settings values by default
- Renamed plugin to "Gato GraphQL"
