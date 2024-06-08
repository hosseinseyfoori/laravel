<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MobileReciveResource\Pages;
use App\Filament\Resources\MobileReciveResource\RelationManagers;
use App\Models\MobileRecive;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MobileReciveResource extends Resource
{
    protected static ?string $model = MobileRecive::class;

    protected static ?string $navigationIcon = 'heroicon-o-bell';

    protected static ?string $label = 'دریافت هشدار تمدید';
    protected static ?string $navigationLabel = 'دریافت هشدار تمدید';
    protected static ?string $modelLabel = 'دریافت کنندگان هشدار بیمه';
    protected static ?string $pluralModelLabel = 'دریافت هشدار تمدید';
    protected static ?string $pluralLabel = 'دریافت کنندگان هشدار بیمه';
    protected static ?string $navigationGroup = 'کاربران';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->name('نام ')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('mobile')->label('موبایل')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('active')->label('وضعیت')
                    ->default(1),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('نام و نام خانوادگی')
                    ->searchable(),
                Tables\Columns\TextColumn::make('mobile')
                    ->label('موبایل')
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('active')->label('وضعیت'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
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
            'index' => Pages\ListMobileRecives::route('/'),
            'create' => Pages\CreateMobileRecive::route('/create'),
            'edit' => Pages\EditMobileRecive::route('/{record}/edit'),
        ];
    }
}
