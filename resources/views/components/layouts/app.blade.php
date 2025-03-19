<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sistem Ulangan</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    @livewireStyles
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f4f6f8;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }

        .quiz-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        .quiz-content {
            display: flex;
            gap: 20px;
        }



        .timer {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            text-align: center;
            font-size: 1.2em;
            font-weight: bold;
            color: #dc3545;
            margin: 20px 0;
            user-select: none;
        }

        .question-section {
            flex: 2;
            /* margin: 25px 0;
            padding: 20px; */
            background: #fff;
            border-radius: 6px;
            user-select: none;
        }

        .question {
            font-size: 1.1em;
            color: #2c3e50;
            margin-bottom: 20px;
            user-select: none;
        }

        .answers {
            display: flex;
            flex-direction: column;
            gap: 10px;
            user-select: none;
        }

        .right-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-top: 50px;
            /* Menambahkan jarak di atas panel */
        }

        .answer-option {
            padding: 15px;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .answer-option:hover {
            background: #e9ecef;
            transform: translateX(5px);
        }

        
        /* .navigation {
            text-align: center;
            margin-bottom: 20px;
        }

        .navigation h3 {
            color: #495057;
            margin-bottom: 15px;
        }

        .navigation button {
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
        }

        .navigation button:hover {
            background: #007bff;
            color: white;
        } */

        .question-numbers {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
        }

        .number {
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .number:hover {
            background: #007bff;
            color: white;
        }

        .submit-button {
            display: block;
            width: 100%;
            padding: 15px;
            background: rgb(8, 246, 40);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 1.1em;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .submit-button:hover {
            background: rgb(38, 218, 62);
        }

        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }

            .quiz-container {
                padding: 15px;
            }

            .question-numbers {
                gap: 5px;
            }

            .number {
                width: 30px;
                height: 30px;
                font-size: 0.9em;
            }

            .question-image {
                max-width: 100px;
                /* Ukuran maksimum lebih kecil */
                height: auto;
                /* Menjaga aspek rasio */
                display: block;
                margin: 5px auto;
                /* Pusatkan gambar */
                border-radius: 5px;
                /* Sudut lebih halus */
                box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
                /* Efek shadow ringan */
                object-fit: contain;
                /* Pastikan gambar tidak terpotong */
            }



        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h1>Sistem Ulangan</h1>
        {{ $slot }}
    </div>


    @livewireScripts
    <script>
        // Fungsi untuk timer countdown
        function startTimer(duration, display) {
            let timer = duration, hours, minutes, seconds;

            let countdown = setInterval(function () {
                hours = parseInt(timer / 3600, 10);
                minutes = parseInt((timer % 3600) / 60, 10);
                seconds = parseInt(timer % 60, 10);

                hours = hours < 10 ? "0" + hours : hours;
                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                display.textContent = hours + ":" + minutes + ":" + seconds;

                if (--timer < 0) {
                    clearInterval(countdown);
                    display.textContent = "Waktu Habis!";
                    // Tambahkan fungsi untuk submit otomatis di sini
                }
            }, 1000);
        }
    </script>
</body>

</html>