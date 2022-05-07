# Electrum for Laravel 5.4+
Author: Tim Schipper <info@aranea-development.nl>   
Description: Electrum support for Laravel with optional Vue wallet component.

![Wallet Web Interface](https://raw.githubusercontent.com/AraneaDev/laravel-electrum/master/assets/wallet.gif "Wallet Web Interface")   

[![Latest Version on Packagist](https://img.shields.io/packagist/vpre/araneadev/laravel-electrum.svg?style=flat-square)](https://packagist.org/packages/araneadev/laravel-electrum)
[![Packagist](https://img.shields.io/packagist/l/araneadev/laravel-electrum.svg?style=flat-square)](https://packagist.org/packages/araneadev/laravel-electrum)
[![Scrutinizer Build](https://img.shields.io/scrutinizer/build/g/AraneaDev/laravel-electrum.svg?style=flat-square)](https://scrutinizer-ci.com/g/AraneaDev/laravel-electrum/)
[![StyleCI](https://styleci.io/repos/103412671/shield?branch=master)](https://styleci.io/repos/103412671)
[![Quality Score](https://img.shields.io/scrutinizer/g/araneadev/laravel-electrum.svg?style=flat-square)](https://scrutinizer-ci.com/g/araneadev/laravel-electrum)
[![Total Downloads](https://img.shields.io/packagist/dt/araneadev/laravel-electrum.svg?style=flat-square)](https://packagist.org/packages/araneadev/laravel-electrum)   

WARNING: Be safe and never ever put your private keys on a webserver, use a watch only wallet or even better, get and setup a hardware wallet, so your keys and coins will be safe. 

## Installation:
Require this package in your composer.json and update composer. This will download the package.
```
composer require araneadev/laravel-electrum
```
After updating composer, add the ServiceProvider to the providers array in config/app.php
```
AraneaDev\Electrum\ElectrumServiceProvider::class,
```
   
## Requirements:   
* PHP >=7.0 
* Laravel >= 5.4+
* Electrum >= 4.0

## Setup Electrum
Download and install [Electrum](https://electrum.org/#download) if you haven't done so yet.
```
electrum daemon -d
electrum setconfig log_to_file true
electrum setconfig rpchost 127.0.0.1
electrum setconfig rpcport 7777
electrum setconfig rpcuser your_user
electrum setconfig rpcpassword your_password
electrum stop
electrum daemon -d
electrum create
electrum load_wallet
```
And set then following variables in your .env file:
```
ELECTRUM_RPC_HOST=http://127.0.0.1
ELECTRUM_RPC_PORT=7777
ELECTRUM_RPC_USER=your_user
ELECTRUM_RPC_PASS=your_password
```

## Optional Web Interface installation

### Requirements
* Clipboard.js >= 1.7.1
* Moment.js >= 2.4.0
* Vue * >= 2.1.10
* Vue QR Component >= 2.1.1
* Vue2 Bootstrap Modal > 0.1.11
* Axios * >= 0.16.2
* Lodash * >= 4.17.4
* Bootstrap * >= 3.3.7

_\* Included in Laravel 5.4+_

### Install Clipboard.js, Moment.js and Vue QR Component
```
npm install clipboard --save-dev
npm install moment --save-dev
npm install vue2-bootstrap-modal --save-dev
npm install vue-qrcode-component --save-dev

```

### Publish the assets
```
php artisan vendor:publish --provider=AraneaDev\Electrum\ElectrumServiceProvider
```
Enable the Web interface in config/electrum.php. 
```
[
    ....
    'webinterface'=> [
        'enabled' => true,
        ....
    ]
]
```
Then add the following line to your app.js:
```
Vue.component('electrum-wallet', require('./vendor/araneadev/Electrum.vue'));
```

## Available Commands   
Electrum's JSON-RPC methods are mapped to artisan commands:
```
php artisan electrum [METHOD] [--address=ADDRESS] [--txid=TXID] [--key=KEY]
```
