<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\State\ApplicationState;

class PoP_AutomatedEmails_Operator
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addAction(
            'popcms:shutdown',
            array($this, 'maybeSendAutomatedemail')
        );
    }
    
    public function maybeSendAutomatedemail()
    {
        global $pop_automatedemails_manager;
        $vars = ApplicationState::getVars();
        $cmsusersapi = \PoPSchema\Users\FunctionAPIFactory::getInstance();

        if ($vars['routing-state']['is-standard']) {
            $route = $vars['route'];
            if ($automatedemails = $pop_automatedemails_manager->getAutomatedEmails($route)) {
                foreach ($automatedemails as $automatedemail) {
                    // Allow to change the header to 'newsletter' under PoPTheme Wassup
                    $header = HooksAPIFacade::getInstance()->applyFilters(
                        'PoP_AutomatedEmails_Operator:automatedemail:header',
                        null
                    );
                    foreach ($automatedemail->getEmails() as $email) {
                        $users = $email['users'] ?? array();
                        $recipients = $email['recipients'] ?? array();
                        if ($users || $recipients) {
                            $useremails = $names = array();
                            foreach ($users as $user_id) {
                                $useremails[] = $cmsusersapi->getUserEmail($user_id);
                                $names[] = $cmsusersapi->getUserDisplayName($user_id);
                            }

                            // Allow Gravity Forms to already send a list of useremails and names
                            foreach ($recipients as $recipient) {
                                $useremails[] = $recipient['email'];
                                $names[] = $recipient['name'];
                            }

                            // // Comment Leo: descomentar acá
                            // echo PHP_EOL.PHP_EOL;
                            // echo 'USERS: '.implode(',', $names).PHP_EOL;
                            // echo 'EMAILS: '.implode(',', $useremails).PHP_EOL;
                            // echo 'CONTENT: '.PHP_EOL;
                            // echo $email['content'];
                            // echo PHP_EOL;
                            PoP_EmailSender_Utils::sendemailToUsers($useremails, $names, $email['subject'], $email['content'], true, $header, $email['frame']);
                        }
                    }
                }
            }
        }
    }
}
    
/**
 * Initialize
 */
new PoP_AutomatedEmails_Operator();
