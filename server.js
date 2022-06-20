var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);
app.get('/', function (req,res) {
    res.send('burası anasayfa');
});

io.on('connection',(socket)=>{
    socket.on('new_appointment_create',function(){
        console.log("calisti");
        io.emit('admin_appointment_list');
    })
});

/**listen ile server dinleyecez*/
http.listen(3000, function(){
    console.log('server çalışıyor');
});
