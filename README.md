# FilePool plugin for CakePHP 5

Gives you a fast and simple way to add files to entities.

> [!NOTE]
> This is an extension to the [passchn/cakephp-assets](https://packagist.org/packages/passchn/cakephp-assets) plugin.

**Features:**

* No-config FilePool widget (through a ViewHelper) for any Entity you have 
* Possibility to upload, sort, edit or delete files from within the widget 
* Drag and drop functionality to upload multiple files 
* You can easily control if a visitor can upload, edit or delete items 
* Translations in english and german

## Prerequisites

Follow the installation guide for [passchn/cakephp-assets](https://packagist.org/packages/passchn/cakephp-assets)
and make sure the plugin is working in your app.

## Installation

You can install this plugin into your CakePHP application using [composer](https://getcomposer.org).

The recommended way to install composer packages is:

```sh
composer require passchn/cakephp-file-pool
```

Then, load the plugin:

```sh
bin/cake plugin load FilePool
```

Load the helper in your `AppView.php`:

```php
$this->loadHelper('FilePool.FilePool');
```

Run the migrations

```sh
bin/cake migrations migrate --plugin FilePool
```

... or copy the migration to your App's migration files.

## Usage

Use the `FilePool` helper for any Entity in a template:

```php
<?= $this->FilePool->forEntity(
  $entity,
  title: 'File Pool',
  allowUpload: true,
  allowEdit: $currentUser->canEditFiles(),
) ?>
```

### Define relations 

You can easily define Relations to the entity in your `ExamplesTable`:

```php
$this->hasMany('Downloads', ['foreignKey' => 'owner_id'])
    ->setConditions(['owner_source' => 'Examples'])
    ->setClassName('FilePool.FilePoolAssets');
```

… and then access the files through `$example->downloads`  after containing `Downloads.Assets` in your Controller.

### Rendering the Widget

If the widget does not show up, make sure you are fetching scripts somewhere in your template: 

```php
<?= $this->fetch('script') ?>
```

You can change the viewBlock the plugin is using via the `'FilePool.ViewBlock'` config, e.g. in your `app.php`.

> [!IMPORTANT]  
> Scripts should be fetched at the end of your html. Styles will be loaded via JavaScript. 

## Contribution

You are welcome to open Issues or Pull Requests.
