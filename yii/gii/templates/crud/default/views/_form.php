<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

/* @var $model \yii\db\ActiveRecord */
$model = new $generator->modelClass();
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $safeAttributes = $model->attributes();
}

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form">

    <?= "<?php " ?>$form = ActiveForm::begin([
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
    'template' => '<label class="col-sm-2 control-label">{label}</label><div class="col-sm-5">{input}</div><div class="col-sm-5">{input}{error}{hint}</div>',
    ],
    ]); ?>
<div class="panel panel-primary">
    <div class="panel-heading">
        <?=$this->title ?: ' '?>
    </div>
    <div class="panel-body">

        <?php foreach ($generator->getColumnNames() as $attribute) {
            if (in_array($attribute, $safeAttributes)) {
                echo "    <?= " . $generator->generateActiveField($attribute) . " ?>\n\n";
            }
        } ?>
        <div class="form-group text-center">
            <?= "<?= " ?>Html::submitButton(<?= $generator->generateString('提交') ?>, ['class' => 'btn btn-success']) ?>
        </div>
    </div>
</div>

    <?= "<?php " ?>ActiveForm::end(); ?>

</div>
