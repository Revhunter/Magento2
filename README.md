# RevHunter
Module for RevHunter. It adds before body end an image tag with dynamic adjusted src depending on specific sites (only homepage, category page, product view, checkout cart, checkout login, all checkout steps and success page).
This image tag is only generated when module is enabled and RevHunter ID is not empty.

### Version
1.0.0

### Compatibility
- Magento CE 2.3.3
- Not tested in other versions

## Requirements 
 - Magento 2
 - Composer
 
### Installation
To enable this extension just run this commands from Magento CLI
```
- php bin/magento module:enable Fwc_RevHunter
- php bin/magento setup:upgrade
- php bin/magento setup:di:compile
- php bin/magento setup:static-content:deploy -f
- php bin/magento cache:flush
```

### Configuration options
1. Stores -> Configuration -> RevHunter -> RevHunter Configuration
2. Available options: Module status and RevHunter ID

#### Change log

##### 02 Oct 2020
- Init Module
