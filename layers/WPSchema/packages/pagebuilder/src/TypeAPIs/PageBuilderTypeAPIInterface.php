<?php

declare(strict_types=1);

namespace PoPWPSchema\PageBuilder\TypeAPIs;

interface PageBuilderTypeAPIInterface
{
    /**
     * Return the ID of the Page Builders installed on
     * the site. The ID must not contain spaces or "-".
     * 
     * @return string[]
     */
    public function getInstalledPageBuilders(): array;
}
