<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use App\Models\Employee;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\Models\Subkon;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeeResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-c-user-circle';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Assuming you have a relationship named 'subkon' in your model
              Forms\Components\Select::make('subkon_id')
                    ->label('Select Subkon')
                    ->required()
                    ->relationship('subkon', 'name', function ($query) {
                        $user = Auth::user();

                        // Fetch the user's role ID from the model_has_roles table
                        $roleId = DB::table('model_has_roles')
                            ->where('model_type', get_class($user))
                            ->where('model_id', $user->id)
                            ->value('role_id');

                        // Use caching to get the super_admin role ID
                        $superAdminRoleId = Cache::remember('super_admin_role_id', now()->addDay(), function () {
                            return Role::where('name', 'super_admin')->value('id') ?? 0;
                        });

                        // Check if the user is a super admin
                        if ($roleId === $superAdminRoleId) {
                            // If the user is a super admin, bypass filtering
                            return $query->select('id', 'name', 'kode_subkon'); // Show all subkons
                        } else {
                            // If not a super admin, filter by user's subkon_id
                            return $query->where('id', $user->subkon_id)->select('id', 'name', 'kode_subkon');
                        }
                    })
                    ->preload()
                    ->searchable()
                    ->getSearchResultsUsing(fn (string $search) => 
                        \App\Models\Subkon::where(function ($query) use ($search) {
                            $query->where('name', 'like', "%{$search}%")
                                ->orWhere('kode_subkon', 'like', "%{$search}%");

                            // Further restrict for non-admin users
                            $user = Auth::user();
                            $roleId = DB::table('model_has_roles')
                                ->where('model_type', get_class($user))
                                ->where('model_id', $user->id)
                                ->value('role_id');

                            $superAdminRoleId = Cache::remember('super_admin_role_id', now()->addDay(), function () {
                                return Role::where('name', 'super_admin')->value('id') ?? 0;
                            });

                            if ($roleId !== $superAdminRoleId) {
                                // Non-super admin users can only see their own subkon
                                $query->where('id', $user->subkon_id);
                            }
                        })
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
                Forms\Components\TextInput::make('nik')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('address')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone_number')
                    ->tel()
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('date_of_birth')
                    ->required(),
                Forms\Components\Select::make('speciality')
                    // ->multiple()
                    ->options([
                        'koordinator' => 'Koordinator',
                        'semi' => 'Semi',
                        'welder' => 'Welder',
                        'helper' => 'Helper',
                    ]),
                Forms\Components\FileUpload::make('attachment_ktp')
                    // ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $user = Auth::user();

                if (!$user) {
                    // Handle unauthenticated users (optional)
                    return;
                }

                // Fetch the user's role ID from the model_has_roles table
                $roleId = DB::table('model_has_roles')
                            ->where('model_type', get_class($user))
                            ->where('model_id', $user->id)
                            ->value('role_id');

                // Use caching to get the super_admin role ID
                $superAdminRoleId = Cache::remember('super_admin_role_id', now()->addDay(), function () {
                    return Role::where('name', 'super_admin')->value('id') ?? 0;
                });

                // Check if the user is a super admin
                if ($roleId === $superAdminRoleId) {
                    // If the user is a super admin, bypass filtering
                    return;
                }

                // Apply filter for non-super_admin users
                $userSubkonId = $user->subkon_id ?? null;

                if ($userSubkonId) {
                    $query->where('subkon_id', $userSubkonId);
                } else {
                    $query->whereNull('subkon_id');
                }
            })
            ->columns([
                Tables\Columns\TextColumn::make('subkon.name')
                    ->label('Subkon Name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('nik')
                    ->label('NIK')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_of_birth')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('speciality')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('attachment_ktp')
                    ->square(),
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
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
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
