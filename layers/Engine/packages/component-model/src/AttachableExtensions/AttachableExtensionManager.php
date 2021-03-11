<?php

declare(strict_types=1);

namespace PoP\ComponentModel\AttachableExtensions;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionInterface;

class AttachableExtensionManager implements AttachableExtensionManagerInterface
{
    /**
     * @var array<string, array<string, AttachableExtensionInterface[]>
     */
    protected array $extensionClasses = [];

    public function setExtensionClass(string $attachableClass, string $group, AttachableExtensionInterface $extensionClass): void
    {
        $this->extensionClasses[$attachableClass][$group][] = $extensionClass;
    }

    /**
     * @return array<string, array<string, AttachableExtensionInterface[]>
     */
    public function getExtensionClasses(string $attachableClass, string $group): array
    {
        return $this->extensionClasses[$attachableClass][$group] ?? [];
    }
}
