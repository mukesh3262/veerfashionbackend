# Basecode

<p align="center">
    <img src="https://img.shields.io/static/v1?label=php&message=%E2%89%A58.2&color=7A86B8&logo=php" alt="PHP Version">
    <img src="https://img.shields.io/static/v1?label=laravel&message=%E2%89%A511.31&color=F32E21&logo=laravel" alt="Laravel Version">
    <img src="https://img.shields.io/static/v1?label=node&message=%E2%89%A522.3&color=339933&logo=node.js" alt="Node.js Version">
    <img src="https://img.shields.io/static/v1?label=inertia&message=%E2%89%A52.0&color=9553E9&logo=Inertia&logoColor=white" alt="Inertia.js Version">
    <img src="https://img.shields.io/static/v1?label=react&message=%E2%89%A519.0&color=61DAFB&logo=react" alt="React.js Version">
</p>

## Made By Space-O Technologies

## Index

1. [Introduction](#introduction)
2. [Prerequisites](#prerequisites)
3. [Installation](#installation)
4. [Push Notification](#push-notification)
5. [Notes](#notes)

### Introduction

- Supports latest version of laravel i.e. Laravel 11 along with PHP 8.2. :zap:
- Authentication APIs are introduced to boost the development speed. :closed_lock_with_key:
- Swagger integration available to prepare APIs documentation. :nail_care:
- Swagger docummentation is ready to use for all the pre-implemented APIs. :sunglasses:
- Admin Panel introduced along with forgot & reset password. :european_castle:
- Update password feature in Admin Panel has been introduced. :eyes:
- In Admin Panel, "Content Pages" module CRUD has been added to deal with static pages directly in website. :bookmark_tabs:
- Localization mechanism in REST APIs are implemented to leverage the multi language support. :books:
- Country, State & Cities data seeder has been added.  :earth_asia:
- Sub Admins along with Roles & Permission feature has been added. :construction:

### Prerequisites

| **Plugin** | **Version**|
| ------ | ------ |
| PHP | ^8.2 |
| MySQL | ~8.0 |
| Laravel | ^11.31 |
| Node | ^22.3.0 |
| InertiaJS | ~2.0 |
| ReactJS | ~19.0 |

### Installation

##### Create .env file

```sh
cp .env.example .env
```
> ##### 1. Update .env details as following:

```sh
DB_DATABASE=DATABASE_NAME
DB_USERNAME=DATABASE_USER
DB_PASSWORD=DATABASE_PASSWORD
```
> ##### 2. Setup The Project

```sh
composer setup
```

> ##### 3. Run seeders to add some data to kickstart

```sh
php artisan db:seed --class=RolesTableSeeder # If you're willing to add all roles data then run following command
php artisan db:seed --class=AdminsTableSeeder # If you're willing to add all admins data then run following command
php artisan db:seed --class=SettingsTableSeeder # If you're willing to add all settings data then run following command
php artisan db:seed --class=ContentPagesTableSeeder # If you're willing to add all content pages data then run following command
php artisan db:seed --class=CountriesTableSeeder # If you're willing to add all countries alongs with states & cities then run following command
php artisan db:seed --class=UsersTableSeeder # If you're willing to add all users data then run following command
```

> ##### 4. Following accounts will be available @ ``/admin/login``

##### For client use

```html
admin@example.com
admin@spaceo
```

##### For developer use

```html
developer@example.com
developer@spaceo
```

###### 5. Swagger UI will be available @ ``/api/v1/documentation``


###### 6. To run admin panel in local server

```sh
npm run dev
```

###### 7. To create build in dev and production server

```sh
npm run build
```

### Push Notification
> #### Enable firebase push notification access
```sh
# Setting up .env
FCM_PROJECT_ID="YOUR_PROJECT_ID"
FCM_JSON_PATH="storage/app/SERVICE-ACCOUNT.json"

# Ready to use
FcmPushJob::dispatchSync("token", "title", "body"); # execute immediately
FcmPushJob::dispatch("token", "title", "body"); # queue base execution
```

### Notes
- Change the app name to your project's name.
- Change the admin panel credentials before starting development.
- Make sure [swagger](https://github.com/DarkaOnLine/L5-Swagger) & admin panel URLs must be in working position.
- For role & permission we have used spatie's [Laravel Permission](https://spatie.be/docs/laravel-permission/v6/introduction) package.
- For Country data we have gether the information from this repo : <https://github.com/dr5hn/countries-states-cities-database>
- If you dont have API requirements then please remove these packages: [darkaonline/l5-swagger](https://github.com/DarkaOnLine/L5-Swagger) & [spaceo/rest-auth](http://172.16.16.51:9999/snippets/157).

**HAPPY CODING :+1: :computer:**

