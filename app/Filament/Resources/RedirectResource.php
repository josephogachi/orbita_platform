<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RedirectResource\Pages;
use App\Models\Redirect;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RedirectResource extends Resource
{
    protected static ?string $model = Redirect::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';
    protected static ?string $navigationGroup = 'SEO & Traffic';
    protected static ?string $navigationLabel = '301 Redirects';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Redirect Mapping')->schema([
                    Forms\Components\TextInput::make('old_url')
                        ->label('Old Dead URL (e.g., old-hotel-lock-page)')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->helperText('Do not include the domain name. Just the path.'),
                        
                    Forms\Components\TextInput::make('new_url')
                        ->label('New Target URL (e.g., /products/rfid-hotel-lock)')
                        ->required()
                        ->helperText('Where should this traffic go now?'),
                        
                    Forms\Components\Select::make('status_code')
                        ->options([
                            301 => '301 - Moved Permanently (Best for SEO)',
                            302 => '302 - Moved Temporarily',
                        ])
                        ->default(301)
                        ->required(),
                ])->columns(1)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('old_url')->searchable()->color('danger'),
                Tables\Columns\IconColumn::make('arrow')
                    ->icon('heroicon-o-arrow-right')
                    ->label('')
                    ->color('success'),
                Tables\Columns\TextColumn::make('new_url')->searchable()->color('success'),
                Tables\Columns\TextColumn::make('status_code')->badge(),
            ])
            ->filters([])
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRedirects::route('/'),
            'create' => Pages\CreateRedirect::route('/create'),
            'edit' => Pages\EditRedirect::route('/{record}/edit'),
        ];
    }
}