<?php

declare(strict_types=1);

namespace PoPCMSSchema\MetaQueryWP\TypeAPIs;

class StubProtectedMetaKeyResolver
{
    use ProtectedMetaKeyResolverTrait;

    /**
     * @param array<string,string[]> $databaseResolvedMetaKeys
     */
    public function __construct(
        private readonly array $databaseResolvedMetaKeys = [],
    ) {
    }

    public function isProtected(string $key): bool
    {
        return $this->matchesProtectedMetaKey(
            $key,
            fn (string $candidateKey): bool => str_starts_with($candidateKey, '_')
        );
    }

    /**
     * @return string[]
     */
    public function getCandidates(string $key): array
    {
        return $this->getMetaKeyProtectionCandidates($key);
    }

    /**
     * @return string[]
     */
    protected function getDatabaseResolvedMetaKeys(string $key): array
    {
        return $this->databaseResolvedMetaKeys[$key] ?? [];
    }

    protected function getMetaDatabaseTableName(): string
    {
        return 'wp_postmeta';
    }
}
