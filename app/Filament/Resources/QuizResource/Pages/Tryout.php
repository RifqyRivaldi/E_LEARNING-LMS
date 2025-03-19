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
use Filament\Tables;
use Filament\Tables\Actions\Action;
use App\Models\Quiz;



class Tryout extends ListRecords
{
    protected static string $resource = QuizResource::class;

    public function getTitle(): string
    {
        return 'Tryout';
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
        ->modifyQueryUsing(function (Builder $query): Builder {
            return $query->where('quiz_type', 'tryout');
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
                ->url(fn(Quiz $record): string => route('do-quiztryout', $record))
                ->color('success')
                ->openUrlInNewTab()
                ->icon('fas-pencil')
                ->extraAttributes(['onclick' => 'return confirm("Apakah Anda yakin ingin mengerjakan kuis ini?")']),
        ])
        ->bulkActions([
            BulkActionGroup::make([
                DeleteBulkAction::make(),
            ]),
        ])
        ->headerActions([
            CreateAction::make()->label('Tambah Tryout'),
        ])
        ->emptyStateHeading('No tryout available')
        ->emptyStateIcon('heroicon-o-document');
    }
}