chokidar = require('chokidar');
var fs = require('fs');
var http = require('http');
var filename = require("path");
var gm = require('gm');
// Let¡¦s make node/socketio listen on port 3000
var mysql = require('mysql')
// Let’s make node/socketio listen on port 3000
var io = require('socket.io').listen(3000)
// Define our db creds
var db = mysql.createConnection({
    host: 'localhost',
    user: 'root',
	password: '23602312',
    database: 'FYPPROJECT',
    insecureAuth: true
})
// do this once
var pool = mysql.createPool( {
    host: 'localhost',
    user: 'root',
	password: '23602312',
    database: 'FYPPROJECT',
    insecureAuth: true
} );

// then, just before you need a connection do this
pool.getConnection(function(err, connection) {
    if (err) throw err;

db.connect(function(err){
    if (err) console.log(err)
});

    connection.release();
});
/*
var watcher = chokidar.watch('/home/www/pad/album', {
  ignored: /[\/\\]\./, persistent: true
});
*/

var log = console.log.bind(console);
var images = [];
var isInit = false;
var eventID;
io.sockets.on('connection', function(socket){
	
	//console.log(socket.handshake.query.album_id);
	socket.on('sent eventID', function(data, cb){
        // New note added, push to all sockets and insert into db
		console.log(data);
        eventID = data.eventID;
		socket.emit('re eventID', eventID);
        //console.log(eventID);
		cb({images: data.eventID});
})




socket.on('click like', function(data){
        // New note added, push to all sockets and insert into db
        //notes.push(data)
        
        // Use node's db injection format to filter incoming data
        db.query('INSERT INTO LikeRecord (imagePath, eventID, collection) VALUES (?, ?, ?)', [data.imagePath, data.eventID, data.collection])
        
        db.query('SELECT count(*) as total, imagePath, eventID FROM LikeRecord where imagePath = ?', [data.imagePath])
            .on('result', function(data1){
                // Push results onto the notes array
                //notes.push(data)
                io.sockets.emit('show like', {path:data1.imagePath, likecount: data1.total, eventID:eventID})
                console.log(data1); 

            });
             
    db.query('select count(*) as total, imagePath, eventID, collection from LikeRecord where eventID = ? group by imagePath order by total DESC limit 1', [data.eventID])
            .on('result', function(data2){
                // Push results onto the notes array
                //notes.push(data)
                io.sockets.emit('get hottest', {path:data2.imagePath, total: data2.total, folder:data2.eventID})
                console.log(data2); 

            });
             
       
    });


socket.on('click view', function(data){
        // New note added, push to all sockets and insert into db
        //notes.push(data)
        
        // Use node's db injection format to filter incoming data
        db.query('INSERT INTO ViewRecord (imagePath, eventID, collection) VALUES (?, ?, ?)', [data.imagePath, data.eventID, data.collection])
        
        db.query('SELECT count(*) as total, imagePath, eventID FROM ViewRecord where imagePath = ?', [data.imagePath])
            .on('result', function(data1){
                // Push results onto the notes array
                //notes.push(data)
                io.sockets.emit('show view', {path:data1.imagePath, likecount: data1.total, eventID:eventID})
                console.log(data1); 

            });
             
       //
       
    });

socket.on('get hot', function(data){
   db.query('select count(*) as total, imagePath, eventID, collection from LikeRecord where eventID = ? group by imagePath order by total DESC limit 1', [data.eventID])
            .on('result', function(data2){
                // Push results onto the notes array
                //notes.push(data)
                io.sockets.emit('get hottest', {path:data2.imagePath, total: data2.total, folder:data2.eventID})
                console.log(data2); 

            });
             
 });
 
chokidar.watch('/home/www/pad/album/', {
	ignored: "/home/www/pad/album/*/*/org/*", 
	persistent: true,
	alwaysStat:true,
	awaitWriteFinish: {
    stabilityThreshold: 2000,
    pollInterval: 100
  }
  })

  .on('add', function(path) {
	  var thumb = "thumb_"+filename.basename(path);
	  var myarr = path.split("/");
	  var folder = myarr[5];
	  var collection = myarr[6];
	  var thumb_path = "/home/www/pad/album/"+folder+"/"+collection+"/thumb/";
	  var imagePath = "/images/album/"+folder+"/"+collection+"/"+filename.basename(path);

/* if (!fs.existsSync(thumb_path+thumb)) {
	gm(path).resize(200,200).write(thumb_path+thumb, function (err) {
	socket.emit('init images', {images: [filename.basename(path)], path: path,folder:folder,collection:collection});
	});
}else{
	socket.emit('init images', {images: [filename.basename(path)], path: path,folder:folder,collection:collection});
} */

 db.query('SELECT count(*) as total, imagePath, eventID FROM LikeRecord where imagePath = ?', [imagePath])
            .on('result', function(data1){
                // Push results onto the notes array
                //notes.push(data)
                socket.emit('init images', {images: [filename.basename(path)], path: path,folder:folder,collection:collection});
                io.sockets.emit('show like', {path:data1.imagePath, likecount: data1.total, eventID:eventID})
                //console.log(data1); 

            })
            .on('end', function(){
                // Only emit notes after query has been completed
                //socket.emit('initial notes', notes)
             })
             
 db.query('SELECT count(*) as total, imagePath, eventID FROM ViewRecord where imagePath = ?', [imagePath])
            .on('result', function(data1){
                // Push results onto the notes array
                //notes.push(data)
                io.sockets.emit('show view', {path:data1.imagePath, likecount: data1.total, eventID:eventID})
                console.log(data1); 

            })
            .on('end', function(){
                // Only emit notes after query has been completed
                //socket.emit('initial notes', notes)
             })
             
              


console.log('Found file'); 
images = [];
});







chokidar.watch('/home/www/pad/album/', {ignored: /[\/\\]\./, persistent: true,awaitWriteFinish: {
    stabilityThreshold: 2000,
    pollInterval: 100
  }}).on('unlink', function(path) {
	  
	   var thumb = "thumb_"+filename.basename(path);
	  var myarr = path.split("/");
	  var folder = myarr[5];
	 var collection = myarr[6];
	  var thumb_path = "/home/www/pad/album/"+folder+"/"+collection+"/thumb/";
  	//socket.emit('init images', {images: images, path: path,folder:folder});
 //socket.emit('delete images', filename.basename(path));
 socket.emit('delete images',  {images: filename.basename(path), path: path, folder:folder,collection:collection});
 //fs.unlinkSync(thumb_path+thumb);
  
});  


chokidar.watch('/home/www/pad/album/', {
	ignored: "/home/www/pad/album/*/*/org/*", 
	persistent: true,
	awaitWriteFinish: {
    stabilityThreshold: 2000,
    pollInterval: 100
  }
  })
  .on('addDir', function(path) { 
	//log('Directory', path, 'has been added'); 
	var myarr = path.split("/");
	  var folder = myarr[5];
	  var collection = myarr[6];
	  if(collection){
	socket.emit('cat button', {catbutton:collection,folder:folder});
	console.log(path);
	}
	
})
.on('unlinkDir', function(path) { 
	var myarr = path.split("/");
	  var folder = myarr[5];
	  var collection = myarr[6];
	  if(collection){
	socket.emit('cat button del', {catbutton:collection,folder:folder});
	  }
});


});
/* chokidar.watch('/home/www/pad/album', {ignored: /[\/\\]\./}).on('all', function(event, path) {
  console.log(event, path);
  //io.sockets.emit('users connected', socketCount);
}); */