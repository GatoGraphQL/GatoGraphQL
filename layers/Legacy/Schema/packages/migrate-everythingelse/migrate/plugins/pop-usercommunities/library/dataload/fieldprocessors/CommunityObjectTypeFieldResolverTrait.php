<?php
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

trait CommunityObjectTypeFieldResolverTrait
{
    /**
     * @param array<string, mixed> $fieldArgs
     */
    public function resolveCanProcessObject(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs = []
    ): bool {
        $user = $object;
        if (!gdUreIsCommunity($objectTypeResolver->getID($user))) {
            return false;
        }
        return parent::resolveCanProcessObject($objectTypeResolver, $object, $fieldName, $fieldArgs);
    }
}
