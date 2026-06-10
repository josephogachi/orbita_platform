<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JobPostingResource\Pages;
use App\Models\JobPosting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class JobPostingResource extends Resource
{
    protected static ?string $model = JobPosting::class;
    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationGroup = 'HR & Recruitment';
    protected static ?string $navigationLabel = 'Job Postings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Job Details')->schema([
                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->maxLength(255),
                    
                    Forms\Components\Select::make('department')
                        ->options([
                            'Sales & Marketing' => 'Sales & Marketing',
                            'Engineering & Tech' => 'Engineering & Tech',
                            'Customer Support' => 'Customer Support',
                            'Operations & Logistics' => 'Operations & Logistics',
                            'Finance & Admin' => 'Finance & Admin',
                        ])
                        ->searchable(),
                        
                    Forms\Components\TextInput::make('location')
                        ->default('Nairobi, Kenya')
                        ->required(),
                        
                    Forms\Components\Select::make('employment_type')
                        ->options([
                            'Full-time' => 'Full-time',
                            'Part-time' => 'Part-time',
                            'Contract' => 'Contract',
                            'Internship' => 'Internship',
                        ])
                        ->default('Full-time')
                        ->required(),
                ])->columns(2),

                Forms\Components\Section::make('Job Description')->schema([
                    Forms\Components\RichEditor::make('description')
                        ->label('Role Overview & Responsibilities')
                        ->required(),
                        
                    Forms\Components\RichEditor::make('requirements')
                        ->label('Requirements & Qualifications'),
                ]),

                Forms\Components\Section::make('Status & Deadline')->schema([
                    Forms\Components\Toggle::make('is_published')
                        ->label('Published (Visible on /jobs)')
                        ->default(true),
                        
                    Forms\Components\DatePicker::make('closing_date')
                        ->label('Application Deadline')
                        ->minDate(now()),
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->weight('bold'),
                Tables\Columns\TextColumn::make('department')->searchable()->badge(),
                Tables\Columns\TextColumn::make('employment_type')->color('gray'),
                
                Tables\Columns\TextColumn::make('applications_count')
                    ->counts('applications')
                    ->label('Applicants')
                    ->badge()
                    ->color('info')
                    ->icon('heroicon-o-users'),
                    
                Tables\Columns\ToggleColumn::make('is_published')->label('Live'),
                
                Tables\Columns\TextColumn::make('closing_date')
                    ->date('M d, Y')
                    ->sortable()
                    ->color(fn ($record) => $record->closing_date && $record->closing_date < now() ? 'danger' : 'success'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJobPostings::route('/'),
            'create' => Pages\CreateJobPosting::route('/create'),
            'edit' => Pages\EditJobPosting::route('/{record}/edit'),
        ];
    }
}