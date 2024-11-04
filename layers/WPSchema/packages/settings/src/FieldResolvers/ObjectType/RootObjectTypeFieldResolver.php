<?php

declare(strict_types=1);

namespace PoPWPSchema\Settings\FieldResolvers\ObjectType;

use PoPWPSchema\Settings\TypeAPIs\SettingsTypeAPIInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use WP_Post;

use function use_block_editor_for_post_type;

class RootObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;
    private ?SettingsTypeAPIInterface $settingsTypeAPI = null;

    final protected function getBooleanScalarTypeResolver(): BooleanScalarTypeResolver
    {
        if ($this->booleanScalarTypeResolver === null) {
            /** @var BooleanScalarTypeResolver */
            $booleanScalarTypeResolver = $this->instanceManager->getInstance(BooleanScalarTypeResolver::class);
            $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
        }
        return $this->booleanScalarTypeResolver;
    }
    final protected function getSettingsTypeAPI(): SettingsTypeAPIInterface
    {
        if ($this->settingsTypeAPI === null) {
            /** @var SettingsTypeAPIInterface */
            $settingsTypeAPI = $this->instanceManager->getInstance(SettingsTypeAPIInterface::class);
            $this->settingsTypeAPI = $settingsTypeAPI;
        }
        return $this->settingsTypeAPI;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootObjectTypeResolver::class,
        ];
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
            'isGutenbergEditorEnabled',
            'isGutenbergEditorEnabledForCustomPost',
            'isGutenbergEditorEnabledForCustomPostType',
        ];
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'isGutenbergEditorEnabled' => $this->__('Is the Gutenberg editor enabled? (i.e. the "Classic Editor" plugin is not enabled)', 'settings'),
            'isGutenbergEditorEnabledForCustomPost' => $this->__('Is the Gutenberg editor enabled for the custom post?', 'settings'),
            'isGutenbergEditorEnabledForCustomPostType' => $this->__('Is the Gutenberg editor enabled for the custom post type?', 'settings'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'isGutenbergEditorEnabled',
            'isGutenbergEditorEnabledForCustomPost',
            'isGutenbergEditorEnabledForCustomPostType'
                => $this->getBooleanScalarTypeResolver(),
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'isGutenbergEditorEnabled',
            'isGutenbergEditorEnabledForCustomPost',
            'isGutenbergEditorEnabledForCustomPostType'
                => SchemaTypeModifiers::NON_NULLABLE,
            default
                => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        /** @var WP_Post */
        $customPost = object;
        switch ($fieldDataAccessor->getFieldName()) {
            case 'isGutenbergEditorEnabled':
                return $this->getSettingsTypeAPI()->isGutenbergEditorEnabled();
            case 'isGutenbergEditorEnabledForCustomPost':
                if (!$this->getSettingsTypeAPI()->isGutenbergEditorEnabled()) {
                    return false;
                }
                return use_block_editor_for_post_type($customPost->post_type);
            case 'isGutenbergEditorEnabledForCustomPostType':
                if (!$this->getSettingsTypeAPI()->isGutenbergEditorEnabled()) {
                    return false;
                }
                return use_block_editor_for_post_type($customPost->post_type);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }

    /**
     * Since the return type is known for all the fields in this
     * FieldResolver, there's no need to validate them
     */
    public function validateResolvedFieldType(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldInterface $field,
    ): bool {
        return false;
    }
}
