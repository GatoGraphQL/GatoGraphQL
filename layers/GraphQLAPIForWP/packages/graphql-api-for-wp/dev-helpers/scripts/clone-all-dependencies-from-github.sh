#!/bin/bash
# Clone all dependencies from GraphQL API.
#
# This script creates a folder "graphql-api-components" with 3 subfolders
# containing all the components needed by the plugin,
# which are distributed across several organizations in GitHub:
# PoP, GraphQLByPoP, and PoPSchema.
# The subfolder names ("getpop", "graphql-by-pop" and "pop-schema")
# correspond to the package owners in Packagist. These are the same names
# set-up in `composer.local-sample.json` to override the PSR-4 mapping,
# needed to use the local sources and speed up development.

mkdir graphql-api-components && cd graphql-api-components
mkdir getpop && cd getpop

git clone git@github.com:getpop/access-control.git #https://github.com/getpop/access-control.git
git clone git@github.com:getpop/api-clients.git #https://github.com/getpop/api-clients.git
git clone git@github.com:getpop/api-endpoints-for-wp.git #https://github.com/getpop/api-endpoints-for-wp.git
git clone git@github.com:getpop/api-endpoints.git #https://github.com/getpop/api-endpoints.git
git clone git@github.com:getpop/api-graphql.git #https://github.com/getpop/api-graphql.git
git clone git@github.com:getpop/api-mirrorquery.git #https://github.com/getpop/api-mirrorquery.git
git clone git@github.com:getpop/api.git #https://github.com/getpop/api.git
git clone git@github.com:getpop/cache-control.git #https://github.com/getpop/cache-control.git
git clone git@github.com:getpop/component-model.git #https://github.com/getpop/component-model.git
git clone git@github.com:getpop/definitions.git #https://github.com/getpop/definitions.git
git clone git@github.com:getpop/engine-wp.git #https://github.com/getpop/engine-wp.git
git clone git@github.com:getpop/engine.git #https://github.com/getpop/engine.git
git clone git@github.com:getpop/field-query.git #https://github.com/getpop/field-query.git
git clone git@github.com:getpop/guzzle-helpers.git #https://github.com/getpop/guzzle-helpers.git
git clone git@github.com:getpop/hooks-wp.git #https://github.com/getpop/hooks-wp.git
git clone git@github.com:getpop/hooks.git #https://github.com/getpop/hooks.git
git clone git@github.com:getpop/loosecontracts.git #https://github.com/getpop/loosecontracts.git
git clone git@github.com:getpop/mandatory-directives-by-configuration.git #https://github.com/getpop/mandatory-directives-by-configuration.git
git clone git@github.com:getpop/migrate-api-graphql.git #https://github.com/getpop/migrate-api-graphql.git
git clone git@github.com:getpop/migrate-api.git #https://github.com/getpop/migrate-api.git
git clone git@github.com:getpop/migrate-component-model.git #https://github.com/getpop/migrate-component-model.git
git clone git@github.com:getpop/migrate-engine-wp.git #https://github.com/getpop/migrate-engine-wp.git
git clone git@github.com:getpop/migrate-engine.git #https://github.com/getpop/migrate-engine.git
git clone git@github.com:getpop/modulerouting.git #https://github.com/getpop/modulerouting.git
git clone git@github.com:getpop/query-parsing.git #https://github.com/getpop/query-parsing.git
git clone git@github.com:getpop/root.git #https://github.com/getpop/root.git
git clone git@github.com:getpop/routing-wp.git #https://github.com/getpop/routing-wp.git
git clone git@github.com:getpop/routing.git #https://github.com/getpop/routing.git
git clone git@github.com:getpop/translation-wp.git #https://github.com/getpop/translation-wp.git
git clone git@github.com:getpop/translation.git #https://github.com/getpop/translation.git

cd ..
mkdir graphql-by-pop && cd graphql-by-pop

git clone git@github.com:GraphQLByPoP/graphql-clients-for-wp.git #https://github.com/GraphQLByPoP/graphql-clients-for-wp.git
git clone git@github.com:GraphQLByPoP/graphql-endpoint-for-wp.git #https://github.com/GraphQLByPoP/graphql-endpoint-for-wp.git
git clone git@github.com:GraphQLByPoP/graphql-parser.git #https://github.com/GraphQLByPoP/graphql-parser.git
git clone git@github.com:GraphQLByPoP/graphql-query.git #https://github.com/GraphQLByPoP/graphql-query.git
git clone git@github.com:GraphQLByPoP/graphql-request.git #https://github.com/GraphQLByPoP/graphql-request.git
git clone git@github.com:GraphQLByPoP/graphql.git #https://github.com/GraphQLByPoP/graphql.git

cd ..
mkdir pop-schema && cd pop-schema

