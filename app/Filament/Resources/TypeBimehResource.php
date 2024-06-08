<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TypeBimehResource\Pages;
use App\Filament\Resources\TypeBimehResource\RelationManagers;
use App\Models\TypeBimeh;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TypeBimehResource extends Resource
{
    protected static ?string $model = TypeBimeh::class;
    protected static ?string $label = 'نوع بیمه';
    protected static ?string $navigationGroup ='اطلاعات پایه';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'نوع بیمه';
    protected static ?string $modelLabel = 'نوع بیمه';
    protected static ?string $pluralModelLabel = 'نوع بیمه ها';
    protected static ?string $pluralLabel = 'نوع بیمه ها';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make()->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->label('نام')
                    ])
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('نام'),

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
            'index' => Pages\ListTypeBimehs::route('/'),
//            'create' => Pages\CreateTypeBimeh::route('/create'),
//            'edit' => Pages\EditTypeBimeh::route('/{record}/edit'),
        ];
    }
}
