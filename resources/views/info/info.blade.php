@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Vista de Informacion Guia</h1>
@stop

@section('content')
    <p>Primeros Pasos</p>
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