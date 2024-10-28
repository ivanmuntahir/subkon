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
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SubkonResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Subkon::class;

    protected static ?string $navigationGroup = 'Data Subkon';

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

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
                    ->hint('Automatically generated as SUB-0001, SUB-0002, etc.'),

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
        // Retrieve the highest numeric part from all kode_subkon records (e.g., 'SUBKON-0005')
        $maxSubkon = \App\Models\Subkon::selectRaw("MAX(CAST(SUBSTRING(kode_subkon, 8) AS UNSIGNED)) as max_number")
                        ->where('kode_subkon', 'LIKE', 'SUBKON-%')
                        ->first();

        if ($maxSubkon && $maxSubkon->max_number) {
            // Increment the highest number found and pad to 4 digits
            $newNumber = str_pad($maxSubkon->max_number + 1, 4, '0', STR_PAD_LEFT);
        } else {
            // Start from '0001' if no records exist
            $newNumber = '0001';
        }

        return "SUBKON-{$newNumber}";
    }

     public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any'
        ];
    }
}
