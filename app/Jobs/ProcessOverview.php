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
use Illuminate\Support\Facades\Cache;
use App\Services\TwinfieldReportComposer;
use App\Events\Overview\Composing\Started as OverviewComposingStarted;
use App\Events\Overview\Composing\Finished as OverviewComposingFinished;
use App\Events\Report\Composing\Started as ReportComposingStarted;
use App\Events\Report\Composing\Finished as ReportComposingFinished;

class ProcessOverview implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var \App\Models\Overview
     */
    protected $overview;

    protected $owner;

    public function uniqueId()
    {
        return $this->overview->id;
    }

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Overview $overview, $owner)
    {
        $this->overview = $overview;
        $this->owner = $owner;
        OverviewComposingQueued::dispatch($this->overview);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {   
        OverviewComposingStarted::dispatch($this->overview);

        foreach($this->overview->reports as $report)
        {   
            ReportComposingStarted::dispatch($report);

            /*
                When supporting other accountancy software create a separate report composer
                and check here if the report will based on a Twinfield administration
                or another accountancy software administration, like Exact Online.
            */
            TwinfieldReportComposer::compose($report);

            ReportComposingFinished::dispatch($report);
        }

        OverviewComposingFinished::dispatch($this->overview);

        $this->overview->notifyStakeHolders();

        $this->release_lock();
    }

    public function failed($e)
    {   
        if($e->getMessage() == 'office_does_not_exist')
        {
          $reason = 'Administratie niet gevonden::Administratie kan niet gevonden worden in Twinfield. Controleer of de administratie in Twinfield bestaat en dat je de juiste rechten hebt.';
        }

        if($e->getMessage() == 'Service unavailable')
        {
          $reason = 'Er is iets misgegaan::Het lijkt er op dat Twinfield een storing heeft. Probeer het nogmaals of anders op een later moment.';
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

        $this->release_lock();
    }

    public function release_lock() {
        Cache::restoreLock($this->overview->administration->account->id.'sendOverview'.$this->overview->administration->id, $this->owner)->release();
    }
}
