<div class="quiz-container">
    <!-- Judul Quiz -->
    <h2>{{ $quiz->name }}</h2>

    <!-- Timer -->
    <div class="timer" id="timer">
        Waktu tersisa: <span>00:00:00</span>
    </div>

    <div class="quiz-content">
        <!-- Bagian Soal -->
        <div class="question-section">
            @isset($currentQuestion)
                <div class="question">
                    <p>{{ $currentQuestion->question_text }}</p>

                    {{-- Tambahkan gambar di sini --}}
                    @if ($currentQuestion->image)
                        <img src="{{ $currentQuestion->image_url }}" alt="Gambar Pertanyaan" class="question-image"
                            style="max-width: 500px; height: auto; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);">
                    @endif

                    <div class="answers">
                        @foreach ($currentQuestion->answers ?? [] as $answer)
                            <div class="form-check answer-option">
                                <input class="form-check-input" type="radio" name="question" id="answer_{{ $answer->id }}"
                                    value="{{ $answer->id }}">
                                <label class="form-check-label" for="answer_{{ $answer->id }}">
                                    {{ $answer->answer_text }}
                                </label>
                            </div>
                        @endforeach
                    </div>

                </div>
            @else
                <p class="no-question">Tidak ada pertanyaan.</p>
            @endisset
        </div>


        <div class="right-panel">
            <!-- Navigasi Soal -->
            <div class="navigation">
                <h3>Navigasi Soal</h3>
                @if ($questions->isNotEmpty())
                    @foreach ($questions as $index => $item)
                        <button type="button" wire:click="goToQuestion({{ $index }})" class="btn btn-outline-primary">
                            {{ $index + 1 }}
                        </button>
                    @endforeach
                @else
                    <p class="text-danger">Tidak ada pertanyaan yang tersedia.</p>
                @endif
            </div>

            <!-- Tombol Submit -->
            <button type="submit" class="submit-button" onclick="submitQuiz()">
                Kumpulkan Jawaban
            </button>
        </div>
    </div>
</div>

<script>
    // Inisialisasi timer saat halaman dimuat
    window.onload = function () {
        const duration = 7200; // 2 jam dalam detik
        const display = document.querySelector('#timer span');
        startTimer(duration, display);
    }

    // Fungsi untuk memilih jawaban
    function selectAnswer(element, answerId) {
        // Hapus seleksi dari semua opsi jawaban
        document.querySelectorAll('.answer-option').forEach(option => {
            option.style.background = '#f8f9fa';
            option.style.color = '#000';
        });

        // Tandai opsi yang dipilih
        element.style.background = '#007bff';
        element.style.color = '#fff';

        // Di sini bisa ditambahkan logika untuk menyimpan jawaban
        // Misalnya dengan AJAX atau Livewire
    }

    // Fungsi untuk navigasi soal
    function navigateQuestion(questionNumber) {
        // Implementasi navigasi soal
        // Bisa menggunakan AJAX atau Livewire
    }

    // Fungsi untuk submit quiz
    function submitQuiz() {
        if (confirm('Apakah Anda yakin ingin mengumpulkan jawaban?')) {
            // Implementasi submit quiz
            // Bisa menggunakan form submission atau AJAX
        }
    }
</script>