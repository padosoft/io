# Simple agnostic package for IO related (files, dir, etc..)

![Agnostic](https://img.shields.io/badge/php-agnostic-blue.svg?style=flat-square)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/padosoft/io.svg?style=flat-square)](https://packagist.org/packages/padosoft/io)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/padosoft/io/master.svg?style=flat-square)](https://travis-ci.org/padosoft/io)
[![Quality Score](https://img.shields.io/scrutinizer/g/padosoft/io.svg?style=flat-square)](https://scrutinizer-ci.com/g/padosoft/io)
[![Total Downloads](https://img.shields.io/packagist/dt/padosoft/io.svg?style=flat-square)](https://packagist.org/packages/padosoft/io)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/e5f0885a-912d-4bd8-a6c4-76127603d2b6.svg?style=flat-square)](https://insight.sensiolabs.com/projects/e5f0885a-912d-4bd8-a6c4-76127603d2b6)

This is a simple agnostic package with few or zero dipendencies. 
This is ideal to provides support and helpers for creating packeges without include many and many dipendencies.

##Requires
  
- php: >=7.0.0
  
## Installation

You can install the package via composer:
``` bash
$ composer require padosoft/io
```

## Usage

Then here's an example of how to use `FileHelper` class:

Add a use statement `use Padosoft\Io\FileHelper;`

```php
<?php

if(FileHelper::checkDirExistOrCreate('public/upload', '0755')){
    echo 'dir already exists or now created!';
}else{
    echo 'OOPS! dir not exists and unable to create it!';
}

//get all files in public/upload/
$arrFiles = FileHelper::findFiles('public/upload/*');
var_dump($arrFiles);

//get all jpeg in public/upload/
$arrFiles = FileHelper::findFiles('public/upload/*.jpg');
var_dump($arrFiles);

//check if the path ends with slash
if(DirHelper::endsWithSlash('public/upload')){
    echo 'add a final slash...';
    $path = DirHelper::addFinalSlash('public/upload');
    echo $path;
}else{
    echo 'path already finish with slash.';
}

//get all directories in public/upload/
$arrFolders = DirHelper::findDirs('public/upload/*');
var_dump($arrFolders);

//truncate log to last 2000 bytes without truncate line
echo 'before:'.PHP_EOL;
echo file_get_contents('log.csv');
if(LogHelper::ftruncatestart('log.csv', 2000)){
    echo 'after:'.PHP_EOL;
    echo file_get_contents('log.csv');
}

```

## List of functions

### DirHelper

- isDirSafe
- checkDirExistOrCreate
- addFinalSlash
- addFinalSlashToAllPaths
- endsWithSlash
- endsWithStar
- endsWith
- startsWithSlash
- startsWith
- findDirs
- delete
- removeFinalSlash
- removeFinalSlashToAllPaths
- removeStartSlash
- removeStartSlashToAllPaths
- copy
- isLocal
- isAbsoluteUnix
- isAbsoluteWindows
- isAbsoluteWindowsRoot
- isAbsolute
- isRelative
- join
- njoin
- canonicalize
- isDirEmpty : Check if a directory is empty in efficent way. Check hidden files too.
- isDotDir : Check if an antry is linux dot dir (i.e.: . or .. )
- isReadable : Check if a path is a dir and is readable.

### FileHelper

- arrMimeType
- getPathinfoPart
- getDirname
- getFilename
- getFilenameWithoutExtension
- getFilenameExtension
- hasExtension
- variable2Array
- changeExtension
- unlinkSafe
- fileExistsSafe
- findFiles
- filePutContentsSafe
- getMimeType
- getMimeTypeByFinfo
- getMimeTypeByMimeContentType
- isReadable : Check if a path is a file and is readable.

### LogHelper

- ftruncatestart

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email instead of using the issue tracker.

## Credits
- [Lorenzo Padovani](https://github.com/lopadova)
- [All Contributors](../../contributors)

## About Padosoft
Padosoft (https://www.padosoft.com) is a software house based in Florence, Italy. Specialized in E-commerce and web sites.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
