<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

HooksAPIFacade::getInstance()->addAction('init', 'gdAddScriptsHeaderHook');
function gdAddScriptsHeaderHook()
{
    if (!is_admin()) {

        // Allow PoP Server-Side Rendering to change from header to footer
        $where = HooksAPIFacade::getInstance()->applyFilters(
            'add-scripts:where',
            'header'
        );

        if ($where == 'header') {
            HooksAPIFacade::getInstance()->addAction('wp_head', 'gdAddScriptsHeader', 0);
        } elseif ($where == 'footer') {
            HooksAPIFacade::getInstance()->addAction('wp_footer', 'gdAddScriptsHeader', 0);
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
