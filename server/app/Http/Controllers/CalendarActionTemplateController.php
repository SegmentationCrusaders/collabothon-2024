<?php

namespace App\Http\Controllers;

use App\Http\Resources\CalendarActionTemplateResource;
use App\Models\CalendarActionTemplate;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Spatie\QueryBuilder\QueryBuilder;

class CalendarActionTemplateController extends Controller
{
    public function index(Request $request): ResourceCollection
    {
        $calendarActionTemplates = QueryBuilder::for(CalendarActionTemplate::class)
            ->with([
                'calendarActionTags',
            ])
            ->whereHasPermission(Auth::user())
            ->paginate(5);

        return CalendarActionTemplateResource::collection($calendarActionTemplates);
    }
}
