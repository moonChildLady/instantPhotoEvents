<?php
$this->breadcrumbs=array(
	'Events'=>array('index'),
	$model->id,
);

$this->menu=array(
array('label'=>'List Events','url'=>array('index')),
array('label'=>'Create Events','url'=>array('create')),
array('label'=>'Update Events','url'=>array('update','id'=>$model->id)),
array('label'=>'Delete Events','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
array('label'=>'Manage Events','url'=>array('admin')),
);
?>

<h1>View Events #<?php echo $model->id; ?></h1>

<?php $this->widget('booster.widgets.TbDetailView',array(
'data'=>$model,
'attributes'=>array(
		'id',
		'eventName',
		'passCode',
		'startDate',
		'endDate',
		'createDate',
		'active',
),
)); ?>
 <a class="btn btn-default" href="<?php echo Yii::app()->createUrl('events/admin');?>" role="button">Back</a>