#!/bin/sh
set -e
echo "DEBUG: environment PORT=${PORT}"
echo "Running entrypoint..."

# Generate key if not exists
# php artisan key:generate --force

# Wait until DB is ready
#until php -r "new PDO('mysql:host=${MYSQLHOST};dbname=${MYSQLDATABASE}', '${MYSQLUSER}', '${MYSQLPASSWORD}');" >/dev/null 2>&1; do
#    echo "Waiting for database... Host : "${MYSQLHOST}"--DB : "${MYSQLDATABASE}"--Pass"${MYSQLPASSWORD}""
#    sleep 2
#done

# echo "Database is ready!"

# Set application key if missing
# if [ -z "$APP_KEY" ]; then
#     echo "APP_KEY not found, generating..."
#     php artisan key:generate
# fi

# drop all table

if [ -n "$RAILWAY_STATIC_URL" ]; then
  echo "Using Railway static URL: $RAILWAY_STATIC_URL"
  export APP_URL=$RAILWAY_STATIC_URL
else
  echo "RAILWAY_STATIC_URL not found, using default http://localhost"
  export APP_URL=http://localhost
fi

php artisan db:wipe --force

echo "Running migrations..."
php artisan optimize:clear || true
php artisan config:cache || true
php artisan migrate --force || echo "Migration failed, continuing..."
php artisan up || true
echo "Migration End..."

echo "Starting Laravel on port ${PORT:-8080} ..."
exec php -S 0.0.0.0:${PORT} -t public

# Start Laravel server on Railway-provided port
# PORT=${PORT:-9000}
# echo "Starting Laravel on port $PORT"
# exec php -S 0.0.0.0:${PORT:-9000} -t public

#PORT=${PORT:-8080}
#echo "Starting Laravel on port $PORT"
#CMD ["sh", "-c", "php -S 0.0.0.0:${PORT:-8080} -t public"]
# php -S 0.0.0.0:$PORT -t public
# php artisan serve --host=0.0.0.0 --port=$PORT
# php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
