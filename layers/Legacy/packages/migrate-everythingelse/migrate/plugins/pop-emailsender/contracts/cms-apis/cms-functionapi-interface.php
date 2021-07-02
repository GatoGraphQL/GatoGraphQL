<?php
namespace PoP\EmailSender;

interface FunctionAPI
{
    public function getAdminUserEmail();
    public function sendEmail($to, $subject, $msg, $headers = '', $attachments = array());
}
