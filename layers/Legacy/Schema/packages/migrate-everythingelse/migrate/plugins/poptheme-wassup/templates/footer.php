        <?php

        // include POPTHEME_WASSUP_TEMPLATES.'/hover.php';
        
        // Include the Theme Footer
        $vars = \PoP\ComponentModel\State\ApplicationState::getVars();
        $theme_footer = \PoP\Root\App::getState('theme-path').'/footer.php';
        if (file_exists($theme_footer)) {
            include $theme_footer;
        }

        // IMPORTANT: do not move the position of Operational pageSection
        // Because we have styles that place it directly after the pagesection-group (eg: .pagesection-group.active-top+.operational)
        ?>
    <?php /* ?>
    <div class="pagesection-group-after">
    <?php
    include POPTHEME_WASSUP_TEMPLATES.'/hole.php';
    ?>
    </div>
    <?php */ ?>
    <?php

    // // Include the 'Startup Components' panel
    // include POPTHEME_WASSUP_TEMPLATES.'/framecomponents.php';

    // // Include the 'Modals' panel
    // include POPTHEME_WASSUP_TEMPLATES.'/modals.php';

    // // Include the 'Quickview' panel
    // include POPTHEME_WASSUP_TEMPLATES.'/quickview.php';
    ?>
    </div><!--#wrapper-->

    <?php $htmlcssplatformapi = \PoP\EngineHTMLCSSPlatform\FunctionAPIFactory::getInstance(); ?>
    <?php $htmlcssplatformapi->printFooterHTML() ?>
</body>
</html>
