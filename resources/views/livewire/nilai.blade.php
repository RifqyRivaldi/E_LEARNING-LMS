@extends('components.layouts.app1')

@section('title', 'Halaman Nilai')

@section('content')
    <div class="container">
        <h1 class="mb-4">Daftar Nilai</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Quiz</th>
                    <th>Benar</th>
                    <th>Salah</th>
                    <th>Skor</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($scores as $index => $score)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $score->quiz->title }}</td>
                        <td>{{ $score->total_correct }}</td>
                        <td>{{ $score->total_wrong }}</td>
                        <td>{{ $score->score }}</td>
                        <td>{{ $score->created_at->format('d M Y') }}</td>
                        
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
