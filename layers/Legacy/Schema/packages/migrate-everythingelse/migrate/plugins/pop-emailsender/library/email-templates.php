<?php
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\Engine\Facades\CMS\CMSServiceFacade;
use PoP\Root\Facades\Hooks\HooksAPIFacade;

define('POP_EMAILTEMPLATE_EMAIL', 'email.html');
define('POP_EMAILTEMPLATE_EMAILBODY', 'emailbody.html');

class PoP_EmailSender_Templates
{
    protected $template_folders;
    protected $email_frames;

    public function __construct()
    {

        // Cache the already generated frames for other users
        $this->email_frames = array();

        // Set myself as the instance in the Factory
        PoP_EmailTemplatesFactory::setInstance($this);
    }

    public function getName(): string
    {
        return '';
    }

    public function getModuleFolders()
    {
        if (!$this->template_folders) {
            $this->template_folders = \PoP\Root\App::getHookManager()->applyFilters(
                'sendemailToUsers:template_folders',
                array(
                    POP_EMAILSENDER_DIR_RESOURCES.'/email-templates/default/',
                )
            );
        }
        return $this->template_folders;
    }

    public function addEmailframe($title, $content, $emails = array(), $names = array(), $template_name = POP_EMAILTEMPLATE_EMAIL/*, $frame = null*/)
    {
		$cmsService = CMSServiceFacade::getInstance();
        // If passing null, initialize it to the default value
        $template_name = $template_name ?? POP_EMAILTEMPLATE_EMAIL;
        // $frame = $frame ?? POP_EMAILFRAME_DEFAULT;

        // "addEmailframe" because if the template_folder is not set (eg: explicitly set to null, such as with PoP Mailer AWS) then it won't add anything
        $template = '';
        foreach ($this->getModuleFolders() as $template_folder) {
            if (file_exists($template_folder . $template_name)) {
                $template = $template_folder . $template_name;
                break;
            }
        }

        if ($template) {
            // if (!isset($this->email_frames[$template])) {
            //     $this->email_frames[$template] = array();
            // }

            // If the frame had been generated, then fetch it from the cache
            if (!isset($this->email_frames[$template]/*[$frame]*/)) {
                $url = GeneralUtils::maybeAddTrailingSlash($cmsService->getHomeURL());
                ob_start();
                include $template;
                $this->email_frames[$template]/*[$frame]*/ = str_replace(
                    '{{URL}}',
                    $url,
                    ob_get_clean()
                );
            }
            // Message
            $header = $this->getEmailframeHeader(/*$frame, */$title, $emails, $names, $template_name);
            $beforefooter = $this->getEmailframeBeforefooter(/*$frame, */$title, $emails, $names, $template_name);
            $footer = $this->getEmailframeFooter(/*$frame, */$title, $emails, $names, $template_name);
            $msg = str_replace(
                array('{{TITLE}}', '{{HEADER}}', '{{CONTENT}}', '{{BEFOREFOOTER}}', '{{FOOTER}}'),
                array($title, $header, $content, $beforefooter, $footer),
                $this->email_frames[$template]/*[$frame]*/
            );
        } else {
            $msg = $content;
        }

        return $msg;
    }

    public function getEmailframeHeader(/*$frame, */$title, $emails, $names, $template_name)
    {
        return '';
    }

    public function getEmailframeFooter(/*$frame, */$title, $emails, $names, $template_name)
    {
        return '';
    }

    public function getEmailframeBeforefooter(/*$frame, */$title, $emails, $names, $template_name)
    {
        return '';
    }

    public function getUserhtml($user_id)
    {
        return '';
    }

    public function getPosthtml($post_id)
    {
        return '';
    }

    public function getCommenthtml($comment)
    {
        return '';
    }

    public function getCommentcontenthtml($comment)
    {
        return '';
    }

    public function getTaghtml($tag_id)
    {
        return '';
    }

    public function getWebsitehtml()
    {
        return '';
    }

    public function getButtonhtml($title, $url)
    {
        return '';
    }
}
