<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    
    protected static ?string $navigationLabel = 'Gebruikers';
    
    protected static ?string $modelLabel = 'Gebruiker';
    
    protected static ?string $pluralModelLabel = 'Gebruikers';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Persoonlijke Informatie')
                    ->schema([
                        Forms\Components\TextInput::make('first_name')
                            ->label('Voornaam')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('last_name')
                            ->label('Achternaam')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('username')
                            ->label('Gebruikersnaam')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                    ])->columns(3),
                    
                Forms\Components\Section::make('Account Informatie')
                    ->schema([
                        Forms\Components\TextInput::make('email')
                            ->label('E-mailadres')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('password')
                            ->label('Wachtwoord')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create'),
                        Forms\Components\Select::make('lang')
                            ->label('Taal')
                            ->options([
                                'nl' => 'Nederlands',
                                'en' => 'English',
                                'de' => 'Deutsch',
                                'fr' => 'Français',
                            ])
                            ->default('nl')
                            ->required(),
                    ])->columns(3),
                    
                Forms\Components\Section::make('Rechten & Status')
                    ->schema([
                        Forms\Components\Toggle::make('is_admin')
                            ->label('Beheerder')
                            ->helperText('Geef deze gebruiker beheerdersrechten'),
                        Forms\Components\DateTimePicker::make('email_verified_at')
                            ->label('E-mail geverifieerd op')
                            ->displayFormat('d-m-Y H:i'),
                    ])->columns(2),
                    
                Forms\Components\Section::make('Twee-Factor Authenticatie')
                    ->schema([
                        Forms\Components\Toggle::make('two_factor_enabled')
                            ->label('2FA Ingeschakeld')
                            ->disabled(),
                        Forms\Components\DateTimePicker::make('two_factor_confirmed_at')
                            ->label('2FA Bevestigd op')
                            ->displayFormat('d-m-Y H:i')
                            ->disabled(),
                    ])->columns(2)
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Naam')
                    ->getStateUsing(fn ($record) => $record->first_name . ' ' . $record->last_name)
                    ->searchable(['first_name', 'last_name'])
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('E-mailadres')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('username')
                    ->label('Gebruikersnaam')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_admin')
                    ->label('Beheerder')
                    ->boolean()
                    ->trueIcon('heroicon-o-shield-check')
                    ->falseIcon('heroicon-o-user'),
                Tables\Columns\IconColumn::make('email_verified_at')
                    ->label('Geverifieerd')
                    ->boolean()
                    ->getStateUsing(fn ($record) => !is_null($record->email_verified_at))
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                Tables\Columns\IconColumn::make('two_factor_enabled')
                    ->label('2FA')
                    ->boolean()
                    ->trueIcon('heroicon-o-shield-check')
                    ->falseIcon('heroicon-o-shield-exclamation')
                    ->trueColor('success')
                    ->falseColor('gray'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Aangemaakt')
                    ->dateTime('d-m-Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_admin')
                    ->label('Beheerders')
                    ->placeholder('Alle gebruikers')
                    ->trueLabel('Alleen beheerders')
                    ->falseLabel('Geen beheerders'),
                Tables\Filters\TernaryFilter::make('email_verified_at')
                    ->label('E-mail verificatie')
                    ->placeholder('Alle gebruikers')
                    ->trueLabel('Geverifieerd')
                    ->falseLabel('Niet geverifieerd')
                    ->queries(
                        true: fn (Builder $query) => $query->whereNotNull('email_verified_at'),
                        false: fn (Builder $query) => $query->whereNull('email_verified_at'),
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
                        ->label('Verwijderen'),
                ]),
            ])
            ->emptyStateHeading('Geen gebruikers gevonden')
            ->emptyStateDescription('Er zijn nog geen gebruikers geregistreerd in het SSO systeem. Gebruikers kunnen zich registreren via de frontend of door beheerders worden aangemaakt.')
            ->emptyStateIcon('heroicon-o-users')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Nieuwe gebruiker aanmaken')
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
