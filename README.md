# OpenCart Extended layout
[![License: GPLv3](https://img.shields.io/badge/license-GPL%20V3-green?style=plastic)](LICENSE)

The extension extends the standard layout mechanism, allowing you to show / hide other modules depending on the conditions (filters). It will make the output of modules more flexible, and the content of the site more personalized.

A extension does not hide other modules with CSS, it extends the standard layout mechanism by allowing a particular module to execute or not to execute. The module does not perform unnecessary calculations, does not affect performance.

## Other Languages

* [Russian](README_RU.md)

## Change Log

* [CHANGELOG.md](docs/CHANGELOG.md)

## Screenshots

* [SCREENSHOTS.md](docs/SCREENSHOTS.md)

## Features

With the module, it will be possible:

* hide / show the module on the pages of selected products / categories / manufacturers / products in selected categories / products of selected manufacturers / information pages;
* hide / show the module for mobile, tablets, desktops / Apple devices, Android / for operating systems Windows, macOS, Linux / for browsers Chrome, Firefox, Opera and so on;
* hide / show the module for selected languages / currencies / user groups / stores;
* hide / show the module depending on the range of the basket cost / weight, number of goods;
* hide / show the module, depending on the specified $_GET parameters.

Filters can be combined.

## Available filters

For any layouts:

* group of users;
* languages;
* currencies;
* shops (supports multistore);
* mobile devices (mobile, tablets, desktops);
* platforms (Windows, macOS, Linux, etc.);
* browsers (Chrome, Firefox, Opera, etc.);
* cart - ranges (subtotal, total, weight, number of goods);
* custom $_GET parameters.

Additional filters for individual layouts:

* Product scheme
    * selected goods;
    * goods from selected categories;
    * goods of selected manufacturers.
* Category scheme
    * selected categories.
* Manufacturer's scheme
    * selected manufacturers.
* Schema Information
    * selected information pages.

## Compatibility

* OpenCart 2.x, 3.x, 4.x versions.

## Dependencies

* For versions 2.x, 3.x, ocmod is used.
* For version 4.x, events are used.

## Demo

Admin

* [https://extended-layout.shtt.blog/admin/](https://extended-layout.shtt.blog/admin/) (auto login)

Catalog

* [https://extended-layout.shtt.blog/](https://extended-layout.shtt.blog/)

The demo site has a top menu for quick navigation.

## Description of the demo site

#### Main page

* There are 3 HTML modules, each of which will be shown only to a specific browser (Chrome, Firefox, Edge).
* There is an HTML module that will be shown only if there are more than two items in the cart.
* Hidden slideshow module for mobile devices.

#### Product page

* There is an HTML module that will be shown only for Apple products, with USD currency.
* There is a carousel module that will be shown only for products from the monitors category.

## Installation

* Install the module through the standard extension installation section.
* In the "System > Users > User Groups" section, select the "Administrator" group and allow editing the design/extended_layout section.
* Go to the page of the desired layout to use the module.

## Management

* The module is simple, no manual required. The module does not have its own page, all control is carried out from the layout editing page.

## License

* [GPL v3.0](LICENSE.MD)

## Thank You for Using My Extensions!

I have decided to make all my OpenCart extensions free and open-source to benefit the community. Developing, maintaining, and updating these extensions takes time and effort.

If my extensions have been helpful for your project and you’d like to support my work, any donation is greatly appreciated.

### 💙 You can support me via:

* [PayPal](https://paypal.me/TalgatShashakhmetov?country.x=US&locale.x=en_US)
* [CashApp](https://cash.app/$TalgatShashakhmetov)

Your support inspires me to keep improving and developing these tools. Thank you!
