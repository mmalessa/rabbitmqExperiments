#!/bin/bash
cd ssl
openssl s_server -accept 8443 -cert server/cert.pem -key server/key.pem   -CAfile testca/cacert.pem
cd -

