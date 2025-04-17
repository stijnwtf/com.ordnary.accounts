<?php

namespace App\Filament\Resources\ClientResource\Pages;

use App\Filament\Resources\ClientResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditClient extends EditRecord
{
    protected static string $resource = ClientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\Action::make('regenerateCredentials')
                ->label('Regenerate Credentials')
                ->color('warning')
                ->icon('heroicon-o-key')
                ->requiresConfirmation()
                ->action(function () {
                    $this->record->update([
                        'client_id' => \Illuminate\Support\Str::random(32),
                        'client_secret' => \Illuminate\Support\Str::random(64),
                    ]);

                    $this->notify('success', 'Credentials regenerated successfully');
                    $this->redirect(ClientResource::getUrl('edit', ['record' => $this->record]));
                }),
        ];
    }
} 