<?php

declare(strict_types=1);

namespace PoPSchema\Pages\Routing;

use PoPSchema\Pages\Facades\PageTypeAPIFacade;

class PathUtils
{
    public static function getPagePath($page_id)
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        $pageTypeAPI = PageTypeAPIFacade::getInstance();

        // Generate the page path. Eg: http://mesym.com/events/past/ will render events/past
        $permalink = $pageTypeAPI->getPermalink($page_id);

        $domain = $cmsengineapi->getHomeURL();

        // Remove the domain from the permalink => page path
        $page_path = substr($permalink, strlen($domain));

        // Remove the first and last '/'
        $page_path = trim($page_path, '/');

        return $page_path;
    }
}
