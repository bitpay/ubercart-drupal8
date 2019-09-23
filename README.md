BitPay Checkout for Ubercart
===============================

## Build Status

[![Build Status](https://travis-ci.org/bitpay/ubercart-drupal8.svg?branch=master)](https://travis-ci.org/bitpay/ubercart-drupal8)

This plugin allows stores using the Ubercart shopping cart system to accept cryptocurrency payments via the BitPay gateway. It only takes a few minutes to configure.

# Requirements

This plugin requires the following:

* [Drupal 8](https://www.drupal.org/download)
* [Ubercart](https://www.drupal.org/project/ubercart)
* A BitPay merchant account ([Test](http://test.bitpay.com) and [Production](http://www.bitpay.com))

# Installation

Install the plugin via our [GitHub Repo](https://github.com/bitpay/ubercart-drupal8).

### When Installing From the Downloadable Archive

Visit the [Releases](https://github.com/bitpay/bitpay-checkout-for-woocommerce/releases) page of this repository and download the latest version. Once this is done, you can just go to Wordpress's Adminstration Panels > Plugins > Add New > Upload Plugin, select the downloaded archive and click Install Now. After the plugin is installed, click on Activate.

**WARNING:** 
* It is good practice to backup your database before installing plugins. Please make sure you create backups.
* If you were using a previous version of this plugin, this version (3.0) was completely rewritten to improve the user experience and the security. You will need to renew the configuration of the plugin (fetch a new API token from the BitPay merchant dashboard).

## Configuration
After you have installed the extension, you will need to add your API token and choose the environment (Sandbox or Production)

```Configuration->BitPay Checkout Settings```

Next you will need to add the payment method to Ubercart

```Store->Payment Methods->(Choose BitPay in the dropdown->Add payment method``` 

Set a title to show on the Checkout page

```Click EDIT on the payment method->Add a label```

Your site is now enabled.  Order status will automatically be updated from the IPN, with the final status as `Processing`.  After it has reached this step, you can ship physical goods or allow digital access to products.

## Support

**BitPay Support:**

* Last Cart Version Tested: Drupal 8.7.7
* [GitHub Issues](https://github.com/bitpay/bitpay-checkout-for-woocommerce/issues)
  * Open an issue if you are having troubles with this plugin.
* [Support](https://support.bitpay.com/hc/en-us)
  * BitPay merchant support documentation

## Troubleshooting

The latest version of this plugin can always be downloaded from the official BitPay repository located here: https://github.com/bitpay/ubercart-drupal8

* This plugin requires PHP 5.6.40 or higher to function correctly. Contact your webhosting provider or server administrator if you are unsure which version is installed on your web server.
* Ensure a valid SSL certificate is installed on your server. Also ensure your root CA cert is updated. If your CA cert is not current, you will see curl SSL verification errors.
* Verify that your web server is not blocking POSTs from servers it may not recognize. Double check this on your firewall as well, if one is being used.
* Check the system error log file (usually the web server error log) for any errors during BitPay payment attempts. If you contact BitPay support, they will ask to see the log file to help diagnose the problem.
* Check the version of this plugin against the official plugin repository to ensure you are using the latest version. Your issue might have been addressed in a newer version!

**NOTE:** When contacting support it will help us if you provide:

* Drupal and Ubercart Version
* PHP Version
* Other plugins you have installed
* Configuration settings for the plugin (Most merchants take screen grabs)
* Any log files that will help
  * Web server error logs
* Screen grabs of error message if applicable.

## Contribute

Would you like to help with this project?  Great!  You don't have to be a developer, either.  If you've found a bug or have an idea for an improvement, please open an [issue](https://github.com/bitpay/ubercart-drupal8/issues) and tell us about it.

If you *are* a developer wanting contribute an enhancement, bugfix or other patch to this project, please fork this repository and submit a pull request detailing your changes.  We review all PRs!

This open source project is released under the [MIT license](http://opensource.org/licenses/MIT) which means if you would like to use this project's code in your own project you are free to do so. Speaking of, if you have used our code in a cool new project we would like to hear about it!  Please send us an [email](mailto:integrations@bitpay.com).