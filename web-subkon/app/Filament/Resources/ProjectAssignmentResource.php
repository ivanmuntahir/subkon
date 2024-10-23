<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectAssignmentResource\Pages;
use App\Filament\Resources\ProjectAssignmentResource\RelationManagers;
use App\Models\ProjectAssignment;
use App\Models\Employee;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProjectAssignmentResource extends Resource
{
    protected static ?string $model = ProjectAssignment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('project_id')
                    ->label('Project')
                    ->required()
                    ->relationship('project', 'name'),

                Forms\Components\TextInput::make('total_needed')
                    ->label('Total Needed Employees')
                    ->numeric()
                    ->required(),

                Forms\Components\Repeater::make('certificates_skills')
                    ->label('Certificates / Skills')
                    ->schema([
                        Forms\Components\Select::make('skill')
                            ->label('Skill')
                            ->options([
                                'koordinator' => 'Koordinator',
                                'semi' => 'Semi',
                                'welder' => 'Welder',
                                'helper' => 'Helper',
                            ])
                            ->required(),
                    ])
                    ->minItems(1)
                    ->columns(3),

                Forms\Components\Select::make('employee_id')
                    ->label('Employee')
                    ->options(Employee::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('project.name')
                    ->label('Project'),

                Tables\Columns\TextColumn::make('total_needed')
                    ->label('Total Needed'),

                Tables\Columns\TextColumn::make('employee.name')
                    ->label('Assigned Employee'),

                Tables\Columns\TextColumn::make('certificates_skills')
                    ->label('Certificates / Skills')
                    ->formatStateUsing(function ($state) {
                        return implode(', ', array_column($state, 'skill'));
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListProjectAssignments::route('/'),
            'create' => Pages\CreateProjectAssignment::route('/create'),
            'edit' => Pages\EditProjectAssignment::route('/{record}/edit'),
        ];
    }
}
