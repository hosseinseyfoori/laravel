<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CarResource\Pages;
use App\Filament\Resources\CarResource\RelationManagers;
use App\Models\Car;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Gate;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class CarResource extends Resource
{
    protected static ?string $model = Car::class;

    protected static ?string $label = 'ماشین';
    protected static ?string $navigationLabel = 'ماشین';
    protected static ?string $modelLabel = 'ماشین';
    protected static ?string $pluralModelLabel = 'ماشین ها';
    protected static ?string $pluralLabel = 'ماشین ها';
    protected static ?string $navigationGroup ='اطلاعات پایه';


    public static function getGloballySearchableAttributes(): array
    {
        return [
          'name','code_kargahi','shomaremotor','shomareshasi','pelak'
        ];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
   {
       return [
           $record->name . '' . $record->code_kargahi,' ',$record->shomareshasi,' ',$record->shomaremotor,' ',$record->pelak
       ];
   }

    public static function getNavigationBadge(): ?string
    {
        return static::$model::count();
    }

    protected static ?int $navigationSort = 4;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('نام')
                ->required(),
                Forms\Components\TextInput::make('code_kargahi')
                    ->live(onBlur: true)
                    ->label('کد کارگاهی')->unique('cars', 'code_kargahi',ignoreRecord: true),

                Forms\Components\TextInput::make('pelak')
                    ->label('پلاک'),
                Forms\Components\TextInput::make('year')
                    ->label('سال ساخت'),

                Forms\Components\TextInput::make('shomaremotor')
                    ->label('شماره موتور')->unique('cars', 'shomaremotor',ignoreRecord: true),

                Forms\Components\TextInput::make('shomareshasi')
                    ->label('شماره شاسی')->unique('cars', 'shomareshasi',ignoreRecord: true),
                Forms\Components\TextInput::make('description')
                    ->label('توضیحات')->columnSpanFull(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name',)->label('نام ماشین')
                    ->description(function (Car $car){
                        return "تعداد بیمه نامه ها : ".$car->cardexs->count();
                    })
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('code_kargahi')->label('کد کارگاهی')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('pelak')->label('پلاک')
                    ->toggleable()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('year')->label('سال ساخت')
                    ->toggleable()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('shomaremotor')->label('شماره موتور')
                    ->toggleable()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('shomareshasi')->label('شماره شاسی')
                    ->toggleable()
                    ->sortable()
                    ->searchable(),

                auth()->user()->isAdmin() ? Tables\Columns\ToggleColumn::make('active')->label('وضعیت')
                    ->sortable()
                    ->searchable() :  Tables\Columns\TextColumn::make('active')
                    ->label('وضعیت')
                    ->formatStateUsing(function ($state){
                        return $state === 1 ? "فعال"  : 'غیرفعال';
                }) ,

                Tables\Columns\TextColumn::make('description')->label('توضیحات')
                    ->toggleable()
                    ->sortable()
                    ->searchable(),


            ])
            ->deferLoading()
            ->striped()


            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    ExportBulkAction::make()->label('اکسل')

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
            'index' => Pages\ListCars::route('/'),
//            'create' => Pages\CreateCar::route('/create'),
            'edit' => Pages\EditCar::route('/{record}/edit'),
        ];
    }


}
