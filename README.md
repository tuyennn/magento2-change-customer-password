# Change Customer Password In Admin - Magento 2
---

Change Customer Password In Admin Magento 2 module is implements the form in customer edit which allow admin change customer password directly like old-fashion way Magento 1.

[![License: GPL v3](https://img.shields.io/badge/License-GPL%20v3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0)
[![Donate](https://img.shields.io/badge/Donate-PayPal-green.svg)](https://www.paypal.me/thinghost)

---
## [![Alt GhoSter](http://thinghost.info/wp-content/uploads/2015/12/ghoster.png "thinghost.info")](http://thinghost.info) Overview

- [Extension on GitHub](https://github.com/tuyennn/ChangeCustomerPassword)
- [Direct download link](https://github.com/tuyennn/ChangeCustomerPassword/tarball/master)

![Alt Screenshot-1](https://thinghost.info/wp-content/uploads/2018/09/Selection_128-1024x368.jpg "thinghost.info")

## Main Features

* Add a quick update Password button to customer view in Admin

## Installation with Composer

* Connect to your server with SSH
* Navigation to your project and run these commands
 
```bash
composer require ghoster/changecustomerpassword


php bin/magento setup:upgrade
rm -rf pub/static/* 
rm -rf var/*

php bin/magento setup:static-content:deploy
```

## Installation without Composer

* Download the files from github: [Direct download link](https://github.com/tuyennn/ChangeCustomerPassword/tarball/master)
* Extract archive and copy all directories to app/code/GhoSter/ChangeCustomerPassword
* Go to project home directory and execute these commands

```bash
php bin/magento setup:upgrade
rm -rf pub/static/* 
rm -rf var/*

php bin/magento setup:static-content:deploy
```
## Licence
[Open Software License (OSL 3.0)](http://opensource.org/licenses/osl-3.0.php)


## Donation
If this project help you reduce time to develop, you can give me a cup of coffee :) 

[![paypal](https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif)](https://www.paypal.me/thinghost)
