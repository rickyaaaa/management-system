<?php

namespace App\Filament\Resources\ReviewResource\Pages;

use App\Filament\Resources\ReviewResource;
use Filament\Resources\Pages\ListRecords;

class ListReviews extends ListRecords
{
    protected static string $resource = ReviewResource::class;

    public function getTitle(): string
    {
        return 'Submissions Awaiting Review';
    }
}
