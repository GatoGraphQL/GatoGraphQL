<?php
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

trait IndividualFieldResolverTrait
{
    /**
     * @param array<string, mixed> $fieldArgs
     */
    public function resolveCanProcessResultItem(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = []
    ): bool {
        $user = $resultItem;
        if (!gdUreIsIndividual($objectTypeResolver->getID($user))) {
            return false;
        }
        return parent::resolveCanProcessResultItem($objectTypeResolver, $resultItem, $fieldName, $fieldArgs);
    }
}
