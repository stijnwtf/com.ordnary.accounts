<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'SSO Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('User Information')
                    ->schema([
                        Forms\Components\TextInput::make('username')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('first_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('last_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create'),
                        Forms\Components\Select::make('lang')
                            ->options([
                                'nl' => 'Nederlands',
                                'en' => 'English',
                            ])
                            ->default('nl'),
                        Forms\Components\Toggle::make('is_admin')
                            ->label('Admin Access')
                            ->helperText('Grant administrative access to the SSO platform')
                            ->default(false),
                    ]),
                Forms\Components\Section::make('Recovery Information')
                    ->schema([
                        Forms\Components\TextInput::make('recovery_email')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\Toggle::make('recovery_email_verified')
                            ->disabled()
                            ->dehydrated(true),
                        Forms\Components\DateTimePicker::make('recovery_email_verified_at')
                            ->disabled()
                            ->dehydrated(true),
                    ])->collapsible(),
                Forms\Components\Section::make('Two-Factor Authentication')
                    ->schema([
                        Forms\Components\Toggle::make('two_factor_enabled')
                            ->disabled()
                            ->dehydrated(true),
                        Forms\Components\DateTimePicker::make('two_factor_confirmed_at')
                            ->disabled()
                            ->dehydrated(true),
                        Forms\Components\Toggle::make('reset_2fa')
                            ->label('Reset 2FA')
                            ->helperText('This will disable 2FA for the user')
                            ->dehydrated(false)
                            ->live()
                            ->afterStateUpdated(function (Forms\Set $set, bool $state) {
                                if ($state) {
                                    $set('two_factor_enabled', false);
                                    $set('two_factor_secret', null);
                                    $set('two_factor_recovery_codes', null);
                                    $set('two_factor_confirmed_at', null);
                                }
                            }),
                    ])->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('username')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_admin')
                    ->boolean()
                    ->label('Admin'),
                Tables\Columns\IconColumn::make('email_verified_at')
                    ->boolean()
                    ->label('Verified')
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-mark')
                    ->getStateUsing(fn (User $record): bool => $record->email_verified_at !== null),
                Tables\Columns\IconColumn::make('two_factor_enabled')
                    ->boolean()
                    ->label('2FA'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_admin')
                    ->label('Admin Access')
                    ->boolean()
                    ->trueLabel('Yes')
                    ->falseLabel('No')
                    ->placeholder('All'),
                Tables\Filters\TernaryFilter::make('two_factor_enabled')
                    ->label('2FA Enabled')
                    ->boolean()
                    ->trueLabel('Yes')
                    ->falseLabel('No')
                    ->placeholder('All'),
                Tables\Filters\TernaryFilter::make('email_verified')
                    ->label('Email Verified')
                    ->query(function ($query, $state) {
                        if ($state === true) {
                            return $query->whereNotNull('email_verified_at');
                        }
                        if ($state === false) {
                            return $query->whereNull('email_verified_at');
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
            'view' => Pages\ViewUser::route('/{record}'),
        ];
    }
} 