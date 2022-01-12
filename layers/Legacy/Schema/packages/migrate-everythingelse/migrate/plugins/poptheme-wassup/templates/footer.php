        <?php

        // Include the Theme Footer
        $theme_footer = \PoP\Root\App::getState('theme-path').'/footer.php';
        if (file_exists($theme_footer)) {
            include $theme_footer;
        }
        ?>
    </div><!--#wrapper-->

    <?php $htmlcssplatformapi = \PoP\EngineHTMLCSSPlatform\FunctionAPIFactory::getInstance(); ?>
    <?php $htmlcssplatformapi->printFooterHTML() ?>
</body>
</html>
