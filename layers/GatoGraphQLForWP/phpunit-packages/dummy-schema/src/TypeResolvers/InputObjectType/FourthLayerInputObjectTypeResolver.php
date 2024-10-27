<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\DummySchema\TypeResolvers\InputObjectType;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use PoP\Root\FeedbackItemProviders\GenericFeedbackItemProvider;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;

class FourthLayerInputObjectTypeResolver extends AbstractInputObjectTypeResolver
{
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;

    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        if ($this->stringScalarTypeResolver === null) {
            /** @var StringScalarTypeResolver */
            $stringScalarTypeResolver = $this->instanceManager->getInstance(StringScalarTypeResolver::class);
            $this->stringScalarTypeResolver = $stringScalarTypeResolver;
        }
        return $this->stringScalarTypeResolver;
    }

    public function getTypeName(): string
    {
        return 'FourthLayerInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Last level of the Input Object containing other Input Objects, to test their validation is performed', 'dummy-schema');
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(): array
    {
        return [
            'name' => $this->getStringScalarTypeResolver(),
        ];
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            'name' => $this->__('Random name', 'dummy-schema'),
            default => parent::getInputFieldDefaultValue($inputFieldName),
        };
    }

    public function getInputFieldTypeModifiers(string $inputFieldName): int
    {
        return match ($inputFieldName) {
            'name' => SchemaTypeModifiers::MANDATORY,
            default => parent::getInputFieldTypeModifiers($inputFieldName),
        };
    }

    /**
     * Validate constraints on the input field's value
     */
    protected function validateInputFieldValue(
        string $inputFieldName,
        mixed $inputFieldValue,
        AstInterface $astNode,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        parent::validateInputFieldValue($inputFieldName, $inputFieldValue, $astNode, $objectTypeFieldResolutionFeedbackStore);

        if ($inputFieldName === 'name' && $inputFieldValue === '') {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        GenericFeedbackItemProvider::class,
                        GenericFeedbackItemProvider::E1,
                        [
                            sprintf(
                                $this->__('Input \'%s\' cannot be empty', 'dummy-schema'),
                                $inputFieldName,
                            ),
                        ]
                    ),
                    $astNode,
                )
            );
            return;
        }
    }
}
