# Automation

Use GraphQL to automate tasks in your app: Execute queries when some event happens, chain queries, and schedule and trigger queries via WP-Cron. (The **Internal GraphQL Server** extension is required).

This extension is composed of:

- Hooks provided by **Query Resolution Action**
- Integration with **WP-Cron**

## Query Resolution Action

When the GraphQL server resolves a query, it triggers the following action hooks with the GraphQL response:

1. `gatographql__executed_query_{$operationName}` (only if the GraphQL operation to execute was provided)
2. `gatographql__executed_query`

The action hooks that are triggered are:

```php
// Triggered only if the GraphQL operation to execute was provided
do_action(
  "gatographql__executed_query_{$operationName}",
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

1. `gatographql__execute_query`
2. `gatographql__execute_persisted_query`

These hooks receive the following parameters (in this same order):

| # | Mandatory? | Param | Description |
| --- | --- | --- | --- |
| 1 | ✅ | `$query` for `gatographql__execute_query`, or<br/><br/>`$persistedQueryIDOrSlug` for `gatographql__execute_persisted_query` | The GraphQL query to execute with `gatographql__execute_query`, or<br/><br/>The Persisted Query ID (as an int) or slug (as a string) for `gatographql__execute_persisted_query` |
| 2 | ❌ | `$variables` | GraphQL variables |
| 3 | ❌ | `$operationName` | The operation name to execute |
| 4 | ❌ | `$executeAsUser` | The user to log-in to execute the query |

The `$executeAsUser` parameter is needed if the query requires the user to be logged-in, such as when executing a mutation:

- If provided, the user with given ID (as an int) or username (as a string) will be logged-in right before executing the GraphQL query, and logged-out immediately afterwards.
- If not provided, no user will be logged-in when executing the query.

## Examples

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
  "gatographql__executed_query_CreatePost",
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
  
  timeYesterday: _intSubstract(substract: 86400, from: $__timeToday)
  dateYesterday: _date(format: $__DATE_ISO8601, timestamp: $__timeYesterday)
  
  time1YearAgo: _intSubstract(substract: 31536000, from: $__timeToday)
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
| **Since beggining of this month**: | {$commentsAddedSinceBegOfThisMonth} |
| **Since beggining of this year**: | {$commentsAddedSinceBegOfThisYear} |

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

## Bundles including extension

- [“All in One Toolbox for WordPress” Bundle](../../../../../bundle-extensions/all-extensions/docs/modules/all-extensions/en.md)
- [“Tailored WordPress Automator” Bundle](../../../../../bundle-extensions/tailored-wordpress-automator/docs/modules/tailored-wordpress-automator/en.md)

## Tutorial lessons referencing extension

- [Sending a notification when there is a new post](../../../../../docs/tutorial/sending-a-notification-when-there-is-a-new-post/en.md)
- [Sending a daily summary of activity](../../../../../docs/tutorial/sending-a-daily-summary-of-activity/en.md)
