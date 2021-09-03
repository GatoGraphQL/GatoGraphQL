<?php
use PoP\ComponentModel\TypeResolvers\ObjectTypeResolverInterface;

trait CommunityFieldResolverTrait
{
    /**
     * @param array<string, mixed> $fieldArgs
     */
    public function resolveCanProcessResultItem(
        ObjectTypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = []
    ): bool {
        $user = $resultItem;
        if (!gdUreIsCommunity($typeResolver->getID($user))) {
            return false;
        }
        return parent::resolveCanProcessResultItem($typeResolver, $resultItem, $fieldName, $fieldArgs);
    }
}
