<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\MenuPages;

/**
 * Menu pages that trigger opening a modal
 */
trait OpenInModalTriggerMenuPageTrait
{
    /**
     * Enqueue the required assets and initialize the localized scripts
     */
    protected function enqueueModalTriggerAssets(): void
    {
        /**
         * Hack to open the modal thickbox iframe with the documentation
         */
        \wp_enqueue_style(
            'thickbox'
        );
        \wp_enqueue_script(
            'plugin-install'
        );
    }
}
