<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Resources\ProjectResource\RelationManagers;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-s-document-duplicate';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('subkon_id')
                ->label('Subkon')
                ->relationship('subkon', 'name')  // Refers to the 'subkon' relationship in Employee model
                ->required()
                ->preload()
                ->searchable()
                ->getSearchResultsUsing(fn (string $query) => 
                    \App\Models\Subkon::where('name', 'like', "%{$query}%")
                        ->orWhere('kode_subkon', 'like', "%{$query}%")
                        ->get()
                        ->mapWithKeys(fn ($subkon) => [
                            $subkon->id => "{$subkon->kode_subkon} - {$subkon->name}"
                        ])
                )
                ->getOptionLabelUsing(fn ($value) => 
                    optional(\App\Models\Subkon::find($value))->kode_subkon
                        . ' - ' 
                        . optional(\App\Models\Subkon::find($value))->name
                ),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('pic_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('total_needed')
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('certificates_skills')
                    ->options([
                        'welder' => 'Welder',
                        'helper' => 'Helper',
                        'multi' => 'Multi Role',
                    ]),
                Forms\Components\Textarea::make('comment')
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('attachment_bast'),
                Forms\Components\FileUpload::make('attachment_photo'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('subkon_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('pic_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_needed')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('certificates_skills')
                    ->searchable(),
                Tables\Columns\TextColumn::make('attachment_bast')
                    ->searchable(),
                Tables\Columns\TextColumn::make('attachment_photo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
