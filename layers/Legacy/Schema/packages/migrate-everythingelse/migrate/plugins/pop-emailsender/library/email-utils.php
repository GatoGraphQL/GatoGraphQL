<?php
use PoP\Engine\Facades\CMS\CMSServiceFacade;
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSchema\Users\Facades\UserTypeAPIFacade;

class PoP_EmailSender_Utils
{
    protected static $headers;

    public static function sendEmail($to, $subject, $msg, $headers_name = null)
    {
        if (is_null(self::$headers)) {
            self::init();
        }

        // Header is default, or a custom one
        $headers_name = $headers_name ?? 'default';
        $headers = self::$headers[$headers_name] ?? self::$headers['default'];

        $cmsemailsenderapi = \PoP\EmailSender\FunctionAPIFactory::getInstance();
        return $cmsemailsenderapi->sendEmail($to, $subject, $msg, $headers);
    }
    protected static function init()
    {

        // Allow to add extra headers. Eg: newsletters
        self::$headers = \PoP\Root\App::applyFilters(
            'PoP_EmailSender_Utils:init:headers',
            array(
                'default' => sprintf(
                    "From: %s <%s>\r\n",
                    self::getFromName(),
                    self::getFromEmail()
                ).sprintf(
                    "Content-Type: %s; charset=\"%s\"\n",
                    self::getContenttype(),
                    self::getCharset()
                )
            )
        );
    }

    public static function sendemailSkip($post_id)
    {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        $cmsapplicationpostsapi = \PoP\Application\PostsFunctionAPIFactory::getInstance();
        $skip = !in_array($customPostTypeAPI->getCustomPostType($post_id), $cmsapplicationpostsapi->getAllcontentPostTypes());

        // Check if for a given type of post the email must not be sent (eg: Highlights)
        return \PoP\Root\App::applyFilters('create_post:skip_sendemail', $skip, $post_id);
    }
    public static function getAdminNotificationsEmail()
    {

        $cmsemailsenderapi = \PoP\EmailSender\FunctionAPIFactory::getInstance();

        // By default, use the admin_email, but this can be overriden
        return \PoP\Root\App::applyFilters('gd_email_notifications_email', $cmsemailsenderapi->getAdminUserEmail());
    }
    public static function getFromName()
    {
        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        return \PoP\Root\App::applyFilters('gd_email_fromname', $cmsapplicationapi->getSiteName());
    }
    public static function getFromEmail()
    {
        $cmsemailsenderapi = \PoP\EmailSender\FunctionAPIFactory::getInstance();

        // By default, use the admin_email, but this can be overriden
        return \PoP\Root\App::applyFilters('gd_email_info_email', $cmsemailsenderapi->getAdminUserEmail());
    }
    public static function getContenttype()
    {
        return \PoP\Root\App::applyFilters('gd_email_contenttype', 'text/html');
    }
    public static function getCharset()
    {
        $cmsService = CMSServiceFacade::getInstance();
        return \PoP\Root\App::applyFilters('gd_email_charset', strtolower($cmsService->getOption(NameResolverFacade::getInstance()->getName('popcms:option:charset'))));
    }

    public static function sendemailToUsers($emails, $names, $subject, $msg, $individual = true, $header = null, $frame = null)
    {
        if (!is_array($emails)) {
            $emails = array($emails);
        }
        if ($names && !is_array($names)) {
            $names = array($names);
        }

        // When splitting, send individual emails to each author
        if ($individual) {
            for ($i=0; $i < count($emails); $i++) {
                $to = $emails[$i];
                if ($names) {
                    $name = array($names[$i]);
                }
                $emailmsg = PoP_EmailTemplatesFactory::getInstance($frame)->addEmailframe($subject, $msg, array($to), $name);
                self::sendEmail($to, $subject, $emailmsg, $header);
            }
        } else {
            $to = implode(',', $emails);
            $emailmsg = PoP_EmailTemplatesFactory::getInstance($frame)->addEmailframe($subject, $msg, $emails, $names);
            self::sendEmail($to, $subject, $emailmsg, $header);
        }
    }

    public static function sendemailToUsersFromPost($post_ids, $subject, $content, $exclude_authors = array())
    {
        if (!is_array($post_ids)) {
            $post_ids = array($post_ids);
        }
        $emails = array();
        $names = array();

        // If authors are repeated along different post_ids, they will still receive only 1 email each
        $authors = array();
        foreach ($post_ids as $post_id) {
            $authors = array_merge(
                $authors,
                gdGetPostauthors($post_id)
            );
        }
        // Just in case that some posts had the same author, filter them to send them the email just once
        $authors = array_unique($authors);

        // Exclude authors
        $userTypeAPI = UserTypeAPIFacade::getInstance();
        $authors = array_diff($authors, $exclude_authors);
        foreach ($authors as $author) {
            $emails[] = $userTypeAPI->getUserEmail($author);
            $names[] = $userTypeAPI->getUserDisplayName($author);
        }

        self::sendemailToUsers($emails, $names, $subject, $content, true);

        return $authors;
    }

    public static function sendemailToUser($user_id, $subject, $msg)
    {
        $userTypeAPI = UserTypeAPIFacade::getInstance();
        $email = $userTypeAPI->getUserEmail($user_id);
        $name = $userTypeAPI->getUserDisplayName($user_id);

        self::sendemailToUsers($email, $name, $subject, $msg);
    }
}
