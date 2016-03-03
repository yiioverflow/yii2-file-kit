[![Packagist](https://img.shields.io/packagist/dt/yiioverflow/yii2-file-kit.svg)]()
[![Dependency Status](https://www.versioneye.com/php/yiioverflow:yii2-file-kit/badge.svg)](https://www.versioneye.com/php/yiioverflow:yii2-file-kit/2.0.0)

This kit is designed to automate routine processes of uploading files, their saving and storage.
It includes:
- File upload widget (based on [Blueimp File Upload](https://github.com/blueimp/jQuery-File-Upload))
- Component for storing files (built on top of [flysystem](https://github.com/thephpleague/flysystem))
- Actions to download, delete, and view (download) files
- Behavior for saving files in the model and delete files when you delete a model

Demo
---- [coming soon](http://yiioverflow.com)

# Installation
The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
composer require yiioverflow/yii2-file-kit "*"

```

to the require section of your `composer.json` file.

# File Storage
To work with the File Kit you need to configure FileStorage first. This component is a layer of abstraction over the filesystem
- Its main task to take on the generation of a unique name for each file and trigger corresponding events.
```php
'fileStorage'=>[
    'class' => 'yiioverflow\filekit\Storage',
    'baseUrl' => '@web/uploads'
    'filesystem'=> ...
        // OR
    'filesystemComponent' => ...    
],
```
There are several ways to configure `Storage` to work with `flysystem`.

1. Create a builder class that implements `yiioverflow\filekit\filesystem\FilesystemBuilderInterface` and implement method` build`
which returns filesystem object
Example:
```php
namespace app\components;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local as Adapter;
use yiioverflow\filekit\filesystem\FilesystemBuilderInterface;

class LocalFlysystemBuilder implements FilesystemBuilderInterface
{
    public $path;

    public function build()
    {
        $adapter = new Local(\Yii::getAlias($this->path));
        return new Filesystem($adapter);
    }
}
```
Configuration:
```php
'fileStorage'=>[
    ...
    'filesystem'=> [
        'class' => 'app\components\FilesystemBuilder',
        'path' => '@webroot/uploads'
        ...
    ]
]
```
Read more about flysystem at http://flysystem.thephpleague.com/

Then you can use it like this:
```php
$file = UploadedFile::getInstanceByName('file');
Yii::$app->fileStorage->save($file); // method will return new path inside filesystem
$files = UploadedFile::getInstancesByName('files');
Yii::$app->fileStorage->saveAll($files);
```

2. Use third-party extensions, `creocoder/yii2-flysystem` for example, and provide a name of the filesystem component in `filesystemComponent`
Configuration:
```php
'fs' => [
    'class' => 'creocoder\flysystem\LocalFilesystem',
    'path' => '@webroot/files'
    ...
],
'fileStorage'=>[
    ...
    'filesystemComponent'=> 'fs'
],
```
# Actions
File Kit contains several Actions to work with uploads.

### Upload Action
Designed to save the file uploaded by the widget
```php
public function actions(){
    return [
           'upload'=>[
               'class'=>'yiioverflow\filekit\actions\UploadAction',
               'validationRules' => [
                    ...
               ],
               'on afterSave' => function($event) {
                    /* @var $file \League\Flysystem\File */
                    $file = $event->file
                    // do something (resize, add watermark etc)
               }
           ]
       ];
}
```
See additional settings in the corresponding class

### Delete Action
```php
public function actions(){
    return [
       'delete'=>[
           'class'=>'yiioverflow\filekit\actions\DeleteAction',
       ]
    ];
}
```
See additional settings in the corresponding class

### View (Download) Action
```php
public function actions(){
    return [
       'view'=>[
           'class'=>'yiioverflow\filekit\actions\ViewAction',
       ]
    ];
}
```
See additional settings in the corresponding class

# Upload Widget
Standalone usage
```php
echo \yiioverflow\filekit\widget\Upload::widget([
    'model' => $model,
    'attribute' => 'files',
    'url' => ['upload'],
    'sortable' => true,
    'maxFileSize' => 10 * 1024 * 1024, // 10Mb
    'minFileSize' => 1 * 1024 * 1024, // 1Mb
    'maxNumberOfFiles' => 3 // default 1,
    'acceptFileTypes' => new JsExpression('/(\.|\/)(gif|jpe?g|png)$/i'),
    'clientOptions' => [ ...other blueimp options... ]
]);
```

With ActiveForm
```php
echo $form->field($model, 'files')->widget(
    '\yiioverflow\filekit\widget\Upload',
    [
        'url' => ['upload'],
        'sortable' => true,
        'maxFileSize' => 10 * 1024 * 1024, // 10 MiB
        'maxNumberOfFiles' => 3,
        'clientOptions' => [ ...other blueimp options... ]
    ]
);
```
## Upload Widget events
Upload widget trigger some of built-in blueimp events:
- start
- fail
- done
- always
    
You can use them directly or add your custom handlers in options:
```php
'clientOptions' => [ 
    'start' => 'function(e, data) { ... do something ... }',
    'done' => 'function(e, data) { ... do something ... }',
    'fail' => 'function(e, data) { ... do something ... }',
    'always' => 'function(e, data) { ... do something ... }',
 ]
```

# UploadBehavior
This behavior is designed to save uploaded files in the corresponding relation.

Somewhere in model:

For multiple files
```php
 public function behaviors()
 {
    return [
        'file' => [
            'class' => 'yiioverflow\filekit\behaviors\UploadBehavior',
            'multiple' => true,
            'attribute' => 'files',
            'uploadRelation' => 'uploadedFiles',
            'pathAttribute' => 'path',
            'baseUrlAttribute' => 'base_url',
            'typeAttribute' => 'type',
            'sizeAttribute' => 'size',
            'nameAttribute' => 'name',
            'orderAttribute' => 'order'
        ],
    ];
 }
```

For single file upload
```php
 public function behaviors()
 {
     return [
          'file' => [
              'class' => 'yiioverflow\filekit\behaviors\UploadBehavior',
              'attribute' => 'file',
              'pathAttribute' => 'path',
              'baseUrlAttribute' => 'base_url',
               ...
          ],
      ];
 }
```

See additional settings in the corresponding class.

# Validation
There are two ways you can perform validation over uploads.
On the client side validation is performed by Blueimp File Upload.
Here is [documentation](https://github.com/blueimp/jQuery-File-Upload/wiki/Options#validation-options) about available options.

On the server side validation is performed by [[yii\web\UploadAction]], where you can configure validation rules for 
[[yii\base\DynamicModel]] that will be used in validation process

# Tips
## Adding watermark
Install ``intervention/image`` library
```
composer require intervention/image
```
Edit your upload actions as so
```
public function actions(){
    return [
           'upload'=>[
               'class'=>'yiioverflow\filekit\actions\UploadAction',
               ...
               'on afterSave' => function($event) {
                    /* @var $file \League\Flysystem\File */
                    $file = $event->file;
                    
                    // create new Intervention Image
                    $img = Intervention\Image\ImageManager::make($file->read());
                    
                    // insert watermark at bottom-right corner with 10px offset
                    $img->insert('public/watermark.png', 'bottom-right', 10, 10);
                    
                    // save image
                    $file->put($img->encode());
               }
               ...
           ]
       ];
}
```
