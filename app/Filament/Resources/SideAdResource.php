<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SideAdResource\Pages;
use App\Models\SideAd;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SideAdResource extends Resource
{
    protected static ?string $model = SideAd::class;
    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-bar';
    protected static ?string $navigationGroup = 'Website Content';
    protected static ?string $navigationLabel = 'Hero Side Ads';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Ad Content')->schema([
                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->placeholder('e.g., Fingerprint Access'),
                    
                    Forms\Components\TextInput::make('subtitle')
                        ->placeholder('e.g., 30% Off Bulk Orders'),
                        
                    Forms\Components\TextInput::make('badge_text')
                        ->label('Top Badge')
                        ->placeholder('e.g., TRENDING')
                        ->default('FEATURED DEAL'),

                    Forms\Components\TextInput::make('button_text')
                        ->default('View Deal')
                        ->required(),
                        
                    Forms\Components\TextInput::make('link_url')
                        ->url()
                        ->label('Destination URL'),
                ])->columns(2),

                Forms\Components\Section::make('Visuals')->schema([
                    Forms\Components\FileUpload::make('image_path')
                        ->label('Ad Image')
                        ->image()
                        ->directory('ads')
                        ->required()
                        ->helperText('Best size: 400x600px (Portrait)'),

                    Forms\Components\Toggle::make('is_active')
                        ->label('Visible on Website')
                        ->default(true),
                        
                    Forms\Components\TextInput::make('sort_order')
                        ->numeric()
                        ->default(0),
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')->label('Image'),
                Tables\Columns\TextColumn::make('title')->searchable()->weight('bold'),
                Tables\Columns\TextColumn::make('badge_text')->badge()->color('warning'),
                Tables\Columns\ToggleColumn::make('is_active'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSideAds::route('/'),
            'create' => Pages\CreateSideAd::route('/create'),
            'edit' => Pages\EditSideAd::route('/{record}/edit'),
        ];
    }
}