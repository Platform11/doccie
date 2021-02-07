<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\Administration;
use App\Jobs\GenerateReports;
use App\Http\Requests\StoreAdministrationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\Administration\UpdateRelationManagerRequest;
use App\Http\Requests\Administration\UpdateContactPersonRequest;
use App\Http\Requests\Administration\UpdateInfoRequest;
use App\Http\Requests\Administration\ShowRequest;

class AdministrationController extends Controller
{
    public function __invoke(): \Inertia\Response
    {
        return Inertia::render('Administrations/Index', ['administrations' => Auth::user()->account->administrations()->with(['relation_manager'])->get()]);
    }

    public function create(): \Inertia\Response
    {
        return Inertia::render('Administrations/Create', [
            'colleagues' => Auth::user()->colleagues()->get()
        ]);
    }

    public function store(StoreAdministrationRequest $request): RedirectResponse
    {   
        $validated = $request->validated();
        
        $validated['account_id'] = Auth::user()->account->id;
        $validated['status'] = 'created';
        $validated['last_action_initiator_id'] = Auth::user()->id;

        Administration::create($validated);

        return Redirect::route('administrations.index')->with('success', __('adminsitrations.created_successfully'));
    }

    public function delete(Administration $administration): RedirectResponse
    {   
        $administration->delete();
        return Redirect::route('administrations.index')->with('success', __('adminsitrations.deleted_successfully'));
    }

    public function show(ShowRequest $request, Administration $administration): \Inertia\Response
    {
        return Inertia::render('Administrations/Show', [
            'administration' => $administration->load(['relation_manager', 'reports.author', 'reports.notifications']),
            'colleagues' => Auth::user()->colleagues()->get()
        ]);
    }

    public function sendReport(Administration $administration)
    {
        $administration->status = 'queued';
        $administration->save();

        GenerateReports::dispatch(Administration::find($administration->id), Auth::user());

        return response()->json($administration);
    }

    public function getInfo(Administration $administration)
    {
        return response()->json($administration);
    }

    public function updateInfo(UpdateInfoRequest $request)
    {   
        $administration = Administration::find($request->validated()['administration_id']);
        $administration->name = $request->validated()['name'];
        $administration->code = $request->validated()['code'];
        $administration->save();

        return Redirect::route('administrations.show', ['administration' => $administration->id])->with('success', __('administrations.info_succesfully_updated'));
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
