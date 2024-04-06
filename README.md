# BoringCMS
![PHP](https://img.shields.io/badge/PHP-777BB4?logo=php&logoColor=white) ![Laravel](https://img.shields.io/badge/Laravel-FF2D20?logo=laravel&logoColor=white) ![Docker](https://img.shields.io/badge/Docker-2496ED?logo=docker&logoColor=white) ![PostgreSQL](https://img.shields.io/badge/PostgreSQL-336791?logo=postgresql&logoColor=white) ![PHPUnit](https://img.shields.io/badge/PHPUnit-5468FF?logo=php&logoColor=white)

Simple headless CMS for modern world built as Laravel package for seamless integration into your app

This is a work in progress. You're welcome to explore and play around but please some bumps during exploration.

### Installation
Composer package will be released once core features are completed. In the meantime, you may install following below instructions

```bash
cd your-laravel-core-directory
mkdir packages
cd packages
git clone git@github.com:sakydev/BoringCMS.git
```

Then include following line in Laravel's composer.json under `require` section   
`"sakydev/boring": "*"`

Run `composer install` and you should be good

### Tests
You may run tests by running   
`./vendor/bin/phpunit packages/sakydev/boring/tests`

