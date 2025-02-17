#!/usr/bin/env sh


php -S localhost:8080 -t . \
  -d xdebug.mode=debug \
  -d xdebug.start_with_request=yes \
  -d xdebug.client_host=172.19.32.1 \
  -d xdebug.client_port=9999 \
  -d xdebug.log=/tmp/xdebug.log \
  -d zend_extension=xdebug.so \
  -d xdebug.discover_client_host=true
