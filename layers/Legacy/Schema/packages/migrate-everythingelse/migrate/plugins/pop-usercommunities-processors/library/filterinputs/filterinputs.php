<?php
use PoP\ComponentModel\FilterInputProcessors\AbstractFilterInputProcessor;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPCMSSchema\Users\Facades\UserTypeAPIFacade;

class GD_URE_Module_Processor_FilterInputProcessor extends AbstractFilterInputProcessor
{
    public final const URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITYPLUSMEMBERS = 'filterinput-typeahead-communityplusmembers';
    public final const URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES_POST = 'filterinput-typeahead-communities-post';
    public final const URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES_USER = 'filterinput-typeahead-communities-user';
    public final const URE_FILTERINPUT_MEMBERPRIVILEGES = 'filterinput-memberprivileges';
    public final const URE_FILTERINPUT_MEMBERTAGS = 'filterinput-membertags';
    public final const URE_FILTERINPUT_MEMBERSTATUS = 'filterinput-memberstatus';

    public function getFilterInputsToProcess(): array
    {
        return array(
            [self::class, self::URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES_POST],
            [self::class, self::URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES_USER],
            [self::class, self::URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITYPLUSMEMBERS],
            [self::class, self::URE_FILTERINPUT_MEMBERPRIVILEGES],
            [self::class, self::URE_FILTERINPUT_MEMBERTAGS],
            [self::class, self::URE_FILTERINPUT_MEMBERSTATUS],
        );
    }

    public function filterDataloadQueryArgs(array $filterInput, array &$query, mixed $value): void
    {
        switch ($filterInput[1]) {
            case self::URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITYPLUSMEMBERS:
                $query['authors'] = $value;
                break;

            case self::URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES_POST:
                // Return all selected Communities + their Members
                $userTypeAPI = UserTypeAPIFacade::getInstance();
                $memberstatus_key = \PoPCMSSchema\UserMeta\Utils::getMetaKey(GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERSTATUS);
                $memberprivileges_key = \PoPCMSSchema\UserMeta\Utils::getMetaKey(GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERPRIVILEGES);
                $members = [];
                foreach ($value as $community) {
                    // Taken from https://codex.wordpress.org/Class_Reference/WP_Meta_Query
                    $members = array_merge(
                        $members,
                        $userTypeAPI->getUsers(
                            [
                                // 'fields' => 'ID',
                                'meta-query' => [
                                    'relation' => 'AND',
                                    [
                                        'key' => $memberstatus_key,
                                        'value' => gdUreGetCommunityMetavalue($community, GD_URE_METAVALUE_PROFILE_COMMUNITIES_MEMBERSTATUS_ACTIVE),
                                        'compare' => 'IN'
                                    ],
                                    [
                                        'key' => $memberprivileges_key,
                                        'value' => gdUreGetCommunityMetavalue($community, GD_URE_METAVALUE_PROFILE_COMMUNITIES_MEMBERPRIVILEGES_CONTRIBUTECONTENT),
                                        'compare' => 'IN'
                                    ]
                                ]
                            ],
                            [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]
                        )
                    );
                }

                // Add the communities back, and make sure the results are unique
                $query['authors'] = array_unique(
                    array_merge(
                        $value,
                        $members
                    )
                );
                break;

            case self::URE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES_USER:
                // Add the 'contributecontent' status to the value for each selected community
                $value = array_map(gdUreGetCommunityMetavalueContributecontent(...), $value);
                $query['meta-query'][] = [
                    'key' => \PoPCMSSchema\UserMeta\Utils::getMetaKey(GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERPRIVILEGES),
                    'value' => $value,
                    'compare' => 'IN',
                ];
                break;

            case self::URE_FILTERINPUT_MEMBERPRIVILEGES:
                $value = array_map(gdUreGetCommunityMetavalueCurrentcommunity(...), $value);
                $query['meta-query'][] = [
                    'key' => \PoPCMSSchema\UserMeta\Utils::getMetaKey(GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERPRIVILEGES),
                    'value' => $value,
                    'compare' => 'IN',
                ];
                break;

            case self::URE_FILTERINPUT_MEMBERTAGS:
                $value = array_map(gdUreGetCommunityMetavalueCurrentcommunity(...), $value);
                $query['meta-query'][] = [
                    'key' => \PoPCMSSchema\UserMeta\Utils::getMetaKey(GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERTAGS),
                    'value' => $value,
                    'compare' => 'IN',
                ];
                break;

            case self::URE_FILTERINPUT_MEMBERSTATUS:
                $value = array_map(gdUreGetCommunityMetavalueCurrentcommunity(...), $value);
                $query['meta-query'][] = [
                    'key' => \PoPCMSSchema\UserMeta\Utils::getMetaKey(GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERSTATUS),
                    'value' => $value,
                    'compare' => 'IN',
                ];
                break;
        }
    }
}
