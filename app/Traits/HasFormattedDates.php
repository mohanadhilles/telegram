<?php

namespace App\Traits;

use Carbon\Carbon;

trait HasFormattedDates
{
    /**
     * Format the model's date attributes.
     *
     * @param  \DateTimeInterface  $date
     * @return string|null
     */
    protected function serializeDate(\DateTimeInterface $date): ?string
    {
        return $date->format('D, M j, Y h:i A');
    }
}
