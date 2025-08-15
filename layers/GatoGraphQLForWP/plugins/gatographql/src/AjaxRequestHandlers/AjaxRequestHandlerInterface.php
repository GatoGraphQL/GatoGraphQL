<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\AjaxRequestHandlers;

interface AjaxRequestHandlerInterface
{
    /**
     * Ajax action name, without `wp_ajax_` prefix
     */
    public function getAjaxAction(): string;

    public function getAjaxNonce(): string;
}
