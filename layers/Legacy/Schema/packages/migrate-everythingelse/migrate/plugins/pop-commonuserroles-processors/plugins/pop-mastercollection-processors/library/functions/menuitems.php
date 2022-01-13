<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\Root\Facades\Hooks\HooksAPIFacade;

HooksAPIFacade::getInstance()->addFilter('gdAuthorParentpageid', 'gdUreAuthorParentpageidImpl', 10, 2);
function gdUreAuthorParentpageidImpl($pageid, $author_id = null)
{
    if (is_null($author_id)) {
        $author_id = \PoP\Root\App::getState(['routing', 'queried-object-id']);
    }

    if (gdUreIsOrganization($author_id)) {
        return POP_COMMONUSERROLES_ROUTE_ORGANIZATIONS;
    } elseif (gdUreIsIndividual($author_id)) {
        return POP_COMMONUSERROLES_ROUTE_INDIVIDUALS;
    }

    return $pageid;
}
