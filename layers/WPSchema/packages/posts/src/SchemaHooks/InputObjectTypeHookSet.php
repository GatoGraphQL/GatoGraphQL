<?php

declare(strict_types=1);

namespace PoPWPSchema\Posts\SchemaHooks;

use PoP\ComponentModel\TypeResolvers\InputObjectType\HookNames;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\Engine\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\Hooks\AbstractHookSet;
use PoPSchema\Posts\TypeResolvers\InputObjectType\AbstractPostsFilterInputObjectTypeResolver;
use PoPWPSchema\Posts\FilterInputProcessors\FilterInputProcessor;

class InputObjectTypeHookSet extends AbstractHookSet
{
    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;

    final public function setBooleanScalarTypeResolver(BooleanScalarTypeResolver $booleanScalarTypeResolver): void
    {
        $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
    }
    final protected function getBooleanScalarTypeResolver(): BooleanScalarTypeResolver
    {
        return $this->booleanScalarTypeResolver ??= $this->instanceManager->getInstance(BooleanScalarTypeResolver::class);
    }

    protected function init(): void
    {
        $this->getHooksAPI()->addFilter(
            HookNames::INPUT_FIELD_NAME_TYPE_RESOLVERS,
            [$this, 'getInputFieldNameTypeResolvers'],
            10,
            2
        );
        $this->getHooksAPI()->addFilter(
            HookNames::INPUT_FIELD_DESCRIPTION,
            [$this, 'getInputFieldDescription'],
            10,
            3
        );
        $this->getHooksAPI()->addFilter(
            HookNames::INPUT_FIELD_DEFAULT_VALUE,
            [$this, 'getInputFieldDefaultValue'],
            10,
            3
        );
        $this->getHooksAPI()->addFilter(
            HookNames::INPUT_FIELD_FILTER_INPUT,
            [$this, 'getInputFieldFilterInput'],
            10,
            3
        );
    }

    /**
     * @param array<string, InputTypeResolverInterface> $inputFieldNameTypeResolvers
     */
    public function getInputFieldNameTypeResolvers(
        array $inputFieldNameTypeResolvers,
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
    ): array {
        if (!($inputObjectTypeResolver instanceof AbstractPostsFilterInputObjectTypeResolver)) {
            return $inputFieldNameTypeResolvers;
        }
        return array_merge(
            $inputFieldNameTypeResolvers,
            [
                'ignoreSticky' => $this->getBooleanScalarTypeResolver(),
                'excludeSticky' => $this->getBooleanScalarTypeResolver(),
            ]
        );
    }

    public function getInputFieldDescription(
        ?string $inputFieldDescription,
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
        string $inputFieldName
    ): ?string {
        if (!($inputObjectTypeResolver instanceof AbstractPostsFilterInputObjectTypeResolver)) {
            return $inputFieldDescription;
        }
        return match ($inputFieldName) {
            'ignoreSticky' => $this->getTranslationAPI()->__('Ignore post stickiness. `false` (default): move sticky posts to the start of the set. `true`: do not move sticky posts to the start of the set. See: https://developer.wordpress.org/reference/classes/wp_query/#pagination-parameters', 'posts'),
            'excludeSticky' => $this->getTranslationAPI()->__('Exclude sticky posts', 'posts'),
            default => $inputFieldDescription,
        };
    }

    public function getInputFieldDefaultValue(
        mixed $inputFieldDefaultValue,
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
        string $inputFieldName
    ): mixed {
        if (!($inputObjectTypeResolver instanceof AbstractPostsFilterInputObjectTypeResolver)) {
            return $inputFieldDefaultValue;
        }
        return match ($inputFieldName) {
            'ignoreSticky',
            'excludeSticky'
                => false,
            default
                => $inputFieldDefaultValue,
        };
    }

    public function getInputFieldFilterInput(
        ?array $inputFieldFilterInput,
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
        string $inputFieldName,
    ): ?array {
        if (!($inputObjectTypeResolver instanceof AbstractPostsFilterInputObjectTypeResolver)) {
            return $inputFieldFilterInput;
        }
        return match ($inputFieldName) {
            'ignoreSticky' => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_IGNORE_STICKY],
            'excludeSticky' => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_EXCLUDE_STICKY],
            default => $inputFieldFilterInput,
        };
    }
}
