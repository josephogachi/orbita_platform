<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestimonialResource\Pages;
use App\Models\Testimonial;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

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
                Forms\Components\Section::make('Review Details')->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('client_name')
                            ->required()
                            ->label('Client Name'),

                        Forms\Components\TextInput::make('role')
                            ->label('Role (e.g., General Manager)')
                            ->placeholder('General Manager'),

                        Forms\Components\TextInput::make('company_name')
                            ->label('Company (e.g., Sarova Stanley)')
                            ->placeholder('Sarova Stanley'),

                        Forms\Components\Select::make('rating')
                            ->options([
                                5 => '5 Stars - Excellent',
                                4 => '4 Stars - Good',
                                3 => '3 Stars - Average',
                                2 => '2 Stars - Poor',
                                1 => '1 Star - Terrible',
                            ])
                            ->default(5)
                            ->required(),
                    ]),

                    Forms\Components\Textarea::make('content')
                        ->label('Testimonial Text')
                        ->rows(4)
                        ->required()
                        ->columnSpanFull(),

                    Forms\Components\FileUpload::make('image')
                        ->label('Client Photo (Optional)')
                        ->image()
                        ->directory('testimonials')
                        ->avatar(),

                    Forms\Components\Toggle::make('is_active')
                        ->label('Show on Website')
                        ->default(true),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->circular(),
                
                Tables\Columns\TextColumn::make('client_name')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Testimonial $record): string => $record->role ?? ''),

                Tables\Columns\TextColumn::make('company_name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('rating')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        '5' => 'success',
                        '4' => 'info',
                        '3', '2', '1' => 'warning',
                    })
                    ->formatStateUsing(fn (string $state): string => $state . ' ★'),

                Tables\Columns\ToggleColumn::make('is_active'),
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