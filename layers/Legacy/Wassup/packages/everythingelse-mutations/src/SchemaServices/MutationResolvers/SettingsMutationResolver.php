<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers;

use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\Exception\AbstractException;
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoPCMSSchema\SchemaCommons\Facades\CMS\CMSServiceFacade;

class SettingsMutationResolver extends AbstractMutationResolver
{
    /**
     * @var array<array<string,mixed>>
     */
    public array $fieldoperators = [];

    // These values must be injected from outside
    public function add($field, $operator): void
    {
        // each operator must be of class GD_Settings_UrlOperator
        $this->fieldoperators[] = [
            'field' => $field,
            'operator' => $operator,
        ];
    }

    /**
     * @throws AbstractException In case of error
     */
    public function executeMutation(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
        $cmsService = CMSServiceFacade::getInstance();

        // Return the redirect. Use Hard redirect
        // $redirect_to = $componentprocessor_manager->getComponentProcessor([PoP_Module_Processor_TextFormInputs::class, PoP_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_BROWSERURL])->getValue([PoP_Module_Processor_TextFormInputs::class, PoP_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_BROWSERURL]);
        // if (!$redirect_to) {
        //     $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        //     $redirect_to = $cmsengineapi->getHomeURL();
        // }
        // Comment Leo 22/05/2015: If we forward to the same URL but with different lang, it will always go to https://www.mesym.com/ms/settings/
        // So forward to the homepage instead (temporary solution)
        // Using $cmsService->getSiteURL() instead of $cmsService->getHomeURL() so that it doesn't include the language bit, which will be changed later on
        $redirect_to = $cmsService->getSiteURL();

        // Add all the params selected by the user
        foreach ($this->fieldoperators as $fieldoperator) {
            $value = trim($componentprocessor_manager->getComponentProcessor($fieldoperator['field'])->getValue($fieldoperator['field']));
            $redirect_to = $fieldoperator['operator']->getUrl($redirect_to, $fieldoperator['field'], $value);
        }

        return $redirect_to;
    }
}
