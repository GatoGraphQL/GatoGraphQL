<?php
namespace PoP\TrendingTags\WP;
use PoPCMSSchema\SchemaCommons\Facades\CMS\CMSServiceFacade;
use PoP\LooseContracts\Facades\NameResolverFacade;

class FunctionAPI extends \PoP\TrendingTags\FunctionAPI_Base
{
    public function getTrendingHashtagIds($days, $number, $offset)
    {
        global $wpdb;

        // Solution to get the Trending Tags taken from https://wordpress.org/support/topic/limit-tags-by-date
        $cmsService = CMSServiceFacade::getInstance();
        $time_difference = $cmsService->getOption(NameResolverFacade::getInstance()->getName('popcms:option:gmtOffset')) * HOUR_IN_SECONDS;
        $timenow = time() + $time_difference;
        $timelimit = $timenow - ($days * 24 * HOUR_IN_SECONDS);
        $now = gmdate('Y-m-d H:i:s', $timenow);
        $datelimit = gmdate('Y-m-d H:i:s', $timelimit);

        $sql = "
            SELECT
                $wpdb->terms.term_id,
                COUNT($wpdb->terms.term_id) as count
            FROM
                $wpdb->posts,
                $wpdb->term_relationships,
                $wpdb->term_taxonomy,
                $wpdb->terms
            WHERE
                $wpdb->posts.ID = $wpdb->term_relationships.object_id AND
                $wpdb->term_taxonomy.term_taxonomy_id = $wpdb->term_relationships.term_taxonomy_id AND
                $wpdb->term_taxonomy.term_id = $wpdb->terms.term_id AND
                post_status = 'publish' AND
                post_date < '$now' AND
                post_date > '$datelimit' AND
                $wpdb->term_taxonomy.taxonomy='post_tag'
            GROUP BY
                $wpdb->terms.term_id
            ORDER BY
                count
            DESC
        ";

        // Use pagination if a limit was set
        if ($number && (intval($number) > 0)) {
            $sql .= sprintf(
                ' LIMIT %s',
                intval($number)
            );
        }
        if ($offset && (intval($offset) > 0)) {
            $sql .= sprintf(
                ' OFFSET %s',
                intval($offset)
            );
        }

        $ids = array();
        if ($results = $wpdb->get_results($sql)) {
            foreach ($results as $result) {
                $ids[] = $result->term_id;
            }
        }

        return $ids;
    }
}

/**
 * Initialize
 */
new FunctionAPI();
