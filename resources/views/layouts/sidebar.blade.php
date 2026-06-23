@php
    $user = auth()->user();
    $isFarmaceutico = $user && method_exists($user, 'hasRole') && $user->hasRole('Farmaceutico');
    $isCajero = $user && method_exists($user, 'hasRole') && $user->hasRole('Cajero');
@endphp

<!-- Dashboard -->
@can('home')
<li class="nav-item" @if($isFarmaceutico || $isCajero) hidden @endif>
    <a href="{{ route('home') }}" class="nav-link {{ Request::is('home') ? 'active' : '' }}">
        <i class="nav-icon fas fa-tachometer-alt"></i>
        <p>Dashboard</p>
    </a>
</li>
@endcan

<!-- Perfil -->
@can('perfil')
<li class="nav-item" @if($isFarmaceutico || $isCajero) hidden @endif>
    <a href="{{ route('perfil') }}" class="nav-link {{ Request::is('perfil') ? 'active' : '' }}">
        <i class="nav-icon fas fa-user"></i>
        <p>Perfil</p>
    </a>
</li>
@endcan

<!-- Administración -->
@can('vista_admin')
<li class="nav-item" @if($isFarmaceutico || $isCajero) hidden @endif>
    <a href="{{ route('vista_admin') }}" class="nav-link {{ Request::is('vista_admin*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-users-cog"></i>
        <p>Administración</p>
    </a>
</li>
@endcan

<!-- Productos handled by AdminLTE menu (config/adminlte.php) to avoid duplicates -->

<!-- Recetas -->
@can('recetas')
<li class="nav-item" @if($isFarmaceutico || $isCajero) hidden @endif>
    <a href="{{ route('recetas') }}" class="nav-link {{ Request::is('recetas*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-prescription"></i>
        <p>Recetas</p>
    </a>
</li>
@endcan

<!-- Obras Sociales -->
@can('obras_sociales')
<li class="nav-item" @if($isCajero) hidden @endif>
    <a href="{{ route('obras-sociales.index') }}" class="nav-link {{ Request::is('obras-sociales*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-hospital"></i>
        <p>Obras Sociales</p>
    </a>
</li>
@endcan

<!-- Proveedores -->
@can('proveedores')
<li class="nav-item" @if($isCajero) hidden @endif>
    <a href="{{ route('proveedores.index') }}" class="nav-link {{ Request::is('proveedores*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-truck"></i>
        <p>Proveedores</p>
    </a>
</li>
@endcan

<!-- Ventas -->
@can('ventas')
<li class="nav-item" @if($isFarmaceutico) hidden @endif>
    <a href="{{ route('ventas.index') }}" class="nav-link {{ Request::is('ventas*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-cash-register"></i>
        <p>Ventas</p>
    </a>
</li>
@endcan

<!-- Reportes -->
@can('reportes')
<li class="nav-item" @if($isFarmaceutico || $isCajero) hidden @endif>
    <a href="{{ route('reportes.index') }}" class="nav-link {{ Request::is('reportes*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-chart-bar"></i>
        <p>Reportes</p>
    </a>
</li>
@endcan

<!-- Información -->
@can('info')
<li class="nav-item" @if($isFarmaceutico || $isCajero) hidden @endif>
    <a href="{{ route('info') }}" class="nav-link {{ Request::is('info') ? 'active' : '' }}">
        <i class="nav-icon fas fa-info-circle"></i>
        <p>Información</p>
    </a>
</li>
@endcan