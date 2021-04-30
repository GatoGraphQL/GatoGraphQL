<?php

declare(strict_types=1);

namespace Leoloso\ExamplesForPoP\DirectiveResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\DirectiveResolvers\AbstractGlobalDirectiveResolver;
use PoP\ComponentModel\Feedback\Tokens;

class SendByEmailDirectiveResolver extends AbstractGlobalDirectiveResolver
{
    public function getDirectiveName(): string
    {
        return 'sendByEmail';
    }

    // public function getSchemaDirectiveArgs(TypeResolverInterface $typeResolver): array
    // {
    //     return [
    //         [
    //             SchemaDefinition::ARGNAME_NAME => 'to',
    //             SchemaDefinition::ARGNAME_TYPE => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_EMAIL),
    //             SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('Emails to send the email to', 'component-model'),
    //             SchemaDefinition::ARGNAME_MANDATORY => true,
    //         ],
    //         [
    //             SchemaDefinition::ARGNAME_NAME => 'subject',
    //             SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
    //             SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('Email subject', 'component-model'),,
    //         ],
    //         [
    //             SchemaDefinition::ARGNAME_NAME => 'content',
    //             SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
    //             SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('Email content', 'component-model'),
    //         ],
    //     ];
    // }

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
        $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
        $dbKey = $typeResolver->getTypeOutputName();
        foreach ($idsDataFields as $id => $dataFields) {
            foreach ($dataFields['direct'] as $field) {
                $fieldOutputKey = $fieldQueryInterpreter->getFieldOutputKey($field);
                // Validate that the property exists
                $isValueInDBItems = array_key_exists($fieldOutputKey, $dbItems[(string)$id] ?? []);
                if (!$isValueInDBItems && !array_key_exists($fieldOutputKey, $previousDBItems[$dbKey][(string)$id] ?? [])) {
                    if ($fieldOutputKey != $field) {
                        $dbErrors[(string)$id][] = [
                            Tokens::PATH => [$this->directive],
                            Tokens::MESSAGE => sprintf(
                                $this->translationAPI->__('Field \'%s\' (under property \'%s\') hadn\'t been set for object with ID \'%s\', so it can\'t be transformed', 'component-model'),
                                $field,
                                $fieldOutputKey,
                                $id
                            )
                        ];
                    } else {
                        $dbErrors[(string)$id][] = [
                            Tokens::PATH => [$this->directive],
                            Tokens::MESSAGE => sprintf(
                                $this->translationAPI->__('Field \'%s\' hadn\'t been set for object with ID \'%s\', so it can\'t be transformed', 'component-model'),
                                $fieldOutputKey,
                                $id
                            )
                        ];
                    }
                    continue;
                }

                $value = $isValueInDBItems ?
                    $dbItems[(string)$id][$fieldOutputKey] :
                    $previousDBItems[$dbKey][(string)$id][$fieldOutputKey];

                // Validate that the value is an array
                if (!is_array($value)) {
                    if ($fieldOutputKey != $field) {
                        $dbErrors[(string)$id][] = [
                            Tokens::PATH => [$this->directive],
                            Tokens::MESSAGE => sprintf(
                                $this->translationAPI->__('The value for field \'%s\' (under property \'%s\') is not an array, so execution of this directive can\'t continue', 'component-model'),
                                $field,
                                $fieldOutputKey,
                                $id
                            )
                        ];
                    } else {
                        $dbErrors[(string)$id][] = [
                            Tokens::PATH => [$this->directive],
                            Tokens::MESSAGE => sprintf(
                                $this->translationAPI->__('The value for field \'%s\' is not an array, so execution of this directive can\'t continue', 'component-model'),
                                $fieldOutputKey,
                                $id
                            )
                        ];
                    }
                    continue;
                }

                // Get the contents for the email, and validate
                $to = $value['to'];
                if (!$to) {
                    $dbErrors[(string)$id][] = [
                        Tokens::PATH => [$this->directive],
                        Tokens::MESSAGE => sprintf(
                            $this->translationAPI->__('The \'to\' item in the array in field \'%s\' (under property \'%s\') is empty, so the emails can\'t be sent', 'component-model'),
                            $field,
                            $fieldOutputKey,
                            $id
                        )
                    ];
                    continue;
                }
                if (!is_array($to)) {
                    $to = [$to];
                }
                $content = $value['content'];
                $subject = $value['subject'];

                // We are not sending emails yet! Just add a new entry, with the contents to send
                $dbItems[(string)$id][$fieldOutputKey] = sprintf(
                    'to:%s; subject:%s; content:%s',
                    implode(',', $to),
                    $subject,
                    $content
                );
            }
        }
    }
}
