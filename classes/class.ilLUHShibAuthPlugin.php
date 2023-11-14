<?php

include_once './Services/AuthShibboleth/classes/class.ilShibbolethAuthenticationPlugin.php';

/**
 * Shibboleth authentication plugin for:
 * matriculation number modification
 * login username modification
 *
 * @author Stefan Meyer <smeyer.ilias@gmx.de>
 * @author Fadi Asbih <asbih@elsa.uni-hannover.de>
 **/
class ilLUHShibAuthPlugin extends ilShibbolethAuthenticationPlugin
{
    const PLNAME = 'LUHShibAuth';

    /**
     * @var string
     */
    const SHIB_MATRICULATION_FIELD = 'shib_matriculation';

    /**
     * @var string
     */
    const SHIB_MATRICULATION_UPDATE = 'shib_update_matriculation';

    /**
     * @var null | \ilLogger
     */
    private $logger = null;

    /**
     * @var null | \ilSetting
     */
    private $settings = null;

    /**
     * @inheritDoc
     */
    protected function init(): void
    {
        global $DIC;
        $this->settings = $DIC->settings();
    }

    /**
     * Get plugin name
     * @return string
     */
    public function getPluginName(): string
    {
        return static::PLNAME;
    }

    /**
     * @param ilObjUser $user
     * @return ilObjUser
     */
    public function beforeCreateUser(ilObjUser $user): ilObjUser
    {
        $this->getLogger()->debug('Before user creation');
        $user = $this->updateMatriculation($user, true);
        $user = $this->createCustomShibLogin($user);

        return $user;
    }

    /**
     * @param ilObjUser $user
     * @return ilObjUser
     */
    public function beforeUpdateUser(ilObjUser $user): ilObjUser
    {
        $this->getLogger()->debug('Before user update');
        $user = $this->updateMatriculation($user, false);

        return $user;
    }

    /**
     * @param ilObjUser $user
     * @return ilObjUser
     *
     * Sets the User Login when first time signing in as the LUH-ID
     *
     */
    public function createCustomShibLogin(ilObjUser $user)
    {
        // Get the external account value
        $login = $user->getExternalAccount();
        $login = strtoupper($login);

        // fallback mechanism using first name and last name
        // if ext_account is not empty, use it as login
        if (!empty($login)) {
            $user->setLogin($login);
            $this->getLogger()->debug('ext_accout is found, username is being set now as the LUH-ID:' . $user->getExternalAccount());
        }
        else { // else if ext_account is empty do nothing, uses ILIAS default login scheme
            $this->getLogger()->debug('ext_account is not found, falling back to the ILIAS default login');
        }

        return $user;
    }

    /**
     * @param ilObjUser $user
     * @param bool      $is_creation_mode
     * @return ilObjUser
     */
    private function updateMatriculation(\ilObjUser $user, bool $is_creation_mode)
    {
        $shib_mn_field = $this->settings->get(self::SHIB_MATRICULATION_FIELD, '');
        if (!strlen($shib_mn_field)) {
            $this->getLogger()->debug('No matriculation number mapping configured');
            return $user;
        }

        $shib_mn_update = $this->settings->get(self::SHIB_MATRICULATION_UPDATE, 0 );
        if (!$is_creation_mode && !$shib_mn_update) {
            $this->getLogger()->debug('No update configured for matriculation in global settings');
            return $user;
        }

        if (array_key_exists($shib_mn_field, $_SERVER)) {
            $shib_mn_value = trim($_SERVER[$shib_mn_field]);
            if (!strlen($shib_mn_value)) {
                $this->getLogger()->debug('No matriculation number send by shib server.');
                return $user;
            }

            $shib_mn_parts = explode(':', $shib_mn_value);
            if($shib_mn_parts === false) {
                $this->getLogger()->debug('Cannot parse matriculation number: ' . $shib_mn_value);
                return $user;
            }

            $matriculation = end($shib_mn_parts);
            $this->getLogger()->debug('Update matriculation number: ' . $matriculation);
            $user->setMatriculation($matriculation);
        }
        else {
            $this->getLogger()->debug('No matriculation number found. ');
        }
        return $user;
    }
    private function getLogger() {
        if ($this->getLogger() === null) {
            global $DIC;
            $this->logger = $DIC->logger()->auth();
        }
        return $this->logger;
    }

}
