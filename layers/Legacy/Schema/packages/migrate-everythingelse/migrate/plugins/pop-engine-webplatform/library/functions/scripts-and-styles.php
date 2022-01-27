<?php

\PoP\Root\App::addAction('init', 'gdAddScriptsHeaderHook');
function gdAddScriptsHeaderHook()
{
    if (!is_admin()) {

        // Allow PoP Server-Side Rendering to change from header to footer
        $where = \PoP\Root\App::applyFilters(
            'add-scripts:where',
            'header'
        );

        if ($where == 'header') {
            \PoP\Root\App::addAction('wp_head', 'gdAddScriptsHeader', 0);
        } elseif ($where == 'footer') {
            \PoP\Root\App::addAction('wp_footer', 'gdAddScriptsHeader', 0);
        }
    }
}
function gdAddScriptsHeader()
{
    // Code taken from /wp-admin/admin-header.php
    // Otherwise we get an error:
    // Uncaught ReferenceError: ajaxurl is not defined wp-lists.min.js?ver=4.2.2:1 ?>
<script type="text/javascript">
var ajaxurl = '<?php echo admin_url('admin-ajax.php', 'relative'); ?>';
</script>
<?php }
