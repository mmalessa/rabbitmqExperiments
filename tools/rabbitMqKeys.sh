#!/bin/bash

# config
BASE_DIR=`pwd`"/mycerts"
RABBITMQ_ENV_DIR="/etc/rabbitmq"
RABBITMQ_CRT_DIR="/etc/rabbitmq/ssl"

DEFAULT_BITS=4096

CA_DIR="cadir"
CA_DEFAULT_DAYS=3650
CA_CN="PmCA"

SERVER_CN="PmServer"

# end config

echo "BASE_DIR="$BASE_DIR
if ! test -d $BASE_DIR; then
    mkdir -p $BASE_DIR
fi

create_ca() {
    echo "Create CA"
    if test -d $BASE_DIR/$CA_DIR; then
        echo "ERROR: /$CA_DIR exists"
	return
    fi
    mkdir -p $BASE_DIR/$CA_DIR
    mkdir -p $BASE_DIR/$CA_DIR/certs
    mkdir -p $BASE_DIR/$CA_DIR/private
    chmod 700 $BASE_DIR/$CA_DIR/private
    echo 01 > $BASE_DIR/$CA_DIR/serial
    touch $BASE_DIR/$CA_DIR/index.txt

cat > $BASE_DIR/$CA_DIR/openssl.cnf <<END
[ ca ]
default_ca = ${CA_DIR}

[ ${CA_DIR} ]
dir = .
certificate = \$dir/cacert.pem
database = \$dir/index.txt
new_certs_dir = \$dir/certs
private_key = \$dir/private/cakey.pem
serial = \$dir/serial

default_crl_days = 7
default_days = ${CA_DEFAULT_DAYS}
default_md = sha256

policy = ${CA_DIR}_policy
x509_extensions = certificate_extensions

[ ${CA_DIR}_policy ]
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
END
    cd $BASE_DIR/$CA_DIR
    # Now we can generate the key and certificates that our test Certificate Authority will use. Still within the testca directory:
    openssl req -x509 -config openssl.cnf -newkey rsa:$DEFAULT_BITS -days $CA_DEFAULT_DAYS -out cacert.pem -outform PEM -subj /CN=$CA_CN/ -nodes
}

create_server_key(){
    # The process for creating server certificates
    echo "Create server key"
    if ! test -d $BASE_DIR/$CA_DIR; then
        echo "ERROR: /$CA_DIR doesn't exists"
	return
    fi
    if ! test -d $BASE_DIR/server; then
        mkdir -p $BASE_DIR/server
    fi
    cd $BASE_DIR/server
    openssl genrsa -out key.pem $DEFAULT_BITS
    openssl req -new -key key.pem -out req.pem -outform PEM -subj /CN=$SERVER_CN/O=server/ -nodes
    cd ../$CA_DIR
    openssl ca -config openssl.cnf -in ../server/req.pem -out ../server/cert.pem -notext -batch -extensions server_ca_extensions
}

create_client_key(){
    CLIENT_NAME=$1
    CLIENT_DIR="client-"$CLIENT_NAME
    echo "Create Client "$CLIENT_NAME
    if test -d $BASE_DIR/$CLIENT_DIR; then
        echo "ERROR: /$CLIENT_DIR exists"
	return
    fi
    mkdir -p $BASE_DIR/$CLIENT_DIR

    cd $BASE_DIR/$CLIENT_DIR
    openssl genrsa -out key.pem $DEFAULT_BITS
    openssl req -new -key key.pem -out req.pem -outform PEM -subj /CN=$CLIENT_NAME/O=client/ -nodes
    cd ../$CA_DIR
    openssl ca -config openssl.cnf -in ../$CLIENT_DIR/req.pem -out ../$CLIENT_DIR/cert.pem -notext -batch -extensions client_ca_extensions
}
case "$1" in
    create-ca)
	create_ca
	;;
    create-server)
        create_server_key
        ;;
    create-client)
	    create_client_key $2
esac

exit 0;       

