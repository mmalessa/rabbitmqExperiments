#!/bin/bash
openssl s_client -connect localhost:5671 -cert client/cert.pem -key client/key.pem   -CAfile testca/cacert.pem
