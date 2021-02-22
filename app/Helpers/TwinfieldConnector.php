<?php

namespace App\Helpers;

use App\Models\User;
use PhpTwinfield\Office;
use PhpTwinfield\ApiConnectors\UserApiConnector;
use PhpTwinfield\Exception as TwinfieldException;
use PhpTwinfield\ApiConnectors\OfficeApiConnector;
use PhpTwinfield\Secure\WebservicesAuthentication;
use PhpTwinfield\ApiConnectors\BrowseDataApiConnector;
use PhpTwinfield\Enums\BrowseColumnOperator;
use PhpTwinfield\BrowseColumn;

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

    public static function setup($report)
    {
        $connection = new WebservicesAuthentication(
            $report->overview->author->twinfield_username,
            $report->overview->author->twinfield_password,
            $report->overview->author->account->twinfield_office_code,
        );

        $office = Office::fromCode($report->overview->administration->code);
        $officeApi = new OfficeApiConnector($connection);

        if(!$officeApi->setOffice($office))
        {   
            throw new \Exception('account_office_code_does_not_exist');
        }

        $connector = new BrowseDataApiConnector($connection);

        if(!$connector)
        {
          throw new \Exception('office_does_not_exist');
        }

        return $connector;
    }

    public static function browseData($report_components)
    {     
          $connector = self::setup($report_components->report);

          $sortFields[] = new \PhpTwinfield\BrowseSortField('fin.trs.head.date');

          if($report_components->report->type == 'call_posts')
          {
             return self::callPostsRows($connector, $sortFields, $report_components);
          }else {
            try {
              $browseData = $connector->getBrowseData($report_components->report->configuration()['browse_definition'], $report_components->report->twinfield_columns(), $sortFields);
            } catch (\Exception $e) {
              sleep(5);
              try {
                $browseData = $connector->getBrowseData($report_components->report->configuration()['browse_definition'], $report_components->report->twinfield_columns(), $sortFields);
              } catch (\Exception $e) {
                  return;
              }
            }
             return $browseData->getRows();
          }
    }

    public static function callPostsRows($connector, $sortFields, $report_components)
    {
      $call_posts_codes = explode(',', str_replace(' ', '', $report_components->report->overview->administration->call_posts_code));

      $rows = [];

      foreach($call_posts_codes as $call_posts_code)
      {   
          $twinfield_columns = $report_components->report->twinfield_columns();
          array_push($twinfield_columns, (new BrowseColumn())
                      ->setField('fin.trs.line.dim1')
                      ->setLabel('General Ledger')
                      ->setVisible(false)
                      ->setAsk(true)
                      ->setOperator(BrowseColumnOperator::BETWEEN())
                      ->setFrom($call_posts_code)
                      ->setTo($call_posts_code));

          try {
              $browseData = $connector->getBrowseData($report_components->report->configuration()['browse_definition'], $twinfield_columns, $sortFields);
          } catch (\Exception $e) {
              sleep(5);
              try {
                  $browseData = $connector->getBrowseData($report_components->report->configuration()['browse_definition'], $twinfield_columns, $sortFields);
              } catch (\Exception $e) {
                  return;
              }
          }
          $rows = array_merge($rows, $browseData->getRows());

          sleep(2);
      }

      return $rows;
    }
}


