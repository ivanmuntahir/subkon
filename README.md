How to Setup Properly 
1. clone with gitbash this project to local computer
2. open the folder of your git to code editor (e.g. VS code)
4. copy file of .env.example and rename to .env
5. setup the app_name and database based on your project
6. change the app_url to your port localhost to be able upload file
7. open the VS code terminal
8. run composer install in terminal to install composer in clone project
9. run php artisan migrate to run database migrate if any
10. run composer require filament/filament:"^3.2" -W to install filament
11. run php artisan shield:install to install filament-shield policies
12. run php artisan key:generate to generate key for your app local
13. run php artisan serve to open in localhost
14. run php artisan make:policy YourModelName --model=YourModelName to add filament policy access
