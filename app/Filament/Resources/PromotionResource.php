<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PromotionResource\Pages;
use App\Models\Promotion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PromotionResource extends Resource
{
    protected static ?string $model = Promotion::class;
    protected static ?string $navigationIcon = 'heroicon-o-megaphone';
    protected static ?string $navigationGroup = 'Website Content';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Banner Details')->schema([
                    Forms\Components\TextInput::make('title')
                        ->placeholder('e.g. Summer Sale Banner'),
                    
                    Forms\Components\Select::make('type')
                        ->options([
                            'image' => 'Image (JPG/PNG)',
                            'video' => 'Video (MP4)',
                        ])
                        ->required()
                        ->default('image')
                        ->reactive(),

                    Forms\Components\FileUpload::make('file_path')
                        ->label('Upload Banner/Video')
                        ->directory('promotions')
                        ->acceptedFileTypes(['image/*', 'video/mp4'])
                        ->maxSize(50000) // 50MB for videos
                        ->required()
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('link_url')
                        ->url()
                        ->placeholder('https://...'),

                    Forms\Components\Toggle::make('is_active')->default(true),
                    Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('file_path')
                    ->label('Preview')
                    ->checkFileExistence(false) // Fixes preview for some servers
                    ->square(),
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('type')->badge(),
                Tables\Columns\ToggleColumn::make('is_active'),
            ])
            ->defaultSort('sort_order', 'asc')
            ->reorderable('sort_order') // Allow drag-drop reordering
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPromotions::route('/'),
            'create' => Pages\CreatePromotion::route('/create'),
            'edit' => Pages\EditPromotion::route('/{record}/edit'),
        ];
    }
}