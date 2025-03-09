<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Quiz_attempts;  // Pastikan model ini ada
use App\Models\Quiz_Scores; 
use Illuminate\Support\Facades\Auth;

class QuizTryout extends Component
{
    public $quiz;
    public $questions = [];
    public $currentQuestionIndex = 0;
    public $currentQuestion = null;
    public $selectedAnswer = null;
    public $totalCorrect = 0; // Menyimpan total jawaban benar
    public $totalWrong = 0;   // Menyimpan total jawaban salah

    public function mount($id = null)
    {
        if (!$id) {
            session()->flash('error', 'Quiz ID tidak valid.');
            return redirect()->route('dashboard');
        }

        \Log::info("QuizTryout mount() dipanggil dengan ID: " . $id);

        $this->quiz = Quiz::find($id);

        if (!$this->quiz) {
            \Log::error("Quiz tidak ditemukan dengan ID: " . $id);
            abort(404, 'Quiz tidak ditemukan.');
        }

        \Log::info("Quiz ditemukan: ", ['quiz' => $this->quiz]);

        // Ambil pertanyaan yang terkait dengan kuis
        $this->questions = Question::where('quiz_id', $id)->with('answers')->get();

        \Log::info("Jumlah pertanyaan ditemukan: " . count($this->questions));

        if ($this->questions->isEmpty()) {
            \Log::error("Tidak ada pertanyaan untuk quiz ID: " . $id);
            session()->flash('error', 'Tidak ada pertanyaan untuk kuis ini.');
            return redirect()->route('dashboard');
        }

        $this->currentQuestion = $this->questions->first();
        \Log::info("Pertanyaan pertama: ", ['currentQuestion' => $this->currentQuestion]);

        $this->loadSelectedAnswer();
    }

    public function goToQuestion($index)
    {
        if (!is_numeric($index) || $index < 0 || $index >= count($this->questions)) {
            return;
        }

        $this->saveAnswer(); // Simpan jawaban sebelum pindah soal

        $this->currentQuestionIndex = $index;
        $this->currentQuestion = $this->questions[$index];
        $this->selectedAnswer = null;

        $this->loadSelectedAnswer();
    }


    public function render()
    {
        return view('livewire.tryout', [
            'quizId' => $this->quiz->id ?? null,
            'questions' => $this->questions,
            'currentQuestion' => $this->currentQuestion ?? null
        ])->layout('components.layouts.app');
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    public function saveAnswer()
    {
        $user = Auth::user();
    
        if (!$user) {
            session()->flash('error', 'Anda harus login untuk menjawab kuis.');
            return;
        }
    
        if (is_null($this->selectedAnswer)) {
            session()->flash('error', 'Silakan pilih jawaban terlebih dahulu.');
            return;
        }
    
        if (!$this->currentQuestion) {
            \Log::error("Tidak ada pertanyaan yang sedang aktif.");
            return;
        }
    
        // Cek apakah jawaban benar
        $isCorrect = $this->selectedAnswer == $this->currentQuestion->correct_answer_id;
        \Log::info("Selected Answer: " . $this->selectedAnswer);
\Log::info("Correct Answer ID: " . $this->currentQuestion->correct_answer_id);

    
        // Cek apakah user sudah menjawab pertanyaan ini sebelumnya
        $existingAttempt = Quiz_attempts::where([
            'user_id' => $user->id,
            'quiz_id' => $this->quiz->id,
            'question_id' => $this->currentQuestion->id,
        ])->first();
    
        if ($existingAttempt) {
            // Jika jawaban sebelumnya benar, kurangi dari totalCorrect
            if ($existingAttempt->is_correct) {
                $this->totalCorrect--;
            } else {
                $this->totalWrong--;
            }
        }
    
        // Simpan jawaban baru
        Quiz_attempts::updateOrCreate(
            [
                'user_id' => $user->id,
                'quiz_id' => $this->quiz->id,
                'question_id' => $this->currentQuestion->id,
            ],
            [
                'question_answer_id' => $this->selectedAnswer,
                'is_correct' => $isCorrect,
                'updated_at' => now(),
            ]
        );
    
        // Perbarui total benar dan salah
        if ($isCorrect) {
            $this->totalCorrect++;
        } else {
            $this->totalWrong++;
        }
    
        session()->flash('success', 'Jawaban berhasil disimpan.');
    }
    
    

    public function loadSelectedAnswer()
    {
        $user = Auth::user();

        if (!$user || !$this->currentQuestion) {
            return;
        }

        $existingAnswer = Quiz_attempts::where([
            'user_id' => $user->id,
            'quiz_id' => $this->quiz->id,
            'question_id' => $this->currentQuestion->id,
        ])->first();

        $this->selectedAnswer = $existingAnswer ? $existingAnswer->question_answer_id : null;
    }

    public function selectAnswer($answerId)
    {
        \Log::info("Jawaban dipilih: " . $answerId);
        $this->selectedAnswer = $answerId;
    }

    public function finishQuiz()
    {
        $user = Auth::user();
    
        if (!$user) {
            session()->flash('error', 'Anda harus login untuk menyelesaikan kuis.');
            return;
        }
    
        // Simpan skor ke dalam model Quiz_Scores
        Quiz_Scores::create([
            'quiz_id' => $this->quiz->id,
            'user_id' => $user->id,
            'total_correct' => $this->totalCorrect,
            'total_wrong' => $this->totalWrong,
            'score' => $this->calculateScore(), // Menyimpan nilai berdasarkan bobot
        ]);
    
        session()->flash('success', 'Kuis selesai. Skor Anda telah disimpan.');
        return redirect()->route('nilai'); // Pastikan rute ini sesuai
    }
    
    

    public function calculateScore()
    {
        $totalQuestions = count($this->quiz->questions);
        $maxScore = 5;
    
        if ($totalQuestions > 0) {
            return ($this->totalCorrect / $totalQuestions) * $maxScore;
        }
    
        return 0.00; // Pastikan selalu ada nilai
    }
    
    

public function checkAnswer($selectedOption)
{
    $currentQuestion = $this->quiz->questions[$this->currentQuestionIndex];

    if ($selectedOption === $currentQuestion->correct_option) {
        $this->totalCorrect++; // Jawaban benar
        \Log::info('Jawaban Benar: ' . $this->totalCorrect);
    } else {
        $this->totalWrong++; // Jawaban salah
        \Log::info('Jawaban Salah: ' . $this->totalWrong);
    }
    
    $this->currentQuestionIndex++;

    if ($this->currentQuestionIndex >= count($this->quiz->questions)) {
        $this->finishQuiz();
    }
}

}
