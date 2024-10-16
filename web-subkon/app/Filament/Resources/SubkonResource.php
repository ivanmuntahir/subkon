<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubkonResource\Pages;
use App\Filament\Resources\SubkonResource\RelationManagers;
use App\Models\Subkon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SubkonResource extends Resource
{
    protected static ?string $model = Subkon::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
             ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->reactive()  // Trigger live updates

                    // Auto-generate kode_subkon when 'name' is updated
                    ->afterStateUpdated(fn (callable $set) => 
                        $set('kode_subkon', self::generateKodeSubkon())
                    ),

                Forms\Components\TextInput::make('kode_subkon')
                    ->required()
                    ->maxLength(255)
                    ->disabled()  // Prevent manual edits
                    ->hint('Automatically generated as SUB-001, SUB-002, etc.'),

                Forms\Components\TextInput::make('total_employee')
                    ->required()
                    ->numeric(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kode_subkon')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_employee')
                    ->numeric()
                    ->sortable(),
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
            'index' => Pages\ListSubkons::route('/'),
            'create' => Pages\CreateSubkon::route('/create'),
            'edit' => Pages\EditSubkon::route('/{record}/edit'),
        ];
    }

    public static function generateKodeSubkon(): string
    {
        // Get the last kode_subkon from the database (e.g., 'SUB-005')
        $lastSubkon = \App\Models\Subkon::orderBy('id', 'desc')->first();

        if ($lastSubkon) {
            // Extract the numeric part and increment it
            $lastNumber = (int) substr($lastSubkon->kode_subkon, 4);  // Extract '005'
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);  // Increment and pad to 3 digits
        } else {
            // If no record exists, start with '001'
            $newNumber = '001';
        }

        // Return the new kode_subkon, e.g., 'SUB-006'
        return "SUB-{$newNumber}";
    }
}
