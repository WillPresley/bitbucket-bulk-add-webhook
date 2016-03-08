# Bitbucket Bulk Add Webhook

PHP script to add a specific webhook to all bitbucket account **OR TEAM** repositories at once. Can be used with a cron job to keep them up-to-date.

## Update to Gentle's BitBucket API

This repo uses a modified version of the [Gentle Software](https://github.com/gentlero/bitbucket-api) BitBucket API, properly looping through ALL repos from BB's JSON-paginated results, as seen in [this commit](https://github.com/WillPresley/bitbucket-bulk-add-webhook/commit/d84804b0aeebf957545899b06f3d192acd214457).

## Usage

- [Download](https://github.com/InterativaDigital/bitbucket-bulk-add-webhook/archive/master.zip) and unzip this package.
- Open `bitbucket_bulk_add_webhook.php` in your favorite text editor, setup the CONFIG class on the top of the file.
- Run the file through command line or upload this package to your webserver and execute it via url (be sure to secure it properly, it _does_ contain a password in cleartext, be smart).

## Do I need to run Composer?

No. All the files are already in this package. If you want, you can use composer to update the dependencies, but it's not necessary.

## Requirements

PHP >= 5.4 with cURL extension.

## License

The MIT License (MIT)
