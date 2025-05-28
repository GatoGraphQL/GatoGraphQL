<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ObjectModels;

abstract class AbstractDependedOnWordPressTheme
{
    public readonly string $url;

    /**
     * @param string[] $alternativeSlugs
     */
    public function __construct(
        public readonly string $name,
        public readonly string $slug,
        public readonly array $alternativeSlugs = [],
        ?string $url = null,
    ) {
        $this->url = $this->calculateURL($url, $slug);
    }

    /**
     * Passing a `null` URL, it builds the URL pointing to the WP repo.
     * To avoid building this URL, instantiate it with empty string.
     */
    protected function calculateURL(?string $url, string $slug): string
    {
        if ($url !== null) {
            return $url;
        }
        return sprintf(
            'https://wordpress.org/themes/%s/',
            $slug
        );
    }
}
