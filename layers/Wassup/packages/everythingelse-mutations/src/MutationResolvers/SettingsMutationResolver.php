<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\MutationResolvers;

use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;

class SettingsMutationResolver extends AbstractMutationResolver
{
    /**
     * @var array<array>
     */
    public array $fieldoperators = [];

    // These values must be injected from outside
    public function add($field, $operator)
    {
        // each operator must be of class GD_Settings_UrlOperator
        $this->fieldoperators[] = [
            'field' => $field,
            'operator' => $operator,
        ];
    }

    /**
     * @return mixed
     */
    public function execute(array $form_data)
    {
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();

        // Return the redirect. Use Hard redirect
        // $redirect_to = $moduleprocessor_manager->getProcessor([PoP_Module_Processor_TextFormInputs::class, PoP_Module_Processor_TextFormInputs::MODULE_FORMINPUT_BROWSERURL])->getValue([PoP_Module_Processor_TextFormInputs::class, PoP_Module_Processor_TextFormInputs::MODULE_FORMINPUT_BROWSERURL]);
        // if (!$redirect_to) {

        //     $redirect_to = $cmsengineapi->getHomeURL();
        // }
        // Comment Leo 22/05/2015: If we forward to the same URL but with different lang, it will always go to https://www.mesym.com/ms/settings/
        // So forward to the homepage instead (temporary solution)
        // Using $cmsengineapi->getSiteURL() instead of $cmsengineapi->getHomeURL() so that it doesn't include the language bit, which will be changed later on
        $redirect_to = $cmsengineapi->getSiteURL();

        // Add all the params selected by the user
        foreach ($this->fieldoperators as $fieldoperator) {
            $value = trim($moduleprocessor_manager->getProcessor($fieldoperator['field'])->getValue($fieldoperator['field']));
            $redirect_to = $fieldoperator['operator']->getUrl($redirect_to, $fieldoperator['field'], $value);
        }

        return $redirect_to;
    }
}
