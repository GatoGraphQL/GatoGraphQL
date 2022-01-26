<?php

declare(strict_types=1);

namespace PoP\EngineWP\Hooks;

use PoP\ComponentModel\HelperServices\ApplicationStateHelperServiceInterface;
use PoP\EngineWP\HelperServices\TemplateHelpersInterface;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;

class TemplateHookSet extends AbstractHookSet
{
    private ?ApplicationStateHelperServiceInterface $applicationStateHelperService = null;
    private ?TemplateHelpersInterface $templateHelpers = null;

    final public function setApplicationStateHelperService(ApplicationStateHelperServiceInterface $applicationStateHelperService): void
    {
        $this->applicationStateHelperService = $applicationStateHelperService;
    }
    final protected function getApplicationStateHelperService(): ApplicationStateHelperServiceInterface
    {
        return $this->applicationStateHelperService ??= $this->instanceManager->getInstance(ApplicationStateHelperServiceInterface::class);
    }
    final public function setTemplateHelpers(TemplateHelpersInterface $templateHelpers): void
    {
        $this->templateHelpers = $templateHelpers;
    }
    final protected function getTemplateHelpers(): TemplateHelpersInterface
    {
        return $this->templateHelpers ??= $this->instanceManager->getInstance(TemplateHelpersInterface::class);
    }

    protected function init(): void
    {
        App::addFilter(
            'template_include',
            [$this, 'getTemplate'],
            PHP_INT_MAX // Execute last
        );
    }
    
    public function getTemplate(string $template): string
    {
        if ($this->useTemplate()) {
            return $this->getTemplateHelpers()->getGenerateDataAndSendResponseTemplateFile();
        }
        return $template;
    }

    /**
     * If doing JSON, return the template which prints the encoded JSON
     */
    protected function useTemplate(): bool
    {
        return $this->getApplicationStateHelperService()->doingJSON();
    }
}
