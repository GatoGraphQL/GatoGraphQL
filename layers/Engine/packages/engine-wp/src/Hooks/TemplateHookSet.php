<?php

declare(strict_types=1);

namespace PoP\EngineWP\Hooks;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\ComponentModel\HelperServices\ApplicationStateHelperServiceInterface;
use PoP\EngineWP\Templates\TemplateHelpers;
use PoP\Hooks\AbstractHookSet;

class TemplateHookSet extends AbstractHookSet
{
    protected ApplicationStateHelperServiceInterface $applicationStateHelperService;

    #[Required]
    public function autowireTemplateHookSet(
        ApplicationStateHelperServiceInterface $applicationStateHelperService,
    ): void {
        $this->applicationStateHelperService = $applicationStateHelperService;
    }

    protected function init(): void
    {
        $this->hooksAPI->addFilter(
            'template_include',
            [$this, 'setTemplate'],
            // Execute last
            PHP_INT_MAX
        );
    }
    public function setTemplate(string $template): string
    {
        // If doing JSON, for sure return json.php which only prints the encoded JSON
        if ($this->applicationStateHelperService->doingJSON()) {
            return TemplateHelpers::getTemplateFile();
        }
        return $template;
    }
}
