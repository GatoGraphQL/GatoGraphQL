<?php

declare(strict_types=1);

namespace PoPCMSSchema\Comments\ConditionalOnModule\Users\SchemaHooks;

use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\HookNames;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPCMSSchema\Comments\ConditionalOnModule\Users\FilterInputs\CustomPostAuthorIDsFilterInput;
use PoPCMSSchema\Comments\ConditionalOnModule\Users\FilterInputs\ExcludeCustomPostAuthorIDsFilterInput;
use PoPCMSSchema\Comments\TypeResolvers\InputObjectType\RootCommentsFilterInputObjectTypeResolver;
use PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\FilterInputs\AuthorIDsFilterInput;
use PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\FilterInputs\ExcludeAuthorIDsFilterInput;

class InputObjectTypeHookSet extends AbstractHookSet
{
    private ?IDScalarTypeResolver $idScalarTypeResolver = null;
    private ?CustomPostAuthorIDsFilterInput $customPostAuthorIDsFilterInput = null;
    private ?ExcludeCustomPostAuthorIDsFilterInput $excludeCustomPostAuthorIDsFilterInput = null;
    private ?AuthorIDsFilterInput $authorIDsFilterInput = null;
    private ?ExcludeAuthorIDsFilterInput $excludeAuthorIDsFilterInput = null;

    final public function setIDScalarTypeResolver(IDScalarTypeResolver $idScalarTypeResolver): void
    {
        $this->idScalarTypeResolver = $idScalarTypeResolver;
    }
    final protected function getIDScalarTypeResolver(): IDScalarTypeResolver
    {
        /** @var IDScalarTypeResolver */
        return $this->idScalarTypeResolver ??= $this->instanceManager->getInstance(IDScalarTypeResolver::class);
    }
    final public function setCustomPostAuthorIDsFilterInput(CustomPostAuthorIDsFilterInput $customPostAuthorIDsFilterInput): void
    {
        $this->customPostAuthorIDsFilterInput = $customPostAuthorIDsFilterInput;
    }
    final protected function getCustomPostAuthorIDsFilterInput(): CustomPostAuthorIDsFilterInput
    {
        /** @var CustomPostAuthorIDsFilterInput */
        return $this->customPostAuthorIDsFilterInput ??= $this->instanceManager->getInstance(CustomPostAuthorIDsFilterInput::class);
    }
    final public function setExcludeCustomPostAuthorIDsFilterInput(ExcludeCustomPostAuthorIDsFilterInput $excludeCustomPostAuthorIDsFilterInput): void
    {
        $this->excludeCustomPostAuthorIDsFilterInput = $excludeCustomPostAuthorIDsFilterInput;
    }
    final protected function getExcludeCustomPostAuthorIDsFilterInput(): ExcludeCustomPostAuthorIDsFilterInput
    {
        /** @var ExcludeCustomPostAuthorIDsFilterInput */
        return $this->excludeCustomPostAuthorIDsFilterInput ??= $this->instanceManager->getInstance(ExcludeCustomPostAuthorIDsFilterInput::class);
    }
    final public function setAuthorIDsFilterInput(AuthorIDsFilterInput $authorIDsFilterInput): void
    {
        $this->authorIDsFilterInput = $authorIDsFilterInput;
    }
    final protected function getAuthorIDsFilterInput(): AuthorIDsFilterInput
    {
        /** @var AuthorIDsFilterInput */
        return $this->authorIDsFilterInput ??= $this->instanceManager->getInstance(AuthorIDsFilterInput::class);
    }
    final public function setExcludeAuthorIDsFilterInput(ExcludeAuthorIDsFilterInput $excludeAuthorIDsFilterInput): void
    {
        $this->excludeAuthorIDsFilterInput = $excludeAuthorIDsFilterInput;
    }
    final protected function getExcludeAuthorIDsFilterInput(): ExcludeAuthorIDsFilterInput
    {
        /** @var ExcludeAuthorIDsFilterInput */
        return $this->excludeAuthorIDsFilterInput ??= $this->instanceManager->getInstance(ExcludeAuthorIDsFilterInput::class);
    }

