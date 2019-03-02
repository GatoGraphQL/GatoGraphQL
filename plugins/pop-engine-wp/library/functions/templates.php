<?php

// Priority: execute last
\PoP\CMS\HooksAPI_Factory::getInstance()->addFilter('template_include', 'popEngineTemplateIncludeJson', PHP_INT_MAX);
function popEngineTemplateIncludeJson($template)
{

    // If doing JSON, for sure return json.php which only prints the encoded JSON
    if (doingJson()) {
        return POP_ENGINE_TEMPLATES.'/json.php';
    }
    // Otherwise, if the theme doesn't implement the template, use the default one
    elseif (!$template) {
        return POP_ENGINE_TEMPLATES.'/index.php';
    }

    return $template;
}
