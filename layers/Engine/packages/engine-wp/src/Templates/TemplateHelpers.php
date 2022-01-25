<?php

declare(strict_types=1);

namespace PoP\EngineWP\Templates;

use PoP\Root\App;
use PoP\EngineWP\Component;
use PoP\EngineWP\ComponentInfo;

class TemplateHelpers
{
    public static function getTemplateFile(): string
    {
        /** @var ComponentInfo */
        $componentInfo = App::getComponent(Component::class)->getInfo();
        return $componentInfo->getTemplatesDir() . '/GenerateDataAndPrintOutput.php';
    }
}
