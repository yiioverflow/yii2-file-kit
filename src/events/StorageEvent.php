<?php

namespace yiioverflow\filekit\events;

use yii\base\Event;

/**
 * Class StorageEvent
 * @package yiioverflow\filekit\events
 * @author Roopan valiya veetil <yiioverflow@gmail.com>
 */
class StorageEvent extends Event
{
    /**
     * @var \League\Flysystem\FilesystemInterface
     */
    public $filesystem;
    /**
     * @var string
     */
    public $path;
}
