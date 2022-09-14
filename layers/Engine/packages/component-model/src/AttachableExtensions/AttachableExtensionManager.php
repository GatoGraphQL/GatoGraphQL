<?php

declare(strict_types=1);

namespace PoP\ComponentModel\AttachableExtensions;

use PoP\ComponentModel\Constants\ConfigurationValues;

class AttachableExtensionManager implements AttachableExtensionManagerInterface
{
    /**
     * @var array<string,array<string,AttachableExtensionInterface[]>>
     */
    protected array $attachableExtensions = [];

    /**
     * @param string $attachableClass Class or "*" to represent _any_ class
     */
    public function attachExtensionToClass(string $attachableClass, string $group, AttachableExtensionInterface $attachableExtension): void
    {
        $this->attachableExtensions[$attachableClass][$group][] = $attachableExtension;
    }

    /**
     * @return AttachableExtensionInterface[]
     */
    public function getAttachedExtensions(string $attachableClass, string $group): array
    {
        return array_merge(
            $this->attachableExtensions[ConfigurationValues::ANY][$group] ?? [],
            $this->attachableExtensions[$attachableClass][$group] ?? []
        );
    }
}
