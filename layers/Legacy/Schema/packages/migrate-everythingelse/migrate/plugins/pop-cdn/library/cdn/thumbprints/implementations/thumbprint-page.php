<?php

define('POP_CDN_THUMBPRINT_PAGE', 'page');

class PoP_CDN_Thumbprint_Page extends PoP_CDN_Thumbprint_PageBase
{
    public function getName(): string
    {
        return POP_CDN_THUMBPRINT_PAGE;
    }
}
    
/**
 * Initialize
 */
new PoP_CDN_Thumbprint_Page();
