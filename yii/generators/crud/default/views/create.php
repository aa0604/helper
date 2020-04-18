<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$model = new $generator->modelClass;
$talbeName = $model::getTableComment();
echo "<?php\n";
?>

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */

$this->title = '增加<?= $talbeName ?>';
$this->params['breadcrumbs'][] = ['label' => '<?= $talbeName ?>', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-create">


    <?= "<?= " ?>$this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
