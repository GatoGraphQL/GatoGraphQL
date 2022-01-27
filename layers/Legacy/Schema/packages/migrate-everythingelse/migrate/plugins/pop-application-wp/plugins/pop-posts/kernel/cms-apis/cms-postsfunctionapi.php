<?php

namespace PoP\Application\WP;

use PoPCMSSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;

class ApplicationPostsFunctionAPI extends \PoP\Application\PostsFunctionAPI_Base
{
    public function getAllcontentPostTypes()
    {
        // All searchable post types
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        $args = array(
            'exclude-from-search' => false,
        );
        return array_keys($customPostTypeAPI->getCustomPostTypes($args));
    }
}

/**
 * Initialize
 */
new ApplicationPostsFunctionAPI();
