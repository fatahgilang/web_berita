<?php

namespace App\Filament\Resources\News\Tables;

class NewsTable
{
    public static function getActions(): array
    {
        return [
            // Tambahkan action di sini, contoh:
            // EditAction::make(),
            // DeleteAction::make(),
        ];
    }

    public static function getBulkActions(): array
    {
        return [
            // Tambahkan bulk action di sini, contoh:
            // BulkDeleteAction::make(),
        ];
    }
}
