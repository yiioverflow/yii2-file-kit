<?php
/**
 * @author Roopan valiya veetil <yiioverflow@gmail.com>
 */

namespace yiioverflow\filekit\actions;

use yii\web\HttpException;

/**
 * public function actions(){
 *   return [
 *           'upload'=>[
 *               'class'=>'yiioverflow\filekit\actions\DeleteAction',
 *           ]
 *       ];
 *   }
 */
class DeleteAction extends BaseAction
{
    /**
     * @var string path request param
     */
    public $pathParam = 'path';
    /**
     * @return bool
     * @throws HttpException
     * @throws \HttpException
     */
    public function run()
    {
        $path = \Yii::$app->request->get($this->pathParam);
        $paths = \Yii::$app->session->get($this->sessionKey, []);
        if (in_array($path, $paths, true)) {
            $success = $this->getFileStorage()->delete($path);
            if (!$success) {
                throw new HttpException(400);
            }
            return $success;
        } else {
            throw new HttpException(403);
        }
    }
}
