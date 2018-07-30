#!/bin/bash
openssl s_client -connect localhost:8443 -cert client/cert.pem -key client/key.pem   -CAfile testca/cacert.pem
