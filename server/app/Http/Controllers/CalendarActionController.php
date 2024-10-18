<?php

namespace App\Http\Controllers;

use App\Http\Resources\CalendarActionResource;
use App\Models\CalendarAction;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Spatie\QueryBuilder\QueryBuilder;

class CalendarActionController extends Controller
{
    public function index(Request $request): ResourceCollection
    {
        $calendarActions = QueryBuilder::for(CalendarAction::class)
            ->with([
                'calendarActionTags',
                'calendarEvents' => function ($query) {
                    $query->withTrashed();
                },
                'calendarActionStatus'
            ])
            ->paginate(5);

        return CalendarActionResource::collection($calendarActions);
    }
}
