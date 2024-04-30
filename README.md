# DCafe API Project
## _oleh: timDev_

[![N|Solid](https://cldup.com/dTxpPi9lDf.thumb.png)](https://nodesource.com/products/nsolid)
<p><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

Membuat CRUD REST API

## Installer
-   `git clone https://github.com/amirullah/Project-DCafe.git nama_folder`
-   `cd nama_folder`
-   `composer install`
-   `cp .env-example .env`
-   `php artisan key:generate`
-   `php artisan migrate`
-   `php artisan db:seed`

Ikuti Langkah Berikut:
- Ubah file .env didalam folder "nama_folder"
    ```sh
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=nama_database_anda
    DB_USERNAME=username_database_anda
    DB_PASSWORD=password_database_anda
    ```
- Selesai, Jalankan `php artisan serve` di command

Domain ---> dcafe.markazvirtual.com
