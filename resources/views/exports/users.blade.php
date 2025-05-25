<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Roles</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{$user->role}} </td>
            </tr>
        @endforeach
    </tbody>
</table>
