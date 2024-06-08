<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShBimehResource\Pages;
use App\Filament\Resources\ShBimehResource\RelationManagers;
use App\Models\ShBimeh;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ShBimehResource extends Resource
{
    protected static ?string $model = ShBimeh::class;
    protected static ?string $label = 'شرکت بیمه';
    protected static ?string $navigationGroup ='اطلاعات پایه';
    protected static ?int $navigationSort = 2;


    protected static ?string $navigationLabel = 'شرکت بیمه';
    protected static ?string $modelLabel = 'شرکت بیمه';
    protected static ?string $pluralModelLabel = 'شرکت های بیمه';
    protected static ?string $pluralLabel = 'شرکت های بیمه';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('نام شرکت بیمه')
                    ->autofocus()
                    ->required(),
                Forms\Components\TextInput::make('city')
                    ->label('شهر')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('شماره'),
                Tables\Columns\TextColumn::make('name')
                    ->label('نام شرکت بیمه')
                    ->searchable(),
                Tables\Columns\TextColumn::make('city')
                    ->label('شهر')
                    ->searchable(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListShBimehs::route('/'),
            'create' => Pages\CreateShBimeh::route('/create'),
            'edit' => Pages\EditShBimeh::route('/{record}/edit'),
        ];
    }
}
