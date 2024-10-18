<?php

namespace App\Helpers;

use App\Helpers\Enums\CalendarActionTagEnum;
use App\Helpers\Enums\RoleEnum;
use App\Models\CalendarAction;
use App\Models\ClientEmployee;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

trait PermissionScopes
{
    private static $permissionsMap = [

        RoleEnum::CEO->value => [

            CalendarAction::class => [
                'calendarActionTags' => [
                    'tag' => [
                        CalendarActionTagEnum::PAYMENT->value,
                        CalendarActionTagEnum::CASH_FLOW->value,
                        CalendarActionTagEnum::SHORT_TERM_LOAN->value,
                        CalendarActionTagEnum::LONG_TERM_LOAN->value,
                        CalendarActionTagEnum::TECHNICAL->value,
                        CalendarActionTagEnum::OTHER->value
                    ]
                ]
            ]

        ],

        RoleEnum::CONTROLLER->value => [

            CalendarAction::class => [
                'calendarActionTags' => [
                    'tag' => [
                        CalendarActionTagEnum::PAYMENT->value,
                        CalendarActionTagEnum::CASH_FLOW->value,
                        CalendarActionTagEnum::SHORT_TERM_LOAN->value,
                        CalendarActionTagEnum::LONG_TERM_LOAN->value,
                        CalendarActionTagEnum::TECHNICAL->value,
                        CalendarActionTagEnum::OTHER->value
                    ]
                ]
            ]

        ],

        RoleEnum::CASH_MANAGEMENT_SPECIALIST->value => [

            CalendarAction::class => [
                'calendarActionTags' => [
                    'tag' => [
                        CalendarActionTagEnum::PAYMENT->value,
                        CalendarActionTagEnum::CASH_FLOW->value,
                        CalendarActionTagEnum::SHORT_TERM_LOAN->value,
                        CalendarActionTagEnum::LONG_TERM_LOAN->value
                    ]
                ]
            ]

        ],

        RoleEnum::ACCOUNTANT->value => [

            CalendarAction::class => [
                'calendarActionTags' => [
                    'tag' => [
                        CalendarActionTagEnum::PAYMENT->value
                    ]
                ]
            ]

        ],

    ];

    /**
     * Scope a query to only include records that have the specified permission.
     *
     * @param  Builder  $query
     * @param  ClientEmployee  $emp
     * @return Builder
     */
    public static function scopeWhereHasPermission(Builder $query, ClientEmployee $emp): Builder
    {
        $permissions = static::$permissionsMap[$emp->role->name] ?? null;

        if ($permissions) {
            foreach ($permissions as $model => $modelPermissions) {
                $query->where(function ($query) use ($model, $modelPermissions) {
                    foreach ($modelPermissions as $relation => $relationPermissions) {
                        $query->whereHas($relation, function ($query) use ($relationPermissions) {
                            foreach ($relationPermissions as $key => $value) {
                                if (is_array($value)) {
                                    $query->whereIn($key, $value);
                                } else {
                                    $query->where($key, $value);
                                }
                            }
                        });
                    }
                });
            }
        }

        return $query;
    }
}
