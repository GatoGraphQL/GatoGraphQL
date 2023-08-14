<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

/**
 * Open documentation within the About page
 */
abstract class AbstractDocAboutMenuPage extends AbstractDocsMenuPage
{
    use DocMenuPageTrait;

    protected function openInModalWindow(): bool
    {
        return true;
    }

    protected function getContentToPrint(): string
    {
        return $this->getDocumentationContentToPrint();
    }
}
