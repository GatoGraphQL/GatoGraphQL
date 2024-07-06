# Release Notes: 3.0

## Breaking changes

### Require at least WordPress v6.0 ([#2719](https://github.com/GatoGraphQL/GatoGraphQL/pull/2719))

When using Gato GraphQL with WordPress `v6.6` (just ahead of its release), [blocks in the plugin stopped working](https://github.com/WordPress/gutenberg/issues/63009).

As a solution, blocks were adapted and re-compiled, and the new [compiled files only work only with WordPress `v6.0`+](https://github.com/WordPress/gutenberg/issues/63135#issuecomment-2211631051).

That's why, from now on, Gato GraphQL requires at least WordPress `v6.0`.

## Improvements

- Added compatibility with WordPress 6.6 ([#2717](https://github.com/GatoGraphQL/GatoGraphQL/pull/2717))

## Fixed

- Catch exception if dependency version is not semver ([#2712](https://github.com/GatoGraphQL/GatoGraphQL/pull/2712))
- Convert entries in JSON dictionary of variables in persisted query from array to object ([#2715](https://github.com/GatoGraphQL/GatoGraphQL/pull/2715))
