<?php
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

class ObjectTypeFieldResolver_IndividualUsers extends AbstractObjectTypeFieldResolver
{
    use IndividualObjectTypeFieldResolverTrait;

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            UserObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'individualinterests',
            'hasIndividualDetails',
        ];
    }

    public function getSchemaFieldType(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): string
    {
        $types = [
			'individualinterests' => SchemaDefinition::TYPE_STRING,
            'hasIndividualDetails' => SchemaDefinition::TYPE_BOOL,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($objectTypeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?int
    {
        return match($fieldName) {
            'hasIndividualDetails' => SchemaTypeModifiers::NON_NULLABLE,
            'individualinterests' => SchemaTypeModifiers::IS_ARRAY,
            default => parent::getSchemaFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
			'individualinterests' => $translationAPI->__('', ''),
            'hasIndividualDetails' => $translationAPI->__('', ''),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($objectTypeResolver, $fieldName);
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $user = $object;
        switch ($fieldName) {
            case 'individualinterests':
                return \PoPSchema\UserMeta\Utils::getUserMeta($objectTypeResolver->getID($user), GD_URE_METAKEY_PROFILE_INDIVIDUALINTERESTS);

            case 'hasIndividualDetails':
                return !empty($objectTypeResolver->resolveValue($user, 'individualinterests', $variables, $expressions, $options));
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}

// Static Initialization: Attach
(new ObjectTypeFieldResolver_IndividualUsers())->attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::OBJECT_TYPE_FIELD_RESOLVERS);
