<?php
$this->breadcrumbs=array(
	'Events'=>array('index'),
	$model->id,
);
?>

<h1><?php echo $model->eventName; ?></h1>
<p class="helper">Long press the photo for save to your device</p>
<?php /*
$this->widget('booster.widgets.TbDetailView',array(
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
));
*/ ?>

<script src="http://pad.dress4u.hk:3000/socket.io/socket.io.js"></script>
<script>
$(document).ready(function(){
    // Connect to our node/websockets server
    var socket = io.connect('http://pad.dress4u.hk:3000');
    
    var eventID = <?php echo $model->id;?>;
	
	socket.emit('sent eventID', {eventID: eventID});
	
	
	
	
    // Initial set of notes, loop through and add to list
   
	if(data.folder==<?php echo $model->id;?>){
		//console.log(data);
        $.each(data.images, function(i, row){
             $("#imageArea").prepend(
        	'<div class="col-md-3 col-xs-6"><a href="<?php echo Yii::app()->request->baseUrl.DIRECTORY_SEPARATOR;?>images/album/<?php echo $model->id;?>/'+row+'" download><img src="<?php echo Yii::app()->request->baseUrl.DIRECTORY_SEPARATOR;?>images/album/<?php echo $model->id;?>/'+row+'" class="img-thumbnail img-responsive"/ ></a></div>'
        );
        });
		
		}
    });
     socket.on('delete images', function(data){

		//console.log(data);
		//console.log(data);
		if(data.folder==<?php echo $model->id;?>){
		$('img').each(function(i, row){
		
		
			if($(row).attr('src') == "<?php echo Yii::app()->request->baseUrl.DIRECTORY_SEPARATOR;?>images/album/<?php echo $model->id;?>/"+data.images){
				$(row).remove();
			}
		});
		}
    
	
    });
    
});
</script>
<div class="row" id="imageArea" >
<div class="clearfix visible-xs-block"></div>
</div>