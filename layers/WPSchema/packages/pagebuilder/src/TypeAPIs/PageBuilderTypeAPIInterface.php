<?php

declare(strict_types=1);

namespace PoPWPSchema\PageBuilder\TypeAPIs;

interface PageBuilderTypeAPIInterface
{
    /**
     * Return the IDs of the Page Builders installed on
     * the site. The ID must not contain spaces or "-".
     *
     * @return string[]
     */
    public function getInstalledPageBuilders(): array;
    /**
     * The name of the page builder that handles that CPT,
     * or `null` if none
     */
    public function getPageBuilderEnabledForCustomPostType(string $customPostType): ?string;
}
