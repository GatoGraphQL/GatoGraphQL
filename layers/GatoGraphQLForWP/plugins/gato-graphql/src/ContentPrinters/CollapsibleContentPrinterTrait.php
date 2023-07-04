<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ContentPrinters;

trait CollapsibleContentPrinterTrait
{
    protected function getCollapsible(
        string $content,
        ?string $showDetailsLabel = null,
    ): string {
        return sprintf(
            '<a href="#" type="button" class="collapsible">%s</a><span class="collapsible-content">%s</span>',
            $showDetailsLabel ?? \__('Show details', 'gato-graphql'),
            $content
        );
    }
}
