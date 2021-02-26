<?php

declare(strict_types=1);

namespace GraphQLAPI\SchemaFeedback\SchemaConfigurators;

use PoP\ComponentModel\Misc\GeneralUtils;
use GraphQLAPI\GraphQLAPI\General\BlockHelpers;
use GraphQLAPI\GraphQLAPI\Blocks\AbstractControlBlock;
use GraphQLAPI\SchemaFeedback\Blocks\SchemaFeedbackBlock;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use GraphQLAPI\SchemaFeedback\ModuleResolvers\FunctionalityModuleResolver;
use PoP\SchemaFeedbackByDirective\Facades\SchemaFeedbackManagerFacade;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\AbstractGraphQLQueryConfigurator;

class SchemaFeedbackGraphQLQueryConfigurator extends AbstractGraphQLQueryConfigurator
{
    /**
     * Extract the configuration items defined in the CPT,
     * and inject them into the service as to take effect
     * in the current GraphQL query
     *
     * @return void
     */
    public function executeSchemaConfiguration(int $fdlPostID): void
    {
        // Only if the module is not disabled
        if (!$this->moduleRegistry->isModuleEnabled(FunctionalityModuleResolver::SCHEMA_FEEDBACK)) {
            return;
        }

        $instanceManager = InstanceManagerFacade::getInstance();
        $fdlBlockItems = BlockHelpers::getBlocksOfTypeFromCustomPost(
            $fdlPostID,
            $instanceManager->getInstance(SchemaFeedbackBlock::class)
        );
        $schemaFeedbackManager = SchemaFeedbackManagerFacade::getInstance();
        $instanceManager = InstanceManagerFacade::getInstance();
        foreach ($fdlBlockItems as $fdlBlockItem) {
            if ($feedbackMessage = $fdlBlockItem['attrs'][SchemaFeedbackBlock::ATTRIBUTE_NAME_FEEDBACK_MESSAGE] ?? null) {
                if ($typeFields = $fdlBlockItem['attrs'][AbstractControlBlock::ATTRIBUTE_NAME_TYPE_FIELDS] ?? null) {
                    // Extract the saved fields
                    if (
                        $entriesForFields = GeneralUtils::arrayFlatten(
                            array_map(
                                function ($selectedField) use ($feedbackMessage) {
                                    return $this->getEntriesFromField($selectedField, $feedbackMessage);
                                },
                                $typeFields
                            )
                        )
                    ) {
                        $schemaFeedbackManager->addEntriesForFields(
                            $entriesForFields
                        );
                    }
                }
            }
        }
    }
}
