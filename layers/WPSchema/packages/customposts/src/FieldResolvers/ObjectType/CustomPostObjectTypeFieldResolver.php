<?php

declare(strict_types=1);

namespace PoPWPSchema\CustomPosts\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\AbstractCustomPostObjectTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\HTMLScalarTypeResolver;
use PoPWPSchema\CustomPosts\Module;
use PoPWPSchema\CustomPosts\ModuleConfiguration;
use PoP\ComponentModel\App;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use WP_Post;

class CustomPostObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?HTMLScalarTypeResolver $htmlScalarTypeResolver = null;

    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        if ($this->stringScalarTypeResolver === null) {
            /** @var StringScalarTypeResolver */
            $stringScalarTypeResolver = $this->instanceManager->getInstance(StringScalarTypeResolver::class);
            $this->stringScalarTypeResolver = $stringScalarTypeResolver;
        }
        return $this->stringScalarTypeResolver;
    }
    final protected function getHTMLScalarTypeResolver(): HTMLScalarTypeResolver
    {
        if ($this->htmlScalarTypeResolver === null) {
            /** @var HTMLScalarTypeResolver */
            $htmlScalarTypeResolver = $this->instanceManager->getInstance(HTMLScalarTypeResolver::class);
            $this->htmlScalarTypeResolver = $htmlScalarTypeResolver;
        }
        return $this->htmlScalarTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractCustomPostObjectTypeResolver::class,
        ];
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
            'wpAdminEditURL',
        ];
    }

    /**
     * @return string[]
     */
    public function getSensitiveFieldNames(): array
    {
        $sensitiveFieldNames = parent::getSensitiveFieldNames();
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($moduleConfiguration->treatCustomPostEditURLAsSensitiveData()) {
            $sensitiveFieldNames[] = 'wpAdminEditURL';
        }
        return $sensitiveFieldNames;
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'wpAdminEditURL' => $this->__('The URL in the wp-admin to edit the custom post, or `null` if the user has no permissions to access it', 'customposts'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'wpAdminEditURL' => $this->getHTMLScalarTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        /** @var WP_Post */
        $customPost = $object;
        switch ($fieldDataAccessor->getFieldName()) {
            case 'wpAdminEditURL':
                // Validate the user can edit the post
                if (!App::getState('is-user-logged-in')) {
                    return null;
                }
                /** @var int */
                $userID = App::getState('current-user-id');
                if (!user_can($userID, 'edit_post', $customPost->ID)) {
                    return null;
                }
                return \get_edit_post_link($customPost);
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
