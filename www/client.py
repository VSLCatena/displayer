# server.py
import socket
import time
import datetime
# create a socket object
serversocket = socket.socket(
 socket.AF_INET, socket.SOCK_STREAM)

# get local machine name
# host = socket.gethostname()
host = '127.0.0.1'
port = 25003
# bind to the port

# Client
name = "screenly"
asset_uri = raw_input("uri?")
serversocket.connect((host, port))
currentTime = time.ctime(time.time())
ts = datetime.datetime.utcnow()

clientdata = {"name": name, "time": currentTime, "asset_uri": asset_uri}

serversocket.sendall(str(clientdata))
serverdata = serversocket.recv(1024)
serversocket.close()
print("{} Connecting to {}. Sending {}".format(str(ts), str(host), str(clientdata['asset_uri'])))
