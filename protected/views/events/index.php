<?php
$this->breadcrumbs=array(
	'Events',
);

$this->menu=array(
array('label'=>'Create Events','url'=>array('create')),
array('label'=>'Manage Events','url'=>array('admin')),
);
?>

<h1>Events</h1>

<?php $this->widget('booster.widgets.TbListView',array(
'dataProvider'=>$dataProvider,
'itemView'=>'_view',
)); ?>
