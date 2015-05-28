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

## TBD
- Changed the logic for Media to allow for versioning, environments and media deletion
- Added migration which renames [[DocumentId]] column to [[For]]

## 1.0.1-alpha, May 25, 2015
- [[Media]] model uses [[mata\db\ActiveQuery]]
- Added caching for getting single individual records with [[cachedOne()]]
- Added dependency on mata-framework ~1.0.3-alpha

## 1.0.0-alpha, May 18, 2015

- Initial release.
