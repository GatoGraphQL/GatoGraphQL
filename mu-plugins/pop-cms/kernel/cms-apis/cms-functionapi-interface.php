<?php
namespace PoP\CMS;

interface FunctionAPI
{
    public function getOption($option, $default = false);
    public function redirect($url);
    public function getSiteName();
    public function getSiteDescription();
    public function getAdminUserEmail();
    public function getVersion();
    public function getHomeURL();
    public function getSiteURL();
    public function hash($data);
    public function kses($string, $allowed_html);
    public function sendEmail($to, $subject, $msg, $headers = '', $attachments = array());
    public function convertError($cmsError);
    public function isError($object);
    public function isAdminPanel();
    public function getDocumentTitle();
}
