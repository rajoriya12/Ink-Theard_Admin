@extends('admin.layouts.admin')

@section('content')


<h2 class="mb-4">Customers</h2>

<div class="card p-4">

    <table class="table table-dark">


        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Role</th>
            </tr>
        </thead>

        <tbody>

            @foreach($customers as $customer)



            <tr>

                <td>
                    {{ $customer->name ?? 'N/A' }}
                </td>

                <td>
                    {{ $customer->email ?? 'N/A' }}
                </td>

                <td>
                    {{ $customer->phone ?? 'N/A' }}
                </td>

                <td>
                    {{ $customer->role ?? 'user' }}
                </td>


            </tr>

            @endforeach

        </tbody>

    </table>

</div>


@endsection