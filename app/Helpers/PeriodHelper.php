<?php

use App\Models\PeriodClosing;

if (!function_exists('isPeriodLocked')) {

    function isPeriodLocked($date)
    {
        $year  = date('Y', strtotime($date));
        $month = date('n', strtotime($date));

        $period = PeriodClosing::where('year', $year)
            ->where('month', $month)
            ->first();

        // Never closed → open
        if (!$period) return false;

        // Not closed → open
        if (!$period->is_closed) return false;

        // Temporarily reopened → open
        if ($period->reopen_expires_at &&
            now()->lt($period->reopen_expires_at)) {
            return false;
        }

        // Otherwise locked
        return true;
    }
}