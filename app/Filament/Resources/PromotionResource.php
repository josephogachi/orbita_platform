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
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Hero Banner Configuration')
                    ->description('Manage the main slider on the homepage. Use high-resolution assets.')
                    ->schema([
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\TextInput::make('title')
                                ->label('Main Heading')
                                ->placeholder('e.g. Next-Gen Smart Locks')
                                ->required()
                                ->maxLength(255),
                            
                            Forms\Components\Select::make('type')
                                ->label('Media Type')
                                ->options([
                                    'image' => 'Static Image (JPG/PNG)',
                                    'video' => 'Motion Video (MP4)',
                                ])
                                ->required()
                                ->default('image')
                                ->native(false),
                        ]),

                        Forms\Components\FileUpload::make('file_path')
                            ->label('Banner Media')
                            ->directory('promotions')
                            ->acceptedFileTypes(['image/*', 'video/mp4'])
                            ->maxSize(51200) // 50MB
                            ->required()
                            ->imageEditor() // Only applies if it's an image
                            ->helperText('Images should be 1920x1080 for best results. Videos must be MP4.')
                            ->columnSpanFull(),

                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\TextInput::make('button_text')
                                ->label('CTA Button Label')
                                ->placeholder('e.g. Explore Now')
                                ->default('Explore Now'),

                            Forms\Components\TextInput::make('link_url')
                                ->label('Redirect URL')
                                ->placeholder('https://orbitakenya.com/products/locks')
                                ->url(),
                        ]),

                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\Toggle::make('is_active')
                                ->label('Published')
                                ->default(true)
                                ->onColor('success'),

                            Forms\Components\TextInput::make('sort_order')
                                ->label('Slide Position')
                                ->numeric()
                                ->default(0)
                                ->helperText('0 is the first slide.'),
                        ]),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('file_path')
                    ->label('Preview')
                    ->visibility(fn ($record) => $record->type === 'image') // Preview image if type is image
                    ->placeholder('Video File')
                    ->square()
                    ->size(60),

                Tables\Columns\TextColumn::make('title')
                    ->label('Banner Title')
                    ->searchable()
                    ->weight('bold')
                    ->wrap(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Format')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'video' => 'info',
                        'image' => 'gray',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'video' => 'heroicon-m-video-camera',
                        'image' => 'heroicon-m-photo',
                    }),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Status'),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable()
                    ->badge(),
            ])
            ->reorderable('sort_order')
            ->defaultSort('sort_order', 'asc')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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