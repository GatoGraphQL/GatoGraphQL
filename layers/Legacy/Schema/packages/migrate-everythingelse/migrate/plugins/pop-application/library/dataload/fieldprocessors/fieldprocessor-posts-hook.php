<?php
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPCMSSchema\CustomPostMedia\Misc\MediaHelpers as CustomPostMediaHelpers;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\AbstractCustomPostObjectTypeResolver;
use PoPCMSSchema\Media\Facades\MediaTypeAPIFacade;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

class PoP_Application_DataLoad_ObjectTypeFieldResolver_Posts extends AbstractObjectTypeFieldResolver
{
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;
    
    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }
    final public function setBooleanScalarTypeResolver(BooleanScalarTypeResolver $booleanScalarTypeResolver): void
    {
        $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
    }
    final protected function getBooleanScalarTypeResolver(): BooleanScalarTypeResolver
    {
        return $this->booleanScalarTypeResolver ??= $this->instanceManager->getInstance(BooleanScalarTypeResolver::class);
    }

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
            $cmsmediaapi = \PoPCMSSchema\Media\FunctionAPIFactory::getInstance();
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

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): \PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'favicon' => \PoP\Engine\TypeResolvers\ScalarType\JSONObjectScalarTypeResolver::class,
            'thumb' => \PoP\Engine\TypeResolvers\ScalarType\JSONObjectScalarTypeResolver::class,
            'thumbFullSrc' => \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver::class,
            'topics' => \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver::class,
            'hasTopics' => \PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver::class,
            'appliesto' => \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver::class,
            'hasAppliesto' => \PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver::class,
            'hasUserpostactivity' => \PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver::class,
            'userPostActivityCount' => \PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver::class,
            'authors' => UserObjectTypeResolver::class,
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match($fieldName) {
            'authors',
            'topics',
            'appliesto'
                => SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match($fieldName) {
			'favicon' => $this->getTranslationAPI()->__('', ''),
            'thumb' => $this->getTranslationAPI()->__('', ''),
            'thumbFullSrc' => $this->getTranslationAPI()->__('', ''),
            'authors' => $this->getTranslationAPI()->__('', ''),
            'topics' => $this->getTranslationAPI()->__('', ''),
            'hasTopics' => $this->getTranslationAPI()->__('', ''),
            'appliesto' => $this->getTranslationAPI()->__('', ''),
            'hasAppliesto' => $this->getTranslationAPI()->__('', ''),
            'hasUserpostactivity' => $this->getTranslationAPI()->__('', ''),
            'userPostActivityCount' => $this->getTranslationAPI()->__('', ''),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            'favicon',
            'thumb' => [
                'size' => $this->stringScalarTypeResolver,
                'addDescription' => $this->booleanScalarTypeResolver,
            ],
            default => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }
    
    public function getFieldArgDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?string
    {
        return match ($fieldArgName) {
            'size' => $this->getTranslationAPI()->__('Thumbnail size', 'pop-posts'),
            'addDescription' => $this->getTranslationAPI()->__('Add description on the thumb', 'pop-posts'),
            default => parent::getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }
    
    public function getFieldArgDefaultValue(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): mixed
    {
        return match ($fieldArgName) {
            'size' => $this->getDefaultThumbSize(),
            'addDescription' => false,
            default => parent::getFieldArgDefaultValue($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    protected function getDefaultThumbSize(): string
    {
        return 'thumb-md';
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed> $variables
     * @param array<string, mixed> $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs,
        array $variables,
        array $expressions,
        array $options = []
    ): mixed {
        $post = $object;
        switch ($fieldName) {
            case 'favicon':
            case 'thumb':
                return $this->getThumb($post, $objectTypeResolver, $fieldArgs['size'], $fieldArgs['addDescription']);

            case 'thumbFullSrc':
                $thumb = $objectTypeResolver->resolveValue($post, FieldQueryInterpreterFacade::getInstance()->getField('thumb', ['size' => 'full', 'addDescription' => true]), $variables, $expressions, $objectTypeFieldResolutionFeedbackStore, $options);
                if (GeneralUtils::isError($thumb)) {
                    return $thumb;
                }
                return $thumb['src'];

            case 'authors':
                return gdGetPostauthors($objectTypeResolver->getID($post));

            case 'topics':
                return \PoPCMSSchema\CustomPostMeta\Utils::getCustomPostMeta($objectTypeResolver->getID($post), GD_METAKEY_POST_CATEGORIES);

            case 'hasTopics':
                $topics = $objectTypeResolver->resolveValue($post, 'topics', $variables, $expressions, $objectTypeFieldResolutionFeedbackStore, $options);
                if (GeneralUtils::isError($topics)) {
                    return $topics;
                } elseif ($topics) {
                    return true;
                }
                return false;

            case 'appliesto':
                return \PoPCMSSchema\CustomPostMeta\Utils::getCustomPostMeta($objectTypeResolver->getID($post), GD_METAKEY_POST_APPLIESTO);

            case 'hasAppliesto':
                $appliesto = $objectTypeResolver->resolveValue($post, 'appliesto', $variables, $expressions, $objectTypeFieldResolutionFeedbackStore, $options);
                if (GeneralUtils::isError($appliesto)) {
                    return $appliesto;
                } elseif ($appliesto) {
                    return true;
                }
                return false;

            case 'hasUserpostactivity':
                // User Post Activity: Comments + Responses/Additionals + Hightlights
                $hasComments = $objectTypeResolver->resolveValue($object, 'hasComments', $variables, $expressions, $objectTypeFieldResolutionFeedbackStore, $options);
                if ($hasComments) {
                    return $hasComments;
                }
                $hasReferencedBy = $objectTypeResolver->resolveValue($object, 'hasReferencedBy', $variables, $expressions, $objectTypeFieldResolutionFeedbackStore, $options);
                if ($hasReferencedBy) {
                    return $hasReferencedBy;
                }
                $hasHighlights = $objectTypeResolver->resolveValue($object, 'hasHighlights', $variables, $expressions, $objectTypeFieldResolutionFeedbackStore, $options);
                if ($hasHighlights) {
                    return $hasHighlights;
                }
                return $hasComments || $hasReferencedBy || $hasHighlights;

            case 'userPostActivityCount':
                // User Post Activity: Comments + Responses/Additionals + Hightlights
                $commentCount = $objectTypeResolver->resolveValue($object, 'commentCount', $variables, $expressions, $objectTypeFieldResolutionFeedbackStore, $options);
                if ($commentCount) {
                    return $commentCount;
                }
                $referencedByCount = $objectTypeResolver->resolveValue($object, 'referencedByCount', $variables, $expressions, $objectTypeFieldResolutionFeedbackStore, $options);
                if ($referencedByCount) {
                    return $referencedByCount;
                }
                $highlightsCount = $objectTypeResolver->resolveValue($object, 'highlightsCount', $variables, $expressions, $objectTypeFieldResolutionFeedbackStore, $options);
                if ($highlightsCount) {
                    return $highlightsCount;
                }
                return $commentCount + $referencedByCount + $highlightsCount;
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $objectTypeFieldResolutionFeedbackStore, $options);
    }
}

// Static Initialization: Attach
(new PoP_Application_DataLoad_ObjectTypeFieldResolver_Posts())->attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::OBJECT_TYPE_FIELD_RESOLVERS);
