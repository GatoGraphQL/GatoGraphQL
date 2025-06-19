<?php

declare(strict_types=1);

namespace PoP\Root\Feedback;

class FeedbackCategories
{
    /**
     * This entry accounts for two types of errors:
     *
     * - Errors
     * - Partial errors
     *
     * The difference is that partial errors are not bubbled up,
     * then they won't necessarily set the whole field to `null`,
     * eg: using nested directives.
     *
     * For instance, in this query:
     *
     *   ```
     *   query {
     *     _echo(value: {
     *       es: ["hello", "world"],
     *       fr: ["how are you", "my friend?"],
     *       de: ["everything good?", "really?"]
     *     })
     *       @underEachJSONObjectProperty(
     *       passKeyOnwardsAs: "toLang",
     *       )
     *       @underEachArrayItem
     *         @strTranslate(
     *           from: "en",
     *           to: $toLang
     *         )
     *   }
     *   ```
     *
     * When the API request for a single language fails, if using ->addError
     * in `@strTranslate`, then the whole field will be set to `null`, whereas
     * using ->addPartialError will set the field to `null` only for the
     * language that failed.
     */
    public final const ERROR = 'error';
    public final const DEPRECATION = 'deprecation';
    public final const LOG = 'log';
}
