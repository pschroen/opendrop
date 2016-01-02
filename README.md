# Opendrop - Share files instantly

Based on Carson McDonald's [direct-browser-s3-upload-example](https://github.com/carsonmcdonald/direct-browser-s3-upload-example), Opendrop is a simple file-sharing [PHP](http://php.net/) website that uses any [Amazon S3](http://aws.amazon.com/s3/) bucket for storage.

Includes a shell script to remove uploads after 7 days and a built-in HTML5 media player. Perfect for sharing audio and video edits.


## CORS Configuration

```xml
<?xml version="1.0" encoding="UTF-8"?>
<CORSConfiguration xmlns="http://s3.amazonaws.com/doc/2006-03-01/">
    <CORSRule>
        <AllowedOrigin>*</AllowedOrigin>
        <AllowedMethod>PUT</AllowedMethod>
        <MaxAgeSeconds>3000</MaxAgeSeconds>
        <AllowedHeader>Content-Type</AllowedHeader>
        <AllowedHeader>x-amz-acl</AllowedHeader>
        <AllowedHeader>origin</AllowedHeader>
    </CORSRule>
</CORSConfiguration>
```


## Lifecycle Rules

Instead of using the included shell script it's much simpler to remove uploads using Amazon's Lifecycle rules, under the `Lifecycle` section of your Bucket.


## Roadmap

##### v0.1.x:

* Audio player
* Video player
* Auto-transcoding media for playback
* Improved browser and mobile support
* Test scripts


## Resources

* [The Wiki](https://github.com/pschroen/opendrop/wiki)
* [Website](http://opendrop.io/)
* [Twitter](https://twitter.com/opendrop)


## Copyright & License

Copyright (c) 2015 Patrick Schroen - Released under the [MIT License](LICENSE).
