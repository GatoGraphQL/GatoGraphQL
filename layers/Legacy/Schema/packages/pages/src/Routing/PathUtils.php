<?php

declare(strict_types=1);

namespace PoPCMSSchema\Pages\Routing;

use PoPCMSSchema\SchemaCommons\Facades\CMS\CMSServiceFacade;
use PoPCMSSchema\Pages\Facades\PageTypeAPIFacade;

class PathUtils
{
    public static function getPagePath(string|int $page_id): ?string
    {
        $cmsService = CMSServiceFacade::getInstance();
        $pageTypeAPI = PageTypeAPIFacade::getInstance();

        // Generate the page path. Eg: http://mesym.com/events/past/ will render events/past
        $permalink = $pageTypeAPI->getPermalink($page_id);
        if ($permalink === null) {
            return null;
        }

        $domain = $cmsService->getHomeURL();

        // Remove the domain from the permalink => page path
        $page_path = substr($permalink, strlen($domain));

        // Remove the first and last '/'
        $page_path = trim($page_path, '/');

        return $page_path;
    }
}
