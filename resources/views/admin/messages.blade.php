@extends('admin.layouts.admin')

@section('content')

<h2 class="mb-4">Contact Messages</h2>

<div class="card card-custom p-4">

    <table class="table table-dark table-hover">

        <thead>

            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Message</th>
                <th>Date</th>
                <th>Action</th>
            </tr>

        </thead>
        <tbody>

            @foreach($messages as $message)

            <tr>

                <td>{{ $message['name'] ?? '-' }}</td>

                <td>{{ $message['email'] ?? '-' }}</td>

                <td>{{ $message['message'] ?? '-' }}</td>

                <td>
                    @if(isset($message['createdAt']))
                    {{ $message['createdAt']->toDateTime()->format('d M Y h:i A') }}
                    @else
                    -
                    @endif
                </td>

                <td>

                    <form
                        action="/messages/delete/{{ $message['_id'] }}"
                        method="POST"
                        style="display:inline;">

                        @csrf

                        @method('DELETE')

                        <button
                            class="btn btn-danger btn-sm"
                            onclick="return confirm('Delete this message?')">
                            Delete
                        </button>

                    </form>

                </td>

            </tr>

            @endforeach

        </tbody>
    </table>

</div>

@endsection