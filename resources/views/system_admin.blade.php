@extends('layouts.system')

@section('content')
<h2>System Feedback Inbox</h2>

<table class="table">
    <thead>
        <tr>
            <th>Sender</th>
            <th>Role</th>
            <th>Subject</th>
            <th>Message</th>
            <th>Status</th>
            <th>Sent</th>
        </tr>
    </thead>
    <tbody>
        @foreach($feedbacks as $feedback)
        <tr>
            <td>{{ $feedback->user->name }}</td>
            <td>{{ ucfirst($feedback->user->role) }}</td>
            <td>{{ $feedback->subject }}</td>
            <td>{{ $feedback->message }}</td>
            <td>
                <span class="badge {{ $feedback->status === 'unread' ? 'bg-danger' : 'bg-success' }}">
                    {{ $feedback->status }}
                </span>
            </td>
            <td>{{ $feedback->created_at->format('Y-m-d H:i') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
