<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Overview;
use App\Events\Overview\Composing\Queued as OverviewComposingQueued;
use App\Events\Overview\Composing\Failed as OverviewComposingFailed;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class ProcessOverview implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 1200;

    /**
     * @var \App\Models\Overview
     */
    protected $overview;

    public function uniqueId()
    {
        return $this->overview->id;
    }

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Overview $overview)
    {
        $this->overview = $overview;
        OverviewComposingQueued::dispatch($this->overview);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {   
        $this->overview->compose()->notifyStakeHolders();
    }

    public function failed($e)
    {   
        if($e->getMessage() == 'office_does_not_exist')
        {
          $reason = 'Administratie niet gevonden::Administratie kan niet gevonden worden in Twinfield. Controleer of de administratie in Twinfield bestaat en dat je de juiste rechten hebt.';
        }

        if($e->getMessage() == 'account_office_code_does_not_exist')
        {
          $reason = 'Geen toegang tot administratie::Controleer of u toegang heeft tot opgegeven administratiecode.';
        }

        if($e->getMessage() == 'Your logon credentials are not valid anymore. Try to log on again.')
        {
          $reason = 'Twinfield inloggegevens niet geacepteerd::De ingestelde inloggegevens werden niet geaccepteerd door Twinfield. Probeer het opnieuw of controleer de Twinfield connectie in je profiel';
        }

        if($e->getMessage() == 'Failed logging in using the credentials, result was "Invalid".')
        {
          $reason = 'Twinfield inloggegevens niet geacepteerd::De ingestelde inloggegevens werden niet geaccepteerd door Twinfield. Controleer de Twinfield connectie in je profiel';
        }

        if($e->getMessage() == 'App\Jobs\GenerateReports has been attempted too many times or run too long. The job may have previously timed out.')
        {
          $reason = 'Rapport opstellen mislukt::Er is een fout opgetreden bij het genereren van het rapport. Probeert het nogmaals of neem contact op met de systeembeheerder.';
        }

        if(strpos('An error occurred on the server. Always include the following error reference when you report this', $e->getMessage()))
        {
          $reason = 'Gegegven ophalen mislukt::Er is een fout opgetreden bij het ophalen van gegevens uit Twinfield. Controleer de opgevraagde kollomen.';
        }

        OverviewComposingFailed::dispatch(
          $this->overview, 
          !empty($reason) ? $reason : 'Er is iets misgegaan::'.$e->getMessage(),
        );
    }
}
