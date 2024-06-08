<?php

namespace App\Filament\Resources\CarResource\RelationManagers;

use App\Models\Bimehgar;
use App\Models\Car;
use App\Models\User;
use Filament\Actions\CreateAction;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;

class CardexsRelationManager extends RelationManager
{
    protected static string $relationship = 'cardexs';

    protected static ?string $label ='بیمه نامه';
    protected static ?string $pluralModelLabel = "بیمه نامه ها";
    protected static ?string $modelLabel = 'بیمه نامه';

    protected static ?string $pluralLabel = "بیمه نامه ها";

    protected static ?string $title = "بیمه نامه ها";
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                    Forms\Components\Select::make('bimehgar_id')->native(false)
                        ->label('بیمه گر')
                        ->required()
                        ->searchable()
                        ->native(false)
                        ->options(Bimehgar::all()->pluck("name", 'id')->map(function ($name, $id) {
                            return $name  .' '.  Bimehgar::where('id', $id)->first()->family . " - " . Bimehgar::where('id', $id)->first()->shbimeh->name . ' ' . Bimehgar::where('id', $id)->first()->shbimeh->city;
                        })),
                    Forms\Components\Select::make('typebimeh_id')
                        ->label('نوع بیمه')
                        ->native(false)
                        ->relationship('typebimeh', 'name')
                    ,

                ])->columns(2),


                Forms\Components\Section::make()->schema([
                    DateTimePicker::make('start')->label(' تاریخ شروع')->jalali()
                        ->displayFormat('Y/m/d')
                        ->native(false)
                        ->time(false)
                        ->closeOnDateSelection()
                        ->default(now())
                        ->live()
                        ->afterStateUpdated(function(Forms\Set $set){
                            return [
                                $set('expired',function ($state){
                                    return Carbon::parse($state)->addYear();
                                }),
                                $set('alarm',function ($state){
                                    return Carbon::parse($state)->addYear()->subWeek();
                                }),
                            ];
                        }),
                    DateTimePicker::make('expired')->label('تاریخ اتمام')
                        ->jalali()
                        ->displayFormat('Y/m/d')
                        ->live()
                        ->afterStateUpdated(function(Forms\Set $set){
                            return [
                                $set('alarm',function ($state){
                                    return Carbon::parse($state)->addYear()->subWeek();
                                }),
                            ];
                        })
                        ->default(now()->addYear()),
                    DateTimePicker::make('alarm')->label('تاریخ هشدار تمدید')
                        ->jalali()
                        ->displayFormat('Y/m/d')
                        ->default(now()->addYear()->subWeek()),

                ])->columns(3),

                Forms\Components\TextInput::make('price')->label('مبلغ بیمه نامه')->prefix('ریال'),

                Forms\Components\Hidden::make('user_id')->default(function (){
                    return auth()->user()->id;
                }),


                Forms\Components\Textarea::make('description')->label('توضیحات')
                    ->maxLength(255)
                    ->default(null)->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table

            ->columns([
                Tables\Columns\TextColumn::make('bimehgar.name')
                    ->label('نام بیمه گر')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('bimehgar.family')
                    ->label('نام خانوادگی بیمه گر')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
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

                Tables\Columns\TextColumn::make('alarm')->jalaliDate()
                    ->sortable()
                    ->searchable()
                    ->label(' هشدار تمدید'),
                Tables\Columns\TextColumn::make('description')
                    ->label('توضیحات')
                    ->toggleable()
                    ->searchable(),
            ])
            ->defaultSort('start','desc')

            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ReplicateAction::make()
                    ->mutateRecordDataUsing(function (array $data): array {
                        $data['user_id'] = auth()->id();
                        return $data;
                    })
                    ->excludeAttributes(['start','expired','price','alarm']),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
