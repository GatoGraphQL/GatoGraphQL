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
     * The difference is that partial errors do not set the field in `null`.
     * As such, their errors state is not bubbled up,
     * and they won't set any wrapping meta directive's value as `null`.
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
     *       @underEachJSONObjectProperty(passKeyOnwardsAs: "toLang")
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
     * using ->addPartialError will allow the translation of all other languages.
     */
    public final const ERROR = 'error';
    public final const DEPRECATION = 'deprecation';
    public final const LOG = 'log';
}
