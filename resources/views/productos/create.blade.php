@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Vista de Productos</h1>
@stop

@section('content')
<div class="container">
    <h1>Agregar Producto</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('productos.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Nombre:</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Descripción:</label>
            <textarea name="descripcion" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <label>Precio Compra:</label>
            <input type="number" name="precio_compra" step="0.01" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Precio Venta:</label>
            <input type="number" name="precio_venta" step="0.01" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Stock Inicial:</label>
            <input type="number" name="stock_inicial" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Stock Actual:</label>
            <input type="number" name="stock_actual" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Stock Mínimo:</label>
            <input type="number" name="stock_minimo" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Fecha de Caducidad:</label>
            <input type="date" name="caducidad" class="form-control">
        </div>
        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="{{ route('productos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
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



