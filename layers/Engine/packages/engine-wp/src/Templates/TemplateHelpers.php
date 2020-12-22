<?php

declare(strict_types=1);

namespace PoP\EngineWP\Templates;

use PoP\EngineWP\Component;

class TemplateHelpers
{
    public static function getTemplateFile(): string
    {
        return Component::getTemplatesDir() . '/Output.php';
    }
}
