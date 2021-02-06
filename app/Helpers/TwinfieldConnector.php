<?php

namespace App\Helpers;

use App\Models\User;
use PhpTwinfield\Secure\WebservicesAuthentication;
use PhpTwinfield\ApiConnectors\UserApiConnector;
use PhpTwinfield\Exception as TwinfieldException;


class TwinfieldConnector
{
    public static function credentialsAreValidForUser(User $user)
    {   
        if(empty($user))
        {
          return false;
        }

        if(empty($user->account))
        {
          return false;
        }

        $connection = new WebservicesAuthentication(
          $user->twinfield_username,
          $user->twinfield_password,
          $user->account->twinfield_office_code
        );

        try {
            $officeApi = new UserApiConnector($connection);
            $officeApi->listAll();

        } catch (TwinfieldException $e) {
            return false;
        }

        return true;
    }
}


