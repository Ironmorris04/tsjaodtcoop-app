#!/bin/sh
set -e

MAX_TRIES=20
TRY=1

echo "Checking database availability..."

if [ -z "$DB_DATABASE" ]; then
  echo "⚠️ DB_DATABASE not set, skipping DB creation"
  exec "$@"
fi

while [ $TRY -le $MAX_TRIES ]; do
  if mysql -h"$DB_HOST" -u"$DB_USERNAME" -p"$DB_PASSWORD" -e "SELECT 1;" >/dev/null 2>&1; then
    echo "Database reachable"

    echo "Ensuring database: $DB_DATABASE"
    mysql -h"$DB_HOST" -u"$DB_USERNAME" -p"$DB_PASSWORD" <<EOF
CREATE DATABASE IF NOT EXISTS \`$DB_DATABASE\`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
EOF

    echo "Database ensured"
    break
  fi

  echo "Waiting for database... ($TRY/$MAX_TRIES)"
  TRY=$((TRY+1))
  sleep 2
done

if [ $TRY -gt $MAX_TRIES ]; then
  echo "⚠️ Database not reachable, continuing without DB creation"
fi

exec "$@"
