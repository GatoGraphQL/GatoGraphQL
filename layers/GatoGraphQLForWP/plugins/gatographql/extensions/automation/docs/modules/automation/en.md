# Automation

Use GraphQL to automate tasks in your app: Execute queries when some event happens, chain queries, and schedule and trigger queries via WP-Cron. (The **Internal GraphQL Server** extension is required).

This extension is composed of:

- A user interface to create automations, provided by **Automation Configurator**
- Hooks provided by **Query Resolution Action**
- Integration with **WP-Cron**

## Automation Configurator

Automatically execute a GraphQL Persisted Query when some event happens on the site.

The **Automation Configurator** module provides an "automator" user interface, to create automations via the WordPress editor.

The automation trigger is any WordPress action hook, and the action is the execution of a GraphQL persisted query.

A Custom Post Type "Automation Rules" is provided to create automations. When creating a new entry, we must provide the configuration for:

- Automation trigger(s)
- Automation action

<div class="img-width-1024" markdown=1>

![Automation Rule editor](../../images/automation-rule-editor.png "Automation Rule editor")

</div>

### Automation action

The automation action indicates what GraphQL persisted query will be executed.

Configure this item with the following elements:

**Persisted Query**: Select which GraphQL persisted query to execute (among all the ones with status `publish` or `private`)

**Static GraphQL Variables**: Provide a JSON string with values for the GraphQL variables in the persisted query. These are static values.

For instance:

```json
{
  "emailSubject": "New post on the site"
}
```

These values are overridden by the "dynamic" GraphQL variables (see **Automation trigger(s)** below).

**Operation name** (optional): If the persisted query contains more than one operation, you can indicate which one to execute (by default, it is the last one).

**Execute as user** (optional): Execute the GraphQL persisted query being logged-in as a specific user, providing the user slug.

<div class="img-width-392" markdown=1>

![Automation Rule - Persisted Query Execution](../../images/automation-mapping-persisted-query-execution.png "Automation Rule - Persisted Query Execution")

</div>

### Automation trigger(s)

An automation trigger indicates what WordPress action hook will trigger the execution of the Persisted Query. We can provide more than one (eg: to react to editing a post or page only, we can provide hooks `edit_post_post` and `edit_post_page`).

Configure this item with the following elements:

**Hook name**: The WordPress action hook name.

**Dynamic GraphQL Variables**: Provide a JSON string mapping GraphQL variables to the arguments provided to the hook function. These dynamic values will then be provided to the query on runtime.

The JSON dictionary must contain the GraphQL variable name as key, and the position of the argument in the action hook as value.

