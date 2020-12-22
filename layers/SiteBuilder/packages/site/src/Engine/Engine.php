<?php

declare(strict_types=1);

namespace PoP\Site\Engine;

class Engine extends \PoP\Application\Engine\Engine
{
    public function outputResponse(): void
    {
        // If doing JSON, the response from the parent is already adequate
        if (doingJson()) {
            parent::outputResponse();
            return;
        }

        // Before anything: check if to do a redirect, and exit
        $this->maybeRedirectAndExit();

        // 1. Generate the data
        $this->generateData();

        // 2. Print the HTML
        // Code implemented maybe in pop-engine-htmlcssplatform/templates/index.php
        echo '<html><body>TODO</body></html>';
    }
}
