<?php

declare(strict_types=1);

namespace PoPSchema\Highlights\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPCMSSchema\CustomPostMeta\Utils;
use PoPCMSSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPCMSSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;
use PoPSchema\Highlights\TypeResolvers\ObjectType\HighlightObjectTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver;

class HighlightObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?URLScalarTypeResolver $urlScalarTypeResolver = null;
    
    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }
    final public function setURLScalarTypeResolver(URLScalarTypeResolver $urlScalarTypeResolver): void
    {
        $this->urlScalarTypeResolver = $urlScalarTypeResolver;
    }
    final protected function getURLScalarTypeResolver(): URLScalarTypeResolver
    {
        return $this->urlScalarTypeResolver ??= $this->instanceManager->getInstance(URLScalarTypeResolver::class);
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            HighlightObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'title',
            'excerpt',
            'content',
            'highlightedpost',
            'highlightedPostURL',
            'highlightedpost',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match($fieldName) {
            'title' => $this->getStringScalarTypeResolver(),
            'excerpt' => $this->getStringScalarTypeResolver(),
            'content' => $this->getStringScalarTypeResolver(),
            'highlightedPostURL' => $this->getUrlScalarTypeResolver(),
            'highlightedpost' => CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetObjectTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'content',
            'highlightedpost'
                => SchemaTypeModifiers::NON_NULLABLE,
            default
                => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match($fieldName) {
            'title' => $this->getTranslationAPI()->__('', ''),
            'excerpt' => $this->getTranslationAPI()->__('', ''),
            'content' => $this->getTranslationAPI()->__('', ''),
            'highlightedpost' => $this->getTranslationAPI()->__('', ''),
            'highlightedPostURL' => $this->getTranslationAPI()->__('', ''),
            'highlightedpost' => $this->getTranslationAPI()->__('', ''),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
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
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        $highlight = $object;
        switch ($fieldName) {
         // Override fields for Highlights
         // The Stance has no title, so return the excerpt instead.
         // Needed for when adding a comment on the Stance, where it will say: Add comment for...
            case 'title':
            case 'excerpt':
            case 'content':
                $value = $customPostTypeAPI->getPlainTextContent($highlight);
                if ($fieldName == 'title') {
                    return limitString($value, 100);
                } elseif ($fieldName == 'excerpt') {
                    return limitString($value, 300);
                }
                return $value;

            case 'highlightedpost':
                return Utils::getCustomPostMeta($objectTypeResolver->getID($highlight), GD_METAKEY_POST_HIGHLIGHTEDPOST, true);

            case 'highlightedPostURL':
                $highlightedPost = $objectTypeResolver->resolveValue($highlight, 'highlightedpost', $variables, $expressions, $options);
                if (GeneralUtils::isError($highlightedPost)) {
                    return $highlightedPost;
                }
                return $customPostTypeAPI->getPermalink($highlightedPost);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
