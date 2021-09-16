<?php
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\CustomPostMedia\Misc\MediaHelpers as CustomPostMediaHelpers;
use PoPSchema\CustomPosts\TypeResolvers\ObjectType\AbstractCustomPostObjectTypeResolver;
use PoPSchema\Media\Facades\MediaTypeAPIFacade;
use PoPSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

class PoP_Application_DataLoad_ObjectTypeFieldResolver_Posts extends AbstractObjectTypeFieldResolver
{
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractCustomPostObjectTypeResolver::class,
        ];
    }

    public function getThumb($post, ObjectTypeResolverInterface $objectTypeResolver, $size = null, $add_description = false)
    {
        $thumb_id = CustomPostMediaHelpers::getThumbId($objectTypeResolver->getID($post));
        $mediaTypeAPI = MediaTypeAPIFacade::getInstance();
        $img = $mediaTypeAPI->getImageProperties($thumb_id, $size);

        // Add the image description
        if ($add_description && $img) {
            $cmsmediaapi = \PoPSchema\Media\FunctionAPIFactory::getInstance();
            if ($description = $cmsmediaapi->getMediaDescription($thumb_id)) {
                $img['description'] = $description;
            }
        }

        return $img;
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'favicon',
            'thumb',
            'thumbFullSrc',
            'authors',
            'topics',
            'hasTopics',
            'appliesto',
            'hasAppliesto',
            'hasUserpostactivity',
            'userPostActivityCount',
        ];
    }

    public function getFieldTypeResolverClass(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): string
    {
        return match ($fieldName) {
            'favicon' => \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\ObjectScalarTypeResolver::class,
            'thumb' => \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\ObjectScalarTypeResolver::class,
            'thumbFullSrc' => \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver::class,
            'topics' => \PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver::class,
            'hasTopics' => \PoP\Engine\TypeResolvers\ScalarType\BooleanScalarTypeResolver::class,
            'appliesto' => \PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver::class,
            'hasAppliesto' => \PoP\Engine\TypeResolvers\ScalarType\BooleanScalarTypeResolver::class,
            'hasUserpostactivity' => \PoP\Engine\TypeResolvers\ScalarType\BooleanScalarTypeResolver::class,
            'userPostActivityCount' => \PoP\Engine\TypeResolvers\ScalarType\IntScalarTypeResolver::class,
            'authors' => UserObjectTypeResolver::class,
            default => parent::getFieldTypeResolverClass($objectTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?int
    {
        return match($fieldName) {
            'authors',
            'topics',
            'appliesto'
                => SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getSchemaFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
			'favicon' => $translationAPI->__('', ''),
            'thumb' => $translationAPI->__('', ''),
            'thumbFullSrc' => $translationAPI->__('', ''),
            'authors' => $translationAPI->__('', ''),
            'topics' => $translationAPI->__('', ''),
            'hasTopics' => $translationAPI->__('', ''),
            'appliesto' => $translationAPI->__('', ''),
            'hasAppliesto' => $translationAPI->__('', ''),
            'hasUserpostactivity' => $translationAPI->__('', ''),
            'userPostActivityCount' => $translationAPI->__('', ''),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($objectTypeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($objectTypeResolver, $fieldName);
        $translationAPI = TranslationAPIFacade::getInstance();
        switch ($fieldName) {
            case 'favicon':
            case 'thumb':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'size',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('Thumbnail size', 'pop-posts'),
                            SchemaDefinition::ARGNAME_DEFAULT_VALUE => $this->getDefaultThumbSize(),
                        ],
                        [
                            SchemaDefinition::ARGNAME_NAME => 'addDescription',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_BOOL,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('Add description on the thumb', 'pop-posts'),
                            SchemaDefinition::ARGNAME_DEFAULT_VALUE => false,
                        ],
                    ]
                );
        }

        return $schemaFieldArgs;
    }

    protected function getDefaultThumbSize(): string
    {
        return 'thumb-md';
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
        $post = $object;
        switch ($fieldName) {
            case 'favicon':
            case 'thumb':
                return $this->getThumb($post, $objectTypeResolver, $fieldArgs['size'], $fieldArgs['addDescription']);

            case 'thumbFullSrc':
                $thumb = $objectTypeResolver->resolveValue($post, FieldQueryInterpreterFacade::getInstance()->getField('thumb', ['size' => 'full', 'addDescription' => true]), $variables, $expressions, $options);
                if (GeneralUtils::isError($thumb)) {
                    return $thumb;
                }
                return $thumb['src'];

            case 'authors':
                return gdGetPostauthors($objectTypeResolver->getID($post));

            case 'topics':
                return \PoPSchema\CustomPostMeta\Utils::getCustomPostMeta($objectTypeResolver->getID($post), GD_METAKEY_POST_CATEGORIES);

            case 'hasTopics':
                $topics = $objectTypeResolver->resolveValue($post, 'topics', $variables, $expressions, $options);
                if (GeneralUtils::isError($topics)) {
                    return $topics;
                } elseif ($topics) {
                    return true;
                }
                return false;

            case 'appliesto':
                return \PoPSchema\CustomPostMeta\Utils::getCustomPostMeta($objectTypeResolver->getID($post), GD_METAKEY_POST_APPLIESTO);

            case 'hasAppliesto':
                $appliesto = $objectTypeResolver->resolveValue($post, 'appliesto', $variables, $expressions, $options);
                if (GeneralUtils::isError($appliesto)) {
                    return $appliesto;
                } elseif ($appliesto) {
                    return true;
                }
                return false;

            case 'hasUserpostactivity':
                // User Post Activity: Comments + Responses/Additionals + Hightlights
                $hasComments = $objectTypeResolver->resolveValue($object, 'hasComments', $variables, $expressions, $options);
                if ($hasComments) {
                    return $hasComments;
                }
                $hasReferencedBy = $objectTypeResolver->resolveValue($object, 'hasReferencedBy', $variables, $expressions, $options);
                if ($hasReferencedBy) {
                    return $hasReferencedBy;
                }
                $hasHighlights = $objectTypeResolver->resolveValue($object, 'hasHighlights', $variables, $expressions, $options);
                if ($hasHighlights) {
                    return $hasHighlights;
                }
                return $hasComments || $hasReferencedBy || $hasHighlights;

            case 'userPostActivityCount':
                // User Post Activity: Comments + Responses/Additionals + Hightlights
                $commentCount = $objectTypeResolver->resolveValue($object, 'commentCount', $variables, $expressions, $options);
                if ($commentCount) {
                    return $commentCount;
                }
                $referencedByCount = $objectTypeResolver->resolveValue($object, 'referencedByCount', $variables, $expressions, $options);
                if ($referencedByCount) {
                    return $referencedByCount;
                }
                $highlightsCount = $objectTypeResolver->resolveValue($object, 'highlightsCount', $variables, $expressions, $options);
                if ($highlightsCount) {
                    return $highlightsCount;
                }
                return $commentCount + $referencedByCount + $highlightsCount;
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}

// Static Initialization: Attach
(new PoP_Application_DataLoad_ObjectTypeFieldResolver_Posts())->attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::OBJECT_TYPE_FIELD_RESOLVERS);
