@php
    $role = Auth::user()->role;
@endphp

@if($role === 'admin' || $role === 'kasir')
    <script>window.location.href = "{{ url('/dashboard') }}";</script>
@else
    <h1>Akses tidak dikenali</h1>
@endif