<?php

declare(strict_types=1);

namespace PoP\ComponentModel\AttachableExtensions;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionInterface;

interface AttachableExtensionManagerInterface
{
    public function attachExtensionToClass(string $attachableClass, string $group, AttachableExtensionInterface $attachableExtension): void;
    /**
     * @return array<string, array<string, AttachableExtensionInterface[]>
     */
    public function getAttachedExtensions(string $attachableClass, string $group): array;
}
