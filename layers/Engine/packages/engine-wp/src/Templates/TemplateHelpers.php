<?php

declare(strict_types=1);

namespace PoP\EngineWP\Templates;

use PoP\EngineWP\ComponentInterface;
use PoP\EngineWP\Component;
use PoP\Root\Managers\ComponentManager;

class TemplateHelpers
{
    public static function getTemplateFile(): string
    {
        /** @var ComponentInterface */
        $component = \PoP\Engine\App::getComponentManager()->getComponent(Component::class);
        return $component->getTemplatesDir() . '/Output.php';
    }
}
