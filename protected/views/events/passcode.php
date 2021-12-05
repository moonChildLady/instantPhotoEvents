
<div class="well">
<h1 class="text-center"><?php echo $event->eventName;?></h1>
<?php $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
	'id'=>'events-form',
	'enableAjaxValidation'=>false,
	'enableClientValidation'=>true,
        'clientOptions' => array(
                'validateOnSubmit'=>true,
                'validateOnChange'=>true,
                'validateOnType'=>false,
        ),
)); ?>

<?php echo $form->errorSummary($model); ?>
<?php echo 
		
	$form->TelFieldGroup($model,'passCode',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5','maxlength'=>10)))); 
	
	$this->widget(
    'booster.widgets.TbButton',
    array('buttonType' => 'submit', 'context'=>'primary','label' => 'GO')
);
	
	?>
	
<?php $this->endWidget(); ?>
</div>