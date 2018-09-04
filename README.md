# README

## Install RabbitMQ on Ubuntu
`sudo apt instal rabbitmq-server`

## Start/stop service

RabbitMQ Server starting: `service rabbitmq-server start`, 

...and stopping: `service rabbitmq-server stop`.

## Users management
```bash
rabbitmqctl add_user adminuser adminpassword
rabbitmqctl set_user_tags adminuser administrator
rabbitmqctl list_users
rabbitmqctl set_permissions -p / adminuser ".*" ".*" ".*"
rabbitmqctl delete_user guest
```

## Enable some plugins

### Management Plugin
`rabbitmq-plugins enable rabbitmq_management`

The Web UI is located at: http://server-name:15672/ (guest/guest)

https://www.rabbitmq.com/rabbitmqctl.8.html

