<div class="quiz-container">
    <!-- Judul Quiz -->
    <h2>{{ $quiz->name }}</h2>

    <!-- Timer -->
    <div class="timer">
        Waktu tersisa: <span id="timer">00:00:00</span>
    </div>

    <div class="quiz-content">
        <!-- Bagian Soal -->
        <div class="question-section">
            @if ($currentQuestion)
                <div class="question">
                    <p>{{ $currentQuestion->question_text }}</p>

                    {{-- Tambahkan gambar jika tersedia --}}
                    @if ($currentQuestion->image)
                        <img src="{{ $currentQuestion->image_url }}" alt="Gambar Pertanyaan" class="question-image"
                            style="max-width: 500px; height: auto; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);">
                    @endif

                    <div class="answers">
                        @foreach ($currentQuestion->answers as $answer)
                            <div class="form-check answer-option"
                                style="cursor: pointer; padding: 10px; border-radius: 5px; transition: 0.3s;"
                                wire:click="selectAnswer({{ $answer->id }})">
                                <input class="form-check-input" type="radio" name="question" id="answer_{{ $answer->id }}"
                                    value="{{ $answer->id }}" wire:model.defer="selectedAnswer">
                                <!-- GUNAKAN .defer AGAR TIDAK HILANG -->
                                <label class="form-check-label" for="answer_{{ $answer->id }}">
                                    {{ $answer->answer_text }}
                                </label>
                            </div>
                        @endforeach

                    </div>
                </div>
            @else
                <p class="no-question">Tidak ada pertanyaan.</p>
            @endif
        </div>

        <div class="right-panel">
            <!-- Navigasi Soal -->
            <div class="navigation">
                <h3>Navigasi Soal</h3>
                @foreach ($questions as $index => $item)
                    <button type="button" wire:click="goToQuestion({{ $index }})"
                        class="btn btn-outline-primary {{ $currentQuestionIndex === $index ? 'active' : '' }}">
                        {{ $index + 1 }}
                    </button>
                @endforeach
            </div>

           <!-- Tombol Submit -->
<button type="button" class="submit-button" wire:click="finishQuiz">
    Kumpulkan Jawaban
</button>
        </div>
    </div>
</div>

<script>
    window.onload = function () {
        const duration = 7200; // 2 jam dalam detik
        const display = document.getElementById('timer');
        startTimer(duration, display);
    }

    function startTimer(duration, display) {
        let timer = duration, hours, minutes, seconds;
        setInterval(function () {
            hours = parseInt(timer / 3600, 10);
            minutes = parseInt((timer % 3600) / 60, 10);
            seconds = parseInt(timer % 60, 10);

            hours = hours < 10 ? "0" + hours : hours;
            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            display.textContent = hours + ":" + minutes + ":" + seconds;

            if (--timer < 0) {
                timer = 0;
                alert("Waktu habis! Jawaban akan dikumpulkan.");
                @this.submitQuiz();
            }
        }, 1000);
    }
</script>