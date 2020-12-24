The `Cache-Control` header indicates for how long the response is to be cached through its `max-age` value.

The `max-age` value is individually defined for fields and directives. The response's `max-age` value is calculated as the lowest value from all the fields and directives in the requested query, or `no-store` if the field or directive has `max-age` with value `0`, or if there is an Access Control rule checking the user state for any field or directive (in which case, the response is specific to the user, so it cannot be cached)
