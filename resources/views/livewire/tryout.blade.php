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

                    <div class="answers mt-3">
                        @foreach ($currentQuestion->answers as $answer)
                            <div class="form-check">
                                <input type="radio" id="answer{{ $answer->id }}" name="answer" value="{{ $answer->id }}"
                                    wire:model="selectedAnswer" class="form-check-input">
                                    <label for="answer{{ $answer->id }}" class="form-check-label">
                                        {{ $answer->answer_text }}
                                    </label>
                            </div>
                        @endforeach
                    </div>

                    <div class="container mt-4">
                        <div class="d-flex justify-content-between">
                            <button class="btn btn-outline-primary btn-sm" wire:click="previousQuestion">
                                <i class="fas fa-arrow-left"></i> Sebelumnya
                            </button>
                            <button class="btn btn-warning btn-sm" wire:click="markForReview">
                                <i class="fas fa-question"></i> Ragu-ragu
                            </button>
                            <button class="btn btn-primary btn-sm" wire:click="nextQuestion">
                                Selanjutnya <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                </div>
            @else
                <p class="no-question">Tidak ada pertanyaan.</p>
            @endif
        </div>

        <div class="right-panel">
<!-- Navigasi Soal -->
<div class="navigation" style="text-align: center; margin-bottom: 20px;">
    <h3 style="color: #495057; margin-bottom: 15px;">Navigasi Soal</h3>
    @foreach ($questions as $index => $item)
        <button type="button" 
                wire:click="goToQuestion({{ $index }})" 
                style="
                    width: 40px; 
                    height: 40px; 
                    margin: 5px; 
                    border-radius: 50%; 
                    border: 1px solid #007bff; 
                    background: white; 
                    color: #007bff; 
                    font-size: 1em; 
                    cursor: pointer; 
                    transition: all 0.3s ease;
                    @if (in_array($item->id, $completedQuestions) && !in_array($item->id, $markedForReview))
                        background: #28a745; color: white; border: 1px solid #28a745; 
                    @elseif (in_array($item->id, $markedForReview))
                        background: #ffc107; color: black; border: 1px solid #ffc107; 
                    @endif
                    {{ $currentQuestionIndex === $index ? 'box-shadow: 0 0 10px rgba(0, 123, 255, 0.5);' : '' }}
                ">
            {{ $index + 1 }}
        </button>
    @endforeach
</div>

            <!-- Tombol Submit -->
            <button type="button" class="submit-button" onclick="confirmSubmit()" wire:click="finishQuiz">
                Kumpulkan Jawaban
            </button>
        </div>
    </div>
</div>

<!-- Tambahkan SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmSubmit() {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Setelah dikumpulkan, jawaban tidak bisa diubah!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Kumpulkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.emit('finishQuiz'); // Memanggil fungsi di Livewire
                Swal.fire('Berhasil!', 'Jawaban Anda telah dikumpulkan.', 'success');
            }
        });
    }

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
                Swal.fire({
                    title: "Waktu Habis!",
                    text: "Jawaban akan dikumpulkan otomatis.",
                    icon: "info",
                    timer: 3000,
                    showConfirmButton: false
                }).then(() => {
                    Livewire.emit('finishQuiz');
                });
            }
        }, 1000);
    }
</script>