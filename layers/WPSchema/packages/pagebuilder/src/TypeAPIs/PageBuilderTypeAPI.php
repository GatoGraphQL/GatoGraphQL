<?php

declare(strict_types=1);

namespace PoPWPSchema\PageBuilder\TypeAPIs;

use PoP\Root\Services\AbstractBasicService;

class PageBuilderTypeAPI extends AbstractBasicService implements PageBuilderTypeAPIInterface
{
    /**
     * Return the IDs of the Page Builders installed on
     * the site. The ID must not contain spaces or "-".
     * 
     * @return string[]
     */
    public function getInstalledPageBuilders(): array
    {
        // @todo Implement getInstalledPageBuilders()!
        return [];
    }
}
