<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ConditionalOnEnvironment\RemoveIfNull\SchemaServices\DirectiveResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\DirectiveResolvers\AbstractGlobalDirectiveResolver;
use PoP\ComponentModel\DirectiveResolvers\RemoveIDsDataFieldsDirectiveResolverTrait;

/**
 * If a field is `null`, then remove it from the response
 *
 * Warning: this directive works over the whole context, so a null field will be removed everywhere,
 * including on that same field whenever this directive is not applied! In order to avoid this issue,
 * if the same field is both applied and not applied this directive, then they must have a different alias
 *
 * For instance, in the queries below, the 1st post has a featuredImage, the 2nd one doesn't (so it returns `null`).
 *
 * This query works well:
 *
 * ```graphql
 * query {
 *   posts(limit:2) {
 *     title
 *     featuredImage @removeIfNull {
 *       src
 *     }
 *   }
 * }
 * ```
 *
 * Response:
 *
 * ```json
 * {
 *   "data": {
 *     "posts": [
 *       {
 *         "title": "Hello world (with image)!",
 *         "featuredImage": {
 *           "src": "https://mycompany.com/images/postFeaturedImage.png"
 *         }
 *       },
 *       {
 *         "title": "Hellow world!"
 *       }
 *     ]
 *   }
 * }
 * ```
 *
 * This query doesn't work well, because `featuredImage` appears twice, once with @removeIfNull, once without:
 *
 * ```graphql
 * query {
 *   posts(limit:2) {
 *     title
 *     featuredImage @removeIfNull {
 *       src
 *     }
 *     author {
 *       posts(limit:2) {
 *         featuredImage {
 *           src
 *         }
 *       }
 *     }
 *   }
 * }
 * ```
 *
 * In the response, the results may be overriden:
 *
 * ```json
 * {
 *   "data": {
 *     "posts": [
 *       {
 *         "title": "Hello world (with image)!",
 *         "featuredImage": {
 *           "src": "https://mycompany.com/images/postFeaturedImage.png"
 *         },
 *         "author": {
 *           "posts": [
 *             {
 *               "featuredImage": {
 *                 "src": "https://mycompany.com/images/postFeaturedImage.png"
 *               }
 *             },
 *             {
 *               "featuredImage": null
 *             }
 *           ]
 *         }
 *       },
 *       {
 *         "title": "Hellow world!",
 *         "featuredImage": null,
 *         "author": {
 *           "posts": [
 *             {
 *               "featuredImage": null
 *             },
 *             {
 *               "featuredImage": null
 *             }
 *           ]
 *         }
 *       }
 *     ]
 *   }
 * }
 * ```
 *
 * To fix it, we add an alias to any of those 2 fields (in this case, alias `featuredImageOrNothing` to the first one):
 *
  * ```graphql
 * query {
 *   posts(limit:2) {
 *     title
 *     featuredImageOrNothing: featuredImage @removeIfNull {
 *       src
 *     }
 *     author {
 *       posts(limit:2) {
 *         featuredImage {
 *           src
 *         }
 *       }
 *     }
 *   }
 * }
 * ```
 *
 * It now produces the right response:
 *
 * ```json
 * {
 *   "data": {
 *     "posts": [
 *       {
 *         "title": "Hello world (with image)!",
 *         "featuredImageOrNothing": {
 *           "src": "https://mycompany.com/images/postFeaturedImage.png"
 *         },
 *         "author": {
 *           "posts": [
 *             {
 *               "featuredImage": {
 *                 "src": "https://mycompany.com/images/postFeaturedImage.png"
 *               }
 *             },
 *             {
 *               "featuredImage": null
 *             }
 *           ]
 *         }
 *       },
 *       {
 *         "title": "Hellow world!",
 *         "author": {
 *           "posts": [
 *             {
 *               "featuredImage": null
 *             },
 *             {
 *               "featuredImage": null
 *             }
 *           ]
 *         }
 *       }
 *     ]
 *   }
 * }
 * ```
 */
class RemoveIfNullDirectiveResolver extends AbstractGlobalDirectiveResolver
{
    use RemoveIDsDataFieldsDirectiveResolverTrait;

    public function getDirectiveName(): string
    {
        return 'removeIfNull';
    }

    public function resolveDirective(
        TypeResolverInterface $typeResolver,
        array &$idsDataFields,
        array &$succeedingPipelineIDsDataFields,
        array &$succeedingPipelineDirectiveResolverInstances,
        array &$resultIDItems,
        array &$unionDBKeyIDs,
        array &$dbItems,
        array &$previousDBItems,
        array &$variables,
        array &$messages,
        array &$dbErrors,
        array &$dbWarnings,
        array &$dbDeprecations,
        array &$dbNotices,
        array &$dbTraces,
        array &$schemaErrors,
        array &$schemaWarnings,
        array &$schemaDeprecations,
        array &$schemaNotices,
        array &$schemaTraces
    ): void {
        $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
        $idsDataFieldsToRemove = [];
        foreach ($idsDataFields as $id => $dataFields) {
            foreach ($dataFields['direct'] as $field) {
                $fieldOutputKey = $fieldQueryInterpreter->getFieldOutputKey($field);
                // If the value is null, then remove it from the dbItems object, which contains the data for all retrieved objects
                // Please notice that this object contains the data for all fields for all types in the query!
                // Then, make sure that the affected field has a unique alias to avoid side-effects
                if (is_null($dbItems[(string)$id][$fieldOutputKey])) {
                    $idsDataFieldsToRemove[(string)$id]['direct'][] = $field;
                    unset($dbItems[(string)$id][$fieldOutputKey]);
                }
            }
        }
        /**
         * Remove the IDs for all succeeding pipeline directives
         * Must check that directives which do not apply on the $resultIDItems, such as @cacheControl, are not affected
         * (check function `needsIDsDataFieldsToExecute` must be `false` for them)
         */
        if ($idsDataFieldsToRemove) {
            $this->removeIDsDataFields($idsDataFieldsToRemove, $succeedingPipelineIDsDataFields);
        }
    }

    public function getSchemaDirectiveDescription(TypeResolverInterface $typeResolver): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return $translationAPI->__('Remove the field from the response if it is `null`', 'engine');
    }
}
