chown -R 1000:1000 * .;

chown -R 33:33 storage;

php artisan config:cache;

php artisan cache:clear;

php artisan view:clear;

php artisan route:clear;