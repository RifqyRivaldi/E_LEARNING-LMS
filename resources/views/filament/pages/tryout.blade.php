<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Quiz Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .question-card, .question-navigation {
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            background-color: #ffffff;
            padding: 20px;
        }

        .question-navigation h5 {
            color: #007bff;
        }

        .btn-group-flex {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            justify-content: center;
        }

        .btn-group-flex .btn {
            width: 50px;
            height: 50px;
            font-weight: bold;
            border-radius: 10px;
            transition: 0.3s;
        }

        .btn-group-flex .btn:hover {
            background-color: #0056b3;
            color: white;
        }

        .btn-submit {
            width: 100%;
            font-size: 18px;
            font-weight: bold;
            padding: 12px;
            border-radius: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            transition: 0.3s;
        }

        .btn-submit:hover {
            background-color: #218838;
        }

        .countdown-timer {
            font-size: 18px;
            font-weight: bold;
            color: #dc3545;
            text-align: center;
            padding: 10px;
            border: 2px solid #dc3545;
            border-radius: 10px;
            display: inline-block;
        }
    </style>
</head>

<body>
<div class="container mt-5">
    <div class="row">
        <h3 class="text-primary text-center">{{ $package->name }}</h3>
        <div class="col-md-8 mb-3">
            <div class="card question-card">
                <div class="text-center mb-3">
                    <span class="countdown-timer" id="countdown">Waktu tersisa: <span id="time">00:00:00</span></span>
                </div>
                <div class="card-body">
                    <h5 class="card-title">{{ $currentQuestion->question->question }}</h5>
                    @foreach ($currentQuestion->question->options as $item)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="question" value="">
                        <label class="form-check-label">{{ $item->option_text }}</label>
                    </div>
                    @endforeach
                </div>
            </div>
            
        </div>
        <div class="col-md-4">
            <div class="card question-navigation">
                <div class="card-body text-center">
                    <h5 class="mb-3">Navigasi Soal</h5>
                    <div class="btn-group-flex" role="group">
                        @foreach ($questions as $index => $item)
                        <button type="button" wire:click="goToQuestion({{ $index }})" class="btn btn-outline-primary">
                            {{ $index + 1 }}
                        </button>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-submit mt-4">Kumpulkan Jawaban</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function startCountdown(duration, display) {
        let timer = duration;
        setInterval(function () {
            let hours = Math.floor(timer / 3600);
            let minutes = Math.floor((timer % 3600) / 60);
            let seconds = timer % 60;

            hours = hours < 10 ? "0" + hours : hours;
            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            display.textContent = hours + ":" + minutes + ":" + seconds;
            if (--timer < 0) timer = 0;
        }, 1000);
    }

    window.onload = function () {
        var duration = 60 * 60; // 1 hour
        var display = document.querySelector('#time');
        startCountdown(duration, display);
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>


