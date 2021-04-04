# PNovel
PNovel is a novel publishing platform web app. 

Made with Laravel and Vue. Redis for cache.

The project was cancelled last year so I'm going to open source the unfinished code.

I'll write more thorough documentation later, feel free to browse the code for now.

# Basic Guide

You can login with super admin role with the info given at login page. All the basic functionality (publish, edit new novels/chapters) should work well. There might be some caching bugs though.

# Installation
Remove the `-seed` code if you're in production mode as it fills your database with dummy data for preview/testing purposes.
```python
php artisan migrate:fresh --seed --force
php artisan passport:install --force
php artisan passport:keys --force
npm install
npm run prod
```

# Update script
```python
php artisan config:clear
php artisan route:cache
php artisan view:clear
php artisan migrate --force
npm run prod
```