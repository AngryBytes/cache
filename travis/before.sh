#!/bin/bash
pecl install -f memcached-2.0.1
echo "extension=memcached.so" >> `php --ini | grep "Loaded Configuration" | sed -e "s|.*:\s*||"`
