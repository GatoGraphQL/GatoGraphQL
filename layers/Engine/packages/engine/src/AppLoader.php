<?php

declare(strict_types=1);

namespace PoP\Engine;

use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Root\AppLoader as RootAppLoader;
use PoP\Root\Managers\ComponentManager;

class AppLoader extends RootAppLoader
{
    /**
     * Trigger "beforeBoot", "boot" and "afterBoot" events on all the Components,
     * for them to execute any custom extra logic.
     * Override to execute functions on CMS events.
     */
    protected function bootApplicationForComponents(): void
    {
        // Boot all the components
        ComponentManager::beforeBoot();

        $hooksAPI = HooksAPIFacade::getInstance();
        $hooksAPI->addAction(
            'popcms:boot',
            function (): void {
                // Boot all the components
                ComponentManager::boot();
            },
            5
        );

        $hooksAPI->addAction(
            'popcms:boot',
            function (): void {
                // Boot all the components
                ComponentManager::afterBoot();
            },
            15
        );
    }
}
