# Change Customer Password In Admin - Magento 2

    composer require ghoster/changecustomerpassword


Change Customer Password In Admin Magento 2 module is implements the form in customer edit which allow admin change customer password directly like old-fashion way Magento 1.

[![Latest Stable Version](http://poser.pugx.org/ghoster/changecustomerpassword/v)](https://packagist.org/packages/ghoster/changecustomerpassword)
[![Total Downloads](http://poser.pugx.org/ghoster/changecustomerpassword/downloads)](https://packagist.org/packages/ghoster/changecustomerpassword)
[![Latest Unstable Version](http://poser.pugx.org/ghoster/changecustomerpassword/v/unstable)](https://packagist.org/packages/ghoster/changecustomerpassword)
[![License](http://poser.pugx.org/ghoster/changecustomerpassword/license)](https://packagist.org/packages/ghoster/changecustomerpassword)
[![PHP Version Require](http://poser.pugx.org/ghoster/changecustomerpassword/require/php)](https://packagist.org/packages/ghoster/changecustomerpassword)
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/ae1071a530754edc944356b4e1bcb92f)](https://www.codacy.com/gh/tuyennn/ChangeCustomerPassword/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=tuyennn/ChangeCustomerPassword&amp;utm_campaign=Badge_Grade)
[![Donate](https://img.shields.io/badge/Donate-PayPal-green.svg)](https://www.paypal.me/thinghost)
[![Build Status](https://app.travis-ci.com/tuyennn/magento2-change-customer-password.svg?branch=master)](https://app.travis-ci.com/github/tuyennn/magento2-change-customer-password)


---
- [Extension on GitHub](https://github.com/tuyennn/magento2-change-customer-password)
- [Direct download link](https://github.com/tuyennn/magento2-change-customer-password/tarball/master)

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

* Download the files from github: [Direct download link](https://github.com/tuyennn/magento2-change-customer-password/tarball/master)
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
