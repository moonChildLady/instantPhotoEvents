<?php
$this->breadcrumbs=array(
	'Events'=>array('index'),
	'Manage',
);
/* 
$this->menu=array(
array('label'=>'List Events','url'=>array('index')),
array('label'=>'Create Events','url'=>array('create')),
); */

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
$('.search-form').toggle();
return false;
});
$('.search-form form').submit(function(){
$.fn.yiiGridView.update('events-grid', {
data: $(this).serialize()
});
return false;
});
");
?>

<h1>Manage Events</h1>

<!--p>
	You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>
		&lt;&gt;</b>
	or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p-->
<div class="btn-group pull-left">
  <button type="button" class="btn btn-success dropdown-toggle " data-toggle="dropdown" aria-expanded="false">
    Action <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" role="menu">
    <li><a href="<?php echo Yii::app()->createUrl('events/create');?>">New Event</a></li>
  </ul>
</div>
<?php //echo CHtml::link('Advanced Search','#',array('class'=>'search-button btn')); ?>
<!--div class="search-form" style="display:none">
	<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div--><!-- search-form -->

<?php $this->widget('booster.widgets.TbGridView',array(
'id'=>'events-grid',
'dataProvider'=>$model->search(),
'filter'=>$model,
'columns'=>array(
		'id',
		'eventName',
		'passCode',
		'startDate',
		'endDate',
		'createDate',
		/*
		'active',
		*/
array(
'class'=>'booster.widgets.TbButtonColumn',
'template'=>'{qrcode} {view} {upload} {update} {delete}',
'buttons'=>array(
        'qrcode' => array(
            'url' => 'Yii::app()->urlManager->createUrl("events/QRCode", array("id"=>$data->id))', // view url
			'icon'=>'qrcode',
			'label'=>'QR Code',
            'options' => array('target' => '_blank'),
			
        ),
		 'view' => array(
            'url' => 'Yii::app()->urlManager->createUrl("events/show", array("id"=>$data->id))', // view url
			//'icon'=>'qrcode',
			'label'=>'Show',
            'options' => array('target' => '_blank'),
			
        ),
		'upload' => array(
            'url' => 'Yii::app()->urlManager->createUrl("events/upload", array("id"=>$data->id))', // view url
			'icon'=>'upload',
			'label'=>'Upload',
            'options' => array('target' => '_blank'),
			
        ),
    ),
//'viewButtonUrl'=>'Yii::app()->urlManager->createUrl("events/QRCode", array("id"=>$data->id))',
),
),
)); ?>
