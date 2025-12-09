# H-Simple-Web-Realtime-Chat
A simple Web Chat application with sample codes for client and server side. This sample has been developed using JavaScript WebSocket client API and PHP Ratchet WebSocket Server library.

# The Configurations
## 1. JavaScript Client
For the client side, the configuration is simple. Open `index.html` and edit the `ip` and `port` variable (point to your websocket server). If there is no problem with the connection to the server, then you can start login with your username and room id (any id). Later then, connect with your friends or other client with the same room id but different username. Username are used as user id and must be unique.

## 2. PHP WebSocket server
This sample uses HPFv3.2 (my mini PHP framework) and actually for websocket server development does not really need any PHP framework (depend on your needs). But the important thing is, the PHP Ratchet library, which is the actual library uses by many PHP developers in developing socket base software. You can download PHP Rathet library and run it without any other PHP Framework.

1. Open folder `server/HPFv3.2/Apps/ratchet`, you will see two files; `App.php`, `configure.json`.
    `App.php` is where the websocket code logic is, and the configuration like ip address, port and ssl path is inside the `configure.json`.

2. Open your terminal/command prompt (with installed `php` command), then change directory to server folder (`cd /path/to/server/`) and then run command `php index.php`

If the teminal/command prompt shows `Server Started` it means the websocket server is running. You can open your server ip address and port number in the browser and see a blank page. (eg, `http://192.168.0.144:45100`). If the page returns fail connecting or other error page, that mean there is something wrong with the websocket codes. 


## 3. WebSocket Server with SSL
You can set the SSL cert and key file inside the `configure.json` file, and then comment & uncomment the codes inside the `App.php`. (at the end of file)

# The Security
This is a simplest that I can do and its not too secure. If you understand these codes, you will find that anyone can join your rooms or taking your IDs without any server side validation. Most of the time, developers make the validation on server side as the client connected to the websocket server, simple word, on `onOpen()` method in `App.php`. As the connection receives the user id (for more secure should use tokens), so the server can validate does the user exists and allows to join the rooms and also check if the rooms is exists.

# Languages Suppot?
This sample I use `base64` encoding to encode the chars. As you can see inside the `index.html` I put the custom `base64` encode (I took it from Mozilla Docs) to make sure that all character in different languages can be encoded and decoded so it will not break the JavaScript codes.
