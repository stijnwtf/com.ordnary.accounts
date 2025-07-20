<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TodoResource\Pages;
use App\Models\Todo;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TodoResource extends Resource
{
    protected static ?string $model = Todo::class;

    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    
    protected static ?string $navigationLabel = 'Todo\'s';
    
    protected static ?string $modelLabel = 'Todo';
    
    protected static ?string $pluralModelLabel = 'Todo\'s';
    
    protected static ?string $navigationGroup = 'System';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Todo Details')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Titel')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->label('Beschrijving')
                            ->rows(3)
                            ->maxLength(65535),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'todo' => 'Te doen',
                                'in_progress' => 'Bezig',
                                'completed' => 'Voltooid',
                            ])
                            ->default('todo')
                            ->required(),
                        Forms\Components\Select::make('priority')
                            ->label('Prioriteit')
                            ->options([
                                'low' => 'Laag',
                                'medium' => 'Gemiddeld',
                                'high' => 'Hoog',
                                'urgent' => 'Urgent',
                            ])
                            ->default('medium')
                            ->required(),
                    ])->columns(2),
                    
                Forms\Components\Section::make('Toewijzing & Planning')
                    ->schema([
                        Forms\Components\Select::make('assigned_to')
                            ->label('Toegewezen aan')
                            ->relationship('assignee', 'first_name')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->first_name . ' ' . $record->last_name . ' (' . $record->email . ')')
                            ->searchable(['first_name', 'last_name', 'email'])
                            ->nullable(),
                        Forms\Components\Select::make('created_by')
                            ->label('Aangemaakt door')
                            ->relationship('creator', 'first_name')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->first_name . ' ' . $record->last_name . ' (' . $record->email . ')')
                            ->searchable(['first_name', 'last_name', 'email'])
                            ->default(auth()->id())
                            ->required(),
                        Forms\Components\DatePicker::make('due_date')
                            ->label('Vervaldatum')
                            ->displayFormat('d-m-Y')
                            ->nullable(),
                    ])->columns(3),
                    
                Forms\Components\Section::make('Aanvullende Informatie')
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->label('Notities')
                            ->rows(4)
                            ->maxLength(65535)
                            ->nullable(),
                    ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Titel')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'secondary' => 'todo',
                        'warning' => 'in_progress',
                        'success' => 'completed',
                    ])
                    ->formatStateUsing(function ($state) {
                        return match($state) {
                            'todo' => 'Te doen',
                            'in_progress' => 'Bezig',
                            'completed' => 'Voltooid',
                            default => $state,
                        };
                    }),
                Tables\Columns\BadgeColumn::make('priority')
                    ->label('Prioriteit')
                    ->colors([
                        'secondary' => 'low',
                        'primary' => 'medium',
                        'warning' => 'high',
                        'danger' => 'urgent',
                    ])
                    ->formatStateUsing(function ($state) {
                        return match($state) {
                            'low' => 'Laag',
                            'medium' => 'Gemiddeld',
                            'high' => 'Hoog',
                            'urgent' => 'Urgent',
                            default => $state,
                        };
                    }),
                Tables\Columns\TextColumn::make('assignee.first_name')
                    ->label('Toegewezen aan')
                    ->formatStateUsing(fn ($record) => $record->assignee ? $record->assignee->first_name . ' ' . $record->assignee->last_name : 'Niet toegewezen')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('creator.first_name')
                    ->label('Aangemaakt door')
                    ->formatStateUsing(fn ($record) => $record->creator->first_name . ' ' . $record->creator->last_name)
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('due_date')
                    ->label('Vervaldatum')
                    ->date('d-m-Y')
                    ->sortable()
                    ->color(fn ($record) => $record->due_date && $record->due_date->isPast() && $record->status !== 'completed' ? 'danger' : null),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Aangemaakt')
                    ->dateTime('d-m-Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Bijgewerkt')
                    ->dateTime('d-m-Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'todo' => 'Te doen',
                        'in_progress' => 'Bezig',
                        'completed' => 'Voltooid',
                    ]),
                Tables\Filters\SelectFilter::make('priority')
                    ->label('Prioriteit')
                    ->options([
                        'low' => 'Laag',
                        'medium' => 'Gemiddeld',
                        'high' => 'Hoog',
                        'urgent' => 'Urgent',
                    ]),
                Tables\Filters\SelectFilter::make('assigned_to')
                    ->label('Toegewezen aan')
                    ->relationship('assignee', 'first_name')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->first_name . ' ' . $record->last_name),
                Tables\Filters\TernaryFilter::make('overdue')
                    ->label('Achterstallig')
                    ->placeholder('Alle todo\'s')
                    ->trueLabel('Achterstallig')
                    ->falseLabel('Op tijd')
                    ->queries(
                        true: fn (Builder $query) => $query->overdue(),
                        false: fn (Builder $query) => $query->where(function ($q) {
                            $q->where('due_date', '>=', now())
                              ->orWhereNull('due_date')
                              ->orWhere('status', 'completed');
                        }),
                    ),
            ])
            ->actions([
                Tables\Actions\Action::make('mark_completed')
                    ->label('Markeer als voltooid')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn ($record) => $record->status !== 'completed')
                    ->action(function ($record) {
                        $record->update(['status' => 'completed']);
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Todo voltooid!')
                            ->body('De todo is gemarkeerd als voltooid.')
                            ->success()
                            ->send();
                    }),
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
                    Tables\Actions\BulkAction::make('mark_completed')
                        ->label('Markeer als voltooid')
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->action(function ($records) {
                            $records->each->update(['status' => 'completed']);
                            
                            \Filament\Notifications\Notification::make()
                                ->title('Todo\'s voltooid!')
                                ->body(count($records) . ' todo\'s zijn gemarkeerd als voltooid.')
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Verwijderen'),
                ]),
            ])
            ->emptyStateHeading('Geen todo\'s gevonden')
            ->emptyStateDescription('Er zijn nog geen todo\'s aangemaakt. Begin met het organiseren van je taken door je eerste todo toe te voegen.')
            ->emptyStateIcon('heroicon-o-check-circle')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Eerste todo aanmaken')
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
            'index' => Pages\ListTodos::route('/'),
            'create' => Pages\CreateTodo::route('/create'),
            'edit' => Pages\EditTodo::route('/{record}/edit'),
        ];
    }
}
