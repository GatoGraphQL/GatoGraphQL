(globalThis.webpackChunkschema_config_schema_settings=globalThis.webpackChunkschema_config_schema_settings||[]).push([[882],{776:e=>{e.exports='<h1 id="settings">Settings</h1> <p>Retrieve the settings from the site (stored in table <code>wp_options</code>), by querying fields <code>optionValue</code>, <code>optionValues</code> and <code>optionObjectValue</code>.</p> <p>For security reasons, which options can be queried must be explicitly configured.</p> <h2 id="description">Description</h2> <p>The following <code>Root</code> fields receive argument <code>name</code> and retrieve the corresponding option from the <code>wp_options</code> table:</p> <ul> <li><code>optionValue: AnyBuiltInScalar</code></li> <li><code>optionValues: [AnyBuiltInScalar]</code></li> <li><code>optionObjectValue: JSONObject</code></li> </ul> <p>For instance, this query retrieves the site&#39;s URL:</p> <pre><code class="hljs language-graphql"><span class="hljs"><span class="hljs-punctuation">{</span>\n  <span class="hljs-symbol">homeURL</span><span class="hljs-punctuation">:</span> optionValue<span class="hljs-punctuation">(</span><span class="hljs-symbol">name</span><span class="hljs-punctuation">:</span> <span class="hljs-string">&quot;home&quot;</span><span class="hljs-punctuation">)</span>\n<span class="hljs-punctuation">}</span></span></code></pre> <h2 id="configure-the-allowed-options">Configure the allowed options</h2> <p>We must configure the list of option names that can be queried.</p> <p>Each entry can either be:</p> <ul> <li>A regex (regular expression), if it&#39;s surrounded by <code>/</code> or <code>#</code>, or</li> <li>The full option name, otherwise</li> </ul> <p>For instance, any of these entries match meta key <code>&quot;siteurl&quot;</code>:</p> <ul> <li><code>siteurl</code></li> <li><code>/site.*/</code></li> <li><code>#site([a-zA-Z]*)#</code></li> </ul> <p>There are 2 places where this configuration can take place, in order of priority:</p> <ol> <li>Custom: In the corresponding Schema Configuration</li> <li>General: In the Settings page</li> </ol> <p>In the Schema Configuration applied to the endpoint, select option <code>&quot;Use custom configuration&quot;</code> and then input the desired entries:</p> <p><img src="https://raw.githubusercontent.com/GatoGraphQL/GatoGraphQL/4.0.0/layers/GatoGraphQLForWP/plugins/gatographql/docs/modules/schema-settings/../../images/schema-configuration-settings-entries.png" alt="Defining the entries in the Schema Configuration" title="Defining the entries in the Schema Configuration"></p> <p>Otherwise, the entries defined in the &quot;Settings&quot; tab from the Settings will be used:</p> <div class="img-width-1024" markdown="1"> <p><img src="https://raw.githubusercontent.com/GatoGraphQL/GatoGraphQL/4.0.0/layers/GatoGraphQLForWP/plugins/gatographql/docs/modules/schema-settings/../../images/settings-settings-entries.png" alt="Defining the entries in the Settings" title="Defining the entries in the Settings"></p> </div> <p>There are 2 behaviors, &quot;Allow access&quot; and &quot;Deny access&quot;:</p> <ul> <li><strong>Allow access:</strong> only the configured entries can be accessed, and no other can</li> <li><strong>Deny access:</strong> the configured entries cannot be accessed, all other entries can</li> </ul> <div class="img-width-1024" markdown="1"> <p><img src="https://raw.githubusercontent.com/GatoGraphQL/GatoGraphQL/4.0.0/layers/GatoGraphQLForWP/plugins/gatographql/docs/modules/schema-settings/../../images/schema-configuration-settings-behavior.png" alt="Defining the access behavior" title="Defining the access behavior"></p> </div> <h2 id="default-options">Default options</h2> <p>When the plugin is installed, the following options are pre-defined to be accessible:</p> <ul> <li><code>&quot;siteurl&quot;</code></li> <li><code>&quot;home&quot;</code></li> <li><code>&quot;blogname&quot;</code></li> <li><code>&quot;blogdescription&quot;</code></li> <li><code>&quot;WPLANG&quot;</code></li> <li><code>&quot;posts_per_page&quot;</code></li> <li><code>&quot;comments_per_page&quot;</code></li> <li><code>&quot;date_format&quot;</code></li> <li><code>&quot;time_format&quot;</code></li> <li><code>&quot;blog_charset&quot;</code></li> </ul> '}}]);