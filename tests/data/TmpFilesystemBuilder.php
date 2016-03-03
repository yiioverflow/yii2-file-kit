<?php
namespace yiioverflow\filekit\tests\data;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use yiioverflow\filekit\filesystem\FilesystemBuilderInterface;

/**
 * @author Roopan valiya veetil <yiioverflow@gmail.com>
 */
class TmpFilesystemBuilder implements FilesystemBuilderInterface
{

    /**
     * @return mixed
     */
    public function build()
    {
        return new Filesystem(new Local(sys_get_temp_dir()));
    }
}