git clone git@github.com:PoPSchema/basic-directives.git #https://github.com/PoPSchema/basic-directives.git
git clone git@github.com:PoPSchema/commentmeta-wp.git #https://github.com/PoPSchema/commentmeta-wp.git
git clone git@github.com:PoPSchema/commentmeta.git #https://github.com/PoPSchema/commentmeta.git
git clone git@github.com:PoPSchema/comments-wp.git #https://github.com/PoPSchema/comments-wp.git
git clone git@github.com:PoPSchema/comments.git #https://github.com/PoPSchema/comments.git
git clone git@github.com:PoPSchema/custompost-mutations-wp.git #https://github.com/PoPSchema/custompost-mutations-wp.git
git clone git@github.com:PoPSchema/custompost-mutations.git #https://github.com/PoPSchema/custompost-mutations.git
git clone git@github.com:PoPSchema/custompostmedia-wp.git #https://github.com/PoPSchema/custompostmedia-wp.git
git clone git@github.com:PoPSchema/custompostmedia.git #https://github.com/PoPSchema/custompostmedia.git
git clone git@github.com:PoPSchema/custompostmeta-wp.git #https://github.com/PoPSchema/custompostmeta-wp.git
git clone git@github.com:PoPSchema/custompostmeta.git #https://github.com/PoPSchema/custompostmeta.git
git clone git@github.com:PoPSchema/customposts-wp.git #https://github.com/PoPSchema/customposts-wp.git
git clone git@github.com:PoPSchema/customposts.git #https://github.com/PoPSchema/customposts.git
git clone git@github.com:PoPSchema/generic-customposts.git #https://github.com/PoPSchema/generic-customposts.git
git clone git@github.com:PoPSchema/media-wp.git #https://github.com/PoPSchema/media-wp.git
git clone git@github.com:PoPSchema/media.git #https://github.com/PoPSchema/media.git
git clone git@github.com:PoPSchema/meta.git #https://github.com/PoPSchema/meta.git
git clone git@github.com:PoPSchema/metaquery-wp.git #https://github.com/PoPSchema/metaquery-wp.git
git clone git@github.com:PoPSchema/metaquery.git #https://github.com/PoPSchema/metaquery.git
git clone git@github.com:PoPSchema/migrate-commentmeta-wp.git #https://github.com/PoPSchema/migrate-commentmeta-wp.git
git clone git@github.com:PoPSchema/migrate-commentmeta.git #https://github.com/PoPSchema/migrate-commentmeta.git
git clone git@github.com:PoPSchema/migrate-comments-wp.git #https://github.com/PoPSchema/migrate-comments-wp.git
git clone git@github.com:PoPSchema/migrate-comments.git #https://github.com/PoPSchema/migrate-comments.git
git clone git@github.com:PoPSchema/migrate-custompostmedia-wp.git #https://github.com/PoPSchema/migrate-custompostmedia-wp.git
git clone git@github.com:PoPSchema/migrate-custompostmedia.git #https://github.com/PoPSchema/migrate-custompostmedia.git
git clone git@github.com:PoPSchema/migrate-custompostmeta-wp.git #https://github.com/PoPSchema/migrate-custompostmeta-wp.git
git clone git@github.com:PoPSchema/migrate-custompostmeta.git #https://github.com/PoPSchema/migrate-custompostmeta.git
git clone git@github.com:PoPSchema/migrate-customposts-wp.git #https://github.com/PoPSchema/migrate-customposts-wp.git
git clone git@github.com:PoPSchema/migrate-customposts.git #https://github.com/PoPSchema/migrate-customposts.git
git clone git@github.com:PoPSchema/migrate-media-wp.git #https://github.com/PoPSchema/migrate-media-wp.git
git clone git@github.com:PoPSchema/migrate-media.git #https://github.com/PoPSchema/migrate-media.git
git clone git@github.com:PoPSchema/migrate-meta.git #https://github.com/PoPSchema/migrate-meta.git
git clone git@github.com:PoPSchema/migrate-metaquery-wp.git #https://github.com/PoPSchema/migrate-metaquery-wp.git
git clone git@github.com:PoPSchema/migrate-metaquery.git #https://github.com/PoPSchema/migrate-metaquery.git
git clone git@github.com:PoPSchema/migrate-pages-wp.git #https://github.com/PoPSchema/migrate-pages-wp.git
git clone git@github.com:PoPSchema/migrate-pages.git #https://github.com/PoPSchema/migrate-pages.git
git clone git@github.com:PoPSchema/migrate-post-tags-wp.git #https://github.com/PoPSchema/migrate-post-tags-wp.git
git clone git@github.com:PoPSchema/migrate-post-tags.git #https://github.com/PoPSchema/migrate-post-tags.git
git clone git@github.com:PoPSchema/migrate-posts-wp.git #https://github.com/PoPSchema/migrate-posts-wp.git
git clone git@github.com:PoPSchema/migrate-posts.git #https://github.com/PoPSchema/migrate-posts.git
git clone git@github.com:PoPSchema/migrate-queriedobject-wp.git #https://github.com/PoPSchema/migrate-queriedobject-wp.git
git clone git@github.com:PoPSchema/migrate-queriedobject.git #https://github.com/PoPSchema/migrate-queriedobject.git
git clone git@github.com:PoPSchema/migrate-tags-wp.git #https://github.com/PoPSchema/migrate-tags-wp.git
git clone git@github.com:PoPSchema/migrate-tags.git #https://github.com/PoPSchema/migrate-tags.git
git clone git@github.com:PoPSchema/migrate-taxonomies-wp.git #https://github.com/PoPSchema/migrate-taxonomies-wp.git
git clone git@github.com:PoPSchema/migrate-taxonomies.git #https://github.com/PoPSchema/migrate-taxonomies.git
git clone git@github.com:PoPSchema/migrate-taxonomymeta-wp.git #https://github.com/PoPSchema/migrate-taxonomymeta-wp.git
git clone git@github.com:PoPSchema/migrate-taxonomymeta.git #https://github.com/PoPSchema/migrate-taxonomymeta.git
git clone git@github.com:PoPSchema/migrate-taxonomyquery-wp.git #https://github.com/PoPSchema/migrate-taxonomyquery-wp.git
git clone git@github.com:PoPSchema/migrate-taxonomyquery.git #https://github.com/PoPSchema/migrate-taxonomyquery.git
git clone git@github.com:PoPSchema/migrate-usermeta-wp.git #https://github.com/PoPSchema/migrate-usermeta-wp.git
git clone git@github.com:PoPSchema/migrate-usermeta.git #https://github.com/PoPSchema/migrate-usermeta.git
git clone git@github.com:PoPSchema/migrate-users-wp.git #https://github.com/PoPSchema/migrate-users-wp.git
git clone git@github.com:PoPSchema/migrate-users.git #https://github.com/PoPSchema/migrate-users.git
git clone git@github.com:PoPSchema/pages-wp.git #https://github.com/PoPSchema/pages-wp.git
git clone git@github.com:PoPSchema/pages.git #https://github.com/PoPSchema/pages.git
git clone git@github.com:PoPSchema/custompostmedia-mutations.git #https://github.com/PoPSchema/custompostmedia-mutations.git
git clone git@github.com:PoPSchema/post-mutations.git #https://github.com/PoPSchema/post-mutations.git
git clone git@github.com:PoPSchema/post-tags-wp.git #https://github.com/PoPSchema/post-tags-wp.git
git clone git@github.com:PoPSchema/post-tags.git #https://github.com/PoPSchema/post-tags.git
git clone git@github.com:PoPSchema/posts-wp.git #https://github.com/PoPSchema/posts-wp.git
git clone git@github.com:PoPSchema/posts.git #https://github.com/PoPSchema/posts.git
git clone git@github.com:PoPSchema/queriedobject-wp.git #https://github.com/PoPSchema/queriedobject-wp.git
git clone git@github.com:PoPSchema/queriedobject.git #https://github.com/PoPSchema/queriedobject.git
git clone git@github.com:PoPSchema/schema-commons.git #https://github.com/PoPSchema/schema-commons.git
git clone git@github.com:PoPSchema/tags-wp.git #https://github.com/PoPSchema/tags-wp.git
git clone git@github.com:PoPSchema/tags.git #https://github.com/PoPSchema/tags.git
git clone git@github.com:PoPSchema/taxonomies-wp.git #https://github.com/PoPSchema/taxonomies-wp.git
git clone git@github.com:PoPSchema/taxonomies.git #https://github.com/PoPSchema/taxonomies.git
git clone git@github.com:PoPSchema/taxonomymeta-wp.git #https://github.com/PoPSchema/taxonomymeta-wp.git
git clone git@github.com:PoPSchema/taxonomymeta.git #https://github.com/PoPSchema/taxonomymeta.git
git clone git@github.com:PoPSchema/taxonomyquery-wp.git #https://github.com/PoPSchema/taxonomyquery-wp.git
git clone git@github.com:PoPSchema/taxonomyquery.git #https://github.com/PoPSchema/taxonomyquery.git
git clone git@github.com:PoPSchema/user-roles-access-control.git #https://github.com/PoPSchema/user-roles-access-control.git
git clone git@github.com:PoPSchema/user-roles-wp.git #https://github.com/PoPSchema/user-roles-wp.git
git clone git@github.com:PoPSchema/user-roles.git #https://github.com/PoPSchema/user-roles.git
git clone git@github.com:PoPSchema/user-state-access-control.git #https://github.com/PoPSchema/user-state-access-control.git
git clone git@github.com:PoPSchema/user-state-wp.git #https://github.com/PoPSchema/user-state-wp.git
git clone git@github.com:PoPSchema/user-state.git #https://github.com/PoPSchema/user-state.git
git clone git@github.com:PoPSchema/usermeta-wp.git #https://github.com/PoPSchema/usermeta-wp.git
git clone git@github.com:PoPSchema/usermeta.git #https://github.com/PoPSchema/usermeta.git
git clone git@github.com:PoPSchema/users-wp.git #https://github.com/PoPSchema/users-wp.git
git clone git@github.com:PoPSchema/users.git #https://github.com/PoPSchema/users.git

cd ..
cd ..
