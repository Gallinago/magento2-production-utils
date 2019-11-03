# Magento2 Production Utils

Magento2 module adding useful changes in most Magento applications.

### Features

* Disable ``Additional Cache Management`` section so that admin cannot
remove static files or images cache
* Modify DB status validation checking if any module is too new so that
Magento doesn't crash after ``setup:upgrade``

### Prerequisites

* Magento >=2.2
* PHP >=7.1

### Installing

##### Using composer (suggested)

1. Add to ``composer.json``
```
"repositories": [
    `{
        "type": "vcs",
        "url": "git@github.com:gallinago/magento2-production-utils.git"
    }
],`
```

```
composer require gallinago/module-production-utils
```

#### Install the module

Run this command
```
bin/magento module:enable Gallinago_ProductionUtils
bin/magento setup:upgrade
```

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/gallinago/magento2-production-utils/tags). 

## Authors

* **Maciej Sławik** - *Initial work* - [Gallinago](https://github.com/gallinago)

See also the list of [contributors](https://github.com/gallinago/magento2-production-utils/contributors) who participated in this project.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details

