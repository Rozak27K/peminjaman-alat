@extends('layouts.app', ['title' => 'Log Aktivitas'])

@section('content')
    <x-ui.table-wrap>
        <table>
            <thead><tr><th>Waktu</th><th>User</th><th>Aksi</th><th>Modul</th><th>Subjek</th><th>IP</th></tr></thead>
            <tbody>
                @foreach ($logs as $log)
                    <tr>
                        <td>{{ $log->created_at?->format('d/m/Y H:i') }}</td>
                        <td>{{ $log->user?->name ?? '-' }}</td>
                        <td>{{ $log->aksi }}</td>
                        <td>{{ $log->modul }}</td>
                        <td>{{ $log->subjek_type ? class_basename($log->subjek_type).' #'.$log->subjek_id : '-' }}</td>
                        <td>{{ $log->ip_address ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </x-ui.table-wrap>
    {{ $logs->links() }}
@endsection