For instance, hook `draft_post` (from the [post status transitions](https://codex.wordpress.org/Post_Status_Transitions)) provides the `$post_id` as the first argument. Then, the following JSON indicates that GraphQL variable `$postID` will receive the value of `$post_id` passed to the hook:

```json
{
  "postID": 1
}
```

(In this example, `1` means "value of the 1st argument by `draft_post`".)

If the same key is used for the "dynamic" and "static" GraphQL variables (see **Automation action** above), then the dynamic values take priority.

<div class="img-width-412" markdown=1>

![Automation Rule - Action hook](../../images/automation-mapping-action-hook.png "Automation Rule - Action hook")

</div>

### WordPress hook mapping

There are WordPress hooks which cannot be directly used in the Automation Configurator, because they provide a PHP object via the hook, which can't be input as a GraphQL variable.

Several of these hooks have been mapped by Gato GraphQL, by triggering a new hook prepended with `gatographql:` and the same hook name, and passing the corresponding object ID as a variable, which can be input as a GraphQL variable.

For instance, WordPress hook `draft_to_publish` passes the `$post` as variable (of type `WP_Post`). Gato GraphQL maps this hook as `gatographql:draft_to_publish`, and passes the `$postId` (of type `int`) as variable.

The following table lists down the mapped WordPress hooks:

| WordPress hook | Mapped hook by Gato GraphQL |
| --- | --- |
| [`{$old_status}_to_{$new_status}`](https://developer.wordpress.org/reference/hooks/old_status_to_new_status/) (passing `WP_Post $post`) | `gatographql:{$old_status}_to_{$new_status}` (passing `int $postId, string $postType`) |

In addition, Gato GraphQL re-triggers several WordPress hooks with some extra information on the hook name, to make it easier to capture and automate specific events.

For instance, hooks that create, update and delete meta values are triggered containing the meta key as part of the hook name. Then, an automation can be triggered when a featured image is assigned to a post, on hook `gatographql:added_post_meta:_thumbnail_id`.

These are the additional Gato GraphQL hooks:

| Source WordPress hook | Triggered Gato GraphQL hook |
| --- | --- |
| [`{$old_status}_to_{$new_status}`](https://developer.wordpress.org/reference/hooks/old_status_to_new_status/)<br/><em>(Passing `WP_Post $post`)</em> | `gatographql:any_to_{$new_status}`<br/>`gatographql:{$old_status}_to_any`<br/>`gatographql:{$old_status}_to_{$new_status}:{$post_type}`<br/>`gatographql:any_to_{$new_status}:{$post_type}`<br/>`gatographql:{$old_status}_to_any:{$post_type}`<br/><em>(All passing `int $postId, string $postType`)</em> |
| [`set_object_terms`](https://developer.wordpress.org/reference/hooks/set_object_terms/) | `gatographql:set_object_terms:{$taxonomy}`<br/>`gatographql:updated_object_terms:{$taxonomy}` <em>(When there is a delta between old and new terms)</em> |
| [`added_{$meta_type}_meta`](https://developer.wordpress.org/reference/hooks/added_meta_type_meta/) | `gatographql:added_{$meta_type}_meta:{$meta_key}` |
| [`updated_{$meta_type}_meta`](https://developer.wordpress.org/reference/hooks/updated_meta_type_meta/) | `gatographql:updated_{$meta_type}_meta:{$meta_key}` |
| [`deleted_{$meta_type}_meta`](https://developer.wordpress.org/reference/hooks/deleted_meta_type_meta/) | `gatographql:deleted_{$meta_type}_meta:{$meta_key}` |

### Debugging issues

If the automation hasn't been executed, there could be an error with the configuration of the automation, or execution of the persisted query.

All configuration problems (such as a malformed JSON string for the GraphQL variables, or pointing to a persisted query that has been deleted) and execution errors (such as thrown exceptions, or `errors` entries in the GraphQL query) are sent to PHP function's `error_log`, so these are printed in the [WordPress error log](https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/).

These error logs are prepended with string `[Gato GraphQL]`.

In addition, the complete GraphQL response for the automation (whether successful or not) is logged under file `wp-content/gatographql/logs/info.log`.

## Query Resolution Action

When the GraphQL server resolves a query, it triggers the following action hooks with the GraphQL response:

1. `gatographql__executed_query:{$operationName}` (only if the GraphQL operation to execute was provided)
2. `gatographql__executed_query`

The action hooks that are triggered are:

```php
// Triggered only if the GraphQL operation to execute was provided
do_action(
  "gatographql__executed_query:{$operationName}",
  $response,
  $isInternalExecution,
  $query,
  $variables,
);

// Triggered always
do_action(
  'gatographql__executed_query',
  $response,
  $isInternalExecution,
  $operationName,
  $query,
  $variables,
);
```

The parameters passed are:

- `$response`: An object of class `PoP\Root\HttpFoundation\Response`, containing the GraphQL response (including content and headers)
- `$isInternalExecution`: `true` if the query was executed via the Internal GraphQL Server (eg: via class `GatoGraphQL\InternalGraphQLServer\GraphQLServer`), or `false` otherwise (eg: via the single endpoint)
- `$operationName`: Executed GraphQL operation (for the second action hook only; on the first one, it is implicit on the hook name)
- `$query`: Executed GraphQL query
- `$variables`: Provided GraphQL variables

## WP-Cron

The following action hooks are provided, to be invoked from within [WP-Cron](https://developer.wordpress.org/plugins/cron/):

### `gatographql__execute_query`

This hook receives the following parameters (in this same order):

| # | Mandatory? | Param | Description |
| --- | --- | --- | --- |
| 1 | ✅ | `$query` | The GraphQL query to execute |
| 2 | ❌ | `$variables` | GraphQL variables |
| 3 | ❌ | `$operationName` | The operation name to execute |
| 4 | ❌ | `$executeAsUser` | The user to log-in to execute the query |
| 5 | ❌ | `$schemaConfigurationIDOrSlug` | The schema configuration ID (as an int) or slug (as a string) to apply when executing the query. Passing `null` will use the default value, and passing `-1` means "use no schema configuration" |

The `$executeAsUser` parameter is needed if the query requires the user to be logged-in, such as when executing a mutation:

- If provided, the user with given ID (as an int) or username (as a string) will be logged-in right before executing the GraphQL query, and logged-out immediately afterwards.
- If not provided, no user will be logged-in when executing the query.

### `gatographql__execute_persisted_query`

This hook receives the following parameters (in this same order):

| # | Mandatory? | Param | Description |
| --- | --- | --- | --- |
| 1 | ✅ | `$persistedQueryIDOrSlug` | The Persisted Query ID (as an int) or slug (as a string) |
| 2 | ❌ | `$variables` | GraphQL variables |
| 3 | ❌ | `$operationName` | The operation name to execute |
| 4 | ❌ | `$executeAsUser` | The user to log-in to execute the query |

Notice that the schema configuration to apply is already selected within the persisted query.

## Examples

### Automation Configurator

These are some examples of how we can use it:

- Create a featured image for new posts using AI
- Add a mandatory block to the post when published
- Replace `http` with `https` in all image sources and links when a post is updated
- Send an email to the admin when there's a new post
- Send an email to the user whose comment has a new response
- [Multisite] Translate a new post to different languages, and add the translated posts to each site
- Execute an action on an external service (eg: automatically share new posts on Facebook)

For instance, when creating a new post with `draft` status, the predefined automation rule **Add comments block to new post** checks if the `core/comments` block is present and, if not, it adds it at the bottom of the post:

<div class="img-width-640" markdown=1>

![Automatically inserting the comments block to new 'draft' posts](../../images/automation-rule-insert-mandatory-comments-block.gif "Automatically inserting the comments block to new 'draft' posts")

</div>

### Query Resolution Action

Thanks to the **Internal GraphQL Server**, we can react to the resolution of a GraphQL query (whether executed against the internal GraphQL Server, single endpoint, custom endpoint or persisted query), and execute another GraphQL query against the internal GraphQL Server.

An example workflow is:

- Hook into the execution of a GraphQL query, for instance by its operation name (such as `CreatePost`)
- Send a notification to the admin, by executing mutation `_sendEmail` via `GatoGraphQL\InternalGraphQLServer\GraphQLServer::executeQuery`

This PHP code is chaining 2 GraphQL query executions:

```php
GraphQLServer::executeQuery(
  <<<GRAPHQL
    mutation CreatePost(
      \$postTitle: String!,
      \$postContent: String!
    ) {
      createPost(input: {
        title: \$postTitle
        contentAs: { html: \$postContent }
      }) {
        status
        errors {
          __typename
          ...on ErrorPayload {
            message
          }
        }
        postID
      }
    }
  GRAPHQL,
  [
    'postTitle' => 'New post',
    'postContent' => 'Some content',
  ],
  'CreatePost'
);

add_action(
  "gatographql__executed_query:CreatePost",
  function (Response $response) {
    /** @var string */
    $responseContent = $response->getContent();
    /** @var array<string,mixed> */
    $responseJSON = json_decode($responseContent, true);
    $postID = $responseJSON['data']['createPost']['postID'] ?? null;
    if ($postID === null) {
      // Do nothing
      return;
    }

    $post = get_post($postID);

    // Execute the chained query!
    GraphQLServer::executeQuery(
      <<<GRAPHQL
        mutation SendEmail(
          \$emailSubject: String!
          \$emailMessage: String!
        ) {
          _sendEmail(
            input: {
              to: "admin@site.com"
              subject: \$emailSubject
              messageAs: {
                html: \$emailMessage
              }
            }
          ) {
            status
          }
        }
      GRAPHQL,
      [
        'emailSubject' => sprintf(__("New post: %s"), $post->post_title),
        'emailMessage' => $post->post_content,
      ]
    );
  }
);
```

### WP-Cron

The following WP-Cron event executes hook `gatographql__execute_persisted_query` to send a daily email indicating the number of new comments added to the site:

- In the last 24 hs
- In the last 1 year
- Since the beginning of the month
- Since the beginning of the year

We create a Persisted Query with slug `"daily-stats-by-email-number-of-comments"` and content:

```graphql
query CountComments {
  DATE_ISO8601: _env(name: DATE_ISO8601) @remove

  timeToday: _time
  dateToday: _date(format: $__DATE_ISO8601, timestamp: $__timeToday)
  
  timeYesterday: _intSubtract(subtract: 86400, from: $__timeToday)
  dateYesterday: _date(format: $__DATE_ISO8601, timestamp: $__timeYesterday)
  
  time1YearAgo: _intSubtract(subtract: 31536000, from: $__timeToday)
  date1YearAgo: _date(format: $__DATE_ISO8601, timestamp: $__time1YearAgo)

  timeBegOfThisMonth: _makeTime(hour: 0, minute: 0, second: 0, day: 1)
  dateBegOfThisMonth: _date(format: $__DATE_ISO8601, timestamp: $__timeBegOfThisMonth)

  timeBegOfThisYear: _makeTime(hour: 0, minute: 0, second: 0, month: 1, day: 1)
  dateBegOfThisYear: _date(format: $__DATE_ISO8601, timestamp: $__timeBegOfThisYear)
  
  commentsAddedInLast24Hs: commentCount(filter: { dateQuery: { after: $__dateYesterday } } )
    @export(as: "commentsAddedInLast24Hs")
  commentsAddedInLast1Year: commentCount(filter: { dateQuery: { after: $__date1YearAgo } } )
    @export(as: "commentsAddedInLast1Year")
  commentsAddedSinceBegOfThisMonth: commentCount(filter: { dateQuery: { after: $__dateBegOfThisMonth } } )
    @export(as: "commentsAddedSinceBegOfThisMonth")
  commentsAddedSinceBegOfThisYear: commentCount(filter: { dateQuery: { after: $__dateBegOfThisYear } } )
    @export(as: "commentsAddedSinceBegOfThisYear")
}

query CreateEmailMessage @depends(on: "CountComments") {
  emailMessageTemplate: _strConvertMarkdownToHTML(
    text: """

This is the number of comments added to the site:

| Period | # Comments added |
| --- | --- |
| **In the last 24 hs**: | {$commentsAddedInLast24Hs} |
| **In the last 365 days**: | {$commentsAddedInLast1Year} |
| **Since beginning of this month**: | {$commentsAddedSinceBegOfThisMonth} |
| **Since beginning of this year**: | {$commentsAddedSinceBegOfThisYear} |

    """
  )
  emailMessage: _strReplaceMultiple(
    search: [
      "{$commentsAddedInLast24Hs}",
      "{$commentsAddedInLast1Year}",
      "{$commentsAddedSinceBegOfThisMonth}",
      "{$commentsAddedSinceBegOfThisYear}"
    ],
    replaceWith: [
      $commentsAddedInLast24Hs,
      $commentsAddedInLast1Year,
      $commentsAddedSinceBegOfThisMonth,
      $commentsAddedSinceBegOfThisYear
    ],
    in: $__emailMessageTemplate
  )
    @export(as: "emailMessage")
}

mutation SendDailyStatsByEmailNumberOfComments(
  $to: [String!]!
)
  @depends(on: "CreateEmailMessage")
{
  _sendEmail(
    input: {
      to: $to
      subject: "Daily stats: Number of new comments"
      messageAs: {
        html: $emailMessage
      }
    }
  ) {
    status
  }
}
```

Then, we schedule the WP-Cron event, either via PHP:

```php
\wp_schedule_event(
  time(),
  'daily',
  'gatographql__execute_persisted_query',
  [
    'daily-stats-by-email-number-of-comments',
    [
      'to' => ['admin@mysite.com']
    ],
    'SendDailyStatsByEmailNumberOfComments',
    1 // This is the admin user's ID
  ]
);
```

Or via the [WP-Crontrol](https://wordpress.org/plugins/wp-crontrol/) plugin:

- Hook name: `gatographql__execute_persisted_query`
- Arguments: `["daily-stats-by-email-number-of-comments",{"to":["admin@mysite.com"]},"SendDailyStatsByEmailNumberOfComments",1]`
- Recurrence: Once Daily

<div class="img-width-1024" markdown=1>

![New entry in WP-Crontrol](../../images/wp-crontrol-entry.png "New entry in WP-Crontrol")

</div>
<!-- 
## Bundles including extension

- [“All in One Toolbox for WordPress” Bundle](../../../../../bundle-extensions/all-in-one-toolbox-for-wordpress/docs/modules/all-in-one-toolbox-for-wordpress/en.md)
- [“Automated Content Translation & Sync for WordPress Multisite” Bundle](../../../../../bundle-extensions/automated-content-translation-and-sync-for-wordpress-multisite/docs/modules/automated-content-translation-and-sync-for-wordpress-multisite/en.md)
- [“Tailored WordPress Automator” Bundle](../../../../../bundle-extensions/tailored-wordpress-automator/docs/modules/tailored-wordpress-automator/en.md) -->
