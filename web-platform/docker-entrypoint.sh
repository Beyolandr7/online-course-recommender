#!/bin/bash
set -e

echo "==> Clearing cached config (injecting Render env vars)..."
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo "==> Re-caching with live environment..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "==> Running database migrations..."
php artisan migrate --force

# Only seed if the courses table is empty or incomplete (total expected is 62154)
COURSE_COUNT=$(php artisan tinker --execute="echo \App\Models\Course::count();" 2>/dev/null | tr -d '[:space:]')
if [ -z "$COURSE_COUNT" ] || [ "$COURSE_COUNT" -lt "60000" ]; then
    echo "==> Seeding course dataset (first boot or incomplete: $COURSE_COUNT rows)..."
    php artisan db:seed --force
else
    echo "==> Courses already fully seeded ($COURSE_COUNT rows), skipping."
fi

echo "==> Starting Apache..."
exec apache2-foreground
