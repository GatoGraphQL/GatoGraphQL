<?php
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractFunctionalFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\CustomPostMedia\Facades\CustomPostMediaTypeAPIFacade;
use PoPSchema\CustomPosts\TypeResolvers\ObjectType\AbstractCustomPostTypeResolver;
use PoPSchema\Media\Facades\MediaTypeAPIFacade;

class GD_ContentCreation_Media_DataLoad_FieldResolver_FunctionalPosts extends AbstractFunctionalFieldResolver
{
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractCustomPostTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
			'featuredImageAttrs',
        ];
    }

    public function getSchemaFieldType(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): string
    {
        $types = [
			'featuredImageAttrs' => SchemaDefinition::TYPE_OBJECT,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($relationalTypeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
			'featuredImageAttrs' => $translationAPI->__('', ''),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($relationalTypeResolver, $fieldName);
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        RelationalTypeResolverInterface $relationalTypeResolver,
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
                if ($featuredimage = $customPostMediaTypeAPI->getCustomPostThumbnailID($relationalTypeResolver->getID($post))) {
                    $mediaTypeAPI = MediaTypeAPIFacade::getInstance();
                    return $mediaTypeAPI->getImageProperties($featuredimage, 'thumb-md');
                }
                return null;
        }

        return parent::resolveValue($relationalTypeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}

// Static Initialization: Attach
(new GD_ContentCreation_Media_DataLoad_FieldResolver_FunctionalPosts())->attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::FIELDRESOLVERS);
