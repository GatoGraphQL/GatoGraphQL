<?php

declare(strict_types=1);

namespace PoP\ConfigurableSchemaFeedback\TypeResolverDecorators;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ConfigurableSchemaFeedback\Facades\SchemaFeedbackManagerFacade;
use PoP\Engine\DirectiveResolvers\AddFeedbackForFieldDirectiveResolver;
use PoP\Engine\Enums\FieldFeedbackTargetEnum;
use PoP\Engine\Enums\FieldFeedbackTypeEnum;
use PoP\MandatoryDirectivesByConfiguration\TypeResolverDecorators\AbstractMandatoryDirectivesForFieldsTypeResolverDecorator;

class ConfigurableSchemaFeedbackForFieldsTypeResolverDecorator extends AbstractMandatoryDirectivesForFieldsTypeResolverDecorator
{
    protected function getConfigurationEntries(): array
    {
        $schemaFeedbackManager = SchemaFeedbackManagerFacade::getInstance();
        return $schemaFeedbackManager->getEntriesForFields();
    }

    protected function getMandatoryDirectives($entryValue = null): array
    {
        $message = $entryValue;
        $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var DirectiveResolverInterface */
        $addFeedbackForFieldDirectiveResolver = $instanceManager->getInstance(AddFeedbackForFieldDirectiveResolver::class);
        $directiveName = $addFeedbackForFieldDirectiveResolver->getDirectiveName();
        $schemaFeedbackDirective = $fieldQueryInterpreter->getDirective(
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
