@extends('layout.template')

@section('title', 'Homepage')

@section('content')

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <h1>Popular Movie</h1>
    <div class="row">
        @foreach ($movies as $movie)
            <!-- Perbaikan Grid -->
            <div class="col-lg-6 mb-4 d-flex align-items-stretch">
                <div class="card w-100 h-100" style="max-width: 540px;">
                    <div class="row g-0 h-100">
                        <div class="col-md-4">
                            <img src="{{ asset('storage/' . $movie['foto_sampul']) }}" class="img-fluid rounded-start h-100 w-100" style="object-fit: cover;"
                                alt="...">
                        </div>
                        <div class="col-md-8 d-flex flex-column">
                            <div class="card-body d-flex flex-column h-100">
                                <h5 class="card-title">{{ $movie['judul'] }}</h5>
                                <p class="card-text flex-grow-1">{{ Str::limit($movie['sinopsis'], 120) }}</p>
                                <div class="mt-auto">
                                    <a href="/movie/{{ $movie['id'] }}" class="btn btn-success">Lihat Selanjutnya</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        <div class="d-flex justify-content-center">
            {{ $movies->links() }}
        </div>
    </div>
@endsection
