<?php
namespace PoP\CMS\WP;

class WPCMSRoutingState extends \PoP\CMS\CMSRoutingStateBase
{
    use WPCMSRoutingStateTrait;

    public function getNature()
    {
        $this->init();
        if ($this->isStandard()) {
            return POP_NATURE_STANDARD;
        } elseif ($this->query->is_home()) {
            return POP_NATURE_HOME;
        } elseif ($this->query->is_tag()) {
            return POP_NATURE_TAG;
        } elseif ($this->query->is_page()) {
            return POP_NATURE_PAGE;
        } elseif ($this->query->is_single()) {
            return POP_NATURE_SINGLE;
        } elseif ($this->query->is_author()) {
            return POP_NATURE_AUTHOR;
        } elseif ($this->query->is_404()) {
            return POP_NATURE_404;
        } elseif ($this->query->is_category()) {
            return POP_NATURE_CATEGORY;
        }

        return parent::getNature();
    }
}

/**
 * Initialize
 */
new WPCMSRoutingState();
