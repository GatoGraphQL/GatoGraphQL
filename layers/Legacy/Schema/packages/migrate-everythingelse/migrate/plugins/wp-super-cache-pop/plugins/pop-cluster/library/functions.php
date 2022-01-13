<?php
use PoP\Engine\Facades\CMS\CMSServiceFacade;
use PoP\Root\Facades\Hooks\HooksAPIFacade;

// In file wp-content/plugins/wp-super-cache-pop/plugins/pop-cluster/plugins/pop-system/installation.php
// Generate the .htaccess rules for the Gzip compression inside the cluster folder
// In addition, we also need to add code below add in .htaccess
\PoP\Root\App::getHookManager()->addFilter('supercacherewriterules', 'popClusterSupercacherewriterules');
function popClusterSupercacherewriterules($rules)
{
    $cmsService = CMSServiceFacade::getInstance();

    // Code copied from from plugins/wp-super-cache/wp-cache.php
    if (isset($_SERVER[ "PHP_DOCUMENT_ROOT" ])) {
        $document_root = $_SERVER[ "PHP_DOCUMENT_ROOT" ];
        $apache_root = $_SERVER[ "PHP_DOCUMENT_ROOT" ];
    } else {
        $document_root = $_SERVER[ "DOCUMENT_ROOT" ];
        $apache_root = '%{DOCUMENT_ROOT}';
    }
    $content_dir_root = $document_root;
    if (strpos($document_root, '/kunden/homepages/') === 0) {
        // http://wordpress.org/support/topic/plugin-wp-super-cache-how-to-get-mod_rewrite-working-on-1and1-shared-hosting?replies=1
        // On 1and1, PHP's directory structure starts with '/homepages'. The
        // Apache directory structure has an extra '/kunden' before it.
        // Also 1and1 does not support the %{DOCUMENT_ROOT} variable in
        // .htaccess files.
        // This prevents the $inst_root from being calculated correctly and
        // means that the $apache_root is wrong.
        //
        // e.g. This is an example of how Apache and PHP see the directory
        // structure on    1and1:
        // Apache: /kunden/homepages/xx/dxxxxxxxx/htdocs/site1/index.html
        // PHP:           /homepages/xx/dxxxxxxxx/htdocs/site1/index.html
        // Here we fix up the paths to make mode_rewrite work on 1and1 shared hosting.
        $content_dir_root = substr($content_dir_root, 7);
        $apache_root = $document_root;
    }
    $home_root = parse_url($cmsService->getHomeURL());
    $home_root = isset($home_root['path']) ? trailingslashit($home_root['path']) : '/';
    $home_root_lc = str_replace('//', '/', strtolower($home_root));
    $inst_root = str_replace('//', '/', '/' . trailingslashit(str_replace($content_dir_root, '', str_replace('\\', '/', WP_CONTENT_DIR))));

    // Here below are my own additions
    $rules = str_replace("</IfModule>\n", '', $rules);
    
    // Add new rules: also use %{SERVER_NAME}/ after cache/ for each condition and rule, so we can host several websites together, each one with its own cache folder
    $rules .= "\n\n";
    $rules .= "CONDITION_RULES";
    $rules .= "RewriteCond %{HTTP:Accept-Encoding} gzip\n";
    $rules .= "RewriteCond %{HTTPS} on\n";
    $rules .= "RewriteCond {$apache_root}{$inst_root}cache/%{SERVER_NAME}/supercache/%{SERVER_NAME}{$home_root_lc}$1/index-https.html.gz -f\n";
    $rules .= "RewriteRule ^(.*) \"{$inst_root}cache/%{SERVER_NAME}/supercache/%{SERVER_NAME}{$home_root_lc}$1/index-https.html.gz\" [L]\n\n";

    $rules .= "CONDITION_RULES";
    $rules .= "RewriteCond %{HTTP:Accept-Encoding} gzip\n";
    $rules .= "RewriteCond %{HTTPS} !on\n";
    $rules .= "RewriteCond {$apache_root}{$inst_root}cache/%{SERVER_NAME}/supercache/%{SERVER_NAME}{$home_root_lc}$1/index.html.gz -f\n";
    $rules .= "RewriteRule ^(.*) \"{$inst_root}cache/%{SERVER_NAME}/supercache/%{SERVER_NAME}{$home_root_lc}$1/index.html.gz\" [L]\n\n";

    $rules .= "CONDITION_RULES";
    $rules .= "RewriteCond %{HTTPS} on\n";
    $rules .= "RewriteCond {$apache_root}{$inst_root}cache/%{SERVER_NAME}/supercache/%{SERVER_NAME}{$home_root_lc}$1/index-https.html -f\n";
    $rules .= "RewriteRule ^(.*) \"{$inst_root}cache/%{SERVER_NAME}/supercache/%{SERVER_NAME}{$home_root_lc}$1/index-https.html\" [L]\n\n";

    $rules .= "CONDITION_RULES";
    $rules .= "RewriteCond %{HTTPS} !on\n";
    $rules .= "RewriteCond {$apache_root}{$inst_root}cache/%{SERVER_NAME}/supercache/%{SERVER_NAME}{$home_root_lc}$1/index.html -f\n";
    $rules .= "RewriteRule ^(.*) \"{$inst_root}cache/%{SERVER_NAME}/supercache/%{SERVER_NAME}{$home_root_lc}$1/index.html\" [L]\n";
    $rules .= "</IfModule>\n";

    return $rules;
}
