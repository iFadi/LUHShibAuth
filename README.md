# LUHShibAuth

This plugin parses the matriculate number from WebSSO for the Leibniz University Hanover, it also sets the login {USERNAME} as the LUH-ID instead of the default ILIAS Shibboleth login prefix: {firstname.lastname}

## Installation

> **ILIAS 10:** `Customizing` now lives under the `public/` document root. The Shibboleth plugin
> slot path itself is unchanged.

In your {ILIAS Root} directory
```bash
mkdir -p public/Customizing/global/plugins/Services/AuthShibboleth/ShibbolethAuthenticationHook
cd public/Customizing/global/plugins/Services/AuthShibboleth/ShibbolethAuthenticationHook
git clone https://github.com/iFadi/LUHShibAuth.git
```

Now check out a specific tag:
```bash
git checkout v2.0.0
```

Rebuild the autoloader classmap so ILIAS finds the plugin's classes. From the {ILIAS Root}:
```bash
composer du
```

Goto "Administration -> Extending ILIAS -> Plugins" and install and activate the plugin.

Goto "Administration -> Authentication -> Shibboleth" and configure user profile mapping "Attribut für Matrikelnummer => schacPersonalUniqueCode"

Choose "Update this field on login" if the matriculation field should be modified on every login request.

## Tips
Debug messages are triggered with "component log level" DEBUG in "Administration -> Logging".

If you do not see the plugin under "Administration -> Extending ILIAS -> Plugins", run `composer du`
in your {ILIAS Root} again and check for errors.

## Tested on the following ILIAS Versions
* v10.x

> `master` now targets **ILIAS 10**. For **ILIAS 8** use the `v1.x` tags.

## Releasing

Versions are maintained manually (the authoritative version is `plugin.php`):

1. Bump `$version` in `plugin.php` and adjust `$ilias_min_version` / `$ilias_max_version` if needed.
2. Add a `## Changelog` entry below.
3. Commit, then tag and push: `git tag -a vX.Y.Z -m "vX.Y.Z" && git push origin vX.Y.Z`.

## Changelog

#### v2.0.0:
* ILIAS 10 compatibility: target `ilias_min_version` 10.0 / `ilias_max_version` 10.999
* Drop the obsolete `include_once` of the base class (autoloaded; its source moved to `components/ILIAS/AuthShibboleth/` in ILIAS 10)
* Docs: install under `public/Customizing/...`, rebuild classmap with `composer du`

#### v1.0.2:
* Refactoring logging for ILIAS 8

#### v1.0.1:
* Add falling back mechanism to the default ILIAS login, if "ext_account" is not available
* Login/Username is now all in capital
