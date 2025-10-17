# Release Notes: 15.1

## Added

- Added `CodeNameJSONObject` input type ([#3210](https://github.com/GatoGraphQL/GatoGraphQL/pull/3210))

## Improvements

- Added WooCommerce extension to docs ([#3195](https://github.com/GatoGraphQL/GatoGraphQL/pull/3195))
- Print descriptions in introspection for EnumStringScalarTypes ([#3211](https://github.com/GatoGraphQL/GatoGraphQL/pull/3211))
- Show "No values available" in description for EnumStrings ([#a3dafbf7](https://github.com/GatoGraphQL/GatoGraphQL/commit/a3dafbf7213a4d0464af7315992f7ab17c84fdd2))
- Allow returning null values in field connections of type List ([#3212](https://github.com/GatoGraphQL/GatoGraphQL/pull/3212))

## Fixed

- Cast post ID to int (for if 3rd-party CPT returns it as string) ([#3213](https://github.com/GatoGraphQL/GatoGraphQL/pull/3213))
- Meta not returned as array ([#3214](https://github.com/GatoGraphQL/GatoGraphQL/pull/3214))
- QueryableInterface adapter ([#3215](https://github.com/GatoGraphQL/GatoGraphQL/pull/3215))
- CustomPostMeta module depends on Meta module ([#3216](https://github.com/GatoGraphQL/GatoGraphQL/pull/3216))