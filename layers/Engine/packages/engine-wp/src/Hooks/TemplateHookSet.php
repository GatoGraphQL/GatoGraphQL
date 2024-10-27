<?php

declare(strict_types=1);

namespace PoP\EngineWP\Hooks;

use PoP\ComponentModel\HelperServices\ApplicationStateHelperServiceInterface;
use PoP\EngineWP\HelperServices\TemplateHelpersInterface;
use PoP\Root\Hooks\AbstractHookSet;

class TemplateHookSet extends AbstractHookSet
{
    private ?ApplicationStateHelperServiceInterface $applicationStateHelperService = null;
    private ?TemplateHelpersInterface $templateHelpers = null;

    final protected function getApplicationStateHelperService(): ApplicationStateHelperServiceInterface
    {
        if ($this->applicationStateHelperService === null) {
            /** @var ApplicationStateHelperServiceInterface */
            $applicationStateHelperService = $this->instanceManager->getInstance(ApplicationStateHelperServiceInterface::class);
            $this->applicationStateHelperService = $applicationStateHelperService;
        }
        return $this->applicationStateHelperService;
    }
    final protected function getTemplateHelpers(): TemplateHelpersInterface
    {
        if ($this->templateHelpers === null) {
            /** @var TemplateHelpersInterface */
            $templateHelpers = $this->instanceManager->getInstance(TemplateHelpersInterface::class);
            $this->templateHelpers = $templateHelpers;
        }
        return $this->templateHelpers;
    }

    protected function init(): void
    {
        \add_filter(
            'template_include',
            $this->getTemplate(...),
            PHP_INT_MAX // Execute last
        );
    }

    public function getTemplate(string $template): string
    {
        if ($this->useTemplate()) {
            return $this->getTemplateHelpers()->getGenerateDataAndPrepareAndSendResponseTemplateFile();
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
