<?php

namespace App\Services;

use App\Models\User;
use PhpTwinfield\Office;
use PhpTwinfield\ApiConnectors\UserApiConnector;
use PhpTwinfield\Exception as TwinfieldException;
use PhpTwinfield\ApiConnectors\OfficeApiConnector;
use PhpTwinfield\Secure\WebservicesAuthentication;
use PhpTwinfield\ApiConnectors\BrowseDataApiConnector;
use PhpTwinfield\Enums\BrowseColumnOperator;
use PhpTwinfield\BrowseColumn;
use App\Models\Report;

class TwinfieldConnector
{   
    private $report;
    private $connector;

    public static function credentialsAreValidForUser(User $user)
    {   
        if(empty($user) || empty($user->account))
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

    public static function fetchData(Report $report)
    {   
        return (new static)->start($report);  
    }

    private function start($report)
    {   
        $this->report = $report;
        $this->connector = $this->setupConnector();

        return match ($this->report->type) {
            'unspecified_posts' => $this->getUnspecifiedPostsDataRows(),
            default => collect($this->fetchBrowseData()),
        };
    }

    private function setupConnector()
    { 
        $connection = new WebservicesAuthentication(
            $this->report->overview->author->twinfield_username,
            $this->report->overview->author->twinfield_password,
            $this->report->overview->author->account->twinfield_office_code,
        );

        $office = Office::fromCode($this->report->overview->administration->code);
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

    private function getUnspecifiedPostsDataRows()
    {
      $unspecified_posts_codes = explode(',', str_replace(' ', '', $this->report->overview->administration->unspecified_posts_code));
      
      return collect($unspecified_posts_codes)->map(function ($unspecified_posts_code) {
          
          $twinfield_columns = $this->twinfield_columns()->toArray();

          array_push($twinfield_columns, (new BrowseColumn())
          ->setField('fin.trs.line.dim1')
          ->setLabel('General Ledger')
          ->setVisible(false)
          ->setAsk(true)
          ->setOperator(BrowseColumnOperator::BETWEEN())
          ->setFrom($unspecified_posts_code)
          ->setTo($unspecified_posts_code));

          return $this->fetchBrowseData($twinfield_columns);
      });
    }

    private function fetchBrowseData($twinfield_columns = null)
    {   
        $twinfield_columns = empty($twinfield_columns) ? $this->twinfield_columns()->toArray() : $twinfield_columns;
        $sortFields[] = new \PhpTwinfield\BrowseSortField('fin.trs.head.date');
  
        return $this->connector->getBrowseData($this->report->configuration()['browse_definition'], $twinfield_columns, $sortFields)->getRows();
    }

    private function twinfield_columns()
    {   
        return collect($this->report->configuration()['columns'])->reject(function($column) {
            return $column['twinfield_column'] == '';
        })->map(function($column) {
            if(array_key_exists('filter_by', $column))
            {
                return (new BrowseColumn())
                    ->setField($column['twinfield_column'])
                    ->setLabel($column['label'])
                    ->setOperator(BrowseColumnOperator::EQUAL())
                    ->setFrom($column['filter_by'])
                    ->setTo($column['filter_by'])
                    ->setVisible(true);
            }

            return (new BrowseColumn())
                    ->setField($column['twinfield_column'])
                    ->setLabel($column['label'])
                    ->setVisible(true);
        });
    }
}


