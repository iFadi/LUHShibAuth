# LUHShibAuth

This plugin parses the matricule number from WebSSO for the Leibniz Universität Hannover.

## Installation

In your {ILIAS Root} directory
```bash
mkdir -p Customizing/global/plugins/Services/AuthShibboleth/ShibbolethAuthenticationHook
cd Customizing/global/plugins/Services/AuthShibboleth/ShibbolethAuthenticationHook
git clone https://github.com/iFadi/LUHShibAuth.git
```
Goto "Administration -> Plugin" and install and activate the plugin.

Goto "Administration -> Authentication -> Shibboleth and configure user profile mapping "Attribut für Matrikelnummer => schacPersonalUniqueCode"

Choose "Update this field on login" if the matricalation field should modified on every login request.

## Tips
Debug messages are triggered with "component log level" DEBUG in "Administration -> Logging

If you do not see the plugin under "Administration" > "Plugins", in your {ILIAS Root} you should run:
```bash
composer install --no-dev
```

## Tested on the following ILIAS Versions:
* v8.0
