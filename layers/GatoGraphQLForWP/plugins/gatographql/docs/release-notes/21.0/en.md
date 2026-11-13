# Release Notes: 21.0

## Added

- Added global field `_urlToEntity`, which tells what a URL of the site points to (a custom post, a media item, a taxonomy term, a user, the homepage or a custom post type archive), and returns its type and ID, plus the custom post type or the taxonomy, or `null` when the URL points to none ([#3469](https://github.com/GatoGraphQL/GatoGraphQL/pull/3469))

## Improvements

- Send the `GatoPlugins` user agent on the license and update requests to the Gato Store ([#3463](https://github.com/GatoGraphQL/GatoGraphQL/pull/3463))
- Fix the descriptions of the sort input and the input to create a custom post ([#3464](https://github.com/GatoGraphQL/GatoGraphQL/pull/3464))
- The descriptions of the category and tag `name` fields say that WordPress stores the name HTML-encoded, so `&` comes back as `&amp;` ([#3466](https://github.com/GatoGraphQL/GatoGraphQL/pull/3466))
- The menu mutations take menu items that link to the archive of a post type (`itemType: post_type_archive`, with the post type as `objectType`) ([#3467](https://github.com/GatoGraphQL/GatoGraphQL/pull/3467))
