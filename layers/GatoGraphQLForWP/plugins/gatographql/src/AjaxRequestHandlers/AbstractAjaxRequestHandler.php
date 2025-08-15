<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\AjaxRequestHandlers;

use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;

abstract class AbstractAjaxRequestHandler extends AbstractAutomaticallyInstantiatedService
{
    final public function initialize(): void
    {
        add_action(
            'wp_ajax_' . $this->getAjaxAction(),
            function (): void {
                if (!current_user_can($this->getRequiredCapability())) {
                    wp_send_json_error(['message' => 'Unauthorized'], 403);
                }
                check_ajax_referer($this->getAjaxNonce());

                if (function_exists('wp_cache_delete')) {
                    wp_cache_delete('alloptions', 'options');
                    wp_cache_delete('notoptions', 'options');
                }

                nocache_headers();
                header('Cache-Control: no-store, no-cache, must-revalidate, max-age=zero');
                header('Pragma: no-cache');

                wp_send_json_success($this->getAjaxResponse());
            }
        );
    }

    /**
     * Ajax action name, without `wp_ajax_` prefix
     */
    abstract protected function getAjaxAction(): string;

    protected function getRequiredCapability(): string
    {
        return 'manage_options';
    }

    abstract protected function getAjaxNonce(): string;

    /**
     * Return the response to send to the client
     *
     * @return array<string, mixed>
     */
    abstract protected function getAjaxResponse(): array;

    /**
     * Return the option name to get the data from
     */
}
