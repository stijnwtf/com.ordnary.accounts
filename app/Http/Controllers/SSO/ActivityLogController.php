<?php

namespace App\Http\Controllers\SSO;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Toon een lijst van alle activiteiten.
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latest();
        
        // Filter op gebruiker
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }
        
        // Filter op actie
        if ($request->has('action') && $request->action) {
            $query->where('action', $request->action);
        }
        
        // Filter op model type
        if ($request->has('model_type') && $request->model_type) {
            $query->where('model_type', $request->model_type);
        }
        
        // Filter op datum
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Haal unieke acties op voor het filter
        $actions = ActivityLog::distinct()->pluck('action');
        
        // Haal unieke model types op voor het filter
        $modelTypes = ActivityLog::distinct()->pluck('model_type');
        
        // Pagineer de resultaten
        $logs = $query->paginate(20)->withQueryString();
        
        return view('admin.activity-logs.index', compact('logs', 'actions', 'modelTypes'));
    }
    
    /**
     * Toon de details van een specifieke activiteit.
     */
    public function show(ActivityLog $activityLog)
    {
        return view('admin.activity-logs.show', compact('activityLog'));
    }
    
    /**
     * Verwijder een specifieke activiteit.
     */
    public function destroy(ActivityLog $activityLog)
    {
        $activityLog->delete();
        
        // Log de actie
        ActivityLog::log(
            'delete',
            'Activiteitenlog #' . $activityLog->id . ' verwijderd'
        );
        
        return redirect()->route('activity-logs.index')
            ->with('success', 'Activiteitenlog is succesvol verwijderd.');
    }
    
    /**
     * Verwijder alle activiteiten.
     */
    public function destroyAll()
    {
        $count = ActivityLog::count();
        ActivityLog::truncate();
        
        // Log de actie (deze wordt als eerste nieuwe log toegevoegd)
        ActivityLog::log(
            'delete',
            $count . ' activiteitenlogs verwijderd'
        );
        
        return redirect()->route('activity-logs.index')
            ->with('success', 'Alle activiteitenlogs zijn succesvol verwijderd.');
    }
} 