<?php use PoP\Hooks\Facades\HooksAPIFacade; ?>
<?php use PoP\Root\Facades\Translation\TranslationAPIFacade; ?>
<div class="pop-notificationmsg website-level alert alert-warning" role="alert">
    <?php
        echo HooksAPIFacade::getInstance()->applyFilters(
            'gd_loading_waittoclickmsg',
            TranslationAPIFacade::getInstance()->__('The website is loading, please wait a few moments to click on links.', 'poptheme-wassup')
        );
        ?>
</div>
<div class="loadinglogo">
    <?php $gdLogo = gdLogo('large') ?>
    <?php $maxwidth = $gdLogo[1] ?>
    <p>
        <img id="loading-logo" class="img-responsive" src="<?php echo HooksAPIFacade::getInstance()->applyFilters('gd_images_loading', $gdLogo[0]) ?>">
    </p>
    <p class="loadingmsg">
        <i class="fa fa-lg fa-spinner fa-spin"></i>
    <?php
    printf(
        '<em><strong>%s</strong>, %s</em>',
        HooksAPIFacade::getInstance()->applyFilters('gd_loading_msg', TranslationAPIFacade::getInstance()->__('Loading the website', 'poptheme-wassup')),
        TranslationAPIFacade::getInstance()->__('please wait...', 'poptheme-wassup')
    );
    ?>
    </p>
</div>
