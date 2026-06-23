# Release Notes: 19.0

## Added

- Added the ability to register custom WordPress REST API controllers/endpoints via the service container ([#3330](https://github.com/GatoGraphQL/GatoGraphQL/pull/3330))
- Added the ability to execute queries as a DRY RUN, marked with a `[DRY-RUN]` prefix in the logs ([#3330](https://github.com/GatoGraphQL/GatoGraphQL/pull/3330))
- Added the "External Tools" settings category, for extensions to surface tooling/informational sections ([#3330](https://github.com/GatoGraphQL/GatoGraphQL/pull/3330))

## Improvements

- Updated docs for the Schema Functions extension
- The plugin is now translated to Spanish (`es_ES`) ([#3314](https://github.com/GatoGraphQL/GatoGraphQL/pull/3314))
- The plugin is now translated to French (`fr_FR`) ([#3315](https://github.com/GatoGraphQL/GatoGraphQL/pull/3315))
- The plugin is now translated to Italian (`it_IT`) ([#3316](https://github.com/GatoGraphQL/GatoGraphQL/pull/3316))
- The plugin is now translated to German (`de_DE`) ([#8694a2b](https://github.com/GatoGraphQL/GatoGraphQL/commit/8694a2b))
- The plugin is now translated to Portuguese (`pt_BR`) ([#3317](https://github.com/GatoGraphQL/GatoGraphQL/pull/3317))
- The plugin is now translated to Polish (`pl_PL`) ([#3318](https://github.com/GatoGraphQL/GatoGraphQL/pull/3318))
- The plugin is now translated to Dutch (`nl_NL`) ([#3322](https://github.com/GatoGraphQL/GatoGraphQL/pull/3322))
- The plugin is now translated to Japanese (`ja`) ([#3332](https://github.com/GatoGraphQL/GatoGraphQL/pull/3332))
- The plugin is now translated to Chinese (Simplified) (`zh_CN`) ([#3334](https://github.com/GatoGraphQL/GatoGraphQL/pull/3334))
- The plugin is now translated to Korean (`ko_KR`) ([#3335](https://github.com/GatoGraphQL/GatoGraphQL/pull/3335))
- The plugin is now translated to Vietnamese (`vi`) ([#3336](https://github.com/GatoGraphQL/GatoGraphQL/pull/3336))
- The plugin is now translated to Thai (`th`) ([#3337](https://github.com/GatoGraphQL/GatoGraphQL/pull/3337))
- The plugin is now translated to Bahasa Indonesia (`id_ID`) ([#3338](https://github.com/GatoGraphQL/GatoGraphQL/pull/3338))
- The plugin is now translated to Russian (`ru_RU`) ([#3339](https://github.com/GatoGraphQL/GatoGraphQL/pull/3339))
- The plugin is now translated to Swedish (`sv_SE`) ([#3341](https://github.com/GatoGraphQL/GatoGraphQL/pull/3341))
- The plugin is now translated to Greek (`el`) ([#3345](https://github.com/GatoGraphQL/GatoGraphQL/pull/3345))
- Completed translations for the existing locales and resynced the block-editor JS language packs with the catalog ([#3333](https://github.com/GatoGraphQL/GatoGraphQL/pull/3333))
- Do not show All Inclusive bundle in Extension docs ([#3321](https://github.com/GatoGraphQL/GatoGraphQL/pull/3321))
- Upgraded GraphiQL to version 5.2.3 ([#3323](https://github.com/GatoGraphQL/GatoGraphQL/pull/3323))
- Added the Explorer plugin to the GraphiQL client, to build queries by point-and-click ([#3327](https://github.com/GatoGraphQL/GatoGraphQL/pull/3327))

## Fixed

- Fixed a module being initialized more than once when booting an attached app (such as the Internal GraphQL Server), which could re-register its services and override others ([#3331](https://github.com/GatoGraphQL/GatoGraphQL/pull/3331))
- Fix lost styles in Extensions page ([#3319](https://github.com/GatoGraphQL/GatoGraphQL/pull/3319))
- Fix items not shown as active in Extensions page ([#3320](https://github.com/GatoGraphQL/GatoGraphQL/pull/3320))
- Replace non-standard spaces in block attributes when doing useHTML5Parser ([#3313](https://github.com/GatoGraphQL/GatoGraphQL/pull/3313))
- Fix the GraphiQL editor's Find box (Cmd/Ctrl+F) staying visible after being closed ([#3326](https://github.com/GatoGraphQL/GatoGraphQL/pull/3326))
- Register always first the capability to access the plugin (so it doesn't fail installing whenever the server does not have enough memory) ([#3343](https://github.com/GatoGraphQL/GatoGraphQL/pull/3343))
- Show the Marketplace Provider's own error message when activating or validating a license fails with an error HTTP status code, such as when the license's activation limit has been reached, instead of a generic HTTP error ([#3344](https://github.com/GatoGraphQL/GatoGraphQL/pull/3344))
