<?php

declare(strict_types=1);

namespace PoP\ComponentModel\AttachableExtensions;

interface AttachExtensionServiceInterface
{
    public function enqueueExtension(string $class, string $group/*, ?int $priority = null*/): void;
    public function attachExtensions(): void;
}