    protected function init(): void
    {
        App::addFilter(
            HookNames::INPUT_FIELD_NAME_TYPE_RESOLVERS,
            $this->getInputFieldNameTypeResolvers(...),
            10,
            2
        );
        App::addFilter(
            HookNames::INPUT_FIELD_DESCRIPTION,
            $this->getInputFieldDescription(...),
            10,
            3
        );
        App::addFilter(
            HookNames::INPUT_FIELD_TYPE_MODIFIERS,
            $this->getInputFieldTypeModifiers(...),
            10,
            3
        );
        App::addFilter(
            HookNames::INPUT_FIELD_FILTER_INPUT,
            $this->getInputFieldFilterInput(...),
            10,
            3
        );
    }

    /**
     * @param array<string,InputTypeResolverInterface> $inputFieldNameTypeResolvers
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(
        array $inputFieldNameTypeResolvers,
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
    ): array {
        if (!($inputObjectTypeResolver instanceof RootCommentsFilterInputObjectTypeResolver)) {
            return $inputFieldNameTypeResolvers;
        }
        return array_merge(
            $inputFieldNameTypeResolvers,
            [
                'authorIDs' => $this->getIDScalarTypeResolver(),
                'excludeAuthorIDs' => $this->getIDScalarTypeResolver(),
                'customPostAuthorIDs' => $this->getIDScalarTypeResolver(),
                'excludeCustomPostAuthorIDs' => $this->getIDScalarTypeResolver(),
            ]
        );
    }

    public function getInputFieldDescription(
        ?string $inputFieldDescription,
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
        string $inputFieldName
    ): ?string {
        if (!($inputObjectTypeResolver instanceof RootCommentsFilterInputObjectTypeResolver)) {
            return $inputFieldDescription;
        }
        return match ($inputFieldName) {
            'authorIDs' => $this->__('Filter comments from the authors with given IDs', 'comments'),
            'excludeAuthorIDs' => $this->__('Exclude comments from authors with given IDs', 'comments'),
            'customPostAuthorIDs' => $this->__('Filter comments added to custom posts from the authors with given IDs', 'comments'),
            'excludeCustomPostAuthorIDs' => $this->__('Exclude comments added to custom posts from authors with given IDs', 'comments'),
            default => $inputFieldDescription,
        };
    }

    public function getInputFieldTypeModifiers(
        int $inputFieldTypeModifiers,
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
        string $inputFieldName
    ): int {
        if (!($inputObjectTypeResolver instanceof RootCommentsFilterInputObjectTypeResolver)) {
            return $inputFieldTypeModifiers;
        }
        return match ($inputFieldName) {
            'authorIDs',
            'excludeAuthorIDs',
            'customPostAuthorIDs',
            'excludeCustomPostAuthorIDs'
                => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default
                => $inputFieldTypeModifiers,
        };
    }

    public function getInputFieldFilterInput(
        ?FilterInputInterface $inputFieldFilterInput,
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
        string $inputFieldName,
    ): ?FilterInputInterface {
        if (!($inputObjectTypeResolver instanceof RootCommentsFilterInputObjectTypeResolver)) {
            return $inputFieldFilterInput;
        }
        return match ($inputFieldName) {
            'authorIDs' => $this->getAuthorIDsFilterInput(),
            'excludeAuthorIDs' => $this->getExcludeAuthorIDsFilterInput(),
            'customPostAuthorIDs' => $this->getCustomPostAuthorIDsFilterInput(),
            'excludeCustomPostAuthorIDs' => $this->getExcludeCustomPostAuthorIDsFilterInput(),
            default => $inputFieldFilterInput,
        };
    }
}
