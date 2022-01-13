<?php
use PoPSchema\CustomPosts\Types\Status;

class GD_CreateUpdate_Utils
{
    public static function moderate()
    {

        // Global constant defining if posts in the website can be created straight or subject to moderation
        return \PoP\Root\App::applyFilters('GD_CreateUpdate_Utils:moderate', false);
    }

    public static function getUpdatepostStatus($status, $moderate)
    {
        $statuses = [
            Status::PUBLISHED,
            Status::DRAFT,
        ];
        if ($moderate) {
            $statuses[] = Status::PENDING;
        }

        // Status: Validate the value only is one of the following ones
        if (!in_array($status, $statuses)) {
            $status = Status::DRAFT;
        }

        // When moderating, if the status is publish, then do nothing (so it won't override the existing 'publish' status), and then it can't be hacked by passing this value in the $_POST
        if ($moderate && ($status == Status::PUBLISHED)) {
            return null;
        }

        return $status;
    }
    public static function getCreatepostStatus($status, $moderate)
    {
        $statuses = [
            Status::DRAFT,
        ];
        if ($moderate) {
            // If moderating, cannot publish straight, goes to pending instead
            $statuses[] = Status::PENDING;
        } else {
            // If not moderating, 2 values available: draft or publish
            $statuses[] = Status::PUBLISHED;
        }

        // Status: Validate the value only is one of the following ones
        if (!in_array($status, $statuses)) {
            $status = Status::DRAFT;
        }

        return $status;
    }
}
