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
        $moduleInfo = App::getModule(Module::class)->getInfo();
        return $moduleInfo->getTemplatesDir() . '/GenerateDataAndPrepareAndSendResponse.php';
    }

    public function getGenerateDataAndPrepareResponseTemplateFile(): string
    {
        /** @var ModuleInfo */
        $moduleInfo = App::getModule(Module::class)->getInfo();
        return $moduleInfo->getTemplatesDir() . '/GenerateDataAndPrepareResponse.php';
    }

    public function getSendResponseTemplateFile(): string
    {
        /** @var ModuleInfo */
        $moduleInfo = App::getModule(Module::class)->getInfo();
        return $moduleInfo->getTemplatesDir() . '/SendResponse.php';
    }

    /**
     * Add a hook to send the Response to the client.
     *
     * `App::isInitialized()` is needed to avoid conflicts with other plugins,
     * such as the "All In One Security" plugin.
     * @see https://github.com/GatoGraphQL/GatoGraphQL/issues/3283
     */
    public function sendResponseToClient(): void
    {
        \add_filter(
            'template_include',
            fn (?string $template) => !App::isInitialized() ? $template : $this->getSendResponseTemplateFile(),
            PHP_INT_MAX // Execute last
        );
    }
}
