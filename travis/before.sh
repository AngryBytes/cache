#!/bin/bash
pecl install memcache
echo "extension=memcache.so" >> `php --ini | grep "Loaded Configuration" | sed -e "s|.*:\s*||"`
