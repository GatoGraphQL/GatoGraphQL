# Release Notes: 15.4

## Breaking changes

- Renamed MenuItem fields: title => rawLabel, and rawTitle => titleAttribute ([#3251](https://github.com/GatoGraphQL/GatoGraphQL/pull/3251))
- Rename MenuItem.classes to cssClasses ([#3252](https://github.com/GatoGraphQL/GatoGraphQL/pull/3252))

## Added

- MenuItem.itemType and MenuItem.objectType fields ([#3249](https://github.com/GatoGraphQL/GatoGraphQL/pull/3249))

## Improvements

- Include/exclude properties in MenuItem.itemDataEntries ([#3250](https://github.com/GatoGraphQL/GatoGraphQL/pull/3250))

## Fixed

- Don't initialize an input that includes itself (eg: MenuItemInput)([#3248](https://github.com/GatoGraphQL/GatoGraphQL/pull/3242))
