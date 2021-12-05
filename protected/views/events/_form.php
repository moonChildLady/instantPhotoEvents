<?php $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
	'id'=>'events-form',
	//'enableAjaxValidation'=>true,
    'enableClientValidation'=>true,
        'clientOptions' => array(
                'validateOnSubmit'=>true,
                'validateOnChange'=>true,
                'validateOnType'=>false,
        ),
      'htmlOptions' => array('enctype' => 'multipart/form-data'),
)); ?>

<p class="help-block">Fields with <span class="required">*</span> are required.</p>

<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldGroup($model,'eventName',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5','maxlength'=>200)))); ?>

			<?php echo $form->datePickerGroup(
			$model,
			'startDate',
			array(
				'widgetOptions' => array(
					'options' => array(
						'language' => 'en',
						'format' => 'yyyy-mm-dd',
						//'htmlOptions'=>array('class'=>'span5')
					),
				),
				'wrapperHtmlOptions' => array(
					'class' => 'col-sm-5',
				),
				//'hint' => 'Click inside! This is a super cool date field.',
				'prepend' => '<i class="glyphicon glyphicon-calendar"></i>'
			)
		); ?>
	<?php /* echo $form->textFieldGroup($model,'startDate',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); */ ?>
<?php echo $form->datePickerGroup(
			$model,
			'endDate',
			array(
				'widgetOptions' => array(
					'options' => array(
						'language' => 'en',
						'format' => 'yyyy-mm-dd',
						//'htmlOptions'=>array('class'=>'span5')
					),
				),
				'wrapperHtmlOptions' => array(
					'class' => 'col-sm-5',
				),
				//'hint' => 'Click inside! This is a super cool date field.',
				'prepend' => '<i class="glyphicon glyphicon-calendar"></i>'
			)
		); ?>
<?php if($model->isNewRecord) { ?>
	<?php echo $form->textFieldGroup($model,'collection',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>
<?php } ?>
	<?php //echo $form->textFieldGroup($model,'createDate',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>

	<?php //echo $form->dropDownListGroup($model,'active', array('widgetOptions'=>array('data'=>array("YES"=>"YES","NO"=>"NO",), 'htmlOptions'=>array('class'=>'input-large')))); ?>
	
<?php echo $form->fileFieldGroup($model, 'bannerImg',
			array(
				'wrapperHtmlOptions' => array(
					'class' => 'col-sm-5',
				),
			)
		); ?>
<?php /* echo $form->labelEx($model, 'bannerImg');
echo $form->fileField($model, 'bannerImg'); */
?>
<?php echo 
		$form->checkboxGroup($model, 'passCode'
			//array('checked'=>($model->passCode != null) ? '':'checked')
		);
	/* $form->passwordFieldGroup($model,'passCode',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5','maxlength'=>10)))); */ ?>

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
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	
</div>
</div>
</div>
</div>
<?php $this->endWidget(); ?>
