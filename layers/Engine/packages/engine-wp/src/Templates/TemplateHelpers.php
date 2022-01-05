<?php

declare(strict_types=1);

namespace PoP\EngineWP\Templates;

use PoP\Engine\App;
use PoP\EngineWP\ComponentInterface;
use PoP\EngineWP\Component;

class TemplateHelpers
{
    public static function getTemplateFile(): string
    {
        /** @var ComponentInterface */
        $component = App::getComponentManager()->getComponent(Component::class);
        return $component->getTemplatesDir() . '/Output.php';
    }
}
