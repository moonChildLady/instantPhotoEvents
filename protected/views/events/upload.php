
<h2>Upload Photo <?php echo $model->eventName;?></h2>

<div class="well">
<h3>Collection</h3>
<div class="well">
<?php $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
	'id'=>'events-form',
	//'enableAjaxValidation'=>true,
    'enableClientValidation'=>true,
        'clientOptions' => array(
                'validateOnSubmit'=>true,
                'validateOnChange'=>true,
                'validateOnType'=>false,
        ),
)); ?>
<?php echo $form->textFieldGroup($model,'collection',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5','maxlength'=>200)))); ?>
<div class="form-actions">
<div class="btn-group btn-group-justified" role="group" aria-label="">
  <div class="btn-group" role="group">
    <a class="btn btn-default" href="<?php echo Yii::app()->createUrl('events/admin');?>" role="button">Back</a>
  </div>
  <div class="btn-group" role="group">
<div class="form-actions">
	<?php $this->widget('booster.widgets.TbButton', array(
			'buttonType'=>'submit',
			'context'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Create',
		)); ?>
	
</div>
</div>
</div>
</div>
<?php $this->endWidget(); ?>
</div>
<p>OR</p>
<div class="well">
	<h3>Select the Collection</h3>
	
	<?php $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
	'id'=>'events-form',
	'action'=>Yii::app()->createUrl('events/deleteCollection', array('id'=>$model->id)),
	//'enableAjaxValidation'=>true,
    'enableClientValidation'=>true,
        'clientOptions' => array(
                'validateOnSubmit'=>true,
                'validateOnChange'=>true,
                'validateOnType'=>false,
        ),
)); ?>
		<?php $this->widget('DropDownRedirect', array(
		'name'=>'collection',
    'data' => $this->getCollection($model->id),
    'url' => $this->createUrl($this->route, array_merge($_GET, array('collection' => '__value__'))), // the url (__value__ will be replaced by the selected value)
    'select' => Yii::app()->getRequest()->getParam('collection'), //the preselected value
	'htmlOptions'=>array(
		'class'=>'form-control',
		//'id'=>'collectionDrp'
	),
)); ?>
<div class="form-actions">
<div class="btn-group btn-group-justified" role="group" aria-label="">
<div class="btn-group" role="group">
<div class="form-actions">
	<?php $this->widget('booster.widgets.TbButton', array(
			'buttonType'=>'submit',
			'context'=>'warning',
			'id'=>'deletebtn',
			'label'=>$model->isNewRecord ? 'Delete' : 'Delete',
		)); ?>
	
</div>
</div>
</div>
</div>
<?php $this->endWidget(); ?>
</div>
</div>
<div class="well">
<h3>Uplaod Photoes <small>Below 3M</small></h3>
	<?php
    $this->widget('ext.coco.CocoWidget'
        ,array(
            'id'=>'cocowidget1',
            'onCompleted'=>'function(id,filename,jsoninfo){  }',
            'onCancelled'=>'function(id,filename){ alert("cancelled"); }',
            'onMessage'=>'function(m){ alert(m); }',
            'allowedExtensions'=>array('jpeg','jpg','gif','png'), // server-side mime-type validated
            'sizeLimit'=>3072000, // limit in server-side and in client-side
            'uploadDir' => 'images/album/'.$model->id."/".Yii::app()->getRequest()->getParam('collection')."/org/", // coco will @mkdir it
            // this arguments are used to send a notification
            // on a specific class when a new file is uploaded,
            //'receptorClassName'=>'application.models.MyModel',
            //'methodName'=>'onFileUploaded',
            //'userdata'=>$model->primaryKey,
            // controls how many files must be uploaded
            'maxUploads'=>-1, // defaults to -1 (unlimited)
            'maxUploadsReachMessage'=>'No more files allowed', // if empty, no message is shown
            // controls how many files the can select (not upload, for uploads see also: maxUploads)
            'multipleFileSelection'=>true, // true or false, defaults: true
			//'htmlOptions'=>array('style'=>'height: 300px;'),
			'dropFilesText'=>'Drop Files Here !',
			'defaultControllerName'=>'events',
			'receptorClassName'=>'application.models.Events',
			'methodName'=>'onFileUploaded',
			'userdata'=>array('id'=>$model->id,'collection'=>Yii::app()->getRequest()->getParam('collection')),
        ));
    ?>
</div>
<script>
$(function(){
	$("#deletebtn").click(function(){
		if($("select > option").length == 1){
			alert("You can't delete the collection");
			return false;
			
		}
	});
});
</script>