<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\Pages;
use App\Models\Client;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    
    protected static ?string $navigationLabel = 'Clients';
    
    protected static ?string $modelLabel = 'Client';
    
    protected static ?string $pluralModelLabel = 'Clients';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Client Informatie')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Naam')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('client_id')
                            ->label('Client ID')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->default(fn () => 'client_' . \Illuminate\Support\Str::random(32))
                            ->disabled(fn (string $context): bool => $context === 'create')
                            ->dehydrated()
                            ->helperText('Wordt automatisch gegenereerd bij het aanmaken'),
                        Forms\Components\TextInput::make('client_secret')
                            ->label('Client Secret')
                            ->required()
                            ->password()
                            ->revealable()
                            ->maxLength(255)
                            ->default(fn () => \Illuminate\Support\Str::random(64))
                            ->disabled(fn (string $context): bool => $context === 'create')
                            ->dehydrated()
                            ->helperText('Wordt automatisch gegenereerd bij het aanmaken'),
                    ])->columns(2),
                    
                Forms\Components\Section::make('URL Configuratie')
                    ->schema([
                        Forms\Components\Textarea::make('redirect_uris')
                            ->label('Redirect URIs')
                            ->required()
                            ->helperText('Voer één URI per regel in')
                            ->rows(4),
                        Forms\Components\TextInput::make('webhook_url')
                            ->label('Webhook URL')
                            ->url()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('logout_url')
                            ->label('Logout URL')
                            ->url()
                            ->maxLength(255),
                    ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Naam')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('client_id')
                    ->label('Client ID')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('redirect_uris')
                    ->label('Redirect URIs')
                    ->limit(50)
                    ->tooltip(function ($record) {
                        return $record->redirect_uris;
                    }),
                Tables\Columns\IconColumn::make('webhook_url')
                    ->label('Webhook')
                    ->boolean()
                    ->getStateUsing(fn ($record) => !empty($record->webhook_url))
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray'),
                Tables\Columns\IconColumn::make('logout_url')
                    ->label('Logout URL')
                    ->boolean()
                    ->getStateUsing(fn ($record) => !empty($record->logout_url))
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Aangemaakt')
                    ->dateTime('d-m-Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Bekijken'),
                Tables\Actions\EditAction::make()
                    ->label('Bewerken'),
                Tables\Actions\Action::make('regenerate_credentials')
                    ->label('Credentials Regenereren')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Credentials Regenereren')
                    ->modalDescription('Weet je zeker dat je nieuwe client ID en secret wilt genereren? De huidige credentials werken daarna niet meer.')
                    ->modalSubmitActionLabel('Ja, regenereren')
                    ->action(function ($record) {
                        $record->update([
                            'client_id' => 'client_' . \Illuminate\Support\Str::random(32),
                            'client_secret' => \Illuminate\Support\Str::random(64),
                        ]);
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Credentials geregenereerd')
                            ->body('Nieuwe client ID en secret zijn gegenereerd.')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\DeleteAction::make()
                    ->label('Verwijderen')
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Verwijderen'),
                ]),
            ])
            ->emptyStateHeading('Geen clients gevonden')
            ->emptyStateDescription('Er zijn nog geen OAuth clients geregistreerd in het systeem. Clients zijn applicaties die toegang hebben tot de SSO dienst en gebruikers kunnen authenticeren.')
            ->emptyStateIcon('heroicon-o-building-office')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Eerste client aanmaken')
                    ->icon('heroicon-o-plus'),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
        ];
    }
}
