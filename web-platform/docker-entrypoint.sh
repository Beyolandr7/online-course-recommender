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

# Only seed if the courses table is empty (avoids re-seeding on every restart)
COURSE_COUNT=$(php artisan tinker --execute="echo \App\Models\Course::count();" 2>/dev/null | tr -d '[:space:]')
if [ "$COURSE_COUNT" = "0" ] || [ -z "$COURSE_COUNT" ]; then
    echo "==> Seeding course dataset (first boot)..."
    php artisan db:seed --force
else
    echo "==> Courses already seeded ($COURSE_COUNT rows), skipping."
fi

echo "==> Starting Apache..."
exec apache2-foreground
