# FilePool plugin for CakePHP 5

## Installation

You can install this plugin into your CakePHP application using [composer](https://getcomposer.org).

The recommended way to install composer packages is:

```sh
composer require passchn/file-pool
```

Then, load the plugin: 
```sh
bin/cake plugin load FilePool
```

Load the helper in your `AppView.php`: 
```php
$this->loadHelper('FilePool.FilePool');
```

## Usage

Use the `FilePool` helper for any Entity in a template: 
```php
<?= $this->FilePool->forEntity($content, title: 'File Pool', allowUpload: true) ?>
```

## Usage notes

The plugin might not be ready for your setup. It was copy-pasted from one of my personal projects to be installed in another one. 

E.g., the Vue App uses TailwindCSS, so Styles might not be applied. 

I am working on making the plugin a bit more usable for other apps and so use css which is scoped to the file pool app. 


## Contribution

You are welcome to open Issues or Pull Requests.
