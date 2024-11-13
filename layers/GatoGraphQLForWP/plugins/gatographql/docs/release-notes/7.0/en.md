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

## [Extensions] Improvements

- [Persisted Queries] Created a new "Persisted Query Endpoints" module (from "Persisted Queries"), to disable external execution of persisted queries
