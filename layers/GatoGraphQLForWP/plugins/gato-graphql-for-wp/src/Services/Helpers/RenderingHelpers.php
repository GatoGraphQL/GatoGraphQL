<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\Helpers;

class RenderingHelpers
{
    /**
     * Do not output the content, and show an error message to the visitor
     */
    public function getUnauthorizedAccessMessage(): string
    {
        return \__('You are not authorized to see this content', 'gato-graphql');
    }

    /**
     * Do not output the content, and show an error message to the visitor
     */
    public function getUnauthorizedAccessHTMLMessage(): string
    {
        return sprintf(
            '<p>%s</p>',
            $this->getUnauthorizedAccessMessage()
        );
    }
}
