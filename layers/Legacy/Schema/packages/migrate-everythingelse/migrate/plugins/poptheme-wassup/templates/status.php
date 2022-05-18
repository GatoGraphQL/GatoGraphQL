<?php
    $status =
        '<div id="%1$s-status">'.
            '<div class="pop-loading top-level hidden alert alert-warning alert-sm">'.
                '<span class="pop-loading-msg">'.POP_LOADING_MSG.'</span>'.
                ' <small><span class="pop-box"></span></small>'.
            '</div>'.
            '<div id="%1$s-status-error" class="pop-error top-level hidden alert alert-warning alert-sm">'.
                '<button type="button" class="close" aria-hidden="true" onclick="document.getElementById(\'%1$s-status-error\').className += \' hidden\';">&times;</button>'.
                '<div class="pop-box"></div>'.
            '</div>'.
        '</div>';
?>
<div id="<?php echo POP_IDS_APPSTATUS ?>">
    <?php printf($status, POP_COMPONENTID_PAGESECTIONCONTAINERID_BODY) ?>
    <?php printf($status, POP_COMPONENTID_PAGESECTIONCONTAINERID_QUICKVIEW) ?>
    <?php printf($status, POP_COMPONENTID_PAGESECTIONCONTAINERID_NAVIGATOR) ?>
</div>
