<?php

declare(strict_types=1);

namespace PoP\Engine\DirectiveResolvers;

use PoP\ComponentModel\Feedback\Tokens;
use PoP\Engine\Enums\FieldFeedbackTypeEnum;
use PoP\ComponentModel\Schema\SchemaHelpers;
use PoP\Engine\Enums\FieldFeedbackTargetEnum;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Directives\DirectiveTypes;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\DirectiveResolvers\AbstractGlobalDirectiveResolver;

class AddFeedbackForFieldDirectiveResolver extends AbstractGlobalDirectiveResolver
{
    public function getDirectiveName(): string
    {
        return 'addFeedbackForField';
    }

    /**
     * This is a system directive
     */
    public function getDirectiveType(): string
    {
        return DirectiveTypes::SYSTEM;
    }

    public function isRepeatable(): bool
    {
        return true;
    }

    /**
     * Execute always, even if validation is false
     */
    public function needsIDsDataFieldsToExecute(): bool
    {
        return false;
    }

    /**
     * Execute the directive
     */
    public function resolveDirective(
        TypeResolverInterface $typeResolver,
        array &$idsDataFields,
        array &$succeedingPipelineIDsDataFields,
        array &$succeedingPipelineDirectiveResolverInstances,
        array &$resultIDItems,
        array &$unionDBKeyIDs,
        array &$dbItems,
        array &$previousDBItems,
        array &$variables,
        array &$messages,
        array &$dbErrors,
        array &$dbWarnings,
        array &$dbDeprecations,
        array &$dbNotices,
        array &$dbTraces,
        array &$schemaErrors,
        array &$schemaWarnings,
        array &$schemaDeprecations,
        array &$schemaNotices,
        array &$schemaTraces
    ): void {
        $type = $this->directiveArgsForSchema['type'];
        $target = $this->directiveArgsForSchema['target'];
        if ($target == FieldFeedbackTargetEnum::DB) {
            $translationAPI = TranslationAPIFacade::getInstance();
            foreach (array_keys($idsDataFields) as $id) {
                // Use either the default value passed under param "value" or, if this is NULL, use a predefined value
                $expressions = $this->getExpressionsForResultItem($id, $variables, $messages);
                $resultItem = $resultIDItems[$id];
                list(
                    $resultItemValidDirective,
                    $resultItemDirectiveName,
                    $resultItemDirectiveArgs
                ) = $this->dissectAndValidateDirectiveForResultItem($typeResolver, $resultItem, $variables, $expressions, $dbErrors, $dbWarnings, $dbDeprecations);
                // Check that the directive is valid. If it is not, $dbErrors will have the error already added
                if (is_null($resultItemValidDirective)) {
                    continue;
                }
                // Take the default value from the directiveArgs
                $message = $resultItemDirectiveArgs['message'];
                // Check that the message was composed properly (eg: it didn't fail).
                // If it is not, $dbErrors will have the error already added
                if (is_null($message)) {
                    $dbErrors[(string)$id][] = [
                        Tokens::PATH => [$this->directive],
                        Tokens::MESSAGE => $translationAPI->__(
                            'The message could not be composed. Check previous errors',
                            'engine'
                        ),
                    ];
                    continue;
                }
                $feedbackMessageEntry = $this->getFeedbackMessageEntry($message);
                if ($type == FieldFeedbackTypeEnum::WARNING) {
                    $dbWarnings[(string)$id][] = $feedbackMessageEntry;
                } elseif ($type == FieldFeedbackTypeEnum::DEPRECATION) {
                    $dbDeprecations[(string)$id][] = $feedbackMessageEntry;
                } elseif ($type == FieldFeedbackTypeEnum::NOTICE) {
                    $dbNotices[(string)$id][] = $feedbackMessageEntry;
                }
            }
        } elseif ($target == FieldFeedbackTargetEnum::SCHEMA) {
            $message = $this->directiveArgsForSchema['message'];
            $feedbackMessageEntry = $this->getFeedbackMessageEntry($message);
            if ($type == FieldFeedbackTypeEnum::WARNING) {
                $schemaWarnings[] = $feedbackMessageEntry;
            } elseif ($type == FieldFeedbackTypeEnum::DEPRECATION) {
                $schemaDeprecations[] = $feedbackMessageEntry;
            } elseif ($type == FieldFeedbackTypeEnum::NOTICE) {
                $schemaNotices[] = $feedbackMessageEntry;
            }
        }
    }

    protected function getFeedbackMessageEntry(string $message): array
    {
        return [
            Tokens::PATH => [$this->directive],
            Tokens::MESSAGE => $message,
        ];
    }

    public function getSchemaDirectiveDescription(TypeResolverInterface $typeResolver): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return $translationAPI->__('Whenever a field is queried, add a feedback message to the response, of either type "warning", "deprecation" or "log"', 'engine');
    }

    public function getSchemaDirectiveArgs(TypeResolverInterface $typeResolver): array
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $instanceManager = InstanceManagerFacade::getInstance();
        /**
         * @var FieldFeedbackTypeEnum
         */
        $fieldFeedbackTypeEnum = $instanceManager->getInstance(FieldFeedbackTypeEnum::class);
        /**
         * @var FieldFeedbackTargetEnum
         */
        $fieldFeedbackTargetEnum = $instanceManager->getInstance(FieldFeedbackTargetEnum::class);
        return [
            [
                SchemaDefinition::ARGNAME_NAME => 'message',
                SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The feedback message', 'engine'),
                SchemaDefinition::ARGNAME_MANDATORY => true,
            ],
            [
                SchemaDefinition::ARGNAME_NAME => 'type',
                SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_ENUM,
                SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The type of feedback', 'engine'),
                SchemaDefinition::ARGNAME_ENUM_NAME => $fieldFeedbackTypeEnum->getName(),
                SchemaDefinition::ARGNAME_ENUM_VALUES => SchemaHelpers::convertToSchemaFieldArgEnumValueDefinitions(
                    $fieldFeedbackTypeEnum->getValues()
                ),
                SchemaDefinition::ARGNAME_DEFAULT_VALUE => $this->getDefaultFeedbackType(),
            ],
            [
                SchemaDefinition::ARGNAME_NAME => 'target',
                SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_ENUM,
                SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The target for the feedback', 'engine'),
                SchemaDefinition::ARGNAME_ENUM_NAME => $fieldFeedbackTargetEnum->getName(),
                SchemaDefinition::ARGNAME_ENUM_VALUES => SchemaHelpers::convertToSchemaFieldArgEnumValueDefinitions(
                    $fieldFeedbackTargetEnum->getValues()
                ),
                SchemaDefinition::ARGNAME_DEFAULT_VALUE => $this->getDefaultFeedbackTarget(),
            ],
        ];
    }

    protected function getDefaultFeedbackType(): string
    {
        return FieldFeedbackTypeEnum::NOTICE;
    }

    protected function getDefaultFeedbackTarget(): string
    {
        return FieldFeedbackTargetEnum::SCHEMA;
    }
}
