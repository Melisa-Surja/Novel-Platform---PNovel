php artisan migrate:fresh --seed --force
php artisan passport:install --force
php artisan passport:keys --force

(factory, seed, controller)
php artisan make:model Setting -mfs
php artisan make:controller TagController --resource --model=User
php artisan make:migration create_series_user_table
php artisan make:factory CommentFactory --model=Comment
php artisan db:seed --class=UserSeeder

Future links
https://www.hesk.com/demo/

Website Designer good:
https://www.fiverr.com/share/RrkbK5
Wireframe:
https://www.fiverr.com/share/Apj4ea
Game UI:
https://www.fiverr.com/share/del7Aa

Done:
https://github.com/aFarkas/lazysizes
data-src="" class="lazyload"
https://github.com/creativeorange/gravatar
Gravatar::get('email@email.com')
https://github.com/laravelista/comments?ref=madewithlaravel.com
https://github.com/spatie/laravel-tags

============================================================
local
start redis: 
sudo service redis-server start
https://redislabs.com/blog/redis-on-windows-10/ 

============================================================
TODO
============================================================
✓ Implement design
✓ Create new role, create new permission
✓ Timezone fix (change to Asia/Shanghai)
Documentation - models,controllers,route documentation AT LEAST https://github.com/saleem-hadad/larecipe
Notification and Cache Problems, show bell on HeaderProfile.vue when notification is fixed
Notification events when chapter is published
Cache Series - Data, Chapter List for Series (the chapters)
Cache Chapter Content
✓ Scheduling / Publishing system
Scheduling Notification system - make it a trait instead. Make a table just for publishing schedule.
google analytics
Cover BG on Chapters

Legal documents for novel, translator, and editor.
Tell the authors

============================================================
LATER
============================================================
Loading Animation
Social Login
Badges/Achievements System
Font enlarge
Fuckup the font + encryption
Novel Arcs
Bookmarks
LOGO - Mid Dec
SEO - default logo (config/seotools)


============================================================
DEPLOYMENT
============================================================
npm run prod
push git, commit to origin
============================================================
DEPLOYMENT SCRIPT
============================================================
cd /home/ploi/website_folder
git pull origin main
composer install --no-interaction --prefer-dist --optimize-autoloader

php artisan config:clear
php artisan route:cache
php artisan view:clear
php artisan migrate --force
php artisan telescope:prune

echo "" | sudo -S service php7.4-fpm reload

echo "🚀 Application deployed!"


============================================================
SSH
login as ploi
cd website_folder

============================================================
GITHUB failed deployment because of unsaved changes
putty load ploi, login as ploi, SSH to root then
git reset --hard

============================================================
GITHUB STOP TRACKING .env and .env.prod
If you have already added the files to be tracked, you need to remove them from tracking:

git rm .env --cached
git rm .env.prod --cached
git commit -m "Stopped tracking .env"



============================================================
COMPLETED TASKS
============================================================
Sitemap https://packagist.org/packages/spatie/laravel-sitemap 
✓ Upload ploi
✓ Layout Design Document - behance, color scheme
✓ RSS
✓ Email
✓ Redis Cache - Notifications
✓ Quill JS - Image Compressor, Emojis, Translator Notes
✓ Reading List
✓ Notifications - Reading List, New Novel
✓ Comments Reply (Nesting) + Notification
✓ - The novel list to copy
✓ - CG send cron job every 4 hours to check and sync (user, series, chapters)
    {acronym:[chNumSlug]} {DYLM:[1,2,3,4-1,4-2]}
    username/display_name, email, random password gen
    check: fixed password
    send input = hashed password from all added
✓ - REST API for both (POST) all server request, no exposure to client
✓ - chapter content - strip all html tags (also imgs): strip_tags()
✓ - tooltip/popup tn
✓ - Importing process with progress bar
✓ - Test Cron every 4h
✓ Empty Chapter Title fix in Chapter List
✓ Delete comments when novels/chapters are deleted (events?)
✓ Test wrong URL, modify error page
✓ NovelChapter auto delete when Series are deleted: auto delete when parents are deleted, take a look at examples from spatie-tags, media-library, and laravelista/comments// TODO: Delete notifications on comment deletion - look at events fired
✓ Restore, Delete, Delete Permanently on Novel and Chapters
✓ User Profile change password
✓ Tags management