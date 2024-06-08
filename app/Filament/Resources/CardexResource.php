<?php

namespace App\Filament\Resources;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\Resources\CardexResource\Pages;

use App\Infolists\Components\DateInfo;
use App\Infolists\Components\ImageInfo;
use App\Models\Bimehgar;
use App\Models\Car;
use App\Models\Cardex;

use App\Models\TypeBimeh;


use App\Models\User;
use DateTime;
use Filament\Forms;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;

use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Number;
use Nette\Utils\Image;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use Symfony\Component\Yaml\Yaml;
use function Laravel\Prompts\alert;
use Filament\Forms\Components\Actions\Action;


class CardexResource extends Resource
{
    protected static ?string $model = Cardex::class;

    protected static ?string $label = 'بیمه نامه';
    protected static ?string $navigationLabel = 'بیمه نامه';
    protected static ?string $modelLabel = 'بیمه نامه';
    protected static ?string $pluralModelLabel = 'بیمه نامه ها';
    protected static ?string $pluralLabel = 'بیمه نامه ها';

    protected static ?string $navigationIcon = 'heroicon-o-book-open';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                    Forms\Components\Select::make('bimehgar_id')->native(false)
                        ->label('بیمه گر')
                        ->required()
                        ->validationMessages([
                            'required' => 'بیمه گر باید انتخاب شود.',
                        ])
                        ->searchable()
                        ->native(false)
                        ->options(Bimehgar::all()->pluck("name", 'id')->map(function ($name, $id) {
                            if (auth()->user()->isAdmin())
                                return $name . ' ' .  Bimehgar::where('id', $id)->first()->family . " - " . Bimehgar::where('id', $id)->first()->shbimeh->name . ' ' . Bimehgar::where('id', $id)->first()->shbimeh->city;

                            return Bimehgar::where('id', $id)->first()->shbimeh->name . ' ' . Bimehgar::where('id', $id)->first()->shbimeh->city;


                        })),
                    Forms\Components\Select::make('typebimeh_id')
                        ->label('نوع بیمه')
                        ->required()
                        ->preload()
//                        ->editOptionForm([
//                            Forms\Components\TextInput::make('name')
//                                ->required(),
//                        ])
                        ->validationMessages([
                            'required' => 'نوع بیمه باید انتخاب شود.',
                        ])
                        ->native(false)
                        ->relationship('typebimeh', 'name')
                    ,
                    Forms\Components\Select::make('car_id')
                        ->label('وسیله نقلیه')
                        ->required()
                        ->preload()
                        ->optionsLimit(1000)
                        ->searchPrompt('وسیله نقلیه جستجو کنید ...')
                        ->native(false)
                        ->validationMessages([
                            'required' => 'وسیله نقلیه باید انتخاب شود.',
                        ])
//                        ->options(Car::Where('active','=',true)->pluck("name", 'id')->map(function ($name, $id) {
//                            return Car::find($id)->code_kargahi ." / ". Car::find($id)->name;
//                        }))
                        ->createOptionForm([
                            Forms\Components\TextInput::make('name')
                                ->label('نام ماشین')
                                ->required(),
                            Forms\Components\TextInput::make('code_kargahi')
                                ->label('کد کارگاهی')
                                ->required(),
                        ])
                        ->relationship('car', 'name', modifyQueryUsing: function (Builder $query) {
                            return $query->where('active', true)->orderBy('code_kargahi');
                        })->getOptionLabelFromRecordUsing(fn(Model $record) => "{$record->code_kargahi} - {$record->name}")
                        ->searchable(['name', 'code_kargahi']),


                ])->columns(3),


                Forms\Components\Section::make()->schema([
                    DateTimePicker::make('start')->label(' تاریخ شروع')->jalali()
                        ->displayFormat('Y/m/d')
                        ->native(false)
                        ->time(false)
                        ->closeOnDateSelection()
                        ->default(now())
//                        ->minDate(now()->subWeek())
                        ->live()

                        ->afterStateUpdated(function (Forms\Set $set) {
                            return [
                                $set('expired', function ($state) {
                                    return Carbon::parse($state)->addYear();
                                }),
                                $set('alarm', function ($state) {
                                    return Carbon::parse($state)->addYear()->subWeek();
                                }),
                            ];
                        }),
                    DateTimePicker::make('expired')->label('تاریخ اتمام')
                        ->jalali()
                        ->live()
                        ->time(false)
                        ->afterStateUpdated(function (Forms\Set $set) {
                            return $set('alarm', function ($state) {
                                return Carbon::parse($state)->subWeek();
                            });
                        })
                        ->closeOnDateSelection()
                        ->displayFormat('Y/m/d')
                        ->default(now()->addYear()),

                    DateTimePicker::make('alarm')->label('تاریخ هشدار تمدید')
                        ->jalali()
                        ->time(false)
                        ->closeOnDateSelection()
                        ->displayFormat('Y/m/d')
                        ->default(now()->addYear()->subWeek()),

                ])->columns(3),

                Forms\Components\TextInput::make('price')
                    ->label('مبلغ بیمه نامه')->prefix('ریال'),


                Forms\Components\Section::make()->schema([
                        Forms\Components\FileUpload::make('image')
                            ->multiple()
                            ->openable()
                            ->downloadable()
                            ->imagePreviewHeight('300')
                            ->loadingIndicatorPosition('left')
                            ->removeUploadedFileButtonPosition('right')
                            ->uploadButtonPosition('left')
                            ->uploadProgressIndicatorPosition('left')
                            ->previewable()
                            ->image()
                            ->acceptedFileTypes(['application/pdf','image/jpg','image/jpeg','image/png'])
                            ->imageEditor()
                            ->label('تصویر بیمه نامه'),
                    ])->columns(2),

                Forms\Components\Textarea::make('description')->label('توضیحات')
                    ->maxLength(255)
                    ->default(null)->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table

            ->columns([
                Tables\Columns\TextColumn::make('')
                    ->label(' بیمه گر')
                    ->default(function (Cardex $cardex) {
                        return auth()->user()->isAdmin() ? $cardex->bimehgar->name . ' ' . $cardex->bimehgar->family : $cardex->bimehgar->shbimeh->name . ' ' . $cardex->bimehgar->shbimeh->city ;
                    })

                    ->description(function (Cardex $cardex) {
                        return auth()->user()->isAdmin() ? $cardex->bimehgar->shbimeh->name . ' ' . $cardex->bimehgar->shbimeh->city : '';
                    }),
                Tables\Columns\TextColumn::make('bimehgar.name')
                    ->label('نام  بیمه گر')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),

                Tables\Columns\TextColumn::make('bimehgar.family')
                    ->label('نام خانوادگی بیمه گر')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('bimehgar.shbimeh.name')
                    ->label('شرکت بیمه')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('bimehgar.shbimeh.city')
                    ->label('استان')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),

                Tables\Columns\TextColumn::make('typebimeh.name')
                    ->label('نوع بیمه')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('car.name')
                    ->label('ماشین')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('car.code_kargahi')
                    ->label('کد کارگاهی')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('car.pelak')
                    ->label('پلاک')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('car.shomaremotor')
                    ->label('شماره موتور')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('car.shomareshasi')
                    ->label('شماره شاسی')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('car.year')
                    ->label('سال ساخت')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),


                Tables\Columns\TextColumn::make('user.name')
                    ->label('ثبت کننده')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('price')
