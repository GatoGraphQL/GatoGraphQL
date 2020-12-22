<?php

declare(strict_types=1);

namespace PoP\ConfigurableSchemaFeedback\TypeResolverDecorators;

use PoP\Engine\Enums\FieldFeedbackTypeEnum;
use PoP\Engine\Enums\FieldFeedbackTargetEnum;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\Engine\DirectiveResolvers\AddFeedbackForFieldDirectiveResolver;
use PoP\ConfigurableSchemaFeedback\Facades\SchemaFeedbackManagerFacade;
use PoP\MandatoryDirectivesByConfiguration\TypeResolverDecorators\AbstractMandatoryDirectivesForFieldsTypeResolverDecorator;

class ConfigurableSchemaFeedbackForFieldsTypeResolverDecorator extends AbstractMandatoryDirectivesForFieldsTypeResolverDecorator
{
    protected static function getConfigurationEntries(): array
    {
        $schemaFeedbackManager = SchemaFeedbackManagerFacade::getInstance();
        return $schemaFeedbackManager->getEntriesForFields();
    }

    protected function getMandatoryDirectives($entryValue = null): array
    {
        $message = $entryValue;
        $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
        $directiveName = AddFeedbackForFieldDirectiveResolver::getDirectiveName();
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
