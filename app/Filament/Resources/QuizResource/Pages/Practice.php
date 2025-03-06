<?php

namespace App\Filament\Resources\QuizResource\Pages;

use App\Filament\Resources\QuizResource;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\Action;
use Filament\Tables;
use App\Models\Quiz;


class Practice extends ListRecords
{
    protected static string $resource = QuizResource::class;

    public function getTitle(): string
    {
        return 'Latihan Soal';
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('description')->limit(50),
                IconColumn::make('is_paid')->boolean()->label('Paid'),
                TextColumn::make('quiz_type')->sortable(),
                TextColumn::make('created_at')->dateTime(),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                return $query->where('quiz_type', 'practice');
            })
            ->filters([
                SelectFilter::make('is_paid')->label('Jenis')
                    ->options([
                        true => 'Bayar',
                        false => 'Gratis',
                    ]),
            ])
            ->actions([
                EditAction::make(),
                Action::make('Kerjakan')
                ->url(fn(Quiz $record):string => route('do-quizpractice', $record))
                ->color('success')
                ->openUrlInNewTab()
                ->icon('fas-pencil'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                CreateAction::make()->label('Tambah Latihan Soal'),
            ])
            ->emptyStateHeading('No practice available')
            ->emptyStateIcon('heroicon-o-document');
    }
}