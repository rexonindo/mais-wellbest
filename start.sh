#!/bin/sh
# Fallback port 9000 if $PORT is not set
PORT=${PORT:-9000}
echo "Starting Laravel on port $PORT"
php -S 127.0.0.1:$PORT -t public
