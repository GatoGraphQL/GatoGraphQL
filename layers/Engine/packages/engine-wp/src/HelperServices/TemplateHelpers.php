<?php

declare(strict_types=1);

namespace PoP\EngineWP\HelperServices;

use PoP\Root\App;
use PoP\EngineWP\Module;
use PoP\EngineWP\ModuleInfo;

class TemplateHelpers implements TemplateHelpersInterface
{
    public function getGenerateDataAndPrepareAndSendResponseTemplateFile(): string
    {
        /** @var ModuleInfo */
        $componentInfo = App::getModule(Module::class)->getInfo();
        return $componentInfo->getTemplatesDir() . '/GenerateDataAndPrepareAndSendResponse.php';
    }

    public function getGenerateDataAndPrepareResponseTemplateFile(): string
    {
        /** @var ModuleInfo */
        $componentInfo = App::getModule(Module::class)->getInfo();
        return $componentInfo->getTemplatesDir() . '/GenerateDataAndPrepareResponse.php';
    }

    public function getSendResponseTemplateFile(): string
    {
        /** @var ModuleInfo */
        $componentInfo = App::getModule(Module::class)->getInfo();
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
