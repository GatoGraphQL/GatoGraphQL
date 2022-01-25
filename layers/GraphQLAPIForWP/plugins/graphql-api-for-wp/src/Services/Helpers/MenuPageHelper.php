<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Helpers;

use GraphQLAPI\GraphQLAPI\Constants\RequestParams;
use PoP\Root\App;

/**
 * About menu page
 */
class MenuPageHelper
{
    /**
     * Indicate if we are loading a documentation screen:
     * If it has ?tab=docs
     */
    public function isDocumentationScreen(): bool
    {
        return App::query(RequestParams::TAB) === RequestParams::TAB_DOCS;
    }
}
