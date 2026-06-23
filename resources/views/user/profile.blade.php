@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Vista de Perfil</h1>
@stop

@section('content')
<div class="container py-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title mb-0"><i class="fas fa-user-circle mr-2"></i>Gestión del Perfil</h3>
        </div>
        <div class="card-body bg-light">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <form action="{{ route('user.update') }}" method="POST" class="mt-4">
                @csrf
                <div class="form-group row">
                    <div class="col-md-6 offset-md-3">
                        <label for="name" class="font-weight-bold"><i class="fas fa-user mr-2"></i>Nombre</label>
                        <input type="text" id="name" name="name" 
                               class="form-control form-control-lg @error('name') is-invalid @enderror" 
                               value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group row">
                    <div class="col-md-6 offset-md-3">
                        <label for="email" class="font-weight-bold"><i class="fas fa-envelope mr-2"></i>Correo Electrónico</label>
                        <input type="email" id="email" name="email" 
                               class="form-control form-control-lg @error('email') is-invalid @enderror" 
                               value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group row mt-4">
                    <div class="col-md-6 offset-md-3 text-center">
                        <button type="submit" class="btn btn-primary btn-lg px-5">
                            <i class="fas fa-save mr-2"></i>Actualizar Perfil
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('css')

    <style>
        .card {
            border-radius: 15px;
            overflow: hidden;
        }
        .form-control {
            border-radius: 8px;
            border: 1px solid #ced4da;
        }
        .form-control:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
        }
        .btn-primary {
            border-radius: 25px;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
    </style>
@stop

@section('js')
    <script>
        console.log("Hi, I'm using the Laravel-AdminLTE package!");
        // Handle logout
        document.querySelectorAll('a[href*="logout"]').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/logout';
                const token = document.querySelector('meta[name="csrf-token"]');
                if (token) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = '_token';
                    input.value = token.getAttribute('content');
                    form.appendChild(input);
                }
                document.body.appendChild(form);
                form.submit();
            });
        });
    </script>
@stop