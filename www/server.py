# server.py
import socket 
import time
import datetime
import ast
# create a socket object
serversocket = socket.socket(
 socket.AF_INET, socket.SOCK_STREAM)
host = '127.0.0.1'
port = 25003

# SERVER
serversocket.bind((host, port))
# queue up to 5 requests
serversocket.listen(2)
currentTime = time.ctime(time.time())
serverdata = {"time": currentTime, "asset_uri": "About:blank"}
try:
    while True:
        # establish a connection
        clientsocket,addr = serversocket.accept()
        
        clientdata = clientsocket.recv(1024).decode("ascii")
        clientdata = ast.literal_eval(clientdata)
        if (clientdata['name'] == 'screenly' and str(addr[0]) == '127.0.0.1'):
            serverdata.update({'asset_uri': clientdata['asset_uri']});
            serverdata.update({'time': clientdata['time']});
        ts = datetime.datetime.utcnow()
        print("{} Connection from {} to {}".format(str(ts), clientdata['name'], str(addr)))
        # print serverdata
        clientsocket.send(str(serverdata))
        clientsocket.close()
except Exception as e:
    print e
    serversocket.close()
