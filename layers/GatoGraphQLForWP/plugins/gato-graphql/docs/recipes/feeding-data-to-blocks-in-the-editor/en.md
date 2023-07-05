# Feeding data to blocks in the editor

submodules/PoP/layers/GatoGraphQLForWP/plugins/gato-graphql/blocks/schema-configuration/src/store/controls.js

`GATO_GRAPHQL_PLUGIN_OWN_USE_ADMIN_ENDPOINT`

Document in some recipe:
    `GATO_GRAPHQL_ADMIN_ENDPOINT`
    `GATO_GRAPHQL_BLOCK_EDITOR_ADMIN_ENDPOINT`
Can use?:
    ## Added JS variable `GATO_GRAPHQL_BLOCK_EDITOR_ADMIN_ENDPOINT` with URL of internal block-editor endpoint

    An internal GraphQL endpoint called `blockEditor` is accessible within the wp-admin, to allow developers to fetch data for their Gutenberg blocks. This endpoint has a pre-defined configuration (i.e. it does not have the user preferences from the plugin applied to it), so its behavior is consistent.

    A new global JS variable `GATO_GRAPHQL_BLOCK_EDITOR_ADMIN_ENDPOINT` prints the URL for this endpoint in the wp-admin editor for all users who can access the GraphQL schema, making it easier to point to this endpoint within the block's JavaScript code.

    Inspecting the source code in the wp-admin, you will find the following HTML:

    ```html
    <script type="text/javascript">
    var GATO_GRAPHQL_BLOCK_EDITOR_ADMIN_ENDPOINT = "https://mysite.com/wp-admin/edit.php?page=gato_graphql&action=execute_query&endpoint_group=blockEditor"
    </script>
    ```


Also:

How developers can "lock" behavior for a specific wp-admin endpoint

Use code in layers/GatoGraphQLForWP/phpunit-plugins/gato-graphql-testing/src/Hooks/AddDummyCustomAdminEndpointHook.php

