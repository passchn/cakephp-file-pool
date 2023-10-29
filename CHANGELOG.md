# Changelog

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

