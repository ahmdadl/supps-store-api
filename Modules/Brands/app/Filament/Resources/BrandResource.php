<?php

namespace Modules\Brands\Filament\Resources;

use Modules\Brands\Filament\Resources\BrandResource\Pages;
use Modules\Brands\Filament\Resources\BrandResource\RelationManagers;
use Modules\Brands\Models\Brand;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Modules\Core\Utils\FilamentUtils;

class BrandResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Brand::class;

    protected static ?string $navigationIcon = "heroicon-o-rectangle-stack";

    protected static ?string $navigationGroup = "Catalog";

    public static function getNavigationBadge(): ?string
    {
        return (string) static::$model::count();
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Tabs::make("brandDetails")
                ->translateLabel()
                ->tabs([
                    Forms\Components\Tabs\Tab::make("content")
                        ->icon("heroicon-o-globe-alt")
                        ->translateLabel()
                        ->schema([
                            ...multiLangInput(
                                Forms\Components\TextInput::make("title")
                                    ->translateLabel()
                                    ->required()
                            ),
                            ...multiLangInput(
                                Forms\Components\Textarea::make("description")
                                    ->translateLabel()
                                    ->rows(4)
                            ),
                        ])
                        ->columns(2),
                    Forms\Components\Tabs\Tab::make("image")
                        ->icon("heroicon-o-photo")
                        ->translateLabel()
                        ->schema([
                            Forms\Components\FileUpload::make("image")
                                ->translateLabel()
                                ->image()
                                ->maxSize(1 * 1024)
                                ->disk("public")
                                ->helperText("Maximum file size: 1MB.")
                                ->storeFiles(false)
                                ->dehydrateStateUsing(
                                    fn(
                                        $state
                                    ) => FilamentUtils::storeSingleFile($state)
                                ),
                        ]),
                    Forms\Components\Tabs\Tab::make("settings")
                        ->icon("heroicon-o-cog")
                        ->translateLabel()
                        ->schema([
                            Forms\Components\Toggle::make("is_main")
                                ->translateLabel()
                                ->default(false),
                            Forms\Components\Toggle::make("is_active")
                                ->translateLabel()
                                ->default(true),
                            sortOrderInput(static::$model),
                        ])
                        ->columns(2),
                    metaTabInputs(),
                ])
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("id")
                    ->label("ID")
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\ImageColumn::make("image")
                    ->translateLabel()
                    ->checkFileExistence(false)
                    ->circular(),
                Tables\Columns\TextColumn::make("title")
                    ->translateLabel()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make("slug")
                    ->translateLabel()
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make("is_main")
                    ->translateLabel()
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make("is_active")
                    ->translateLabel()
                    ->boolean(),
                Tables\Columns\TextColumn::make("sort_order")
                    ->translateLabel()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make("created_at")
                    ->translateLabel()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make("deleted_at")
                    ->translateLabel()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters(
                [
                    Tables\Filters\Filter::make("search")
                        ->form([
                            Forms\Components\TextInput::make("search")
                                ->label("Search")
                                ->placeholder(
                                    "Search by title, description, or slug"
                                ),
                        ])
                        ->query(function ($query, array $data) {
                            if ($data["search"]) {
                                $query->where(function ($query) use ($data) {
                                    $search = $data["search"];
                                    $query
                                        ->where(
                                            "title->en",
                                            "like",
                                            "%{$search}%"
                                        )
                                        ->orWhere(
                                            "title->ar",
                                            "like",
                                            "%{$search}%"
                                        )
                                        ->orWhere(
                                            "description->en",
                                            "like",
                                            "%{$search}%"
                                        )
                                        ->orWhere(
                                            "description->ar",
                                            "like",
                                            "%{$search}%"
                                        )
                                        ->orWhere(
                                            "slug",
                                            "like",
                                            "%{$search}%"
                                        );
                                });
                            }
                        })
                        ->indicateUsing(function (array $data): ?string {
                            return $data["search"]
                                ? "Searching for: {$data["search"]}"
                                : null;
                        }),

                    Tables\Filters\TernaryFilter::make(
                        "is_main"
                    )->translateLabel(),

                    Tables\Filters\Filter::make("created_at")
                        ->form([
                            Forms\Components\DatePicker::make(
                                "created_from"
                            )->translateLabel(),
                            Forms\Components\DatePicker::make(
                                "created_until"
                            )->translateLabel(),
                        ])
                        ->query(function ($query, array $data) {
                            if ($data["created_from"]) {
                                $query->whereDate(
                                    "created_at",
                                    ">=",
                                    $data["created_from"]
                                );
                            }
                            if ($data["created_until"]) {
                                $query->whereDate(
                                    "created_at",
                                    "<=",
                                    $data["created_until"]
                                );
                            }
                        })
                        ->columns(2)
                        ->indicateUsing(function (array $data): ?string {
                            $indicators = [];
                            if ($data["created_from"]) {
                                $indicators[] = "Created From: {$data["created_from"]}";
                            }
                            if ($data["created_until"]) {
                                $indicators[] = "Created Until: {$data["created_until"]}";
                            }
                            return !empty($indicators)
                                ? implode(", ", $indicators)
                                : null;
                        }),

                    Tables\Filters\TrashedFilter::make()->translateLabel(),
                    activeToggler(),
                ],
                FiltersLayout::AboveContentCollapsible
            )
            ->filtersFormColumns(4)
            ->actions([
                Tables\Actions\EditAction::make()->iconButton(),
                Tables\Actions\DeleteAction::make()->iconButton(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ReplicateAction::make(),
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
            "index" => Pages\ListBrands::route("/"),
            "create" => Pages\CreateBrand::route("/create"),
            "edit" => Pages\EditBrand::route("/{record}/edit"),
        ];
    }

    public static function getPermissionPrefixes(): array
    {
        return ["view", "create", "update", "delete", "restore", "replicate"];
    }
}
