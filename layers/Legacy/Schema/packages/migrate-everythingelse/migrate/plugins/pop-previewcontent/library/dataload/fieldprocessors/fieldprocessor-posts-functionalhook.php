<?php
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\CustomPosts\TypeResolvers\ObjectType\AbstractCustomPostObjectTypeResolver;

class PPPPoP_DataLoad_ObjectTypeFieldResolver_FunctionalProfiles extends AbstractObjectTypeFieldResolver
{
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractCustomPostObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
			'previewURL',
        ];
    }

    public function getSchemaFieldType(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): string
    {
        $types = [
			'previewURL' => SchemaDefinition::TYPE_URL,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($objectTypeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
			'previewURL' => $translationAPI->__('', ''),
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
        $pluginapi = PoP_PreviewContent_FunctionsAPIFactory::getInstance();
        $post = $object;
        switch ($fieldName) {
            case 'previewURL':
                // Use function getID to also cater for events (whose ID is $event->post_id)
                return $pluginapi->getPreviewLink($objectTypeResolver->getID($post));
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}

// Static Initialization: Attach
(new PPPPoP_DataLoad_ObjectTypeFieldResolver_FunctionalProfiles())->attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::OBJECT_TYPE_FIELD_RESOLVERS);
