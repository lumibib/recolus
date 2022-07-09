<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Periods
    |--------------------------------------------------------------------------
    |
    */

    'periods' => [
        'today' => ['label' => 'Today', 'start' => now()->startOfDay(), 'end' => now()->endOfDay()],
        'this_week' => ['label' => 'This week', 'start' => now()->startOfWeek(), 'end' => now()->endOfWeek()],
        'this_month' => ['label' => 'This month', 'start' => now()->startOfMonth(), 'end' => now()->endOfMonth()],
        '6_months' => ['label' => 'Last 6 months', 'start' => now()->change('6 months ago'), 'end' => now()],
        '12_months' => ['label' => 'This year', 'start' => now()->startOfYear(), 'end' => now()->endOfYear()],
        '24_months' => ['label' => 'Last 2 years', 'start' => now()->change('24 months ago'), 'end' => now()],
    ],

];
