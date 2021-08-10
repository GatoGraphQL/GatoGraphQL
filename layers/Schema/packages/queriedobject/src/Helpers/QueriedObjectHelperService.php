<?php

declare(strict_types=1);

namespace PoPSchema\QueriedObject\Helpers;

// use PoP\ComponentModel\Feedback\Tokens;
// use PoP\ComponentModel\Facades\Schema\FeedbackMessageStoreFacade;
// use PoP\Translation\Facades\TranslationAPIFacade;

class QueriedObjectHelperService implements QueriedObjectHelperServiceInterface
{
    /**
     * Return the minimum number from between the request limit and the max limit.
     */
    public static function getLimitOrMaxLimit(
        ?int $limit,
        ?int $maxLimit/*, bool $addSchemaWarning = true*/
    ): ?int {
        // $limit with values -1 or 0 could mean "unlimited"
        if (!is_null($maxLimit) && $maxLimit != -1 && ($limit <= 0 || $limit > $maxLimit)) {
            // Commented adding the schema warning because it doesn't work in nested queries
            // Eg: "posts" under the author has max limit of 5, the warning is added successfully,
            // but it doesn't show in the response (I didn't check out why)
            // query MyQuery {
            //     users {
            //       posts(limit:8) {
            //         title
            //       }
            //     }
            //   }
            // }

            // Add a warning in the query response
            // if ($addSchemaWarning) {
            //     $translationAPI = TranslationAPIFacade::getInstance();
            //     $schemaWarnings = [];
            //     $schemaWarnings[] = [
            //         // Tokens::PATH => [$typeField],
            //         Tokens::MESSAGE => sprintf(
            //             $translationAPI->__('Using max limit of \'%s\' instead of requested limit of \'%s\'', 'posts'),
            //             $maxLimit,
            //             $limit
            //         ),
            //     ];
            //     $feedbackMessageStore = FeedbackMessageStoreFacade::getInstance();
            //     $feedbackMessageStore->addSchemaWarnings($schemaWarnings);
            // }
            $limit = $maxLimit;
        }
        return $limit;
    }
}
