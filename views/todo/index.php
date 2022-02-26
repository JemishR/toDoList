<?php
use yii\helpers\URL;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Todo';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="todo-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Todo', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <p>
        <a class="btn btn-primary" href="#" id="com-incom">Comlpete/Incomplete Todo</a>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn',
                'checkboxOptions' => function ($model, $key, $index, $column) {
                    if ($model->status == 1) {
                        return ['checked' => true];
                    }
                    return ['value' => $model->id];
                },
            ],
            [
                'attribute' => 'todo',
                'format' => 'text',
                'format'=>'html',    
                'value' => function($model){
                    return Html::a("<span>$model->todo</span>", ['update', 'id' => $model->id], ['style' => $model->status == 1 ? 'color:green' : 'color:black']);
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Actions',
                'template' => '{delete}',
                'buttons' => [
                    'delete' => function($url, $model){
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $model->id], [
                            'class' => '',
                            'data' => [
                                'confirm' => 'Do you want to delete this ?',
                                'method' => 'post',
                            ],
                        ]);
                    }
                ]
            ],
        ],
    ]); ?>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script>
    $('#com-incom').click(function () {
        if (!confirm('Are you sure you want to mark all selected as complete and not selected as incomplete?'))     
            return false;
        var checked = [];
        var unchecked = [];
        var skip_1 = 0;
        $('tr').each(function(){
            if(skip_1 != 0)
            {
                if($(this).find('input[type="checkbox"]').is(':checked'))
                {
                    checked.push($(this).find('input[type="checkbox"]').val());
                }
                else
                {
                    unchecked.push($(this).find('input[type="checkbox"]').val());
                }
            }
            else
            {
                skip_1++;
            }
        });
        $.ajax({
            type: "POST",
            data: {checked:checked, unchecked:unchecked},
            url: "/index.php?r=todo%2Fcom-incom",
            success: function(msg){
            }
        });
    });
</script>