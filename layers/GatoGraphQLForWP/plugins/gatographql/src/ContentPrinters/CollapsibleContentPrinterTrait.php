<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ContentPrinters;

use function preg_replace;

trait CollapsibleContentPrinterTrait
{
    protected function getCollapsible(
        string $content,
        ?string $showDetailsLabel = null,
    ): string {
        return sprintf(
            '<details class="gato-collapsible"><summary>%s</summary><div class="gato-collapsible-content">%s</div></details>',
            $showDetailsLabel ?? \__('Show details', 'gatographql'),
            $this->trimCollapsibleContentLeadingLineBreaks($content)
        );
    }

    private function trimCollapsibleContentLeadingLineBreaks(string $content): string
    {
        return preg_replace('/^(\s*<br\s*\/?>)+/i', '', $content) ?? $content;
    }
}
