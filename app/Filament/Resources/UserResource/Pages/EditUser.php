<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    
    protected function afterSave(): void
    {
        // If 2FA was reset, handle disabling it properly
        if ($this->data['reset_2fa'] ?? false) {
            $this->record->disableTwoFactor();
            $this->notify('success', '2FA has been reset for this user');
        }
    }
} 