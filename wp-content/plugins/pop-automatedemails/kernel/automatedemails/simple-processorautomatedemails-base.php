<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class PoP_SimpleProcessorAutomatedEmailsBase extends PoP_ProcessorAutomatedEmailsBase {

    public function get_emails() {

        // Check that there are results. If not, then no need to send the email
        // global $gd_template_processor_manager;
        // $pagesection_settings_id = $this->get_pagesection_settingsid();
        // $block_template = $this->get_block_template();
        // $block_settings_id = $gd_template_processor_manager->get_processor($block_template)->get_settings_id($block_template);
        // $json = PoP_ServerSideRendering_Factory::get_instance()->get_json();
        // if ($json['dataset'][$pagesection_settings_id][$block_settings_id]) {
        if ($this->has_results()) {

            // If there are no users or recipients, no need to create the content
            $users = $this->get_users();
            $recipients = $this->get_recipients();
            if ($users || $recipients) {
                
                // Emails is an array of arrays, each of which has the following format:
                $item = array(
                    'users' => $users,
                    'recipients' => $recipients,
                    'subject' => $this->get_subject(),
                    'content' => $this->get_content(),
                    'frame' => $this->get_frame(),
                );
                return array($item);
            }
        }
        return array();
    }
    
    protected function get_users() {
        
        return array();
    }
    
    protected function get_recipients() {
        
        return array();
    }

    protected function get_subject() {
        
        return '';
    }

    protected function get_frame() {
        
        return GD_EMAIL_FRAME_PREFERENCES;
    }
}

