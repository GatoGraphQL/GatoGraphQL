<?php

declare(strict_types=1);

namespace PoP\SiteWP\Hooks;

use PoP\Hooks\AbstractHookSet;
use PoP\EngineWP\Component;

class TemplateHookSet extends AbstractHookSet
{
    protected function init()
    {
        $this->hooksAPI->addFilter(
            'template_include',
            [$this, 'setTemplate']
        );
    }
    public function setTemplate(string $template): string
    {
        // If doing JSON, for sure return json.php which only prints the encoded JSON
        if (!\doingJson()) {
            return Component::getTemplatesDir() . '/Output.php';
        }
        return $template;
    }
}
