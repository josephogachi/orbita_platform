<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShippingZoneResource\Pages;
use App\Filament\Resources\ShippingZoneResource\RelationManagers;
use App\Models\ShippingZone;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ShippingZoneResource extends Resource
{
    protected static ?string $model = ShippingZone::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Section 1: The Zone Info
                Forms\Components\Section::make('Zone Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Zone Name')
                            ->placeholder('e.g., Nairobi Region')
                            ->required(),

                        Forms\Components\TagsInput::make('areas')
                            ->label('Covered Areas')
                            ->helperText('Type a town/location and press Enter (e.g., Westlands, Kilimani)')
                            ->placeholder('Add a location...')
                            ->columnSpanFull(),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ]),

                // Section 2: The Rates (Repeater)
                Forms\Components\Section::make('Shipping Rates')
                    ->description('Define shipping costs based on weight ranges.')
                    ->schema([
                        Forms\Components\Repeater::make('rates')
                            ->relationship() // This connects to the 'rates' method in your Model
                            ->schema([
                                Forms\Components\TextInput::make('weight_min')
                                    ->label('Min Weight (kg)')
                                    ->numeric()
                                    ->default(0)
                                    ->required(),

                                Forms\Components\TextInput::make('weight_max')
                                    ->label('Max Weight (kg)')
                                    ->helperText('Leave empty for "and above"')
                                    ->numeric(),

                                Forms\Components\TextInput::make('cost')
                                    ->label('Shipping Cost')
                                    ->numeric()
                                    ->prefix('KES')
                                    ->required(),
                            ])
                            ->columns(3) // Arranges inputs side-by-side
                            ->defaultItems(1)
                            ->addActionLabel('Add New Rate Rule'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // 1. Show the Zone Name
                Tables\Columns\TextColumn::make('name')
                    ->label('Zone Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                // 2. Show the specific areas (truncated so it doesn't take up too much space)
                Tables\Columns\TextColumn::make('areas')
                    ->label('Covered Areas')
                    ->limit(50)
                    ->badge() // Makes them look like little tags
                    ->separator(','),

                // 3. Show Active Status
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),

                // 4. Show Creation Date
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Add a filter to easily see only Active/Inactive zones
                Tables\Filters\Filter::make('is_active')
                    ->label('Active Only')
                    ->query(fn ($query) => $query->where('is_active', true))
                    ->toggle(),
            ])
            ->actions([
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
            'index' => Pages\ListShippingZones::route('/'),
            'create' => Pages\CreateShippingZone::route('/create'),
            'edit' => Pages\EditShippingZone::route('/{record}/edit'),
        ];
    }
}
