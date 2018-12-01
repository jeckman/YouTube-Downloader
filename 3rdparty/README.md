# 3rd party libraries for YouTube-Downloader

The libraries inside this folder are required for YouTube-Downloader and will be in the deployment zip files.

If you have installed YouTube-Downloader via composer, then this folder will be ignored. Instead this dependencies will be automatically installed by Composer just like this library.

## License

All libraries in this folder are licensed under their own license. Please see the corresponding `LICENSE` file inside each library.

## Update libraries

To updates this libraries you need Composer.

Then run either (from the root folder)

```sh
composer update --working-dir=3rdparty
```

... or (from inside this `3rdparty` folder

```sh
# cd 3rdparty
composer update --working-dir=.
```

Then commit all changes.
