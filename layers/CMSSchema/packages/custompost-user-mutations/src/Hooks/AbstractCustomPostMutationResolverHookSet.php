<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostUserMutations\Hooks;

use PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType\CreateCustomPostInputObjectTypeResolverInterface;
use PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType\UpdateCustomPostInputObjectTypeResolverInterface;
use PoPCMSSchema\CustomPostUserMutations\Constants\MutationInputProperties;
use PoPCMSSchema\CustomPostUserMutations\Module;
use PoPCMSSchema\CustomPostUserMutations\ModuleConfiguration;
use PoPCMSSchema\CustomPostUserMutations\TypeResolvers\InputObjectType\AuthorByOneofInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputObjectType\HookNames;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;

abstract class AbstractCustomPostMutationResolverHookSet extends AbstractHookSet
{
    private ?AuthorByOneofInputObjectTypeResolver $authorByOneofInputObjectTypeResolver = null;

    final protected function getAuthorByOneofInputObjectTypeResolver(): AuthorByOneofInputObjectTypeResolver
    {
        if ($this->authorByOneofInputObjectTypeResolver === null) {
            /** @var AuthorByOneofInputObjectTypeResolver */
            $authorByOneofInputObjectTypeResolver = $this->instanceManager->getInstance(AuthorByOneofInputObjectTypeResolver::class);
            $this->authorByOneofInputObjectTypeResolver = $authorByOneofInputObjectTypeResolver;
        }
        return $this->authorByOneofInputObjectTypeResolver;
    }

    protected function init(): void
    {
        App::addFilter(
            HookNames::INPUT_FIELD_NAME_TYPE_RESOLVERS,
            $this->maybeAddInputFieldNameTypeResolvers(...),
            10,
            2
        );
        App::addFilter(
            HookNames::INPUT_FIELD_DESCRIPTION,
            $this->maybeAddInputFieldDescription(...),
            10,
            3
        );
        App::addFilter(
            HookNames::SENSITIVE_INPUT_FIELD_NAMES,
            $this->getSensitiveInputFieldNames(...),
            10,
            2
        );
    }

    /**
     * @param array<string,InputTypeResolverInterface> $inputFieldNameTypeResolvers
     * @return array<string,InputTypeResolverInterface>
     */
    public function maybeAddInputFieldNameTypeResolvers(
        array $inputFieldNameTypeResolvers,
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
    ): array {
        // Only for the specific combinations of Type and fieldName
        if (!$this->isInputObjectTypeResolver($inputObjectTypeResolver)) {
            return $inputFieldNameTypeResolvers;
        }
        $inputFieldNameTypeResolvers[MutationInputProperties::AUTHOR_BY] = $this->getAuthorByOneofInputObjectTypeResolver();
        return $inputFieldNameTypeResolvers;
    }

    protected function isInputObjectTypeResolver(
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
    ): bool {
        return $inputObjectTypeResolver instanceof CreateCustomPostInputObjectTypeResolverInterface
            || $inputObjectTypeResolver instanceof UpdateCustomPostInputObjectTypeResolverInterface;
    }

    public function maybeAddInputFieldDescription(
        ?string $inputFieldDescription,
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
        string $inputFieldName,
    ): ?string {
        // Only for the newly added inputFieldName
        if ($inputFieldName !== MutationInputProperties::AUTHOR_BY || !$this->isInputObjectTypeResolver($inputObjectTypeResolver)) {
            return $inputFieldDescription;
        }
        return $this->__('The author of the custom post', 'custompost-user-mutations');
    }

    /**
     * @param string[] $sensitiveInputFieldNames
     * @return string[]
     */
    public function getSensitiveInputFieldNames(
        array $sensitiveInputFieldNames,
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
    ): array {
        // Only for the newly added inputFieldName
        if (!$this->isInputObjectTypeResolver($inputObjectTypeResolver)) {
            return $sensitiveInputFieldNames;
        }
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($moduleConfiguration->treatAuthorInputInCustomPostMutationAsSensitiveData()) {
            $sensitiveInputFieldNames[] = MutationInputProperties::AUTHOR_BY;
        }
        return $sensitiveInputFieldNames;
    }
}
