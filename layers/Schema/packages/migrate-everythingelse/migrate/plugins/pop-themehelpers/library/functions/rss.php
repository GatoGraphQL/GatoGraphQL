<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoPSchema\CustomPostMedia\Misc\MediaHelpers;
use PoP\ComponentModel\State\ApplicationState;

/**
 * Add the Featured Image to the feed
 * Taken from http://code.tutsplus.com/tutorials/extending-the-default-wordpress-rss-feed--wp-27935
 *
 * Mailchimp Documentation:
 * - http://kb.mailchimp.com/merge-tags/rss-blog/add-a-blog-post-to-any-campaign
 * - http://kb.mailchimp.com/merge-tags/rss-blog/rss-merge-tags
 */

HooksAPIFacade::getInstance()->addAction('rss2_ns', 'gdRssNamespace');
function gdRssNamespace()
{
    echo 'xmlns:media="http://search.yahoo.com/mrss/"
    xmlns:georss="http://www.georss.org/georss"';
}

HooksAPIFacade::getInstance()->addAction('rss2_item', 'gdRssFeaturedImage');
function gdRssFeaturedImage()
{
    $vars = ApplicationState::getVars();
    $post_id = $vars['routing-state']['queried-object-id'];
    gdRssPrintFeaturedImage($post_id);
}
function gdRssPrintFeaturedImage($post_id)
{
    $cmsmediaapi = \PoPSchema\Media\FunctionAPIFactory::getInstance();
    if ($featuredimage_id = MediaHelpers::getThumbId($post_id)) {
        $featuredimage = $cmsmediaapi->getMediaObject($featuredimage_id);

        // Allow to set the image width in the URL: Needed for using the rss merge tag *|RSSITEM:IMAGE|* in Mailchimp,
        // since it does not allow to resize the image
        $img_attr = HooksAPIFacade::getInstance()->applyFilters('gdRssPrintFeaturedImage:img_attr', $cmsmediaapi->getMediaSrc($featuredimage_id, 'thumb-md'), $featuredimage_id); ?>
        <media:content url="<?php echo $img_attr[0] ?>" type="<?php echo $featuredimage->post_mime_type; ?>" medium="image" width="<?php echo $img_attr[1] ?>" height="<?php echo $img_attr[2] ?>">
            <media:description type="plain"><![CDATA[<?php echo $featuredimage->post_title; ?>]]></media:description>
        </media:content>
        <?php
    }
}
