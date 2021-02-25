<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Helpers;

use GraphQLAPI\GraphQLAPI\General\RequestParams;

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
        return isset($_GET[RequestParams::TAB]) && $_GET[RequestParams::TAB] == RequestParams::TAB_DOCS;
    }
}
