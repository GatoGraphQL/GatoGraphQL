<?php

declare(strict_types=1);

namespace PoP\ComponentModel\State;

use PoP\Hooks\Facades\HooksAPIFacade;

class ApplicationState
{
    /**
     * @var array<string, mixed>
     */
    public static array $vars = [];

    /**
     * @return array<string, mixed>
     */
    public static function getVars(): array
    {
        // Only initialize the first time. Then, it will call ->resetState() to retrieve new state, no need to create a new instance        
        if (self::$vars) {
            return self::$vars;
        }

        self::$vars = [];

        // Allow for plug-ins to add their own vars
        $hooksAPI = HooksAPIFacade::getInstance();
        $hooksAPI->doAction(
            'ApplicationState:addVars',
            array(&self::$vars)
        );

        self::augmentVarsProperties();

        return self::$vars;
    }

    public static function augmentVarsProperties(): void
    {
        $hooksAPI = HooksAPIFacade::getInstance();
        $hooksAPI->doAction(
            'augmentVarsProperties',
            array(&self::$vars)
        );
    }
}
