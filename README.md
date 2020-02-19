# UHShibAuth

bash>mkdir -p Customizing/global/plugins/Services/AuthShibboleth/ShibbolethAuthenticationHook

bash>cd Customizing/global/plugins/Services/AuthShibboleth/ShibbolethAuthenticationHook

bash>git clone https://github.com/leifos-gmbh/UHShibAuth.git

Goto "Administration -> Plugin" and install and activate the plugin.

Goto "Administration -> Authentication -> Shibboleth and configure user profile mapping "Attribut für Matrikelnummer => schacPersonalUniqueCode"

Choose "Update this field on login" if the matricalation field should modified on every login request.

Debug messages are triggered with "component log level" DEBUG in "Administration -> Logging

