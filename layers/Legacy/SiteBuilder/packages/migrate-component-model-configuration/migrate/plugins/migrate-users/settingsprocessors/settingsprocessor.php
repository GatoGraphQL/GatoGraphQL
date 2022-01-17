<?php
namespace PoPCMSSchema\Users;

class PoPUsers_Module_SettingsProcessor extends \PoP\ComponentModel\Settings\SettingsProcessorBase
{
    use SettingsProcessor_Trait;
}

/**
 * Initialization
 */
new PoPUsers_Module_SettingsProcessor();
