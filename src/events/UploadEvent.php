<?php
namespace yiioverflow\filekit\events;
use yii\base\Event;

/**
 * Class UploadEvent
 * @package yiioverflow\filekit\events
 * @author Roopan valiya veetil <yiioverflow@gmail.com>
 */
class UploadEvent extends Event
{
    /**
     * @var mixed
     */
    public $filesystem;
    /**
     * @var string
     */
    public $path;
    /**
     * @var \League\Flysystem\File|null
     */
    public $file;
}
