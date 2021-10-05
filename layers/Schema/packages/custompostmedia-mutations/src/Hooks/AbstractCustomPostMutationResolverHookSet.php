<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMediaMutations\Hooks;

use PoP\ComponentModel\FieldResolvers\ObjectType\HookNames;
use PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\Hooks\AbstractHookSet;
use PoPSchema\CustomPostMediaMutations\MutationResolvers\MutationInputProperties;
use PoPSchema\CustomPostMediaMutations\TypeAPIs\CustomPostMediaTypeMutationAPIInterface;
use PoPSchema\CustomPostMutations\MutationResolvers\AbstractCreateUpdateCustomPostMutationResolver;
use PoPSchema\Media\TypeResolvers\ObjectType\MediaObjectTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractCustomPostMutationResolverHookSet extends AbstractHookSet
{
    protected MediaObjectTypeResolver $mediaTypeResolver;
    protected CustomPostMediaTypeMutationAPIInterface $customPostMediaTypeMutationAPI;
    protected IDScalarTypeResolver $idScalarTypeResolver;

    #[Required]
    final public function autowireAbstractCustomPostMutationResolverHookSet(
        MediaObjectTypeResolver $mediaTypeResolver,
        CustomPostMediaTypeMutationAPIInterface $customPostMediaTypeMutationAPI,
        IDScalarTypeResolver $idScalarTypeResolver,
    ): void {
        $this->mediaTypeResolver = $mediaTypeResolver;
        $this->customPostMediaTypeMutationAPI = $customPostMediaTypeMutationAPI;
        $this->idScalarTypeResolver = $idScalarTypeResolver;
    }

    protected function init(): void
    {
        $this->hooksAPI->addFilter(
            HookNames::FIELD_ARG_NAME_RESOLVERS,
            array($this, 'maybeAddSchemaFieldArgNameResolvers'),
            10,
            4
        );
        $this->hooksAPI->addFilter(
            HookNames::FIELD_ARG_DESCRIPTION,
            array($this, 'maybeAddSchemaFieldArgDescription'),
            10,
            5
        );
        $this->hooksAPI->addAction(
            AbstractCreateUpdateCustomPostMutationResolver::HOOK_EXECUTE_CREATE_OR_UPDATE,
            array($this, 'setOrRemoveFeaturedImage'),
            10,
            2
        );
    }

    public function maybeAddSchemaFieldArgNameResolvers(
        array $schemaFieldArgNameResolvers,
        ObjectTypeFieldResolverInterface $objectTypeFieldResolver,
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName,
    ): array {
        // Only for the specific combinations of Type and fieldName
        if (!$this->mustAddSchemaFieldArgs($objectTypeResolver, $fieldName)) {
            return $schemaFieldArgNameResolvers;
        }
        $schemaFieldArgNameResolvers[MutationInputProperties::FEATUREDIMAGE_ID] = $this->idScalarTypeResolver;
        return $schemaFieldArgNameResolvers;
    }

    public function maybeAddSchemaFieldArgDescription(
        ?string $schemaFieldArgDescription,
        ObjectTypeFieldResolverInterface $objectTypeFieldResolver,
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName,
        string $fieldArgName,
    ): ?string {
        // Only for the newly added fieldArgName
        if ($fieldArgName !== MutationInputProperties::FEATUREDIMAGE_ID || !$this->mustAddSchemaFieldArgs($objectTypeResolver, $fieldName)) {
            return $schemaFieldArgDescription;
        }
        return sprintf(
            $this->translationAPI->__('The ID of the featured image (of type %s)', 'custompost-mutations'),
            $this->mediaTypeResolver->getMaybeNamespacedTypeName()
        );
    }

    abstract protected function mustAddSchemaFieldArgs(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName,
    ): bool;

    /**
     * If entry "featuredImageID" has an ID, set it. If it is null, remove it
     */
    public function setOrRemoveFeaturedImage(int | string $customPostID, array $form_data): void
    {
        if (isset($form_data[MutationInputProperties::FEATUREDIMAGE_ID])) {
            if ($featuredImageID = $form_data[MutationInputProperties::FEATUREDIMAGE_ID]) {
                $this->customPostMediaTypeMutationAPI->setFeaturedImage($customPostID, $featuredImageID);
            } else {
                $this->customPostMediaTypeMutationAPI->removeFeaturedImage($customPostID);
            }
        }
    }
}
