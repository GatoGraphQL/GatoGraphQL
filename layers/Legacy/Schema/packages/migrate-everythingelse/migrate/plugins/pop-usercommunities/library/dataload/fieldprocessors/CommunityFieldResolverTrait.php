<?php
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

trait CommunityFieldResolverTrait
{
    /**
     * @param array<string, mixed> $fieldArgs
     */
    public function resolveCanProcessResultItem(
        RelationalTypeResolverInterface $relationalTypeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = []
    ): bool {
        $user = $resultItem;
        if (!gdUreIsCommunity($relationalTypeResolver->getID($user))) {
            return false;
        }
        return parent::resolveCanProcessResultItem($relationalTypeResolver, $resultItem, $fieldName, $fieldArgs);
    }
}
