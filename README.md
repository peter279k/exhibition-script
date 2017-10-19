# exhibition-script

# Introduction

- This project is just for the people who want to fetch the Taiwanese culture info quickly.
- It makes project easy because I don't want to make this service complicated at this time.
- The project included ```fetch.php```, ```Subscriber.php```, ```MailClient.php```, ```mail_list.txt```, ```mailjet_key.ini``` and ```template``` folder.

# Usage

## To fetch the exhibition informations

- clone the repo
```bash
git clone https://github.com/peter279k/exhibition-script
```
- install the dependencies via Composer
```bash
composer install
```
- replace the mailjet public and private key in ```mailjet_key.ini```.
- execute the fetch.php
```php
php fetch.php
```

## To subscribe and send the newsletter manually

- add the received e-mail in ```mail_list.txt```.
- set the scheduled time to execute the ```fetch.php``` and send e-mail newsletter.The schedule time you set is up to you.
