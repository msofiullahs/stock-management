<h1 align="center">Stock Management</h1>

## About Stock Management
This is application for simple stock management, please feel free to contact me on sofiullah.work@gmail.com for modification or customization. This application is built using Laravel 10 and Jetstream-Inertia. Please follow Laravel's requirement, before starting installation.

## Preview
<img src="https://drive.google.com/uc?export=view&id=1M34oby8Yjb-5_mUymU8xzm8Pz2DjAOJP" />
<img src="https://drive.google.com/uc?export=view&id=1Eff8Di4Pqx8T68QU-1WtEFB2RLeTc0zi" />
<img src="https://drive.google.com/uc?export=view&id=135rNrVRX290QyV2BMBSm89qsLEASyUsZ" />
<img src="https://drive.google.com/uc?export=view&id=1zpf_77GiFtyQMKQWlU4ZLUVel3RnmPNx" />
<img src="https://drive.google.com/uc?export=view&id=1oarXwDyAwPgG4RuDAJHTKZgrxyoAmEAY" />
<img src="https://drive.google.com/uc?export=view&id=14CUJlHOSbMrwx8At63ch_6gjOXzYwXbn" />

## Installation
- Clone this project
- `cd /path-to-this-project`
- `cp .env-example .env`
- You must update value on `.env` file
    - APP_NAME=
    - APP_TIMEZONE= #--- please follow TZ Identifier [here](https://en.wikipedia.org/wiki/List_of_tz_database_time_zones)
    - ENABLE_2FA=true #--- true or false, make it true if you want to force enabling 2fa for all users
    - ADMIN_EMAIL="admin<span>@</span>mail<span>.</span>com" #--- this email will be used for seeder on first user
    - ADMIN_PASSWORD="yourpassword" #--- this password will be used for seeder on first user
    - APP_CURRENCY="IDR" #--- use your currency
    - DECIMAL_SEPARATOR="," #--- either comma or dot
    - THOUSAND_SEPARATOR="." #--- either comma or dot
- `php artisan key:generate`
- `composer install`
- `npm install`
- `php artisan migrate`
- `php artisan db:seed`

### Development
- First terminal >> `php artisan serve`
- Second terminal >> `npm run dev`
- `php artisan sample:clear` to remove all sample data

### Production
- `npm run build`
- `php artisan sample:clear` to remove all sample data

## Bring me a cappucino :coffee:

Support me for more application on Paypal [here](https://paypal.me/sofiullahs?country.x=ID&locale.x=id_ID)
