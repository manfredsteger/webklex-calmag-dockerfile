
# CalMag Calculator
A simple calculator to calculate the optimal calcium and magnesium concentration for your nutrient solutions. You can find
a demo of the calculator [here](https://calmag.webklex.com/).

[![Releases][ico-release]](https://github.com/Webklex/calmag/releases)
[![Demo][ico-website-status]](https://calmag.webklex.com/)
[![License][ico-license]](LICENSE.md)
[![Hits][ico-hits]][link-hits]


![calmag_web_gui](https://raw.githubusercontent.com/webklex/calmag/master/calmag_web_gui.png)

## Table of Contents
- [Installation](#installation)
    - [Requirements](#requirements)
- [Configuration](#configuration)
    - [Additives](#additives)
    - [Fertilizers](#fertilizers)
    - [Languages](#languages)
- [Build & Development](#build)
- [Support](#support)
- [Security](#security)
- [Credits](#credits)
- [License](#license)

## Installation
1.) Clone the repository:
```shell script
git clone https://github.com/webklex/calmag.git
```

2.) Install the dependencies:
```shell script
composer install
```

3.) Start the development server:
```shell script
php -S localhost:8000 -t public
```

### Requirements
- PHP >= 8.1
- Composer

## Configuration
All configuration files are located in the `src/config` directory.

### Additives
The `src/config/additives.php` file contains all available additives. You can add new additives or modify existing ones. Make sure to keep the array structure.

### Fertilizers
The `src/config/fertilizers.php` file contains all available fertilizers. You can add new fertilizers or modify existing ones. Make sure to keep the array structure.

### Languages
All language files are located in the `resources/lang` directory. You can add new languages or modify existing ones. Make sure to keep structure and naming conventions - misinterpretation can lead to unexpected behavior.
The user specific language is determined by the `Accept-Language` header. If the language is not available, the default language will be used.

## Build
To build the project you can use the following command:
```shell script
npm install

npm run production
npm run build:tailwind
npm run watch:tailwind
```

## Support
If you encounter any problems or if you find a bug, please don't hesitate to create a new [issue](https://github.com/webklex/calmag/issues).
However, please be aware that it might take some time to get an answer.

If you need **immediate** or **commercial** support, feel free to send me a mail at github@webklex.com.

## Change log
Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Security

If you discover any security related issues, please email github@webklex.com instead of using the issue tracker.

## Credits
- Fumu <3
- [Webklex][link-author]
- [All Contributors][link-contributors]

## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-release]: https://img.shields.io/github/v/release/webklex/calmag?style=flat-square
[ico-downloads]: https://img.shields.io/github/downloads/webklex/calmag/total?style=flat-square
[ico-website-status]: https://img.shields.io/website?down_message=Offline&label=Demo&style=flat-square&up_message=Online&url=https%3A%2F%2Fcalmag.webklex.com%2F
[ico-hits]: https://hits.webklex.com/svg/webklex/calmag?1

[link-hits]: https://hits.webklex.com
[link-author]: https://github.com/webklex
[link-contributors]: https://github.com/webklex/calmag/graphs/contributors