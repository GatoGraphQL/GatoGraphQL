<?php

// const POP_ROUTE_MAIN = 'main';
// const POP_ROUTE_DESCRIPTION = 'description';
// const POP_ROUTE_AUTHORS = 'authors';

if (!defined('POP_ROUTE_MAIN')) {
    define('POP_ROUTE_MAIN', \PoP\Engine\DefinitionUtils::getDefinition('main', POP_DEFINITIONGROUP_ROUTES));
}
if (!defined('POP_ROUTE_DESCRIPTION')) {
    define('POP_ROUTE_DESCRIPTION', \PoP\Engine\DefinitionUtils::getDefinition('description', POP_DEFINITIONGROUP_ROUTES));
}
if (!defined('POP_ROUTE_AUTHORS')) {
    define('POP_ROUTE_AUTHORS', \PoP\Engine\DefinitionUtils::getDefinition('authors', POP_DEFINITIONGROUP_ROUTES));
}