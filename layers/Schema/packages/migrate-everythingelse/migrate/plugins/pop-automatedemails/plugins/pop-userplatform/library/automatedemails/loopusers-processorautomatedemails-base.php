<?php
use PoP\ComponentModel\Modules\ModuleUtils;
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoP\ComponentModel\Facades\Engine\EngineFacade;
use PoP\ComponentModel\Facades\DataStructure\DataStructureManagerFacade;
use PoP\ComponentModel\State\ApplicationState;

class PoP_LoopUsersProcessorAutomatedEmailsBase extends PoP_ProcessorAutomatedEmailsBase
{
    use PoP_ProcessorAutomatedEmails_UserPreferencesTrait;

    public function getEmails()
    {
        $emails = array();

        // Only users make sense here, no recipients, since we are serving personalized content to users on the website
        if ($users = $this->getUsers()) {
            // All the variables needed to operate into the pop-engine.php getData function
            $cmsusersapi = \PoPSchema\Users\FunctionAPIFactory::getInstance();
            $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
            $dataStructureManager = DataStructureManagerFacade::getInstance();
            $engine = EngineFacade::getInstance();
            $serverside_rendering = PoP_ServerSideRenderingFactory::getInstance();
            $module = $engine->getEntryModule();
            $processor = $moduleprocessor_manager->getProcessor($module);
            $formatter = $dataStructureManager->getDataStructureFormatter();
            $request = $_REQUEST;

            // In order to obtain the dbobjectids from the results, located under pssId and bsId
            $pagesection_settings_id = $this->getPagesectionSettingsid();
            $block_module = $this->getBlockModule();
            $block_settings_id = ModuleUtils::getModuleOutputName($block_module);

            // Set the recipient as the "current-user-id", pretending this user is logged in
            $vars = &ApplicationState::$vars;
            // First, save the old values, to restore them later
            $keys = [
                'is-user-logged-in',
                'current-user',
                'current-user-id',
            ];
            $user_global_state = [];
            foreach ($keys as $key) {
                $user_global_state[$key] = $vars['global-userstate'][$key];
            }
            // Then, can start to modify the global state
            $vars['global-userstate']['is-user-logged-in'] = true;

            $yesterday = strtotime("-1 day", POP_CONSTANT_CURRENTTIMESTAMP);
            foreach ($users as $user_id) {
                // Set the recipient as the "current-user-id", pretending this user is logged in
                $vars['global-userstate']['current-user'] = $cmsusersapi->getUserById($user_id)/*new WP_User($user_id, '')*/;
                $vars['global-userstate']['current-user-id'] = $user_id;

                // Return the notifications from within the last 24 hs, or from the last time the user was last seen in the website, whatever is higher
                // By default, use last 24 hs
                $lastaccess = PoP_UserPlatform_UserUtils::getUserLastAccess($user_id);
                $request['hist_time_compare'] = '>=';
                $request['hist_time'] = ($lastaccess && $lastaccess > $yesterday) ? $lastaccess : $yesterday;

                // Regenerate the data
                $data = $engine->getModuleData($module, $processor, $engine->props, $formatter, $request);

                // If the user has no notifications, then skip it
                // Simply check if the dbobjectids for the user is empty, for the main block
                if (empty($data['dbobjectids'][$pagesection_settings_id][$block_settings_id])) {
                    continue;
                }

                // Make sure the JSON in the SSR has been initialized
                $serverside_rendering->initJson();

                // Merge the new data into the server-side rendering context
                $serverside_rendering->mergeJson($data);

                // Initialize the popManager once again, so that it merges the new data into the context
                $serverside_rendering->initPopmanager();

                // Now we can call again function getContent(), which will have the right context for that user
                $emails[] = array(
                    'users' => array($user_id),
                    'subject' => $this->getSubject($user_id),
                    'content' => $this->getContent(),
                    'frame' => $this->getFrame(),
                );
            }

            // Restore the old global_status
            foreach ($keys as $key) {
                $vars['global-userstate'][$key] = $user_global_state[$key];
            }
        }
        return $emails;
    }

    protected function getSubject($user_id)
    {
        return '';
    }
}
