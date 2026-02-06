<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;
    protected static ?string $navigationIcon = 'heroicon-o-camera';
    protected static ?string $navigationGroup = 'Website Content';
    protected static ?string $label = 'Our Projects';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('Project Details')->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, $state) => $set('slug', Str::slug($state))),
                        
                        // ✅ FIXED: Added dehydrated() so the slug saves even when disabled
                        Forms\Components\TextInput::make('slug')
                            ->disabled()
                            ->dehydrated() 
                            ->required()
                            ->unique(Project::class, 'slug', ignoreRecord: true),
                        
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\TextInput::make('client_name')->label('Client / Hotel Name'),
                            Forms\Components\TextInput::make('location')->label('City / Location'),
                            Forms\Components\TextInput::make('service_category')->placeholder('e.g. Hotel Locks Installation'),
                            Forms\Components\DatePicker::make('completion_date'),
                        ]),
                        
                        Forms\Components\MarkdownEditor::make('description')->columnSpanFull(),
                    ])
                ])->columnSpan(2),

                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('Project Images')->schema([
                        Forms\Components\FileUpload::make('thumbnail_image')
                            ->label('Main Thumbnail')
                            ->image()
                            ->imageEditor()
                            ->disk('public')
                            ->directory('projects/thumbnails')
                            ->visibility('public')
                            ->maxSize(10240)
                            ->required(),

                        Forms\Components\FileUpload::make('gallery_images')
                            ->label('Project Gallery')
                            ->multiple()
                            ->reorderable()
                            ->image()
                            ->disk('public')
                            ->directory('projects/gallery')
                            ->visibility('public')
                            ->maxFiles(10)
                            ->maxSize(10240)
                            ->panelLayout('grid'),
                    ]),

                    Forms\Components\Section::make('Visibility')->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Published')
                            ->default(true)
                            ->onColor('success'),

                        Forms\Components\Toggle::make('is_featured')
                            ->label('Feature on Homepage')
                            ->onColor('warning'),
                    ])
                ])->columnSpan(1),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail_image')->square(),
                Tables\Columns\TextColumn::make('title')->searchable()->weight('bold'),
                Tables\Columns\TextColumn::make('client_name')->searchable(),
                Tables\Columns\TextColumn::make('location')->icon('heroicon-m-map-pin')->color('gray'),
                Tables\Columns\ToggleColumn::make('is_active'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}