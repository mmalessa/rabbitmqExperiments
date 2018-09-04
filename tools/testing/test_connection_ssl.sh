#!/bin/bash
cd ssl
#openssl s_server -accept 8443 -cert server/cert.pem -key server/key.pem   -CAfile testca/cacert.pem
openssl s_client -connect localhost:5671 -cert client/cert.pem -key client/key.pem   -CAfile testca/cacert.pem
cd -
