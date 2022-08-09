<?php
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

trait IndividualObjectTypeFieldResolverTrait
{
    /**
     * @todo This function has been removed, adapt it to whatever needs be done!
     * @param array<string, mixed> $fieldArgs
     */
    public function resolveCanProcessObject(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldDataAccessorInterface $fieldDataAccessor,
        object $object,
    ): bool {
        $user = $object;
        if (!gdUreIsIndividual($objectTypeResolver->getID($user))) {
            return false;
        }
        return parent::resolveCanProcessObject($objectTypeResolver, $fieldDataAccessor, $object);
    }
}
