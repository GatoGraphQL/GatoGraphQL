(window.webpackJsonpGatoGraphQLSchemaConfigGlobalFields=window.webpackJsonpGatoGraphQLSchemaConfigGlobalFields||[]).push([[1],{51:function(s,e){s.exports='<h1 id="global-fields">Global Fields</h1> <p>Global fields are fields that are accessible under every single type in the GraphQL schema (while being defined only once).</p> <h2 id="description">Description</h2> <p>The GraphQL schema exposes types, such as <code>Post</code>, <code>User</code> and <code>Comment</code>, and the fields available for every type, such as <code>Post.title</code>, <code>User.name</code> and <code>Comment.responses</code>. These fields deal with &quot;data&quot;, as they retrieve some specific piece of data from an entity.</p> <p>Gato GraphQL, in addition, offers a different kind of fields: those providing &quot;functionality&quot; instead of data.</p> <p>These are some examples:</p> <p>Those fields from the <strong>HTTP Client</strong> extension, which connect to external API endpoints and retrieve data from them:</p> <ul> <li><code>_sendHTTPRequest</code></li> <li><code>_sendJSONObjectItemHTTPRequest</code></li> <li><code>_sendJSONObjectCollectionHTTPRequest</code></li> <li><code>_sendGraphQLHTTPRequest</code></li> <li>...</li> </ul> <p>Those fields from the <strong>PHP Functions via Schema</strong> extension, which expose functionalities commonly found in programming languages (such as PHP):</p> <ul> <li><code>_not</code></li> <li><code>_if</code></li> <li><code>_equals</code></li> <li><code>_isEmpty</code></li> <li><code>_echo</code></li> <li><code>_sprintf</code></li> <li><code>_arrayItem</code></li> <li><code>_arrayAddItem</code></li> <li><code>_arrayUnique</code></li> <li>...</li> </ul> <p><strong>Please notice:</strong> By convention, all these fields start with <code>&quot;_&quot;</code>. This convention helps differentiate which are data (i.e. &quot;normal&quot;) fields, and which are functionality fields, when visualizing the GraphQL schema.</p> <p>As can be appreciated from these available modules, functionality fields are useful for obtaining data that is stored outside of WordPress, and for manipulating the data once it has been retrieved, allowing us to transform a field value in whatever way it is required, and granting us powerful data import/export capabilities.</p> <p>Functionality fields belong not to a specific type, such as <code>Post</code> or <code>User</code>, but to all the types in the schema. That&#39;s why these are handled in a distinctive way in Gato GraphQL, under the name of &quot;global fields&quot;.</p> <h2 id="examples">Examples</h2> <p>While we have a <code>Post.hasComments</code> fields, we may need the opposite value. Instead of creating a new field <code>Post.notHasComments</code> (for which we&#39;d need to edit PHP code), we can use the <strong>Field to Input</strong> feature to input the value from <code>hasComments</code> into a <code>_not</code> field, thus calculating the new value always within the GraphQL query:</p> <pre><code class="hljs language-graphql"><span class="hljs"><span class="hljs-punctuation">{</span>\n  posts <span class="hljs-punctuation">{</span>\n    id\n    hasComments\n    <span class="hljs-symbol">notHasComments</span><span class="hljs-punctuation">:</span> _not<span class="hljs-punctuation">(</span><span class="hljs-symbol">value</span><span class="hljs-punctuation">:</span> <span class="hljs-variable">$__hasComments</span>)\n  <span class="hljs-punctuation">}</span>\n<span class="hljs-punctuation">}</span></span></code></pre> <p>We can apply function fields multiple times to perform a more complex calculation, such as generating a <code>summary</code> field based on the values from other fields:</p> <pre><code class="hljs language-graphql"><span class="hljs"><span class="hljs-punctuation">{</span>\n  posts <span class="hljs-punctuation">{</span>\n    id\n    content <span class="hljs-meta">@remove</span>\n    <span class="hljs-symbol">shortContent</span><span class="hljs-punctuation">:</span> _strSubstr<span class="hljs-punctuation">(</span><span class="hljs-symbol">string</span><span class="hljs-punctuation">:</span> <span class="hljs-variable">$__content</span>, <span class="hljs-symbol">offset</span><span class="hljs-punctuation">:</span> <span class="hljs-number">0</span>, <span class="hljs-symbol">length</span><span class="hljs-punctuation">:</span> <span class="hljs-number">150</span><span class="hljs-punctuation">)</span> <span class="hljs-meta">@remove</span>\n    excerpt <span class="hljs-meta">@remove</span>\n    <span class="hljs-symbol">isExcerptEmpty</span><span class="hljs-punctuation">:</span> _isEmpty<span class="hljs-punctuation">(</span><span class="hljs-symbol">value</span><span class="hljs-punctuation">:</span> <span class="hljs-variable">$__excerpt</span>) <span class="hljs-meta">@remove</span>\n    <span class="hljs-symbol">summary</span><span class="hljs-punctuation">:</span> _if<span class="hljs-punctuation">(</span>\n      <span class="hljs-symbol">condition</span><span class="hljs-punctuation">:</span> <span class="hljs-variable">$__isExcerptEmpty</span>\n      <span class="hljs-symbol">then</span><span class="hljs-punctuation">:</span> <span class="hljs-variable">$__content</span>\n      <span class="hljs-symbol">else</span><span class="hljs-punctuation">:</span> <span class="hljs-variable">$__excerpt</span>\n    <span class="hljs-punctuation">)</span>\n  <span class="hljs-punctuation">}</span>\n<span class="hljs-punctuation">}</span></span></code></pre> <h2 id="exposing-global-fields-in-the-schema">Exposing Global Fields in the Schema</h2> <p>Global fields are added to all types in the GraphQL schema, which renders its visualization unwieldy:</p> <p><img src="https://raw.githubusercontent.com/GatoGraphQL/GatoGraphQL/2.6.1/layers/GatoGraphQLForWP/plugins/gatographql/docs/modules/global-fields/../../images/schema-with-global-fields-under-all-types.png" alt="Schema with global fields exposed under all types" title="Schema with global fields exposed under all types"></p> <p>That&#39;s why the configuration (see next section) offers to not expose the global fields (when doing introspection), by either:</p> <ul> <li>Exposing them on the Root type only</li> <li>Not exposing them at all</li> </ul> <p><strong>Please notice:</strong> Global fields will still available under all types from the schema, even when not exposed; in other words, they are simply &quot;hidden from view&quot; when doing introspection. If you desire to actually remove (not just hide) some global field from the schema, this must be done via an Access Control List.</p> <p>By default, the schema exposes global fields under the Root type only, and it is easier to visualize and browse:</p> <p><img src="https://raw.githubusercontent.com/GatoGraphQL/GatoGraphQL/2.6.1/layers/GatoGraphQLForWP/plugins/gatographql/docs/modules/global-fields/../../images/schema-with-global-fields-under-root-type-only.png" alt="Schema with global fields exposed under the Root type only" title="Schema with global fields exposed under the Root type only"></p> <h2 id="configuration">Configuration</h2> <p>To select the general level of exposure of global fields in the GraphQL schema, go to the &quot;Global Fields&quot; module on the Settings page, and select the desired option:</p> <ul> <li>Do not expose</li> <li>Expose under the Root type only <em>(this is the default value)</em></li> <li>Expose under all types</li> </ul> <div class="img-width-1024" markdown="1"> <p><img src="https://raw.githubusercontent.com/GatoGraphQL/GatoGraphQL/2.6.1/layers/GatoGraphQLForWP/plugins/gatographql/docs/modules/global-fields/../../images/settings-global-fields.png" alt="Settings for Global Fields" title="Settings for Global Fields"></p> </div> <p>To modify the exposure of global fields on different custom endpoints, select the desired option in block &quot;Global Fields&quot; from the corresponding Schema Configuration:</p> <p><img src="https://raw.githubusercontent.com/GatoGraphQL/GatoGraphQL/2.6.1/layers/GatoGraphQLForWP/plugins/gatographql/docs/modules/global-fields/../../images/schema-config-global-fields.png" alt="Editing Global Fields in the Schema Configuration" title="Editing Global Fields in the Schema Configuration"></p> '}}]);