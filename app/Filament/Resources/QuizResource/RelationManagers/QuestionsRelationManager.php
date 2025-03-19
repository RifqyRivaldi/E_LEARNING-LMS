<?php

namespace App\Filament\Resources\QuizResource\RelationManagers;

use App\Imports\QuestionsCSVImporter;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use AmidEsfahani\FilamentTinyEditor\TinyEditor;

class QuestionsRelationManager extends RelationManager
{
    protected static string $relationship = 'questions';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\RichEditor::make('question_text')
                    ->label('Question Text')
                    ->toolbarButtons([
                        'attachFiles',
                        'blockquote',
                        'bold',
                        'bulletList',
                        'codeBlock',
                        'h2',
                        'h3',
                        'italic',
                        'link',
                        'orderedList',
                        'redo',
                        'strike',
                        'underline',
                        'undo',
                    ])
                    ->nullable()
                    ->rules(['required_without:image'])
                    ->dehydrateStateUsing(fn ($state) => trim(str_replace('&nbsp;', ' ', strip_tags($state))))
 // Menghapus tag HTML sebelum menyimpan
                    ->columnSpanFull(),

                // TinyEditor::make('question_text')
                //     ->label('Question Text')
                //     ->nullable()
                //     ->rules(['required_without:image'])
                //     ->fileAttachmentsDirectory('uploads/explanation-images')
                //     ->fileAttachmentsVisibility('public')
                //     ->columnSpanFull(),
                
                Forms\Components\Select::make('category')
                    ->options([
                        'twk' => 'TWK',
                        'tiu' => 'TIU',
                        'tkp' => 'TKP',
                    ])
                    ->required()
                    ->rules(['required_without:question_text']),

                Forms\Components\TextInput::make('component')
                    ->label('Component')
                    ->required(),

                Forms\Components\FileUpload::make('image')
                    ->label('Image')
                    ->directory('uploads')
                    ->image()
                    ->nullable(),

                // TinyEditor::make('explanation')
                //     ->label('Pembahasan')
                //     ->fileAttachmentsDirectory('uploads/explanation-images')
                //     ->fileAttachmentsVisibility('public')
                //     ->columnSpanFull(),
                

                Forms\Components\Repeater::make('answers')
                    ->schema([
                        Forms\Components\TextInput::make('answer_text')
                            ->label('Answer Text')
                            ->required(),
                        Forms\Components\TextInput::make('fraction')
                            ->label('Fraction (0-5)')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(5),
                    ])
                    ->relationship('answers')
                    ->addActionLabel('Add Answer'),
                    
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
        ->columns([
            Tables\Columns\TextColumn::make('question_text')
                ->label('Question Text') // Menampilkan teks pertanyaan
                ->sortable()
                ->searchable()
                ->html(),
                Tables\Columns\TextColumn::make('category')
                    ->label('Category') // Menampilkan kategori soal
                    ->sortable(),
                Tables\Columns\TextColumn::make('component')
                    ->label('Component') // Menampilkan komponen soal
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options([
                        'twk' => 'TWK',
                        'tiu' => 'TIU',
                        'tkp' => 'TKP',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])->headerActions([
                Tables\Actions\CreateAction::make(),
                Action::make('import')
                    ->label('Import from CSV')
                    ->icon('heroicon-o-arrow-up-on-square')
                    ->form([
                        FileUpload::make('file')
                            ->label('Upload CSV File')
                            ->directory('uploads')
                            ->acceptedFileTypes(['text/csv'])
                            ->required(),
                    ])
                    ->action(function (array $data, $livewire) {
                        $filePath = storage_path('app/public/' . $data['file']);
                        $quizId = $livewire->ownerRecord->id;
            
                        // Excel::import(new QuestionsCSVImporter($quizId), $filePath);
            
                        Notification::make()
                            ->title('Import Successful!')
                            ->success()
                            ->send();
                    }),
            ]);
    }
}