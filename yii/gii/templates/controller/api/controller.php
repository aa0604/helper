<?php
/**
 * This is the template for generating a controller class file.
 * @var \yii\db\ActiveRecord $model
 */

use yii\helpers\Inflector;
use yii\helpers\StringHelper;
// 模型名
$modelName = substr(StringHelper::basename($generator->controllerClass),0, -10);
// 模块名
$modules = preg_replace(['/\\\controllers/', '/(.*)\\\/'], '', $generator->getControllerNamespace());
// 模型路径
$modelClass = 'common\modules\\'. $modules . '\\' . $modelName;
// 模型对象

$model = new $modelClass;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\controller\Generator */

echo "<?php\n";

?>

namespace <?= $generator->getControllerNamespace() ?>;

use Yii;
use <?= $modelClass ?>;

class <?= StringHelper::basename($generator->controllerClass) ?> extends <?= '\\' . trim($generator->baseClass, '\\') . "\n" ?>
{
    public $modelClass = '<?= $modelClass ?>';


<?php if (empty($generator->getActionIDs()) || in_array('view', $generator->getActionIDs())) { ?>


    public function actionView($id)
    {
        try {
            $data = <?= $modelName ?>::findOne($id);
            return $this->returnData($data);
        } catch (\Exception $e) {
            return $this->returnExceptionError($e);
        }
    }
<?php }?>

<?php if (empty($generator->getActionIDs()) || in_array('index', $generator->getActionIDs())) { ?>
    public function actionIndex($page)
    {
        try {
            $list = <?= $modelName ?>::getLists(['page' => $page]);
            return $this->returnList($list);
        } catch (\Exception $e) {
            return $this->returnExceptionError($e);
        }
    }
<?php }?>

<?php if (empty($generator->getActionIDs()) || in_array('create', $generator->getActionIDs())) { ?>
    public function actionCreate()
    {
        try {
            $m = new <?= $modelName ?>;
            <?php
            foreach ($model::getTableSchema()->columns as $key => $v) {
                if ($v->autoIncrement) continue;
                if ($key == 'userId') {
                    echo '$m->userId = $this->userId;' ;
                } else {

                    echo  '            ';
                    switch ($v->phpType) {

                        case 'integer':
                            echo '$m->'. $key . ' = intval(Yii::$app->request->post(\''. $key . '\'));';
                            break;

                        default:

                            switch ($v->type) {
                                case 'datetime':
                                    echo "\$m->$key = date('Y-m-d H:i:s');";
                                    break;
                                case 'date':
                                    echo "\$m->$key = date('Y-m-d');";
                                    break;
                                default:
                                    echo '$m->load([\''. $key . '\' => Yii::$app->request->post(\''. $key . '\')], \'\');';
                                    break;
                            }
                    }
                }
                echo  PHP_EOL;
            }
            ?>
            $m->logicSave();
            return $this->returnData($m);
        } catch (\Exception $e) {
            return $this->returnExceptionError($e);
        }
    }
<?php }?>

<?php if (empty($generator->getActionIDs()) || in_array('delete', $generator->getActionIDs())) { ?>
    public function actionDelete($id)
    {
        try {
            $this->userDeleteData($id);
            return $this->returnApiSuccess();
        } catch (\Exception $e) {
            return $this->returnExceptionError($e);
        }
    }
<?php }?>
}
