<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
   public function index()
{
    $logs = ActivityLog::with('user')->get();

    $result = $logs->map(function ($log) {
        return [
            'id' => $log->id,
            'action' => $log->action,
            'table_name' => $log->table_name,
            'record_id' => $log->record_id,
            'description' => $log->description,

            'user_id' => $log->user->id ?? null,
            'username' => $log->user->username ?? null,
            

            'created_at' => $log->created_at,
        ];
    });

    return response()->json($result, 200);
}

    public function store(Request $request)
    {
        $request->validate([
            'action' => 'required',
        ]);

        $log = ActivityLog::create([
            'user_id'    => auth()->id(),
            'action'     => $request->action,
            'table_name' => $request->table_name,
            'record_id'  => $request->record_id,
            'description'=> $request->description,
        ]);

        return response()->json([
            'message' => 'Log created',
            'data'    => $log
        ], 201);
    }

    public function destroy($id)
    {
        $log = ActivityLog::find($id);

        if (!$log) {
            return response()->json([
                'message' => 'Log not found'
            ], 404);
        }

        $log->delete();

        return response()->json([
            'message' => 'Log deleted'
        ]);
    }
}