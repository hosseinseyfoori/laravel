<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BimehgarResource\Pages;
use App\Filament\Resources\BimehgarResource\RelationManagers;
use App\Filament\Resources\CarResource\RelationManagers\CardexsRelationManager;
use App\Models\Bimehgar;
use App\Models\ShBimeh;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BimehgarResource extends Resource
{
    protected static ?string $model = Bimehgar::class;

    protected static ?string $label = 'بیمه گر';
    protected static ?string $navigationLabel = 'بیمه گر';
    protected static ?string $modelLabel = 'بیمه گر';
    protected static ?string $pluralModelLabel = 'بیمه گرها';
    protected static ?string $pluralLabel = 'بیمه گرها';
    protected static ?int $navigationSort = 3;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }


    protected static ?string $navigationGroup = 'اطلاعات پایه';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                    Forms\Components\Section::make('مشخصات اولیه')->schema([
                        Forms\Components\TextInput::make('name')->label('نام'),
                        Forms\Components\TextInput::make('family')->label('نام خانوادگی'),
                        Forms\Components\TextInput::make('phone')->label('تلفن دفتر'),
                        Forms\Components\TextInput::make('mobile')->label('همراه'),
                        Forms\Components\TextInput::make('address')->label('آدرس')->columnSpanFull(),
                    ])
                        ->columns(2),

                    Forms\Components\Select::make('shbimeh_id')->native(false)
                        ->label('شرکت بیمه')
                        ->options(ShBimeh::all()->pluck("name", 'id')->map(function ($name, $id) {
                            return $name . " - " . ShBimeh::find($id)->city;
                        }))
                ]
            );
//        Forms\Components\Select::make('shbimeh_id')
//            ->relationship(name:'shbimeh', titleAttribute: 'name',modifyQueryUsing: function ($query){
//                return $query->city;
//            })
//
//            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('نام')
                    ->copyable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('family')->label('نام خانوادگی')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')->label('ادرس')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')->label('تلفن دفتر')
                    ->searchable(),
                Tables\Columns\TextColumn::make('mobile')->label('موبایل')
                    ->searchable(),
                Tables\Columns\TextColumn::make('shbimeh')->label('شرکت بیمه')
                    ->formatStateUsing(fn(ShBimeh $state) => $state->nameAndCity()),
                Tables\Columns\TextColumn::make('created_at')->label('تاریخ ایجاد')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')->label('تاریخ ویرایش')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('shbimeh_id')
                    ->label('شرکت بیمه')
                    ->native(false)
                    ->searchable()
                    ->preload()
                    ->relationship('shbimeh', 'name')
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            RelationManagers\CardexsRelationManager::class
            ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBimehgars::route('/'),
//            'create' => Pages\CreateBimehgar::route('/create'),
            'edit' => Pages\EditBimehgar::route('/{record}/edit'),
        ];
    }
}
