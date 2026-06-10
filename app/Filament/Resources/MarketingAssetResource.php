<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MarketingAssetResource\Pages;
use App\Models\MarketingAsset;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MarketingAssetResource extends Resource
{
    protected static ?string $model = MarketingAsset::class;
    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationGroup = 'Marketing';
    protected static ?string $navigationLabel = 'Branding Assets';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Asset Details')
                    ->description('Upload your corporate letterheads and footers.')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->placeholder('e.g., Standard Gold Header')
                            ->maxLength(255),
                        
                        Forms\Components\Select::make('type')
                            ->options([
                                'header' => 'Email Header (Top)',
                                'footer' => 'Email Footer (Bottom)',
                            ])
                            ->required()
                            ->native(false),

                        Forms\Components\FileUpload::make('image_path')
                            ->label('Banner Image')
                            ->image()
                            ->directory('marketing/assets')
                            ->visibility('public')
                            ->required()
                            ->helperText('Recommended width: 600px. High quality PNG or JPG.'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Preview')
                    ->circular(),
                
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('type')
                    ->colors([
                        'primary' => 'header',
                        'gray' => 'footer',
                    ])
                    ->formatStateUsing(fn (string $state): string => strtoupper($state)),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'header' => 'Headers',
                        'footer' => 'Footers',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMarketingAssets::route('/'),
            'create' => Pages\CreateMarketingAsset::route('/create'),
            'edit' => Pages\EditMarketingAsset::route('/{record}/edit'),
        ];
    }
}