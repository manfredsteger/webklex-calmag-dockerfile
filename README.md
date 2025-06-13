# CalMag Calculator
A simple calculator to calculate the optimal calcium and magnesium concentration for your nutrient solutions. You can find
a demo of the calculator [here](https://www.calmag.eu/).

[![Releases][ico-release]](https://github.com/Webklex/calmag/releases)
[![Demo][ico-website-status]](https://www.calmag.eu/)
[![License][ico-license]](LICENSE.md)
[![Hits][ico-hits]][link-hits]


![calmag_web_gui](https://raw.githubusercontent.com/webklex/calmag/master/calmag_web_gui.png)

## Table of Contents
- [Installation](#installation)
    - [Requirements](#requirements)
- [Docker](#docker)
- [Configuration](#configuration)
    - [Additives](#additives)
    - [Fertilizers](#fertilizers)
    - [Languages](#languages)
- [API](#api)
    - [Endpoints](#endpoints)
    - [Request Format](#request-format)
    - [Response Format](#response-format)
    - [Examples](#examples)
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

## Docker
You can run the app using Docker for a quick and isolated setup:

### Build system architecture
```bash
docker buildx build -t webklex-calmag:dev .
docker run -d -p 8000:8000 --name webklex-calmag webklex-calmag:dev
```

### Build AMD64 architecture
AMD64 (works in general but emulating on ARM)
```bash
docker buildx build --platform linux/amd64 -t webklex-calmag:dev-amd64 .
```

### Build ARM64 architecture
```bash
docker buildx build --platform linux/arm64 -t webklex-calmag:dev-arm64 .
```

A precompiled image maintained by @manfredsteger can be found here: https://hub.docker.com/r/manfredsteger/webklex-calmag


## Configuration
All configuration files are located in the `src/config` directory.

### Additives
The `src/config/additives.php` file contains all available additives. You can add new additives or modify existing ones. Make sure to keep the array structure.

### Fertilizers
The `src/config/fertilizers.php` file contains all available fertilizers. You can add new fertilizers or modify existing ones. Make sure to keep the array structure.

### Languages
All language files are located in the `resources/lang` directory. You can add new languages or modify existing ones. Make sure to keep structure and naming conventions - misinterpretation can lead to unexpected behavior.
The user specific language is determined by the `Accept-Language` header. If the language is not available, the default language will be used.

## API
The CalMag Calculator provides a JSON API that allows you to programmatically access the calculator's functionality.

### Endpoints
The API provides the following endpoints:

- `POST`|`GET` **/** - Calculate nutrient solutions based on provided parameters
- `GET` **/?method=additives** - Get a list of available additives
- `GET` **/?method=fertilizers** - Get a list of available fertilizers
- `GET` **/?method=models** - Get a list of available models

### Request Format
The API accepts both GET and POST requests. For POST requests, the data should be sent as JSON in the request body. For GET requests, parameters can be passed in the URL query string or as a base64-encoded JSON payload in the `p` parameter.

To specify that you want a JSON response, set the `Accept` or `Content-Type` header to `application/json`.

### Response Format
All API responses are in JSON format and include a `version` field with the current application version.

#### / Response
```json
{
  "version": "3.4.0",
  "result": {
    ...
  }
}
```

#### /?method=additives Response
```json
{
  "version": "3.4.0",
  "additives": {
    "calcium": {
      "CaNO3": {
        "elements": {
          "calcium": 24.43,
          "nitrogen": 17.08,
          "magnesium": 0
        },
        "concentration": 10,
        "real": {
          "calcium": 24.430000000000003,
          "magnesium": 0,
          "nitrogen": 17.08
        },
        "name": "Calcium nitrate"
      }
    },
    "magnesium": {
      "MgSO4-7H20": {
        "elements": {
          "magnesium": 9.86,
          "sulfur": 13.01,
          "calcium": 0
        },
        "concentration": 10,
        "real": {
          "calcium": 0,
          "magnesium": 9.86,
          "sulfur": 13.01
        },
        "name": "Epsom salt - Magnesiumsulfate-Heptahydrate"
      }
    }
  }
}
```

#### /?method=fertilizers Response
```json
{
  "version": "3.4.0",
  "fertilizers": {
    "Canna - CalMag Agent": {
      "name": "CalMag Agent",
      "elements": {
        "calcium": 7.0447999999999995,
        "magnesium": 2.1386,
        "nitrogen": 4.12624
      },
      "density": 1.258,
      "ph": 7,
      "schedule": false,
      "link": {
        "de": "https://www.canna-de.com/calmag-agent",
        "us": "https://www.canna-uk.com/calmag-agent"
      },
      "brand": "Canna",
      "ratio": 3.2941176470588234
    },
    "BioBizz - CalMag": {
      "name": "CalMag",
      "elements": {
        "calcium": {
          "CaO": 4.5654
        },
        "magnesium": {
          "MgO": 1.8478999999999999
        }
      },
      "density": 1.087,
      "ph": 7,
      "schedule": false,
      "link": {
        "de": "https://www.biobizz.com/de/producto/calmag/",
        "us": "https://www.biobizz.com/producto/calmag/"
      },
      "brand": "BioBizz",
      "ratio": 2.9256319238570767
    }
  }
}
```

### Examples

#### Example 1: Get available fertilizers
```bash
curl -H "Accept: application/json" "https://www.calmag.eu/?method=fertilizers"
```

#### Example 2: Calculate nutrient solution (GET with encoded payload)
```bash
# Base64-encoded JSON payload
curl -H "Accept: application/json" "https://www.calmag.eu/?expert=1&p=eyJmZXJ0aWxpemVyIjoiIiwiYWRkaXRpdmUiOnsiY2FsY2l1bSI6IkNhTk8zIiwibWFnbmVzaXVtIjoiQ2FubmEgTW9ubyBNYWduZXNpdW0ifSwiYWRkaXRpdmVfY29uY2VudHJhdGlvbiI6eyJjYWxjaXVtIjoxMCwibWFnbmVzaXVtIjoxMDB9LCJhZGRpdGl2ZV91bml0cyI6eyJjYWxjaXVtIjoibWwiLCJtYWduZXNpdW0iOiJtbCJ9LCJlbGVtZW50cyI6eyJjYWxjaXVtIjowLCJtYWduZXNpdW0iOjAsInBvdGFzc2l1bSI6MCwiaXJvbiI6MCwic3VscGhhdGUiOjAsIm5pdHJhdGUiOjAsIm5pdHJpdGUiOjAsIm5pdHJvZ2VuIjowLCJzdWxmdXIiOjAsImNobG9yaWRlIjowfSwiZWxlbWVudF91bml0cyI6eyJjYWxjaXVtIjoibWciLCJtYWduZXNpdW0iOiJtZyIsInBvdGFzc2l1bSI6Im1nIiwiaXJvbiI6Im1nIiwic3VscGhhdGUiOiJtZyIsIm5pdHJhdGUiOiJtZyIsIm5pdHJpdGUiOiJtZyJ9LCJyYXRpbyI6My41LCJ2ZXJzaW9uIjoiMy40LjAiLCJyZWdpb24iOiJ1cyIsInZvbHVtZSI6NSwidGFyZ2V0X21vZGVsIjoibGluZWFyIiwic3VwcG9ydF9kaWx1dGlvbiI6dHJ1ZSwidGFyZ2V0X29mZnNldCI6MCwiYWRkaXRpdmVfZWxlbWVudHMiOnsiY2FsY2l1bSI6eyJjYWxjaXVtIjp7ImNhbGNpdW0iOjI0LjQzLCJDYU8iOjB9fSwibWFnbmVzaXVtIjp7Im1hZ25lc2l1bSI6eyJtYWduZXNpdW0iOjAsIk1nTyI6N319fSwiZmVydGlsaXplcl9lbGVtZW50cyI6eyJjYWxjaXVtIjp7ImNhbGNpdW0iOiIwLjAwMDAiLCJDYU8iOiIwIn0sIm1hZ25lc2l1bSI6eyJtYWduZXNpdW0iOiIwLjAwMDAiLCJNZ08iOiIwIn19LCJ0YXJnZXRfd2Vla3MiOltdLCJ0YXJnZXRfY2FsY2l1bSI6eyIxIjo2MCwiMiI6NjYuODUsIjMiOjczLjM3LCI0Ijo4MCwiNSI6ODUuMTEsIjYiOjkwLjAxLCI3Ijo5NC45LCI4IjoxMDAsIjkiOjEwNy42MiwiMTAiOjExNS4xMiwiMTEiOjEyMi42MiwiMTIiOjEzMH0sInRhcmdldF9tYWduZXNpdW0iOnsiMSI6MCwiMiI6MCwiMyI6MCwiNCI6MCwiNSI6MCwiNiI6MCwiNyI6MCwiOCI6MCwiOSI6MCwiMTAiOjAsIjExIjowLCIxMiI6MH19"
```

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
[ico-website-status]: https://img.shields.io/website?down_message=Offline&label=Demo&style=flat-square&up_message=Online&url=https%3A%2F%2Fwww.calmag.eu%2F
[ico-hits]: https://hits.webklex.com/svg/webklex/calmag?1

[link-hits]: https://hits.webklex.com
[link-author]: https://github.com/webklex
[link-contributors]: https://github.com/webklex/calmag/graphs/contributors
