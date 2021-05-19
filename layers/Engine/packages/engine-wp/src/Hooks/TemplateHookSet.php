<?php

declare(strict_types=1);

namespace PoP\EngineWP\Hooks;

use PoP\Hooks\AbstractHookSet;
use PoP\EngineWP\Templates\TemplateHelpers;

class TemplateHookSet extends AbstractHookSet
{
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
        if (doingJson()) {
            return TemplateHelpers::getTemplateFile();
        }
        return $template;
    }
}
