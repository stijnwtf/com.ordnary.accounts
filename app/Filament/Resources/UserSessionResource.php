<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserSessionResource\Pages;
use App\Models\UserSession;
use App\Models\User;
use App\Models\Client;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class UserSessionResource extends Resource
{
    protected static ?string $model = UserSession::class;

    protected static ?string $navigationIcon = 'heroicon-o-device-phone-mobile';
    
    protected static ?string $navigationLabel = 'Gebruiker Sessies';
    
    protected static ?string $modelLabel = 'Gebruiker Sessie';
    
    protected static ?string $pluralModelLabel = 'Gebruiker Sessies';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Sessie Informatie')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Gebruiker')
                            ->relationship('user', 'first_name')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->first_name . ' ' . $record->last_name . ' (' . $record->email . ')')
                            ->searchable(['first_name', 'last_name', 'email'])
                            ->required(),
                        Forms\Components\Select::make('client_id')
                            ->label('Client')
                            ->relationship('client', 'name')
                            ->searchable()
                            ->required(),
                        Forms\Components\DateTimePicker::make('expires_at')
                            ->label('Verloopt op')
                            ->required()
                            ->displayFormat('d-m-Y H:i'),
                    ])->columns(3),
                    
                Forms\Components\Section::make('Token Informatie')
                    ->schema([
                        Forms\Components\Textarea::make('token')
                            ->label('Access Token')
                            ->disabled()
                            ->rows(3),
                        Forms\Components\TextInput::make('jti')
                            ->label('JWT ID')
                            ->disabled()
                            ->maxLength(255),
                    ])->columns(1)
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Gebruiker')
                    ->getStateUsing(fn ($record) => $record->user ? $record->user->first_name . ' ' . $record->user->last_name : 'Onbekend')
                    ->searchable(['user.first_name', 'user.last_name'])
                    ->sortable(),
                Tables\Columns\TextColumn::make('client.name')
                    ->label('Client')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('expires_at')
                    ->label('Verloopt op')
                    ->dateTime('d-m-Y H:i')
                    ->sortable()
                    ->color(fn ($record) => $record->expires_at < now() ? 'danger' : 'success'),
                Tables\Columns\IconColumn::make('is_expired')
                    ->label('Status')
                    ->getStateUsing(fn ($record) => $record->expires_at > now())
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Aangemaakt')
                    ->dateTime('d-m-Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('Gebruiker')
                    ->relationship('user', 'first_name')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->first_name . ' ' . $record->last_name),
                Tables\Filters\SelectFilter::make('client_id')
                    ->label('Client')
                    ->relationship('client', 'name'),
                Tables\Filters\TernaryFilter::make('expired')
                    ->label('Vervallen')
                    ->placeholder('Alle sessies')
                    ->trueLabel('Vervallen')
                    ->falseLabel('Actief')
                    ->queries(
                        true: fn (Builder $query) => $query->where('expires_at', '<', now()),
                        false: fn (Builder $query) => $query->where('expires_at', '>=', now()),
                    ),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Bekijken'),
                Tables\Actions\EditAction::make()
                    ->label('Bewerken'),
                Tables\Actions\DeleteAction::make()
                    ->label('Verwijderen')
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Verwijderen')
                        ->requiresConfirmation(),
                ]),
            ])
            ->defaultSort('expires_at', 'desc');
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
            'index' => Pages\ListUserSessions::route('/'),
            'create' => Pages\CreateUserSession::route('/create'),
            'edit' => Pages\EditUserSession::route('/{record}/edit'),
        ];
    }
}
