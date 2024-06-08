<?php

namespace App\Filament\Resources\BimehgarResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CardexsRelationManager extends RelationManager
{
    protected static string $relationship = 'cardexs';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('bimehgar.shbimeh.name')
                    ->label('شرکت بیمه')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('bimehgar.shbimeh.city')
                    ->label('استان')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('typebimeh.name')
                    ->label('نوع بیمه')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('ثبت کننده')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('price')
                    ->formatStateUsing(function (string $state) {
                        return number_format($state, 0, ',', ',');
                    })
                    ->label('مبلغ(ریال)')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('start')->jalaliDate()
                    ->sortable()
                    ->searchable()
                    ->label('تاریخ شروع'),

                Tables\Columns\TextColumn::make('expired')->jalaliDate()
                    ->sortable()
                    ->searchable()
                    ->label('تاریخ اتمام'),
                Tables\Columns\TextColumn::make('description')
                    ->label('توضیحات')
                    ->toggleable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
//                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
//                Tables\Actions\EditAction::make(),
//                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
