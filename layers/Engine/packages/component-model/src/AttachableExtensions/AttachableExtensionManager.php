<?php

declare(strict_types=1);

namespace PoP\ComponentModel\AttachableExtensions;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionInterface;

class AttachableExtensionManager implements AttachableExtensionManagerInterface
{
    /**
     * @var array<string, array<string, AttachableExtensionInterface[]>
     */
    protected array $attachableExtensions = [];

    public function attachExtensionToClass(string $attachableClass, string $group, AttachableExtensionInterface $attachableExtension): void
    {
        $this->attachableExtensions[$attachableClass][$group][] = $attachableExtension;
    }

    /**
     * @return array<string, array<string, AttachableExtensionInterface[]>
     */
    public function getAttachedExtensions(string $attachableClass, string $group): array
    {
        return $this->attachableExtensions[$attachableClass][$group] ?? [];
    }
}
