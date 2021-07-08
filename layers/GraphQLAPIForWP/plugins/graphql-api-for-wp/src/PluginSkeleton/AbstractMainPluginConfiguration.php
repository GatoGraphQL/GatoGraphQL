<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\PluginSkeleton;


/**
 * Sets the configuration in all the PoP components.
 *
 * To set the value for properties, it uses this order:
 *
 * 1. Retrieve it as an environment value, if defined
 * 2. Retrieve as a constant `GRAPHQL_API_...` from wp-config.php, if defined
 * 3. Retrieve it from the user settings, if stored
 * 4. Use the default value
 *
 * If a slug is set or updated in the environment variable or wp-config constant,
 * it is necessary to flush the rewrite rules for the change to take effect.
 * For that, on the WordPress admin, go to Settings => Permalinks and click on Save changes
 */
class AbstractMainPluginConfiguration extends AbstractPluginConfiguration
{
    
}
