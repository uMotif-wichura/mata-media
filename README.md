MATA Media
==========================================

Media module interfaces with media storages such as AWS S3 to host content like images, videos and files. It is used extensively by many MATA and MATA CMS modules.


Installation
------------

- Add the module using composer: 

```json
"mata/mata-media": "~1.0.0"
```

-  Run migrations
```
php yii migrate/up --migrationPath=@vendor/mata/mata-media/migrations
```


Changelog
---------

## 1.0.0-alpha, May 18, 2015

- Initial release.
