#!/bin/bash

SECRETPASSWORD='mySecretPassword'

#mkdir ssl
#cd ssl
mkdir testca
cd testca
mkdir certs private
chmod 700 private
echo 01 > serial
touch index.txt

cat > openssl.cnf <<'endmsg'
[ ca ]
default_ca = testca

[ testca ]
dir = .
certificate = $dir/cacert.pem
database = $dir/index.txt
new_certs_dir = $dir/certs
private_key = $dir/private/cakey.pem
serial = $dir/serial

default_crl_days = 7
default_days = 365
default_md = sha256

policy = testca_policy
x509_extensions = certificate_extensions

[ testca_policy ]
commonName = supplied
stateOrProvinceName = optional
countryName = optional
emailAddress = optional
organizationName = optional
organizationalUnitName = optional
domainComponent = optional

[ certificate_extensions ]
basicConstraints = CA:false

[ req ]
default_bits = 2048
default_keyfile = ./private/cakey.pem
default_md = sha256
prompt = yes
distinguished_name = root_ca_distinguished_name
x509_extensions = root_ca_extensions

[ root_ca_distinguished_name ]
commonName = hostname

[ root_ca_extensions ]
basicConstraints = CA:true
keyUsage = keyCertSign, cRLSign

[ client_ca_extensions ]
basicConstraints = CA:false
keyUsage = digitalSignature,keyEncipherment
extendedKeyUsage = 1.3.6.1.5.5.7.3.2

[ server_ca_extensions ]
basicConstraints = CA:false
keyUsage = digitalSignature,keyEncipherment
extendedKeyUsage = 1.3.6.1.5.5.7.3.1
endmsg

# Now we can generate the key and certificates that our test Certificate Authority will use. Still within the testca directory: 
openssl req -x509 -config openssl.cnf -newkey rsa:2048 -days 3650 -out cacert.pem -outform PEM -subj /CN=MyTestCA/ -nodes
#openssl x509 -in cacert.pem -out cacert.cer -outform DER
cd ..

# The process for creating server certificates
echo "SERVER"
mkdir server
cd server
openssl genrsa -out key.pem 2048
openssl req -new -key key.pem -out req.pem -outform PEM -subj /CN=$(hostname)/O=server/ -nodes
cd ../testca
openssl ca -config openssl.cnf -in ../server/req.pem -out ../server/cert.pem -notext -batch -extensions server_ca_extensions
#cd ../server
#openssl pkcs12 -export -out keycert.p12 -in cert.pem -inkey key.pem -passout pass:$SECRETPASSWORD
cd ..

echo "CLIENT"
mkdir client
cd client
openssl genrsa -out key.pem 2048
openssl req -new -key key.pem -out req.pem -outform PEM -subj /CN=$(hostname)/O=client/ -nodes
cd ../testca
openssl ca -config openssl.cnf -in ../client/req.pem -out ../client/cert.pem -notext -batch -extensions client_ca_extensions
#cd ../client
#openssl pkcs12 -export -out keycert.p12 -in cert.pem -inkey key.pem -passout pass:$SECRETPASSWORD
cd ..

echo "THE END"

