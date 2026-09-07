<?php

declare(strict_types=1);

namespace PoPCMSSchema\MetaQueryWP\TypeAPIs;

use function remove_accents;

trait ProtectedMetaKeyResolverTrait
{
    /**
     * @param callable(string):bool $isMetaKeyProtected
     */
    protected function matchesProtectedMetaKey(string $key, callable $isMetaKeyProtected): bool
    {
        foreach ($this->getMetaKeyProtectionCandidates($key) as $candidateKey) {
            if ($isMetaKeyProtected($candidateKey)) {
                return true;
            }
        }
        return false;
    }

    /**
     * The meta key columns are stored under a case, accent and
     * trailing-space insensitive collation, and some characters
     * (such as the zero-width ones) carry no weight at all. Hence
     * the database can resolve a key to a stored entry which the
     * PHP comparison, being exact, would not match.
     *
     * @return string[]
     */
    protected function getMetaKeyProtectionCandidates(string $key): array
    {
        $candidateKeys = [$key];
        $trimmedKey = trim($key);
        if ($trimmedKey !== $key) {
            $candidateKeys[] = $trimmedKey;
        }
        if (preg_match('/[^\x20-\x7E]/', $key) !== 1) {
            return $candidateKeys;
        }
        foreach ($this->getDatabaseResolvedMetaKeys($key) as $databaseResolvedMetaKey) {
            $candidateKeys[] = $databaseResolvedMetaKey;
        }
        return $candidateKeys;
    }

    protected function normalizeMetaKeyForProtection(string $key): string
    {
        return strtolower(remove_accents(trim($key)));
    }

    /**
     * @return string[]
     */
    protected function getDatabaseResolvedMetaKeys(string $key): array
    {
        global $wpdb;
        $metaTableName = $this->getMetaDatabaseTableName();
        /** @var string[] */
        return $wpdb->get_col(
            $wpdb->prepare(
                "SELECT DISTINCT meta_key FROM {$metaTableName} WHERE meta_key = %s",
                $key
            )
        );
    }

    abstract protected function getMetaDatabaseTableName(): string;
}
