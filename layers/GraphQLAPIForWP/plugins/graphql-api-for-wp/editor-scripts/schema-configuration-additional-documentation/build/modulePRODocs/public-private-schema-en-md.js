(window.webpackJsonpGraphQLAPISchemaConfigurationAdditionalDocumentation=window.webpackJsonpGraphQLAPISchemaConfigurationAdditionalDocumentation||[]).push([[29],{75:function(e,t){e.exports='<h1 id="publicprivate-schema">Public/Private Schema</h1> <p>Define if the schema metadata is public, and everyone has access to it, or is private, and can be accessed only when the Access Control validations are satisfied.</p> <h2 id="description">Description</h2> <p>When access to some a field or directive is denied through Access Control, there are 2 ways for the API to behave:</p> <p><strong>Public mode</strong>: the fields in the schema are exposed, and when the permission is not satisfied, the user gets an error message with a description of why the permission was rejected. This behavior makes the metadata from the schema always available.</p> <p><strong>Private mode</strong>: the schema is customized to every user, containing only the fields available to him or her, and so when attempting to access a forbidden field, the error message says that the field doesn&#39;t exist. This behavior exposes the metadata from the schema only to those users who can access it.</p> <h2 id="how-to-use">How to use</h2> <p>The mode to use can be configured as follows, in order of priority:</p> <p>✅ (If option <code>Enable granular control?</code> in the settings is <code>on</code>) Specific mode for a set of operations, fields and directives, defined in the Access Control List</p> <p><img src="https://raw.githubusercontent.com/leoloso/PoP/master/layers/GraphQLAPIForWP/plugins/graphql-api-for-wp/docs/implicit-features//../../images/settings-enable-granular-control.png" alt="Enable granular control?" title="Enable granular control?"></p> <p><img src="https://raw.githubusercontent.com/leoloso/PoP/master/layers/GraphQLAPIForWP/plugins/graphql-api-for-wp/docs/implicit-features//../../images/acl-public-private-schema-mode.png" alt="Individual Public/Private schema mode" title="Individual Public/Private schema mode"></p> <p>✅ Specific mode for the custom endpoint or persisted query, defined in the schema configuration</p> <p><img src="https://raw.githubusercontent.com/leoloso/PoP/master/layers/GraphQLAPIForWP/plugins/graphql-api-for-wp/docs/implicit-features//../../images/schema-configuration-public-private-schema-mode.png" alt="Public/Private schema mode, set in the Schema configuration" title="Public/Private schema mode, set in the Schema configuration"></p> <p>✅ Default mode, defined in the Settings</p> <p>If the schema configuration has value <code>&quot;Default&quot;</code>, it will use the mode defined in the Settings:</p> <p><img src="https://raw.githubusercontent.com/leoloso/PoP/master/layers/GraphQLAPIForWP/plugins/graphql-api-for-wp/docs/implicit-features//../../images/default-public-private-schema-mode.png" alt="Defaul Public/Private schema mode" title="Defaul Public/Private schema mode"></p> \x3c!-- ## Resources\n\nVideo demonstrating usage of the public/private schema modes: <a href="https://vimeo.com/413503284" target="_blank">vimeo.com/413503284</a>. --\x3e '}}]);