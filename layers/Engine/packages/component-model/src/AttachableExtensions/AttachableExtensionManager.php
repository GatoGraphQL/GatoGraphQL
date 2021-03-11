<?php

declare(strict_types=1);

namespace PoP\ComponentModel\AttachableExtensions;

class AttachableExtensionManager implements AttachableExtensionManagerInterface
{
    /**
     * @var array<string, array>
     */
    protected array $extensionClasses = [];

    public function setExtensionClass(string $attachableClass, string $group, string $extensionClass): void
    {
        $this->extensionClasses[$attachableClass][$group][] = $extensionClass;
    }

    public function getExtensionClasses(string $attachableClass, string $group): array
    {
        return $this->extensionClasses[$attachableClass][$group] ?? [];
    }
}
