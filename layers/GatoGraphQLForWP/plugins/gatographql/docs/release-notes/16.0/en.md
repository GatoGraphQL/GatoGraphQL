# Release Notes: 16.0

## Breaking changes

- Renamed MenuItem fields: title to rawLabel, and rawTitle to titleAttribute ([#3251](https://github.com/GatoGraphQL/GatoGraphQL/pull/3251))
- Renamed MenuItem.classes to cssClasses ([#3252](https://github.com/GatoGraphQL/GatoGraphQL/pull/3252))

## Added

- Menu mutations Root.createMenu, Root.updateMenu and Menu.update ([#3253](https://github.com/GatoGraphQL/GatoGraphQL/pull/3253))
- MenuItem.itemType and MenuItem.objectType fields ([#3249](https://github.com/GatoGraphQL/GatoGraphQL/pull/3249))

## Improvements

- Include/exclude properties in MenuItem.itemDataEntries ([#3250](https://github.com/GatoGraphQL/GatoGraphQL/pull/3250))
- Trigger a hook action before initializing enum string possible values ([#3254](https://github.com/GatoGraphQL/GatoGraphQL/pull/3254))
- Support text-only classic-editor CPT for the plugin ([#3255](https://github.com/GatoGraphQL/GatoGraphQL/pull/3255))

## Fixed

- Don't initialize an input that includes itself (eg: MenuItemInput)([#3248](https://github.com/GatoGraphQL/GatoGraphQL/pull/3248))
- Revalidate commercial license ([#3258](https://github.com/GatoGraphQL/GatoGraphQL/pull/3258)) (`v16.0.1`)