//                    ->formatStateUsing(function (string $state) {
//                        return number_format($state, 0, ',', ',');
//                    })
                    ->numeric()
                    ->label('مبلغ(ریال)')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('start')->jalaliDate()
                    ->sortable()
                    ->searchable()
                    ->label(' شروع'),

                Tables\Columns\TextColumn::make('expired')->jalaliDate()
                    ->sortable()
                    ->searchable()
                    ->label(' اتمام'),

                Tables\Columns\TextColumn::make('alarm')->jalaliDate()
                    ->sortable()
                    ->searchable()
                    ->label(' هشدار تمدید'),

                Tables\Columns\TextColumn::make('renew')
                    ->sortable()
                    ->formatStateUsing(function ($state){
                        return $state ? "تمدید شده" : 'تمدید نشده';
                    })
                    ->color(fn (string $state): string => match ($state) {
                        '1' => 'success',
                        '0' => 'danger',
                        default => 'primary',
                    })
                    ->label('تمدید'),

                Tables\Columns\TextColumn::make('روز باقیمانده')
                    ->state(function (Model $record) {
                        $today = Carbon::now();
                        $endDate = Carbon::parse($record->expired);
                        $daysRemaining = floor($today->diffInDays($endDate));

                        if ($daysRemaining > 1) return $daysRemaining;
                        if ($daysRemaining <= 0) return 'زمان گذشته';

                        return $daysRemaining;
                    })
                ,


                Tables\Columns\TextColumn::make('description')
                    ->label('توضیحات')
                    ->toggleable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')->label('تاریخ ایجاد')
                    ->jalaliDate("Y/m/d H:i")
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('تاریخ ویرایش')
                    ->jalaliDate("Y/m/d H:i")
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])

        ->defaultSort('created_at', 'desc')
            ->deferLoading()
            ->recordClasses(fn (Model $record) => match ($record->renew) {
                0 => 'bg-red-600 dark:bg-red-300',
                1 => 'bg-red-600 dark:bg-red-300',
                default => 'bg-red-600 dark:bg-red-300',
            })


        ->filters([
                Tables\Filters\SelectFilter::make('car_id')->label('ماشین')
                    ->searchable(true)
                    ->options(Car::all()->pluck("name", 'id')->map(function ($name, $id) {
                        return Car::find($id)->code_kargahi . ' ' . $name . " ";
                    })),
                Tables\Filters\SelectFilter::make('typebimeh_id')->label('نوع بیمه')
                    ->searchable(true)
                    ->options(Typebimeh::all()->pluck("name", 'id')),
                Tables\Filters\SelectFilter::make('user_id')->label('ثبت کننده')
                    ->searchable(true)
                    ->options(User::all()->pluck("name", 'id')),
                Filter::make('expired')
                    ->form([
                        DatePicker::make('created_from')->jalali()->label('تاریخ اتمام از'),
                        DatePicker::make('created_until')->jalali()->label('تاریخ اتمام تا'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('expired', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('expired', '<=', $date),
                            );
                    })->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['created_from'] ?? null) {
                            $indicators[] = Indicator::make('Created from ' . jalali(Carbon::parse($data['created_from'])))
                                ->removeField('from');
                        }

                        if ($data['created_until'] ?? null) {
                            $indicators[] = Indicator::make('Created until ' . jalali(Carbon::parse($data['created_until'])))
                                ->removeField('until');
                        }

                        return $indicators;
                    })


            ], layout: Tables\Enums\FiltersLayout::Modal)->persistFiltersInSession()->filtersFormColumns(2)
            ->actions([

                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\ViewAction::make()
                ])


            ])
            ->bulkActions(actions: [
                Tables\Actions\DeleteBulkAction::make(),
                ExportBulkAction::make()->label('اکسل'),
                BulkAction::make('تمدید')
                    ->requiresConfirmation()
                    ->deselectRecordsAfterCompletion()
                ->action(function (Collection $records){
                    $records->each(function (Model $record) {
                        if (!$record->renew) {
                            $record->renew = 1;
                            $record->save();
                        }
                    });
                }),
                BulkAction::make('عدم تمدید')
                    ->requiresConfirmation()
                    ->deselectRecordsAfterCompletion()
                    ->action(function (Collection $records){
                        $records->each(function (Model $record) {
                            if ($record->renew) {
                                $record->renew = 0;
                                $record->save();
                            }
                        });
                    })


            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Fieldset::make('مشخصات بیمه')
                    ->schema([
                        TextEntry::make('bimehgar.name')->label('نام بیمه گر'),
                        TextEntry::make('bimehgar.family')->label('نام خانوادگی بیمه گر'),
                        TextEntry::make('typebimeh.name')->label('نوع بیمه'),
                        DateInfo::make('start')->label('تاریخ شروع بیمه نامه'),
                        DateInfo::make('expired')->label('تاریخ اتمام بیمه نامه'),
                        DateInfo::make('alarm')->label('هشدار تمدید بیمه نامه'),
                        TextEntry::make('price')->label('مبلغ بیمه نامه')->numeric(),
                        ImageEntry::make('image')->label('تصویر بیمه نامه')
                        ]),

                Fieldset::make('مشخصات وسیه نقلیه')
                    ->schema([
                        TextEntry::make('car.name')->label('وسیله نقلیه'),
                        TextEntry::make('car.code_kargahi')->label('کد کارگاهی'),
                        TextEntry::make('car.pelak')->label('پلاک'),
                        TextEntry::make('car.shomaremotor')->label('شماره موتور'),
                        TextEntry::make('car.shomareshasi')->label('شماره شاسی'),
                        TextEntry::make('car.year')->label('سال ساخت'),
                    ]),


            ])->columns(3);


    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCardexes::route('/'),
            'create' => Pages\CreateCardex::route('/create'),
            'edit' => Pages\EditCardex::route('/{record}/edit'),
        ];
    }
}
