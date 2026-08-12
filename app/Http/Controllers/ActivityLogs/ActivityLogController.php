<?php

namespace App\Http\Controllers\ActivityLogs;

use App\Http\Controllers\Controller;
use App\Models\ActivityLogs\ActivityLog;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class ActivityLogController extends Controller
{
    public function index(): View
    {
        return view('activity-log.index');
    }

    public function data(): JsonResponse
    {
        $query = ActivityLog::query()->with('user')->latest('created_at');

        return DataTables::eloquent($query)
            ->addColumn('username', fn (ActivityLog $log) => $log->user?->username ?? '—')
            ->addColumn('action_label', fn (ActivityLog $log) => $log->action->label())
            ->editColumn('created_at', fn (ActivityLog $log) => $log->created_at?->format('Y-m-d H:i:s') ?? '—')
            ->toJson();
    }
}
