<?php
/**
 * @author Roopan valiya veetil <yiioverflow@gmail.com>
 */

namespace yiioverflow\filekit\widget;

use yii\web\AssetBundle;

class UploadAsset extends AssetBundle
{
    public $css = [
        'css/upload-kit.min.css'
    ];

    public $js = [
        //Edited by roopan
        'js/upload-kit.js'
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
        'yiioverflow\filekit\widget\BlueimpFileuploadAsset'
    ];

    public function init()
    {
        $this->sourcePath = __DIR__."/assets";
        parent::init();
    }
}
