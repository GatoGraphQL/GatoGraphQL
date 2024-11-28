# Release Notes: 7.0

## Breaking changes

- Bump minimum required PHP version to 7.4 ([#2905](https://github.com/GatoGraphQL/GatoGraphQL/pull/2905))
- Allow to include Gato GraphQL as the engine to power another standalone plugin ([#2897](https://github.com/GatoGraphQL/GatoGraphQL/pull/2897))
- Renamed env var `CACHE_DIR` to `CONTAINER_CACHE_DIR`([#2923](https://github.com/GatoGraphQL/GatoGraphQL/pull/2923))

## Improvements

- Added convenience class for standalone plugins ([#2899](https://github.com/GatoGraphQL/GatoGraphQL/pull/2899))
- Allow to fetch posts with `auto-draft` status ([#2911](https://github.com/GatoGraphQL/GatoGraphQL/pull/2911))
- Allow disabling the private endpoint ([#2913](https://github.com/GatoGraphQL/GatoGraphQL/pull/2913))
- Added field `useGutenbergEditorWithCustomPostType` ([#2960](https://github.com/GatoGraphQL/GatoGraphQL/pull/2960))

## Fixed

- Fetching raw attribute sources with multiple nodes in blocks ([#2909](https://github.com/GatoGraphQL/GatoGraphQL/pull/2909))
- Renamed "Gato GraphQL Shop" to "Gato Shop" (`v7.0.1`)
- Changed label in Settings form button to "Save Changes (all from this tab)" (`v7.0.2`)
- Allow method handling hook `allowed_block_types_all` to receive `null` (`v7.0.3`) ([#2965](https://github.com/GatoGraphQL/GatoGraphQL/pull/2965))
- Do not print block editor scripts in the frontend (`v7.0.4`) ([#2966](https://github.com/GatoGraphQL/GatoGraphQL/pull/2966))
- Do not print block editor stylesheets in the frontend (`v7.0.4`) ([#2967](https://github.com/GatoGraphQL/GatoGraphQL/pull/2967))
- When an extension is activated, execute `flush_rewrite_rules` only at the end (or CPTs are not loaded properly) (`v7.0.5`) ([#2970](https://github.com/GatoGraphQL/GatoGraphQL/pull/2970))
- Don't hardcode Gato GraphQL's plugin base name in `plugin_action_links` hook to allow standalone plugins to use it (`v7.0.8`) ([#2971](https://github.com/GatoGraphQL/GatoGraphQL/pull/2971))

## [Extensions] Improvements

- [Persisted Queries] Created a new "Persisted Query Endpoints" module (from "Persisted Queries"), to disable external execution of persisted queries
