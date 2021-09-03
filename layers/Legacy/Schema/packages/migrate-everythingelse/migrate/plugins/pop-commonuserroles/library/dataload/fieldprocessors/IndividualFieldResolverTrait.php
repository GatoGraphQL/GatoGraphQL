<?php
use PoP\ComponentModel\TypeResolvers\ObjectTypeResolverInterface;

trait IndividualFieldResolverTrait
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
        if (!gdUreIsIndividual($typeResolver->getID($user))) {
            return false;
        }
        return parent::resolveCanProcessResultItem($typeResolver, $resultItem, $fieldName, $fieldArgs);
    }
}
