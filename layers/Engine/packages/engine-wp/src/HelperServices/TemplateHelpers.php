<?php

declare(strict_types=1);

namespace PoP\EngineWP\HelperServices;

use PoP\Root\App;
use PoP\EngineWP\Component;
use PoP\EngineWP\ComponentInfo;

class TemplateHelpers implements TemplateHelpersInterface
{
    public function getGenerateDataPrepareResponseAndSendResponseTemplateFile(): string
    {
        /** @var ComponentInfo */
        $componentInfo = App::getComponent(Component::class)->getInfo();
        return $componentInfo->getTemplatesDir() . '/GenerateDataPrepareResponseAndSendResponse.php';
    }

    public function getSendResponseTemplateFile(): string
    {
        /** @var ComponentInfo */
        $componentInfo = App::getComponent(Component::class)->getInfo();
        return $componentInfo->getTemplatesDir() . '/SendResponse.php';
    }

    /**
     * Add a hook to send the Response to the client
     */
    public function sendResponseToClient(): void
    {
        App::addFilter(
            'template_include',
            fn (string $template) => $this->getSendResponseTemplateFile(),
            PHP_INT_MAX // Execute last
        );
    }
}
