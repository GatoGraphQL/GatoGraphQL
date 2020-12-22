<?php

interface PoP_Mailer_EmailQueue
{
    public function getQueue();
    public function enqueueEmail($to, $subject, $msg, $headers);
}
