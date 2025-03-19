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
    public $answers = [];
    public $markedForReview = [];
    public $completedQuestions = [];

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
        $this->loadUserAnswers();
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
    
        $currentAnswer = $this->currentQuestion->answers->find($this->selectedAnswer);
        $isCorrect = $currentAnswer && $currentAnswer->fraction > 0;
    
        // Cek apakah user sudah menjawab pertanyaan ini sebelumnya
        $existingAttempt = Quiz_attempts::where([
            'user_id' => $user->id,
            'quiz_id' => $this->quiz->id,
            'question_id' => $this->currentQuestion->id,
        ])->first();
    
        if ($existingAttempt) {
            // Jika jawaban sebelumnya benar
            if ($existingAttempt->is_correct) {
                $this->totalCorrect -= $existingAttempt->fraction; // Kurangi nilai dari jawaban sebelumnya
            } else {
                $this->totalWrong--; // Kurangi total salah jika ada
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
                'fraction' => $currentAnswer->fraction,
                'updated_at' => now(),
            ]
        );
    
        if ($isCorrect) {
            $this->totalCorrect += $currentAnswer->fraction; // Tambahkan nilai jika benar
        } else {
            $this->totalWrong++; // Tambahkan total salah jika tidak benar
        }

        if ($this->currentAnswerIsFilled()) { // Ganti dengan logika untuk memeriksa apakah jawaban telah diisi
            if (!in_array($this->currentQuestion->id, $this->completedQuestions)) {
                $this->completedQuestions[] = $this->currentQuestion->id;
            }
        }
    
        session()->flash('success', 'Jawaban berhasil disimpan.');
    }
    
    private function currentAnswerIsFilled()
{
    // Logika untuk memeriksa apakah jawaban saat ini sudah diisi
    return isset($this->answers[$this->currentQuestion->id]);
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
        $this->resetQuiz();
        $this->isSubmitted = true; // Set status setelah submit
        return redirect()->route('nilai');
    }
    
    
    public $isSubmitted = false; // Tambahkan ini di bagian atas kelas
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
public function loadUserAnswers()
{
    $user = Auth::user();
    
    if (!$user) return;

    $userAnswers = Quiz_attempts::where('user_id', $user->id)
        ->where('quiz_id', $this->quiz->id)
        ->get();

    foreach ($userAnswers as $attempt) {
        $this->answers[$attempt->question_id] = $attempt->question_answer_id;
    }
}
public function resetQuiz()
{
    $this->currentQuestionIndex = 0;
    $this->selectedAnswer = null; // Reset jawaban terpilih
    $this->totalCorrect = 0;
    $this->totalWrong = 0;
    $this->answers = []; // Kosongkan semua jawaban yang tersimpan

    // Ambil pertanyaan lagi jika perlu
    $this->questions = Question::where('quiz_id', $this->quiz->id)->with('answers')->get();
    $this->currentQuestion = $this->questions->first();
}
public function nextQuestion()
{
    $this->saveAnswer(); // Simpan jawaban sebelum berpindah
    if ($this->currentQuestionIndex < count($this->questions) - 1) {
        $this->currentQuestionIndex++;
        $this->currentQuestion = $this->questions[$this->currentQuestionIndex];
        $this->loadSelectedAnswer(); // Muat jawaban yang dipilih
    }
}

public function previousQuestion()
{
    $this->saveAnswer(); // Simpan jawaban sebelum berpindah
    if ($this->currentQuestionIndex > 0) {
        $this->currentQuestionIndex--;
        $this->currentQuestion = $this->questions[$this->currentQuestionIndex];
        $this->loadSelectedAnswer(); // Muat jawaban yang dipilih
    }
}

public function markForReview()
{
    if (!in_array($this->currentQuestion->id, $this->markedForReview)) {
        $this->markedForReview[] = $this->currentQuestion->id; // Tandai pertanyaan
        session()->flash('info', 'Pertanyaan ini telah ditandai untuk ditinjau.');
    } else {
        $this->markedForReview = array_diff($this->markedForReview, [$this->currentQuestion->id]); // Hapus tanda
        session()->flash('info', 'Pertanyaan ini telah dihapus dari tanda ragu-ragu.');
    }
}
}
