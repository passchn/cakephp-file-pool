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

## Troubleshooting

### Widget is not rendering

If the widget does not show up, make sure you are fetching scripts somewhere in your template:

```php
<?= $this->fetch('script') ?>
```

You can change the viewBlock the plugin is using via the `'FilePool.ViewBlock'` config, e.g. in your `app.php`.

> [!IMPORTANT]  
> Scripts should be fetched at the end of your html. Styles will be loaded via JavaScript.

### Widget is getting 403 responses (CSRF)

The widget's client uses CakePHP's default csrf cookie name (`csrfToken`) and header name (`X-CSRF-Token`).

If you did not change your config, check if the `CsrfProtectionMiddleware` is configured with `httponly` set to `false`.
This is necessary because JavaScript won't have access to the cookie otherwise:

```php
->add(new CsrfProtectionMiddleware([
    'httponly' => false,
    // ...
]))
```

**Note:** If you change these settings, make sure to remove your old `csrfToken` cookie in your browser's Dev tools. The
changes might otherwise not work immediately as the old cookie is still set to `httponly` and will not be replaced
automatically.

## Contribution

You are welcome to open Issues or Pull Requests.

If you had issues installing or using the plugin, tell me about it and I will update the Troubleshooting section.