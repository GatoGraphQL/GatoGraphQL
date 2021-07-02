<?php

PoP_ServerSideRenderingFactory::getInstance()->addTemplatePath(
    POP_APPLICATIONWEBPLATFORM_PHPTEMPLATES_DIR,
    array(
        POP_TEMPLATE_LAYOUT_LINK_ACCESS,
        POP_TEMPLATE_LAYOUT_VOLUNTEERTAG,
        POP_TEMPLATE_SPEECHBUBBLE,
    )
);
