<?php
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers\SettingsMutationResolver;

\PoP\Root\App::addAction(
    'init', // Must migrate this WP hook to one from PoP (which executes before AFTER_BOOT_APPLICATION
    'gdQtInitsettings'
);
function gdQtInitsettings()
{
    $instanceManager = InstanceManagerFacade::getInstance();
    /** @var SettingsMutationResolver */
    $actionExecuterSettings = $instanceManager->getInstance(SettingsMutationResolver::class);
    $actionExecuterSettings->add(
    	[GD_QT_Module_Processor_SelectFormInputs::class, GD_QT_Module_Processor_SelectFormInputs::COMPONENT_QT_FORMINPUT_LANGUAGE],
    	new GD_QT_Settings_UrlOperator_Language()
    );
}
