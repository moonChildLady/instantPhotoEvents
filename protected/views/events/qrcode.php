<script src="http://pad.dress4u.hk:3000/socket.io/socket.io.js"></script>
<script type="text/javascript" src="/js/jquery.cycle2.min.js"></script>
<script type="text/javascript" src="/js/color-thief.js"></script>
<script type="text/javascript" src="/js/mustache.js"></script>
<script>
$(document).ready(function() {
	$('.shuffle').cycle({
		fx: 'fade', // choose your transition type, ex: fade, scrollUp, shuffle, etc...
		random: true
	});

$( '.shuffle' ).on( 'cycle-before', function( event, optionHash, outgoingSlideEl, incomingSlideEl, forwardFlag) {
var $sourceImage = $(incomingSlideEl);
var colorThief = new ColorThief();
var cp = colorThief.getPalette($sourceImage[0]);
//document.getElementById("mydiv").style.backgroundColor = "rgb(" + color + ")";
$('.showQR, body').css('background-color', 'rgba('+cp[2][0]+','+cp[2][1]+','+cp[2][2]+', 0.1)').fadeIn(1000);
/*
$('.showQR').animate({
  backgroundColor: 'rgba('+cp[2][0]+','+cp[2][1]+','+cp[2][2]+', 0.3)'
});
*/

//$('.showQR').animate({backgroundColor: 'rgb('+cp[2][0]+','+cp[2][1]+','+cp[2][2]+')'}, 'slow');
//console.log(colorThief.getColor(sourceImage));
//var incomingSlideEl.src
});
var socket = io.connect('http://pad.dress4u.hk:3000', {query:'album_id=<?php echo $model->id;?>'});
var eventID = <?php echo $model->id;?>;

socket.on('init images', function(data){
	
	if(data.folder==<?php echo $model->id;?>){
		//var content = "";
		//console.log(data.images);
		var collection = data.collection;
		var res = collection.replace(/\s/g, '_');
		//data.images.reverse(); 
        $.each(data.images, function(i, row){
        	var content = '<img src="<?php echo Yii::app()->request->baseUrl.DIRECTORY_SEPARATOR;?>images/album/<?php echo $model->id;?>/'+data.collection+'/'+row+'" class="img-rounded img-responsive" />'
        	
        	$(".shuffle").cycle('add', content);
        	
        	//$(".shuffle").append(content);
		});
	}

	
	//$('.shuffle').cycle('reinit');


});


socket.on('delete images', function(data){
		if(data.folder==<?php echo $model->id;?>){
		$('img').each(function(i, row){
			//var url = '/events/resized/150x150/images/album/<?php echo $model->id;?>/'+data.collection+'/'+row;
		if($(row).attr('src') == '<?php echo Yii::app()->request->baseUrl.DIRECTORY_SEPARATOR;?>images/album/<?php echo $model->id;?>/'+data.collection+'/'+data.images){
				$(row).remove();
		}
		
		});
		//$('#imageArea').waterfall(); 
		//$('#imageArea').mixItUp(); 
		}
   	
    });
    
socket.emit('get hot', {eventID:<?php echo $model->id;?>});
socket.on('get hottest', function(data){

		if(data.folder==<?php echo $model->id;?>){
			$("#hottest").fadeIn("show", function(){
				$(this).html('<img src="'+data.path+'" class="img-rounded img-responsive"><p></p><p><button type="button" class="btn btn-info btn-lg"><span class="glyphicon glyphicon-heart" aria-hidden="true"></span> '+data.total+'</button> Most Like</p>');
			 	console.log(data);
		});
  }
    });

});

</script>
<div class="well showQR">
<div class="row">
	<div class="col-md-6 col-xs-6">
<div class="bannerContainer">
<img src="<?php echo Yii::app()->request->baseUrl.'/images/banner/'.$model->bannerImg?>" class="img-responsive">
</div>
</div>
<div class="col-md-6 col-xs-6">
	<div class="shuffle">
	</div>
</div>
</div>
<hr>
<div class="row">
	<div class="col-md-6 col-xs-6">
		<h1 class="text-center"><?php echo $model->eventName;?></h1>
		<?php if($model->passCode!="") { ?>
		<h2 class="text-center">PassCode: <kbd><?php echo $model->passCode;?></kbd></h2>
		<?php } ?>
		<p>
		<img src="
		<?php 
		$this->widget('ext.qrcode.QRCodeGenerator',array(
		                            'data' => Yii::app()->createAbsoluteUrl('events/show',array('id'=>$model->id),'http'),
		                            'filename' => $model->id.".png",
		                            'fileUrl'=>Yii::app()->request->baseUrl.'/images/qrcode',
		                            'displayImage'=>false,
		                            'errorCorrectionLevel'=>'L',
		                            'matrixPointSize'=>10, // 1 to 10 only
		    )); 
		?>" class="center-block img-responsive">
		</p>
		<p class="text-center" style="font-size:20px"><a href="<?php echo Yii::app()->createAbsoluteUrl('events/show',array('id'=>$model->id),'http');?>"><?php echo Yii::app()->createAbsoluteUrl('events/show',array('id'=>$model->id),'http');?></a></p>
	</div>
	

		<div class="col-md-6 col-xs-6">
			
			<h3>相片排行榜</h3>
		
				<div id="hottest">
				
				</div>
		</div>

</div>
</div>
<style>
/*
.bannerContainer  .img-responsive {
    display: block;
    width: auto;
    max-height: 100%;
}
*/
</style>