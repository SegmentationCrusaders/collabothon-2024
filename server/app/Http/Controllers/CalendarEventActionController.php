<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\BankEmployee;
use App\Models\CalendarAction;
use App\Models\CalendarEvent;
use App\Models\ClientEmployee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;

class CalendarEventActionController extends Controller
{
    public function accept(string $uuid): JsonResponse
    {
        try {
            $requester = Auth::user();
            $targetEvent = QueryBuilder::for(CalendarEvent::class)
                ->where('uuid', '=', $uuid)
                ->whereHasPermission(Auth::user(), CalendarEvent::class)
                ->firstOrFail()
            ;

            if ($requester instanceof ClientEmployee) {
                $targetEvent->clientEmployees()->updateExistingPivot($requester, ['accepted' => 1]);
            } else {
                $targetEvent->bankEmployees()->updateExistingPivot($requester, ['accepted' => 1]);
            }

            return response()->json(['message' => 'Event has been accepted successfully.']);
        } catch (Exception $e) {
            Log::error('Failed to accept the calendar event: ' . $e->getMessage());

            return response()->json(['error' => 'Failed to accept the event.' . $e->getMessage()], 500);
        }
    }

    public function decline(string $uuid): JsonResponse
    {
        try {
            $targetEvent = QueryBuilder::for(CalendarEvent::class)
                ->where('uuid', '=', $uuid)
                ->whereHasPermission(Auth::user(), CalendarEvent::class)
                ->firstOrFail()
            ;

            DB::transaction(static function() use ($targetEvent) {
                $targetEvent->deleted_at = (string)now();
                $targetEvent->save();

                $targetEvent->clientEmployees()->updateExistingPivot($targetEvent, ['accepted' => 0]);
                $targetEvent->bankEmployees()->updateExistingPivot($targetEvent, ['accepted' => 0]);
            });

            return response()->json(['message' => 'Event has been declined successfully.']);
        } catch (Exception $e) {
            Log::error('Failed to delete the calendar event: ' . $e->getMessage());

            return response()->json(['error' => 'Failed to decline the event.' . $e->getMessage()], 500);
        }
    }

    public function changeInterval(string $uuid, Request $request): JsonResponse
    {
        try {
            $targetEvent = QueryBuilder::for(CalendarEvent::class)
                ->where('uuid', '=', $uuid)
                ->whereHasPermission(Auth::user(), CalendarEvent::class)
                ->firstOrFail()
            ;

            $bodyContent = json_decode($request->getContent(), true);

            $changedCalendarEvent = $targetEvent->clone();
            $changedCalendarEvent->start_date = $bodyContent['start_date'];
            $changedCalendarEvent->end_date = $bodyContent['end_date'];

            $changedCalendarEvent->save();

            return response()->json(['message' => 'Event has been created successfully.']);
        } catch (Exception $e) {
            Log::error('Failed to add the calendar event: ' . $e->getMessage());

            return response()->json(['error' => 'Failed to add the event.' . $e->getMessage()], 500);
        }
    }
}
