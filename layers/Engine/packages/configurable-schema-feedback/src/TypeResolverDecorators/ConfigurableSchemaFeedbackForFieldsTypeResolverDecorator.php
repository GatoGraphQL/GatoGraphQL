<?php

declare(strict_types=1);

namespace PoP\ConfigurableSchemaFeedback\TypeResolverDecorators;

use PoP\Engine\Enums\FieldFeedbackTypeEnum;
use PoP\Engine\Enums\FieldFeedbackTargetEnum;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\Engine\DirectiveResolvers\AddFeedbackForFieldDirectiveResolver;
use PoP\ConfigurableSchemaFeedback\Managers\SchemaFeedbackManagerInterface;
use PoP\MandatoryDirectivesByConfiguration\TypeResolverDecorators\AbstractMandatoryDirectivesForFieldsTypeResolverDecorator;

class ConfigurableSchemaFeedbackForFieldsTypeResolverDecorator extends AbstractMandatoryDirectivesForFieldsTypeResolverDecorator
{
    public function __construct(
        InstanceManagerInterface $instanceManager,
        FieldQueryInterpreterInterface $fieldQueryInterpreter,
        protected SchemaFeedbackManagerInterface $schemaFeedbackManager,
    ) {
        parent::__construct(
            $instanceManager,
            $fieldQueryInterpreter,
        );
    }

    protected function getConfigurationEntries(): array
    {
        return $this->schemaFeedbackManager->getEntriesForFields();
    }

    protected function getMandatoryDirectives(mixed $entryValue = null): array
    {
        $message = $entryValue;
        /** @var DirectiveResolverInterface */
        $addFeedbackForFieldDirectiveResolver = $this->instanceManager->getInstance(AddFeedbackForFieldDirectiveResolver::class);
        $directiveName = $addFeedbackForFieldDirectiveResolver->getDirectiveName();
        $schemaFeedbackDirective = $this->fieldQueryInterpreter->getDirective(
            $directiveName,
            [
                'message' => $message,
                'type' => FieldFeedbackTypeEnum::DEPRECATION,
                'target' => FieldFeedbackTargetEnum::SCHEMA,
            ]
        );
        return [
            $schemaFeedbackDirective,
        ];
    }
}
