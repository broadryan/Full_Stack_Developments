/*
This is a simple chat server application that allows users to connect, create, and join chat rooms.
The server is built using Node.js with the 'http', 'socket.io', and 'fs' modules.
Users can send messages to each other, switch between rooms, create password-protected rooms, and send direct messages.
Additionally, the server allows for user moderation with kick and ban functionality.
The client-side interface is provided through the 'client.html' file which is served to the user upon connection.
Communication between the client and server occurs through the socket.io library.
Rooms are stored in an array, and user information is stored in various objects.
The server listens on port 3456 for incoming connections.
*/
const http = require("http");
const socketio = require("socket.io");
const fs = require("fs");

const bannedUsers = {};
const users = {};
const socketId = {};
let n = 0;
let inHere = "";
const rooms = [];

const server = http.createServer((req, res) => {
	fs.readFile("client.html", (err, data) => {
		if (err) {
		res.writeHead(500);
		res.end();
		return;
		}
		res.writeHead(200);
		res.end(data);
		});
});

server.listen(3456);
const io = socketio(server);

io.sockets.on("connection", function(socket){

	socket.on('addUser', (username) => {
		const thisUser = {
		name: username,
		room: socket.room
	};
		socketId[username] = socket.id;
		socket.user = username;
		users[username] = thisUser;
		n = Object.keys(users).length;
		inHere = Object.values(users)
		.map(user => user.name)
		.join(', ');
		socket.emit('update', 'Please create or join a room to start chatting.');
		socket.emit('updateRoom', rooms);
	});
	
	socket.on('message_to_server', (data) => {
		console.log(`${socket.user}: ${data.message}`);
		socket.emit("message_to_client", socket.user, {message: data.message});
		socket.to(socket.room).emit("message_to_client", socket.user, {message: data.message});
	});
	
	
	socket.on("switchRoom", (newRoom) => {
		if (bannedUsers[socket.user] === newRoom) {
		socket.emit("update", "You have been banned from this room.");
		return;
	}
		const oldRoom = socket.room;
		socket.leave(oldRoom);
		socket.join(newRoom);
		users[socket.user].room = newRoom;

		let newStr = Object.values(users)
			.filter(user => user.room === newRoom)
			.map(user => user.name)
			.join(', ');

		Object.values(users)
			.filter(user => user.room === oldRoom && user.name !== socket.user)
			.forEach(user => {
				const asdf = inHere.replace(socket.user, "");
				io.in(socket.room).emit('who', asdf);
			});

		socket.emit('update', `You have connected to ${newRoom}`);
		socket.broadcast.to(oldRoom).emit('update', `${socket.user} has left this room`);
		socket.room = newRoom;
		socket.broadcast.to(newRoom).emit('update', `${socket.user} has joined this room`);
		socket.emit('updateRoom', rooms, newRoom);
		io.sockets.in(newRoom).emit('who', newStr);
		});
	
	
	socket.on("addRoom", function(addRoom){
		rooms.push({roomName:addRoom});
		console.log(rooms);
		io.emit('addRoom', rooms, socket.room);
	});
	
	socket.on("kick", function (kickedUser) {
		if (users.hasOwnProperty(kickedUser)) {
		  var kickedSocket = io.sockets.sockets.get(socketId[kickedUser]);
		  if (kickedSocket) {
			kickedSocket.leave(kickedSocket.room);
			users[kickedUser].room = "";
			kickedSocket.emit("kicked", kickedUser, "You have been kicked from the room.");
			socket.broadcast.to(kickedSocket.room).emit("update", kickedUser + " has been kicked from the room.");
			kickedSocket.emit("leaveRoom");
		  }
		}
	  });

	  socket.on("ban", function (bannedUser) {
		if (users.hasOwnProperty(bannedUser)) {
		  var bannedSocket = io.sockets.sockets.get(socketId[bannedUser]);
		  if (bannedSocket) {
			if (!bannedUsers[bannedUser]) {
			  bannedUsers[bannedUser] = [];
			}
			bannedUsers[bannedUser].push(bannedSocket.room);
			bannedSocket.leave(bannedSocket.room);
			users[bannedUser].room = "";
			bannedSocket.emit("banned", bannedUser, "You have been banned from the room.");
			socket.broadcast.to(bannedSocket.room).emit("update", bannedUser + " has been banned from the room.");
			bannedSocket.emit("leaveRoom");
			bannedSocket.emit("updateBannedRooms", bannedUsers[bannedUser]);
			var newName = bannedUser + '_banned';
			users[newName] = users[bannedUser];
			delete users[bannedUser];
			socketId[newName] = socketId[bannedUser];
			delete socketId[bannedUser];
			bannedSocket.user = newName;
			bannedSocket.emit("updateName", newName);
	  
			// Create a new room called "banned" if it doesn't exist.
			if (!rooms.find(room => room.roomName === "banned")) {
			  rooms.push({ roomName: "banned" });
			}
			// Move the banned user to the "banned" room.
			bannedSocket.join("banned");
			users[bannedSocket.user].room = "banned";
			socket.broadcast.to("banned").emit('update', `${bannedSocket.user} has been moved to the banned room`);
		  }
		}
	  });
	  
	  
	
	  
	  
	
	  
	  
	  
	
	socket.on("addWithPw", function(addRoom, pw){
		rooms.push({roomName:addRoom, password: pw});
		console.log(rooms);
		io.emit('addRoom', rooms, socket.room);
	});
	
	socket.on("checkPw", function(newRoom, pwGuess){
		for(var i = 0 ;i<rooms.length; i++){
			if(rooms[i].roomName == newRoom){
				if(rooms[i].password == pwGuess){
					console.log("Were in");
					socket.join(newRoom);
					users[socket.user].room = newRoom;
					console.log("in switch room " + users[socket.user].room);
					var newStr = "";
					for(var thing in users){
						if(users.hasOwnProperty(thing)) {
							if(users[thing].room == newRoom){
								if(n==1){
									newStr = newStr.concat(users[thing].name);
								}
								else{
									newStr = newStr.concat( ", " + users[thing].name);
								}
							}
							else{
								var asdf = inHere.replace(socket.user, "");
								io.in(socket.room).emit('who', asdf);
							}
							console.log("is in this new room" + newStr);
						}
					}
					socket.emit('update', 'you have connected to ' +newRoom);
					socket.broadcast.to(socket.room).emit('update', socket.user + ' has left this room');
					socket.room = newRoom;
					socket.broadcast.to(newRoom).emit('update', socket.user + ' has joined this room');
					socket.emit('updateRoom', rooms, newRoom);
					io.sockets.in(newRoom).emit('who', newStr);
				}
			}
		}
	});
	
	socket.on("dm", function(to, msg){
		var from = socket.user;
		socket.broadcast.to(socketId[to]).emit('whatsup', from, msg);
	});
	
	
	socket.on("privateRoom", function(privateRoom){
		for(var i=0; i<rooms.length; i++){
			console.log(rooms[i].hasOwnProperty('password'));
			if(rooms[i].roomName == privateRoom){
				if(rooms[i].hasOwnProperty('password')){
					socket.emit('tryna', privateRoom);
				}
			}
		}
	});

    socket.on("checkUsername", function(username) {
        if (users.hasOwnProperty(username)) {
          socket.emit("usernameStatus", false);
        } else {
          socket.emit("usernameStatus", username);
        }
      });
      
});
