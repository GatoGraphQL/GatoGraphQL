<?php
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

trait IndividualFieldResolverTrait
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
        if (!gdUreIsIndividual($relationalTypeResolver->getID($user))) {
            return false;
        }
        return parent::resolveCanProcessResultItem($relationalTypeResolver, $resultItem, $fieldName, $fieldArgs);
    }
}
