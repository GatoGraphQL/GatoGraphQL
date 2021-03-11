<?php

declare(strict_types=1);

namespace PoP\ComponentModel\AttachableExtensions;

interface AttachableExtensionManagerInterface
{
    public function setExtensionClass(string $attachableClass, string $group, string $extensionClass);
    public function getExtensionClasses(string $attachableClass, string $group): array;
}
