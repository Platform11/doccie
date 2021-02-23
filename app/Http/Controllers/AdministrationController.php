<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\Administration;
use App\Models\Overview;
use App\Jobs\ProcessOverview;
use App\Http\Requests\Administration\StoreRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\Administration\UpdateRelationManagerRequest;
use App\Http\Requests\Administration\UpdateContactPersonRequest;
use App\Http\Requests\Administration\UpdateInfoRequest;
use App\Http\Requests\Administration\UpdateReportsToIncludeRequest;
use App\Http\Requests\Administration\ShowRequest;
use Illuminate\Support\Facades\Cache;

class AdministrationController extends Controller
{
    public function __invoke(): \Inertia\Response
    {   
        return Inertia::render('Administrations/Index', ['administrations' => Auth::user()->account->administrations()->withLastStatus()->with(['relation_manager'])->get()]);
    }

    public function create(): \Inertia\Response
    {
        return Inertia::render('Administrations/Create', [
            'colleagues' => Auth::user()->colleagues()->get()
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {   
        $validated = $request->validated();
        
        $validated['account_id'] = Auth::user()->account->id;
        $validated['status'] = 'created';
        $validated['last_action_initiator_id'] = Auth::user()->id;

        $administration = Administration::create($validated);
        $administration->setStatus('new', 'Deze administratie bevat nog geen overzichten');

        return Redirect::route('administrations.index')->with('success', __('administrations.created_successfully'));
    }

    public function delete(Administration $administration): RedirectResponse
    {   
        $administration->delete();
        return Redirect::route('administrations.index')->with('success', __('adminsitrations.deleted_successfully'));
    }

    public function show(ShowRequest $request, Administration $administration): \Inertia\Response
    {
        return Inertia::render('Administrations/Show', [
            'administration' => $administration->load(['relation_manager']),
            'overviews' => $administration->overviews()->with(['author', 'last_status', 'notifications.last_status'])->limit(20)->get(),
            'colleagues' => Auth::user()->colleagues()->get()
        ]);
    }

    public function sendOverview(Administration $administration)
    {   
        $lock = Cache::lock($administration->account->id.'sendOverview'.$administration->id, 5);

        if ($lock->get()) {

            $administration->setStatus('preparing_new_overview', 'Overzicht voorbereiden::Er wordt een nieuw overzicht voorbereid om opgesteld te worden.');

            $overview = Overview::create([
                'administration_id' => $administration->id,
                'author_id' => Auth::user()->id,
            ]);

            $overview->setStatus('preparing', 'Overzicht voorbereiden::Overzicht wordt voorbereid om samengesteld te worden.');

            foreach($administration->reports_to_include_in_overview as $report_type)
            {
                $report = $overview->reports()->create([
                    'overview_id' => $overview->id,
                    'type' => $report_type,
                ]);

                $report->setStatus('preparing', 'Rapport voorbereiden::Rapport wordt voorbereid om te worden opgesteld.');
            }

            ProcessOverview::dispatch($overview);

            $overview->setStatus('preparing', 'Verzoek ingediend::Verzoek om rapport te genereren ingediend en zal automatisch in de wachtrij worden gezet.');

            $lock->release();

            return $administration->load(['last_status','relation_manager']);
        }
        return;
    }

    public function updateInfo(UpdateInfoRequest $request)
    {   
        $administration = Administration::find($request->validated()['administration_id']);
        $administration->name = $request->validated()['name'];
        $administration->code = $request->validated()['code'];
        $administration->call_posts_code = $request->validated()['call_posts_code'];
        $administration->save();

        return Redirect::route('administrations.show', ['administration' => $administration->id])->with('success', __('administrations.info_succesfully_updated'));
    }

    public function updateReportsToInclude(UpdateReportsToIncludeRequest $request)
    {
        $administration = Administration::find($request->validated()['administration_id']);
        $administration->reports_to_include_in_overview = $request->validated()['reports_to_include_in_overview'];
        $administration->save();

        return Redirect::route('administrations.show', ['administration' => $administration->id])->with('success', __('administrations.reports_to_include_succesfully_updated'));
    }

    public function updateContactPerson(UpdateContactPersonRequest $request)
    {   
        $administration = Administration::find($request->validated()['administration_id']);
        $administration->contact_first_name = $request->validated()['first_name'];
        $administration->contact_last_name = $request->validated()['last_name'];
        $administration->contact_email = $request->validated()['email'];
        $administration->save();

        return Redirect::route('administrations.show', ['administration' => $administration->id])->with('success', __('administrations.contact_person_succesfully_updated'));
    }

    public function updateRelationManager(UpdateRelationManagerRequest $request)
    {   
        $administration = Administration::find($request->validated()['administration_id']);
        $administration->relation_manager_id = $request->validated()['relation_manager_id'];
        $administration->save();

        return Redirect::route('administrations.show', ['administration' => $administration->id])->with('success', __('administrations.relation_manager_succesfully_updated'));
    }
}
