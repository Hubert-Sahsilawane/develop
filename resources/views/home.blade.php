@php
    $role = Auth::user()->role;
@endphp

@if($role === 'admin' || $role === 'kasir' || $role === 'owner')
    <script>window.location.href = "{{ url('/dashboard') }}";</script>
@else
    <h1>Akses tidak dikenali</h1>
@endif
