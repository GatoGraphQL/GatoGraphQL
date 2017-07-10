<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class PoP_LoopUsersProcessorAutomatedEmailsBase extends PoP_ProcessorAutomatedEmailsBase {

    public function get_emails() {
        
        $emails = array();
        if ($recipients = $this->get_recipients()) {

            // All the variables needed to operate into the pop-engine.php get_data function
            global $gd_template_processor_manager;
            $engine = PoP_Engine_Factory::get_instance();
            $serverside_rendering = PoP_ServerSideRendering_Factory::get_instance();
            $template_id = $engine->get_toplevel_template_id();
            $processor = $gd_template_processor_manager->get_processor($template_id);
            $formatter = $engine->get_datastructure_formatter();
            $request = $_REQUEST;

            // In order to obtain the dataset from the results, located under pssId and bsId
            $pagesection_settings_id = $this->get_pagesection_settingsid();
            $block_template = $this->get_block_template();
            $block_settings_id = $gd_template_processor_manager->get_processor($block_template)->get_settings_id($block_template);
            
            // Set the recipient as the "current-user-id", pretending this user is logged in
            $vars = &GD_TemplateManager_Utils::$vars;
            // First, save the old values, to restore them later
            $user_global_state = array(
                'is-user-logged-in' => $vars['global-state']['is-user-logged-in'],
                'current-user' => $vars['global-state']['current-user'],
                'current-user-id' => $vars['global-state']['current-user-id'],
            );
            // Then, can start to modify the global state
            $vars['global-state']['is-user-logged-in'] = true;
            
            $yesterday = strtotime("-1 day", POP_CONSTANT_CURRENTTIMESTAMP/*current_time('timestamp')*/);
            foreach ($recipients as $user_id) {

                // Set the recipient as the "current-user-id", pretending this user is logged in
                $vars['global-state']['current-user'] = new WP_User($user_id, '');
                $vars['global-state']['current-user-id'] = $user_id;

                // // Set the recipient as the "current-user-id", pretending this user is logged in
                // $user_global_state = array(
                //     'is-user-logged-in' => true,
                //     'current-user' => new WP_User($user_id, ''),
                //     'current-user-id' => $user_id,
                // );
                // GD_TemplateManager_Utils::modify_vars_global_state($user_global_state);

                // Return the notifications from within the last 24 hs, or from the last time the user was last seen in the website, whatever is higher
                // By default, use last 24 hs
                $lastaccess = GD_MetaManager::get_user_meta($user_id, POP_METAKEY_USER_LASTACCESS, true);
                $request['hist_time_compare'] = '>=';
                $request['hist_time'] = ($lastaccess && $lastaccess > $yesterday) ? $lastaccess : $yesterday;

                // Regenerate the data
                $data = $engine->get_data($template_id, $processor, $engine->atts, $formatter, $request);

                // If the user has no notifications, then skip it
                // Simply check if the dataset for the user is empty, for the main block
                if (empty($data['dataset'][$pagesection_settings_id][$block_settings_id])) continue;

                // Make sure the JSON in the SSR has been initialized
                $serverside_rendering->init_json();

                // Merge the new data into the server-side rendering context
                $serverside_rendering->merge_json($data);
                // $serverside_rendering->json['dataset'] = $data['dataset'];
                // $serverside_rendering->json['database'] = $data['database'];
                // $serverside_rendering->json['userdatabase'] = $data['userdatabase'];
                // $serverside_rendering->json['params'] = $data['params'];
                // $serverside_rendering->json['feedback'] = $data['feedback'];

                // Initialize the popManager once again, so that it merges the new data into the context
                $serverside_rendering->init_popmanager();
                
                // Now we can call again function get_content(), which will have the right context for that user
                $emails[] = array(
                    'recipients' => array($user_id),
                    'subject' => $this->get_subject($user_id),
                    'content' => $this->get_content(),
                );
            }

            // Restore the old global_status
            $vars['global-state']['is-user-logged-in'] = $user_global_state['is-user-logged-in'];
            $vars['global-state']['current-user'] = $user_global_state['current-user'];
            $vars['global-state']['current-user-id'] = $user_global_state['current-user-id'];
        }
        return $emails;
    }
    
    protected function get_recipients() {
        
        return array();
    }

    protected function get_subject($user_id) {
        
        return '';
    }
}

