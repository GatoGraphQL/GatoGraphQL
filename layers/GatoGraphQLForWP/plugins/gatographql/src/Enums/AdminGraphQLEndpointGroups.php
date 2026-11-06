<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Enums;

enum AdminGraphQLEndpointGroups: string
{
    /**
     * This one is an empty string, as to express that if passing no param
     * then the default one is used
     */
    case DEFAULT = '';
    case PERSISTED_QUERY = 'persistedQuery';
    case PLUGIN_OWN_USE = 'pluginOwnUse';
    case BLOCK_EDITOR = 'blockEditor';
}
