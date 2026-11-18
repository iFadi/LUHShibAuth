# LUHShibAuth

This plugin parses the matriculate number from WebSSO for the Leibniz University Hanover, it also sets the login {USERNAME} as the LUH-ID instead of the default ILIAS Shibboleth login prefix: {firstname.lastname}

## Installation

> Since ILIAS 10, `Customizing` lives under the `public/` document root. The Shibboleth plugin
> slot path itself is unchanged in ILIAS 11.

In your {ILIAS Root} directory
```bash
mkdir -p public/Customizing/global/plugins/Services/AuthShibboleth/ShibbolethAuthenticationHook
cd public/Customizing/global/plugins/Services/AuthShibboleth/ShibbolethAuthenticationHook
git clone https://github.com/iFadi/LUHShibAuth.git
```

Now check out a specific tag:
```bash
git checkout v3.0.0   # ILIAS 11; for ILIAS 10 use v2.0.0
```

Rebuild the autoloader classmap and the ILIAS artifacts so ILIAS finds the plugin, then
register it. From the {ILIAS Root}, as the web server user:
```bash
composer du
php cli/setup.php build
php cli/setup.php update
```
Reload PHP-FPM afterwards (`systemctl reload php8.4-fpm`), otherwise the web process keeps
serving the old plugin list from its OPcache.

Goto "Administration -> Extending ILIAS -> Plugins" and install and activate the plugin.

Goto "Administration -> Authentication -> Shibboleth" and configure user profile mapping "Attribut für Matrikelnummer => schacPersonalUniqueCode"

Choose "Update this field on login" if the matriculation field should be modified on every login request.

## Tips
Debug messages are triggered with "component log level" DEBUG in "Administration -> Logging".

If you do not see the plugin under "Administration -> Extending ILIAS -> Plugins", run `composer du`
in your {ILIAS Root} again and check for errors.

## Tested on the following ILIAS Versions
* v11.5 (PHP 8.4)

| ILIAS | Plugin | Branch |
|-------|--------|--------|
| 11 | `v3.x` | `master` |
| 10 | `v2.x` | `release_10` |
| 8, 9 | `v1.x` | `release_9` |

## Releasing

Versions are maintained manually (the authoritative version is `plugin.php`):

1. Bump `$version` in `plugin.php` and adjust `$ilias_min_version` / `$ilias_max_version` if needed.
2. Add a `## Changelog` entry below.
3. Commit, then tag and push: `git tag -a vX.Y.Z -m "vX.Y.Z" && git push origin vX.Y.Z`.

## Changelog

#### v3.0.0:
* ILIAS 11 compatibility: target `ilias_min_version` 11.0 / `ilias_max_version` 11.999.
  The hook interface of the Shibboleth plugin slot is unchanged, so the plugin code is the same as in v2.0.0.
* CI lints against PHP 8.3 and 8.4, the versions ILIAS 11 runs on
* `plugin.php`: name both maintainers in one `$responsible` value instead of overwriting the first

#### v2.0.0:
* ILIAS 10 compatibility: target `ilias_min_version` 10.0 / `ilias_max_version` 10.999
* Drop the obsolete `include_once` of the base class (autoloaded; its source moved to `components/ILIAS/AuthShibboleth/` in ILIAS 10)
* Docs: install under `public/Customizing/...`, rebuild classmap with `composer du`

#### v1.0.2:
* Refactoring logging for ILIAS 8

#### v1.0.1:
* Add falling back mechanism to the default ILIAS login, if "ext_account" is not available
* Login/Username is now all in capital
