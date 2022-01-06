<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMediaMutations\Hooks;

use PoP\ComponentModel\TypeResolvers\InputObjectType\HookNames;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\Engine\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\BasicService\AbstractHookSet;
use PoPSchema\CustomPostMediaMutations\MutationResolvers\MutationInputProperties;
use PoPSchema\CustomPostMediaMutations\TypeAPIs\CustomPostMediaTypeMutationAPIInterface;
use PoPSchema\CustomPostMutations\TypeResolvers\InputObjectType\CreateCustomPostFilterInputObjectTypeResolverInterface;
use PoPSchema\CustomPostMutations\TypeResolvers\InputObjectType\UpdateCustomPostFilterInputObjectTypeResolverInterface;

abstract class AbstractCustomPostMutationResolverHookSet extends AbstractHookSet
{
    private ?CustomPostMediaTypeMutationAPIInterface $customPostMediaTypeMutationAPI = null;
    private ?IDScalarTypeResolver $idScalarTypeResolver = null;

    final public function setCustomPostMediaTypeMutationAPI(CustomPostMediaTypeMutationAPIInterface $customPostMediaTypeMutationAPI): void
    {
        $this->customPostMediaTypeMutationAPI = $customPostMediaTypeMutationAPI;
    }
    final protected function getCustomPostMediaTypeMutationAPI(): CustomPostMediaTypeMutationAPIInterface
    {
        return $this->customPostMediaTypeMutationAPI ??= $this->instanceManager->getInstance(CustomPostMediaTypeMutationAPIInterface::class);
    }
    final public function setIDScalarTypeResolver(IDScalarTypeResolver $idScalarTypeResolver): void
    {
        $this->idScalarTypeResolver = $idScalarTypeResolver;
    }
    final protected function getIDScalarTypeResolver(): IDScalarTypeResolver
    {
        return $this->idScalarTypeResolver ??= $this->instanceManager->getInstance(IDScalarTypeResolver::class);
    }

    protected function init(): void
    {
        $this->getHooksAPI()->addFilter(
            HookNames::INPUT_FIELD_NAME_TYPE_RESOLVERS,
            array($this, 'maybeAddInputFieldNameTypeResolvers'),
            10,
            2
        );
        $this->getHooksAPI()->addFilter(
            HookNames::INPUT_FIELD_DESCRIPTION,
            array($this, 'maybeAddInputFieldDescription'),
            10,
            3
        );
    }

    /**
     * @param array<string, InputTypeResolverInterface> $inputFieldNameTypeResolvers
     * @return array<string, InputTypeResolverInterface>
     */
    public function maybeAddInputFieldNameTypeResolvers(
        array $inputFieldNameTypeResolvers,
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
    ): array {
        // Only for the specific combinations of Type and fieldName
        if (!$this->isInputObjectTypeResolver($inputObjectTypeResolver)) {
            return $inputFieldNameTypeResolvers;
        }
        $inputFieldNameTypeResolvers[MutationInputProperties::FEATUREDIMAGE_ID] = $this->getIDScalarTypeResolver();
        return $inputFieldNameTypeResolvers;
    }

    protected function isInputObjectTypeResolver(
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
    ): bool {
        return $inputObjectTypeResolver instanceof CreateCustomPostFilterInputObjectTypeResolverInterface
            || $inputObjectTypeResolver instanceof UpdateCustomPostFilterInputObjectTypeResolverInterface;
    }

    public function maybeAddInputFieldDescription(
        ?string $inputFieldDescription,
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
        string $inputFieldName,
    ): ?string {
        // Only for the newly added inputFieldName
        if ($inputFieldName !== MutationInputProperties::FEATUREDIMAGE_ID || !$this->isInputObjectTypeResolver($inputObjectTypeResolver)) {
            return $inputFieldDescription;
        }
        return $this->__('The ID of the image to set as featured', 'custompost-mutations');
    }
}
