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
use Illuminate\Support\Facades\Schema;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    // Professional Staff Icon
    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    // Grouping them in the sidebar
    protected static ?string $navigationGroup = 'User Management';

    protected static ?string $navigationLabel = 'Staff Members';

    protected static ?string $slug = 'staff-members';

    /**
     * 🛡️ INTERNAL STAFF FILTER
     * This ensures only Admins and Sales Agents appear in this resource.
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereIn('role', ['admin', 'sales_agent']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Staff Information')
                    ->description('Manage internal team member access levels.')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Forms\Components\Select::make('role')
                            ->options([
                                'admin' => 'Administrator (Full Access)',
                                'sales_agent' => 'Sales Agent (Restricted)',
                            ])
                            ->required()
                            ->native(false),

                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->helperText('Leave blank to keep current password if editing.'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->weight('bold')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->icon('heroicon-m-envelope')
                    ->searchable(),
                
                Tables\Columns\BadgeColumn::make('role')
                    ->label('Access Level')
                    ->colors([
                        'danger' => 'admin',
                        'warning' => 'sales_agent',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'admin' => 'ADMIN',
                        'sales_agent' => 'SALES TEAM',
                        default => $state,
                    }),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Added On')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->options([
                        'admin' => 'Admins Only',
                        'sales_agent' => 'Sales Team Only',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                
                // 🚀 THE ULTIMATE OVERRIDE DELETE
                Tables\Actions\DeleteAction::make()
                    ->using(function ($record) {
                        // 1. Turn off database strict mode
                        Schema::disableForeignKeyConstraints();
                        
                        // 2. Force the deletion
                        $record->delete();
                        
                        // 3. Turn strict mode back on
                        Schema::enableForeignKeyConstraints();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    
                    // 🚀 THE ULTIMATE OVERRIDE BULK DELETE
                    Tables\Actions\DeleteBulkAction::make()
                        ->using(function ($records) {
                            Schema::disableForeignKeyConstraints();
                            
                            foreach ($records as $record) {
                                $record->delete();
                            }
                            
                            Schema::enableForeignKeyConstraints();
                        }),
                ]),
            ]);
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