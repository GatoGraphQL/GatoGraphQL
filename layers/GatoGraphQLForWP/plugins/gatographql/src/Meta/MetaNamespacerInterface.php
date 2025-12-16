<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Meta;

interface MetaNamespacerInterface
{
    public function namespaceMetaKey(string $metaKey, bool $prefixUnderscore = true): string;
}
