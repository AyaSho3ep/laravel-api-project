const http = require('http');
const io = require('socket.io')(http);
const Redis = require('ioredis');

const redis = new Redis();
redis.subscribe('post.*');

redis.on('message', function (channel, message) {
    console.log(channel, message);
    message = JSON.parse(message);

    io.to(channel).emit(channel, message);
});

const port = process.env.SOCKET_IO_PORT || 3000
