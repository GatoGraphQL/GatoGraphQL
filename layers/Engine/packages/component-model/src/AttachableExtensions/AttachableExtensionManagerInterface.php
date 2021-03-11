<?php

declare(strict_types=1);

namespace PoP\ComponentModel\AttachableExtensions;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionInterface;

interface AttachableExtensionManagerInterface
{
    public function setExtensionClass(string $attachableClass, string $group, AttachableExtensionInterface $extensionClass): void;
    /**
     * @return array<string, array<string, AttachableExtensionInterface[]>
     */
    public function getAttachedExtensions(string $attachableClass, string $group): array;
}
