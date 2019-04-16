<?php
namespace PoP\CMS\WP;

class FunctionAPI extends \PoP\CMS\FunctionAPI_Base
{

    public function getOption($option, $default = false)
    {
        return get_option($option, $default);
    }
    public function redirect($url)
    {
        wp_redirect($url);
    }

    public function getSiteName()
    {
        return get_bloginfo('name');
    }

    public function getSiteDescription()
    {
        return get_bloginfo('description');
    }

    public function getAdminUserEmail()
    {
        return get_bloginfo('admin_email');
    }

    public function getVersion()
    {
        return get_bloginfo('version');
    }

    public function getHomeURL()
    {
        return home_url();
    }

    public function getSiteURL()
    {
        return get_site_url();
    }

    public function hash($data)
    {
        return wp_hash($data);
    }

    public function kses($string, $allowed_html)
    {
        return wp_kses($string, $allowed_html);
    }

    public function sendEmail($to, $subject, $msg, $headers = '', $attachments = array())
    {
        return wp_mail($to, $subject, $msg, $headers, $attachments);
    }

    protected function returnResultOrConvertError($result)
    {
        if ($this->isError($result)) {
            return $this->convertError($result);
        }
        return $result;
    }

    public function convertError($cmsError)
    {
        $error = new \PoP\Engine\Error();
        foreach ($cmsError->get_error_codes() as $code) {
            $error->add($code, $cmsError->get_error_message($code), $cmsError->get_error_data($code));
        }
        return $error;
    }

    public function isError($object)
    {
        return is_wp_error($object);
    }

    public function isAdminPanel()
    {
        return is_admin();
    }

    public function getDocumentTitle()
    {
        return wp_get_document_title();
    }
}

/**
 * Initialize
 */
new FunctionAPI();
