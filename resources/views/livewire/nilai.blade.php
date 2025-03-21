@extends('components.layouts.app1')

@section('content')
<div class="container text-center">
    <h1>Hasil Tryout</h1>

    @if($scores->isEmpty())
        <p>Tidak ada skor yang tersedia untuk Anda.</p>
    @else
        @php
            $latestScore = $scores->where('user_id', auth()->id())->last();
        @endphp
        
        @if($latestScore)
            <table class="table table-bordered table-striped mx-auto" style="width: 80%;">
                <thead>
                    <tr>
                        <th>ID Kuis</th>
                        <th>Nilai SKD</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $latestScore->quiz->id }}</td>
                        <td>{{ $latestScore->total_correct }}</td>
                        <td>{{ $latestScore->created_at->format('d-m-Y H:i') }}</td>
                    </tr>
                </tbody>
            </table>
        @else
            <p>Anda belum mengerjakan tryout terbaru.</p>
        @endif
    @endif
</div>
@endsection