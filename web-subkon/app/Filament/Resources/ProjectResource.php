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
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use App\Models\Employee;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use BezhanSalleh\FilamentShield\Traits\HasRoles;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction as ActionsViewAction;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-s-document-duplicate';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
                Forms\Components\TextInput::make('name')
                    ->label('Nama Proyek')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('pic_name')
                    ->required()
                    ->maxLength(255),
                
                Forms\Components\TextInput::make('total_needed')
                    ->label('Total Employees Needed')
                    ->required()
                    ->numeric()
                    ->reactive() // Enables dynamic reactivity based on input
                    ->afterStateUpdated(fn ($state, callable $set) => 
                        $set('certificates_skills', array_fill(0, (int) $state, ['skill' => null])) 
                    ),
                
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
                            ->required()
                            ->columns(3),
                    ])
                    ->visible(fn($get) => $get('total_needed') > 0),
                Forms\Components\Textarea::make('comment')
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('attachment_bast'),
                Forms\Components\FileUpload::make('attachment_photo')
                
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

                //  $data = [];
                // dd ($data['certificates_skills']);

            })
               
            ->columns([
                Tables\Columns\TextColumn::make('subkon.name')
                    ->label('Subkon Name') // Optional: Set a custom label
                    ->sortable()           // Enable sorting by subkon name
                    ->searchable(),        // Optional: Make the column searchable
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('pic_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_needed')
                    ->numeric()
                    ->sortable(),
                // Tables\Columns\TextColumn::make('certificates_skills.skill')
                //     ->sortable(),
               Tables\Columns\TextColumn::make('formatted_certificates_skills')
                    ->label('Certificates / Skills')
                    ->sortable(),
                Tables\Columns\ImageColumn::make('attachment_bast')
                    ->square(),
                Tables\Columns\ImageColumn::make('attachment_photo')
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
                // Tables\Actions\EditAction::make(),
                ActionGroup::make([
                    ActionsViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
                
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
    // public static function routes(RouteRegistrar $routeRegistrar): void
    // {
    //     $routeRegistrar->get('/drag-drop-assignment', ProjectAssignmentDragDrop::class);
    // }

}
