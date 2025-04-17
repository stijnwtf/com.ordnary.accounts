<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\Pages;
use App\Models\Client;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Str;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationIcon = 'heroicon-o-key';

    protected static ?string $navigationLabel = 'SSO Services';

    protected static ?string $navigationGroup = 'SSO Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('client_id')
                            ->required()
                            ->disabled(fn ($record) => $record !== null)
                            ->dehydrated(fn ($record) => $record === null)
                            ->default(fn () => Str::random(32)),
                        Forms\Components\TextInput::make('client_secret')
                            ->password()
                            ->required()
                            ->disabled(fn ($record) => $record !== null)
                            ->dehydrated(fn ($record) => $record === null)
                            ->default(fn () => Str::random(64)),
                        Forms\Components\Textarea::make('redirect_uris')
                            ->required()
                            ->helperText('Enter one URI per line')
                            ->rows(3),
                        Forms\Components\TextInput::make('webhook_url')
                            ->label('Webhook URL')
                            ->url(),
                        Forms\Components\TextInput::make('logout_url')
                            ->label('Logout URL')
                            ->url(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('client_id')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('users_count')
                    ->counts('users')
                    ->label('Connected Users'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Action::make('regenerateCredentials')
                    ->label('Regenerate Credentials')
                    ->color('warning')
                    ->icon('heroicon-o-key')
                    ->requiresConfirmation()
                    ->action(function (Client $record) {
                        $record->update([
                            'client_id' => Str::random(32),
                            'client_secret' => Str::random(64),
                        ]);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'view' => Pages\ViewClient::route('/{record}'),
        ];
    }
} 