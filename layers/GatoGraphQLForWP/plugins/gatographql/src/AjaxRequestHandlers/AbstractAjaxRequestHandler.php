<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\AjaxRequestHandlers;

use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;

use function add_action;
use function check_ajax_referer;
use function nocache_headers;
use function wp_cache_delete;
use function wp_send_json_success;

abstract class AbstractAjaxRequestHandler extends AbstractAutomaticallyInstantiatedService implements AjaxRequestHandlerInterface
{
    final public function initialize(): void
    {
        add_action(
            'wp_ajax_' . $this->getAjaxAction(),
            [$this, 'processAjaxRequest']
        );
    }

    public function processAjaxRequest(): void
    {        
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

    public function getAjaxNonce(): string
    {   
        return $this->getAjaxAction() . '_nonce';
    }

    protected function getRequiredCapability(): string
    {
        return 'manage_options';
    }

    /**
     * Return the response to send to the client
     *
     * @return array<string, mixed>
     */
    abstract protected function getAjaxResponse(): array;
}
