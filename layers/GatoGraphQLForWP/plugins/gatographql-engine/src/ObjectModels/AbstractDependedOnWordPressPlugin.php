<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ObjectModels;

abstract class AbstractDependedOnWordPressPlugin
{
    public readonly string $slug;
    public readonly string $url;

    /**
     * @param string[] $alternativeFiles
     */
    public function __construct(
        public readonly string $name,
        public readonly string $file,
        public readonly array $alternativeFiles = [],
        ?string $url = null,
    ) {
        $this->slug = $this->extractSlugFromPluginFile($file);
        $this->url = $this->calculateURL($url, $this->slug);
    }

    protected function extractSlugFromPluginFile(string $pluginFile): string
    {
        $pos = strpos($pluginFile, '/');
        if ($pos === false) {
            return $pluginFile;
        }
        return substr($pluginFile, 0, $pos);
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
            'https://wordpress.org/plugins/%s/',
            $slug
        );
    }
}
