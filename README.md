# Symbiotic package loader from the file system

## Install
```
composer require symbiotic/packages-loader-filesytem
```

## How it works?
The component traverses all allowed directories (up to 3 levels of nesting), searches for `composer.json` files and reads
a section from them reads the `extra->symbiotic` section to add a package to the core of the Symbiotic framework.

## Setting up Directories
Adding directories to search for Symbiotic packages is done in the main config of the framework:

```php
<?php
return [
    // Core framework config...
      'packages_paths' => [
        '/home/path_to_project/vendor',
        '/home/path_to_project/modules',
        //.....
    ]
];
```

#### If you add directories outside of the autoload of the composer, you need to connect the Autoload framework and also specify the directories for file autoload in it.
