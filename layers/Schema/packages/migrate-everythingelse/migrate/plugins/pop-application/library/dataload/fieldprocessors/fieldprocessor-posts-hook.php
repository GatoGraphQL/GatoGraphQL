<?php
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\TypeCastingHelpers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\CustomPostMedia\Misc\MediaHelpers as CustomPostMediaHelpers;
use PoPSchema\CustomPosts\FieldInterfaceResolvers\IsCustomPostFieldInterfaceResolver;
use PoPSchema\Media\Facades\MediaTypeAPIFacade;
use PoPSchema\Users\TypeResolvers\UserTypeResolver;

class PoP_Application_DataLoad_FieldResolver_Posts extends AbstractDBDataFieldResolver
{
    public function getClassesToAttachTo(): array
    {
        return array(
            IsCustomPostFieldInterfaceResolver::class,
        );
    }

    public function getThumb($post, TypeResolverInterface $typeResolver, $size = null, $add_description = false)
    {
        $thumb_id = CustomPostMediaHelpers::getThumbId($typeResolver->getID($post));
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

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $types = [
			'favicon' => SchemaDefinition::TYPE_OBJECT,
            'thumb' => SchemaDefinition::TYPE_OBJECT,
            'thumbFullSrc' => SchemaDefinition::TYPE_URL,
            'authors' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_ID),
            'topics' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_STRING),
            'hasTopics' => SchemaDefinition::TYPE_BOOL,
            'appliesto' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_STRING),
            'hasAppliesto' => SchemaDefinition::TYPE_BOOL,
            'hasUserpostactivity' => SchemaDefinition::TYPE_BOOL,
            'userPostActivityCount' => SchemaDefinition::TYPE_INT,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
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
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($typeResolver, $fieldName);
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
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $post = $resultItem;
        switch ($fieldName) {
            case 'favicon':
            case 'thumb':
                return $this->getThumb($post, $typeResolver, $fieldArgs['size'], $fieldArgs['addDescription']);

            case 'thumbFullSrc':
                $thumb = $typeResolver->resolveValue($post, FieldQueryInterpreterFacade::getInstance()->getField('thumb', ['size' => 'full', 'addDescription' => true]), $variables, $expressions, $options);
                if (GeneralUtils::isError($thumb)) {
                    return $thumb;
                }
                return $thumb['src'];

            case 'authors':
                return gdGetPostauthors($typeResolver->getID($post));

            case 'topics':
                return \PoPSchema\CustomPostMeta\Utils::getCustomPostMeta($typeResolver->getID($post), GD_METAKEY_POST_CATEGORIES);

            case 'hasTopics':
                $topics = $typeResolver->resolveValue($post, 'topics', $variables, $expressions, $options);
                if (GeneralUtils::isError($topics)) {
                    return $topics;
                } elseif ($topics) {
                    return true;
                }
                return false;

            case 'appliesto':
                return \PoPSchema\CustomPostMeta\Utils::getCustomPostMeta($typeResolver->getID($post), GD_METAKEY_POST_APPLIESTO);

            case 'hasAppliesto':
                $appliesto = $typeResolver->resolveValue($post, 'appliesto', $variables, $expressions, $options);
                if (GeneralUtils::isError($appliesto)) {
                    return $appliesto;
                } elseif ($appliesto) {
                    return true;
                }
                return false;

            case 'hasUserpostactivity':
                // User Post Activity: Comments + Responses/Additionals + Hightlights
                $hasComments = $typeResolver->resolveValue($resultItem, 'hasComments', $variables, $expressions, $options);
                if ($hasComments) {
                    return $hasComments;
                }
                $hasReferencedBy = $typeResolver->resolveValue($resultItem, 'hasReferencedBy', $variables, $expressions, $options);
                if ($hasReferencedBy) {
                    return $hasReferencedBy;
                }
                $hasHighlights = $typeResolver->resolveValue($resultItem, 'hasHighlights', $variables, $expressions, $options);
                if ($hasHighlights) {
                    return $hasHighlights;
                }
                return $hasComments || $hasReferencedBy || $hasHighlights;

            case 'userPostActivityCount':
                // User Post Activity: Comments + Responses/Additionals + Hightlights
                $commentCount = $typeResolver->resolveValue($resultItem, 'commentCount', $variables, $expressions, $options);
                if ($commentCount) {
                    return $commentCount;
                }
                $referencedByCount = $typeResolver->resolveValue($resultItem, 'referencedByCount', $variables, $expressions, $options);
                if ($referencedByCount) {
                    return $referencedByCount;
                }
                $highlightsCount = $typeResolver->resolveValue($resultItem, 'highlightsCount', $variables, $expressions, $options);
                if ($highlightsCount) {
                    return $highlightsCount;
                }
                return $commentCount + $referencedByCount + $highlightsCount;
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function resolveFieldTypeResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'authors':
                return UserTypeResolver::class;
        }

        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}

// Static Initialization: Attach
(new PoP_Application_DataLoad_FieldResolver_Posts())->attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::FIELDRESOLVERS);
