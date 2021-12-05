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
<script src="/js/libs/handlebars/handlebars.js"></script>
<script src="/js/libs/jquery.easing.js"></script>
<script src="/js/waterfall-light.js"></script>
<script src="/js/jquery.mixitup.js"></script>
<script src="http://imagesloaded.desandro.com/imagesloaded.pkgd.js"></script>
<div class="btn-group" role="group" id="filter_btn">

</div>
<div id="imageArea" >

</div>


<style>
	#imageArea .mix{
    display: none;
}
</style>
<script>
$(document).ready(function(){
    // Connect to our node/websockets server
    var socket = io.connect('http://pad.dress4u.hk:3000', {query:'album_id=<?php echo $model->id;?>'});
    
    var eventID = <?php echo $model->id;?>;

	
	/* socket.emit('sent eventID', {eventID: eventID}, function(data) {
		
		
		var content = "";
		console.log(data);
		
		//data.images.reverse(); 
        $.each(data, function(i, row){
        //);
		content += '<div class="card"><a href="<?php echo Yii::app()->request->baseUrl.DIRECTORY_SEPARATOR;?>images/album/<?php echo $model->id;?>/'+row+'" download><img src="<?php echo Yii::app()->request->baseUrl.DIRECTORY_SEPARATOR;?>images/album/<?php echo $model->id;?>/thumb/thumb_'+row+'" class="img-thumbnail img-responsive" /></a></div>';
		//$('#imageArea').waterfall('prepend', $(content));
		
		
        });
        //$(content).appendTo('#imageArea');
        //$('#imageArea').waterfall('reLayout');
		$('#imageArea').prepend(content);
		 $('.card').imagesLoaded( function(){
			$('#imageArea').waterfall(); 
		 });
	}); */

	//	console.log(data);
	
    // Initial set of notes, loop through and add to list
    socket.on('init images', function(data){
	
	if(data.folder==<?php echo $model->id;?>){
		//var content = "";
		//console.log(data.images);
		var collection = data.collection;
		var res = collection.replace(/\s/g, '_');
		//data.images.reverse(); 
        $.each(data.images, function(i, row){
        var url = '<?php echo Yii::app()->request->baseUrl.DIRECTORY_SEPARATOR;?>images/album/<?php echo $model->id;?>/'+data.collection+'/'+row;
		var content = '<div class="card mix '+res+'"><div class="thumbnail"><a target="_blank" class="imgClick" model="<?php echo $model->id?>" collection="'+collection+'" path="'+url+'" href="<?php echo Yii::app()->request->baseUrl.DIRECTORY_SEPARATOR;?>images/album/<?php echo $model->id;?>/'+data.collection+'/org/'+row+'" download><img src="'+url+'" class="img-rounded img-responsive" /></a> <div class="caption"><div class="row"><div class="col-xs-3"><div class="showLike" path="'+url+'"></div></div><div class="col-xs-3 center-block"><div class="showView " path="'+url+'"></div></div><div class="col-xs-6 text-right"><button type="button" class="btn btn-lg btn-info likebtn" model="<?php echo $model->id?>" collection="'+collection+'" path="'+url+'"><span class="glyphicon glyphicon-heart" aria-hidden="true"></span> LIKE</button></div></div></div></div></div>';
		//var url ='/events/resized/150x150/images/album/<?php echo $model->id;?>/'+data.collection+'/'+row;
		//var content = '<div class="card mix '+res+'"><a href="<?php echo Yii::app()->request->baseUrl.DIRECTORY_SEPARATOR;?>images/album/<?php echo $model->id;?>/'+data.collection+'/'+row+'" download><img src="'+url+'" class="img-thumbnail img-responsive" /></a></div>';
		//$('#imageArea').waterfall('prepend', $(content));
		
		if($('img[src="<?php echo Yii::app()->request->baseUrl.DIRECTORY_SEPARATOR;?>images/album/<?php echo $model->id;?>/'+data.collection+'/'+row+ '" ]').length == 0){
		$('#imageArea').mixItUp('prepend', $(content));
		}
		
		
        });
        //$(content).appendTo('#imageArea');
        //$('#imageArea').waterfall('reLayout');
		//$('#imageArea').prepend(content);
		 
		//$('.card').imagesLoaded( function(){
			//$('#imageArea').waterfall(); 
			
	 
		 
		}
		

	
    });
      var content1 = '<button class="filter btn btn-primary" type="button" data-filter=".mix">All</button>';
  $('#filter_btn').prepend(content1);
socket.on('cat button', function(data){	
var collect = data.catbutton;
var res1 = collect.replace(/\s/g, '_');
if ($('.filter[data-filter=".' + res1 + '" ]').length == 0 && data.folder==<?php echo $model->id;?>){
	var newcollection = collect.replace(/_/g,' ');
  var content1 = '<button class="filter btn btn-primary" type="button" data-filter=".'+res1+'">'+newcollection+'</button>';
  $('#filter_btn').prepend(content1);
  $('#imageArea').mixItUp('filter', '.'+res1);
}


		 });
		 
socket.on('cat button del', function(data){

		if(data.folder==<?php echo $model->id;?>){
			var collect = data.catbutton;
			var res1 = collect.replace(/\s/g, '_');
		//if ($('.filter[data-filter=".' + res1 + '" ]').length != 0){
			$('.filter[data-filter=".' + res1 + '" ]').remove();
		//}
		}
		//$('#imageArea').mixItUp('filter', '.all');
		//$('#imageArea').waterfall(); 
		$('#imageArea').mixItUp('filter', '.mix', function(state){
	// callback function
	$('#imageArea').waterfall();
});
    
	
    });		 
		 
	socket.on('delete images', function(data){
		if(data.folder==<?php echo $model->id;?>){
		$('img').each(function(i, row){
			//var url = '/events/resized/150x150/images/album/<?php echo $model->id;?>/'+data.collection+'/'+row;
		if($(row).attr('src') == '<?php echo Yii::app()->request->baseUrl.DIRECTORY_SEPARATOR;?>images/album/<?php echo $model->id;?>/'+data.collection+'/'+data.images){
				$(row).parent().parent().parent().remove();
		}
		
		});
		//$('#imageArea').waterfall(); 
		//$('#imageArea').mixItUp(); 
		$('#imageArea').waterfall(); 
		}
    
	
    });
	
$('#imageArea').on('mixEnd', function(e, state){
    console.log(state.totalShow+' elements match the current filter');
	//$('#imageArea').mixItUp(); 
	//$('#imageArea').waterfall(); 
	
	
});
  
$('#imageArea').mixItUp({
	controls: {
		live: true,
		
	},
	animation: {
		animateResizeTargets: true
	}
});


/* like count*/
$("body").on('click', '.likebtn', function() {
	var imagePath = $(this).attr('path');
	var collection = $(this).attr('collection');
	var eventID = $(this).attr('model');
	socket.emit('click like', {imagePath:imagePath, eventID:eventID, collection:collection});
});
$("body").on('click', '.imgClick', function() {
	var imagePath = $(this).attr('path');
	var collection = $(this).attr('collection');
	var eventID = $(this).attr('model');
	socket.emit('click view', {imagePath:imagePath, eventID:eventID, collection:collection});
	//console.log(imagePath);
});

socket.on('show like', function(data){
		//if(data.eventID==<?php echo $model->id;?>){
		$('.showLike[path="'+data.path+'"]').html('<button type="button" class="btn btn-default btn-lg"><span class="glyphicon glyphicon-heart" aria-hidden="true"></span> '+data.likecount+'</button>');

	
		//}
	//console.log(data.path);
	//console.log(data.likecount);
});
socket.on('show view', function(data){

		$('.showView[path="'+data.path+'"]').html('<button type="button" class="btn btn-default btn-lg"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> '+data.likecount+'</button>');
	
		//}
	//console.log(data.path);
	//console.log(data.likecount);
});
/* var $container = $('#imageArea');

if(!$container.mixItUp('isLoaded')){

    $container.mixItUp();
	
} */


});
</script>