<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestimonialResource\Pages;
use App\Models\Testimonial;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class TestimonialResource extends Resource
{
    protected static ?string $model = Testimonial::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';
    protected static ?string $navigationGroup = 'Website Content';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Success Story Details')
                    ->description('Manage client feedback and ratings displayed on the homepage.')
                    ->schema([
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\TextInput::make('client_name')
                                ->label('Client Name')
                                ->placeholder('e.g., John Doe')
                                ->required()
                                ->maxLength(255),

                            Forms\Components\TextInput::make('role')
                                ->label('Professional Role')
                                ->placeholder('e.g., Operations Manager')
                                ->maxLength(255),

                            Forms\Components\TextInput::make('company_name')
                                ->label('Company / Hotel Name')
                                ->placeholder('e.g., Hilton Nairobi')
                                ->maxLength(255),

                            Forms\Components\Select::make('rating')
                                ->label('Client Rating')
                                ->options([
                                    5 => '5 Stars - Excellent',
                                    4 => '4 Stars - Very Good',
                                    3 => '3 Stars - Good',
                                    2 => '2 Stars - Fair',
                                    1 => '1 Star - Poor',
                                ])
                                ->default(5)
                                ->required()
                                ->native(false), // Classier dropdown look
                        ]),

                        Forms\Components\Textarea::make('content')
                            ->label('Testimonial Content')
                            ->placeholder('Paste the client quote here...')
                            ->rows(4)
                            ->required()
                            ->columnSpanFull()
                            ->helperText('Try to keep it under 300 characters for the best display on the homepage.'),

                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\FileUpload::make('image')
                                ->label('Client Avatar')
                                ->image()
                                ->avatar() // Forces circular crop/display
                                ->imageEditor()
                                ->directory('testimonials')
                                ->helperText('Square photos work best.'),

                            Forms\Components\Group::make()->schema([
                                Forms\Components\Toggle::make('is_active')
                                    ->label('Publish to Website')
                                    ->helperText('When enabled, this review will appear in the testimonials slider.')
                                    ->default(true)
                                    ->onColor('success'),
                                
                                Forms\Components\TextInput::make('sort_order')
                                    ->numeric()
                                    ->default(0)
                                    ->label('Display Priority')
                                    ->helperText('Lower numbers appear first in the slider.'),
                            ]),
                        ]),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Photo')
                    ->circular()
                    ->size(40),
                
                Tables\Columns\TextColumn::make('client_name')
                    ->label('Client')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (Testimonial $record): string => $record->company_name ?? 'Individual'),

                Tables\Columns\TextColumn::make('content')
                    ->label('Review Snippet')
                    ->limit(50)
                    ->color('gray')
                    ->wrap()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('rating')
                    ->label('Rating')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        '5' => 'success',
                        '4' => 'info',
                        default => 'warning',
                    })
                    ->formatStateUsing(fn (string $state): string => $state . ' ★')
                    ->sortable(),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Live'),
                
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->reorderable('sort_order') // Allows manual dragging in the list
            ->defaultSort('sort_order', 'asc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')->label('Published Only'),
                Tables\Filters\SelectFilter::make('rating')
                    ->options([
                        5 => '5 Stars',
                        4 => '4 Stars',
                    ]),
            ])
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
            'index' => Pages\ListTestimonials::route('/'),
            'create' => Pages\CreateTestimonial::route('/create'),
            'edit' => Pages\EditTestimonial::route('/{record}/edit'),
        ];
    }
}