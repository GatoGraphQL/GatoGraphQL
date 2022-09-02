(window.webpackJsonpGraphQLAPIExposeAdminData=window.webpackJsonpGraphQLAPIExposeAdminData||[]).push([[1],{46:function(e,t){e.exports='<h1 id="schema-expose-admin-data">Schema Expose Admin Data</h1> <p>Expose &quot;admin&quot; elements in the GraphQL schema, which provide access to private data.</p> <p>The GraphQL schema must strike a balance between public and private elements (including fields and input fields), as to avoid exposing private information in a public API.</p> <p>For instance, to access post data, we have field <code>Root.posts</code>, which by default can only retrieve published posts. With this module, a new property <code>Schema Expose Admin Data</code> is added to the Schema Configuration. When enabled, argument <code>filter</code> in <code>Root.posts</code> exposes an additional input <code>status</code>, enabling to retrieve non-published posts (eg: posts with status <code>&quot;draft&quot;</code>), which is private data.</p> <h2 id="list-of-admin-elements">List of admin elements</h2> <p>The elements below (among others) are, by default, treated as private data:</p> <p><strong>User:</strong></p> <ul> <li><code>email</code></li> <li><code>roles</code></li> <li><code>capabilities</code></li> </ul> <p><strong>Custom Posts:</strong></p> <ul> <li><code>status</code></li> <li><code>hasPassword</code></li> <li><code>password</code></li> </ul> <p><strong>Comments:</strong></p> <ul> <li><code>status</code></li> </ul> <h2 id="inspecting-the-admin-elements-via-schema-introspection">Inspecting the &quot;admin&quot; elements via schema introspection</h2> <p>The <code>isAdminElement</code> property is added to field <code>extensions</code> when doing schema introspection. To find out which are the &quot;admin&quot; elements from the schema, execute this query:</p> <pre><code class="language-graphql">query ViewAdminElements {\n  __schema {\n    types {\n      name\n      fields {\n        name\n        extensions {\n          isAdminElement\n        }\n        args {\n          name\n          extensions {\n            isAdminElement\n          }\n        }\n      }\n      inputFields {\n        name\n        extensions {\n          isAdminElement\n        }\n      }\n      enumValues {\n        name\n        extensions {\n          isAdminElement\n        }\n      }\n    }\n  }\n}</code></pre> <p>And then search for entries with <code>&quot;isAdminElement&quot;: true</code> in the results.</p> <h2 id="overriding-the-default-configuration">Overriding the default configuration</h2> <p>The elements listed above can be made public.</p> <p>In the Settings page, in the corresponding tab for each, there is a checkbox to configure if to treat them as private or public:</p> <p><a href="../../images/settings-treat-user-email-as-private-data.png" target="_blank"><img src="https://raw.githubusercontent.com/GraphQLAPI/graphql-api-for-wp/master/docs/modules/schema-expose-admin-data/../../images/settings-treat-user-email-as-private-data.png" alt="Settings to treat user email as private data"></a></p> <h2 id="how-to-use">How to use</h2> <p>Exposing admin elements in the schema can be configured as follows, in order of priority:</p> <p>✅ Specific mode for the custom endpoint or persisted query, defined in the schema configuration</p> <p><a href="../../images/schema-configuration-adding-admin-fields-to-schema.png" target="_blank"><img src="https://raw.githubusercontent.com/GraphQLAPI/graphql-api-for-wp/master/docs/modules/schema-expose-admin-data/../../images/schema-configuration-adding-admin-fields-to-schema.png" alt="Adding admin fields to the schema, set in the Schema configuration" title="Adding admin fields to the schema, set in the Schema configuration"></a></p> <p>✅ Default mode, defined in the Settings</p> <p>If the schema configuration has value <code>&quot;Default&quot;</code>, it will use the mode defined in the Settings:</p> <p><a href="../../images/settings-admin-schema.png" target="_blank"><img src="https://raw.githubusercontent.com/GraphQLAPI/graphql-api-for-wp/master/docs/modules/schema-expose-admin-data/../../images/settings-admin-schema.png" alt="Schema Expose Admin Data, in the Settings" title="Schema Expose Admin Data, in the Settings"></a></p> <h2 id="when-to-use">When to use</h2> <p>Use whenever exposing private information is allowed, such as when building a static website, fetching data from a local WordPress instance (i.e. not a public API).</p> '}}]);