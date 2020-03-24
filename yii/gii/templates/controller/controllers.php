<?php

namespace apps\api\modules\article\controllers;

use apps\api\modules\ApiBaseController;
use common\map\api\ResponseMap;
use common\map\CommonMap;
use common\modules\article\Article;
use common\modules\article\logic\ArticleLogic;
use xing\helper\exception\ApiException;
use Yii;

/**
 * Default controller for the `article` module
 */
class ArticleCommentController extends ApiBaseController
{
    public $modelClass = 'common\modules\article\ArticleComment';

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionView($articleId)
    {
        try {
            $data = $this->findModel($articleId);
            return $this->returnData($data);
        } catch (\Exception $e) {
            return $this->returnExceptionError($e);
        }
    }

    public function actionIndex($page)
    {
        try {
            $list = $this->getModel()::getLists(['page' => $page]);
            return $this->returnList($list);
        } catch (\Exception $e) {
            return $this->returnExceptionError($e);
        }
    }

    public function actionCreate()
    {
        try {
            $m =  $this->getModel();
            $m->logicSave();
            return $this->returnData($m);
        } catch (\Exception $e) {
            return $this->returnExceptionError($e);
        }
    }

    public function actionDelete($articleId)
    {
        try {
            $this->userDeleteData($articleId);
            return $this->returnApiSuccess();
        } catch (\Exception $e) {
            return $this->returnExceptionError($e);
        }
    }

}

