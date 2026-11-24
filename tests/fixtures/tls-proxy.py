#!/usr/bin/env python3
"""TLS front for the fixture server, which `php -S` can only serve over http.

IndieAuth server metadata needs an https issuer, so tests/run.sh runs this in
front of the fixture server with a certificate it generates for each run.

  tls-proxy.py <listen-port> <target-port> <cert.pem> <key.pem>
"""
import socket, ssl, sys, threading

listen_port, target_port, cert, key = int(sys.argv[1]), int(sys.argv[2]), sys.argv[3], sys.argv[4]


def pipe(src, dst):
    try:
        while data := src.recv(65536):
            dst.sendall(data)
    except OSError:
        pass


def requests(client, upstream):
    pipe(client, upstream)
    try:
        upstream.shutdown(socket.SHUT_WR)
    except OSError:
        pass


def handle(raw):
    try:
        client = context.wrap_socket(raw, server_side=True)
        upstream = socket.create_connection(('127.0.0.1', target_port))
    except OSError:
        raw.close()
        return
    threading.Thread(target=requests, args=(client, upstream), daemon=True).start()
    pipe(upstream, client)
    # End the TLS session properly: curl treats a bare TCP close as an error
    try:
        client.unwrap()
    except OSError:
        pass
    client.close()
    upstream.close()


context = ssl.SSLContext(ssl.PROTOCOL_TLS_SERVER)
context.load_cert_chain(cert, key)
server = socket.create_server(('127.0.0.1', listen_port))
while True:
    conn, _ = server.accept()
    threading.Thread(target=handle, args=(conn,), daemon=True).start()
