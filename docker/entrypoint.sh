#!/bin/sh
set -e

echo "Waiting for database..."

until mysql -h"$DB_HOST" -u"$DB_USERNAME" -p"$DB_PASSWORD" -e "SELECT 1;" >/dev/null 2>&1; do
  sleep 2
done

echo "Database reachable"

mysql -h"$DB_HOST" -u"$DB_USERNAME" -p"$DB_PASSWORD" <<EOF
CREATE DATABASE IF NOT EXISTS \`$DB_DATABASE\`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
EOF

echo "Database ensured"

exec "$@"

