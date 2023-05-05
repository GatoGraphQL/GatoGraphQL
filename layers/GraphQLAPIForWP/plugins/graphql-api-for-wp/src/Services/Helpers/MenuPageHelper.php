<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\Helpers;

use GatoGraphQL\GatoGraphQL\Constants\RequestParams;
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
