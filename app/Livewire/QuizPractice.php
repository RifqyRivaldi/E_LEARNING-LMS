<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Quiz;
use App\Models\Question;

class QuizPractice extends Component
{
    public $quiz;
    public $questions = [];
    public $currentQuestionIndex = 0;
    public $currentQuestion;

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

    // Cek apakah ada pertanyaan sebelum menampilkan halaman
    if ($this->questions->isEmpty()) {
        \Log::error("Tidak ada pertanyaan untuk quiz ID: " . $id);
        session()->flash('error', 'Tidak ada pertanyaan untuk kuis ini.');
        return redirect()->route('dashboard');
    }

    // Set pertanyaan pertama
    $this->currentQuestion = $this->questions->first();
    \Log::info("Pertanyaan pertama: ", ['currentQuestion' => $this->currentQuestion]);
}


    public function goToQuestion($index)
    {
        if (!is_numeric($index) || $index < 0 || $index >= count($this->questions)) {
            \Log::warning("Indeks pertanyaan tidak valid: " . $index);
            return;
        }

        $this->currentQuestionIndex = $index;
        $this->currentQuestion = $this->questions[$index]; // Pastikan tetap sebagai objek Eloquent

        \Log::info("Navigasi ke pertanyaan: " . $index);
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

}
