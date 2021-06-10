<?php
use PoP\ComponentModel\FieldResolvers\AbstractFunctionalFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\CustomPostMedia\Facades\CustomPostMediaTypeAPIFacade;
use PoPSchema\CustomPosts\FieldInterfaceResolvers\IsCustomPostFieldInterfaceResolver;
use PoPSchema\Media\Facades\MediaTypeAPIFacade;

class GD_ContentCreation_Media_DataLoad_FieldResolver_FunctionalPosts extends AbstractFunctionalFieldResolver
{
    public function getClassesToAttachTo(): array
    {
        return [
            IsCustomPostFieldInterfaceResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
			'featuredImageAttrs',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
			'featuredImageAttrs' => SchemaDefinition::TYPE_OBJECT,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
			'featuredImageAttrs' => $translationAPI->__('', ''),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $customPostMediaTypeAPI = CustomPostMediaTypeAPIFacade::getInstance();
        $post = $resultItem;
        switch ($fieldName) {
            case 'featuredImageAttrs':
                if ($featuredimage = $customPostMediaTypeAPI->getCustomPostThumbnailID($typeResolver->getID($post))) {
                    $mediaTypeAPI = MediaTypeAPIFacade::getInstance();
                    return $mediaTypeAPI->getImageProperties($featuredimage, 'thumb-md');
                }
                return null;
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}

// Static Initialization: Attach
(new GD_ContentCreation_Media_DataLoad_FieldResolver_FunctionalPosts())->attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::FIELDRESOLVERS);
