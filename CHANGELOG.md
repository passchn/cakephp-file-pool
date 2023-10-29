# Changelog

## 0.3.3

### Fixed

* `FilePoolAssetsController::initialize()` checks if the `FormProtectionComponent` is loaded before applying settings.
  The component is not loaded by default in `cakephp/app`.
* The `ViteHelper` to load the bundles was loaded incorrectly if not loaded by the app anyway. It now
  uses `View::loadHelper()` instead of `View::addHelper()`.

## v0.3.2

### Added

* Option to define a custom viewBlock where the javascript is rendered. The config key is `FilePool.ViewBlock` which
  defaults to `script`.

## v0.3.1

### Changed

* FilePoolAssets are ordered by their sort index by default. See `FilePoolAssetsTable::beforeFind()`.

## v0.3.0

### Fixed

* Owning Entity can now have any id type (uuid, int). The `owner_id` field was changed from `CHAR(36)`
  to `VARCHAR(255)`. Please run the Migration `FixOwnerIdField`.

### Added

* Sorting functionality:
    * New Files will get the highest sort index per owning Entity
    * Sort order can be updated via up/down buttons
* Custom behaviors can be added to the `FilePoolAssetsTable` via the config `FilePool.FilePoolAssetsTable.Behaviors`,
  which is expected to be a list of Behavior names.
* Better error handling in case of upload or saving errors.

