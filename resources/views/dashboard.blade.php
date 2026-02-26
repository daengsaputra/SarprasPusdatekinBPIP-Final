@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<main class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header border-0 pb-0">
                        <h4 class="card-title">Dashboard</h4>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">Selamat datang, {{ auth()->user()->name ?? 'User' }}.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
