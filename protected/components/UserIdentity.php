<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
    private $userId;

    /**
     * Authenticates a user.
     * The example implementation makes sure if the username and password
     * are both 'demo'.
     * In practical applications, this should be changed to authenticate
     * against some persistent user identity storage (e.g. database).
     * @return boolean whether authentication succeeds.
     */
    public function authenticate()
    {
        // Find user by email or phone
        $user = User::model()->findByAttributes(array('login' => $this->username));

        if ($user === null) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } else if (crypt($this->password, $user->password) !== $user->password) {
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        } else {
            $this->userId = $user->id;
            $this->username = $user->name;
            $this->setState('login', $user->login);
            $this->errorCode = self::ERROR_NONE;
        }

        return $this->errorCode == self::ERROR_NONE;
    }

    public function getId()
    {
        return $this->userId;
    }
}