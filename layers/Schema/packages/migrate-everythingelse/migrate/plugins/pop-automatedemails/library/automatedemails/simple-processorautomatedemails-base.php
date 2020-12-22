<?php

class PoP_SimpleProcessorAutomatedEmailsBase extends PoP_ProcessorAutomatedEmailsBase
{
    public function getEmails()
    {

        // Check that there are results. If not, then no need to send the email
        if ($this->hasResults()) {
            // If there are no users or recipients, no need to create the content
            $users = $this->getUsers();
            $recipients = $this->getRecipients();
            if ($users || $recipients) {
                // Emails is an array of arrays, each of which has the following format:
                $item = array(
                    'users' => $users,
                    'recipients' => $recipients,
                    'subject' => $this->getSubject(),
                    'content' => $this->getContent(),
                    'frame' => $this->getFrame(),
                );
                return array($item);
            }
        }
        return array();
    }
    
    protected function getUsers()
    {
        return array();
    }
    
    protected function getRecipients()
    {
        return array();
    }

    protected function getSubject()
    {
        return '';
    }
}
