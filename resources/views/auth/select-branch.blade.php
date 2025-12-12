<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" type="image/png" href="{{ asset('storage/default-images/favicon.png') }}">
    <title>Khmer Angkor | Select Branch</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body style="background: url('{{ asset('storage/default-images/angkor-wat.jpg') }}') center/cover no-repeat fixed;">

    <div class="overlay" aria-hidden="true"></div>

    @php
        $user = session('user');
    @endphp

    @if (!$user)
        @php
            redirect()->route('login')->send();
        @endphp
    @endif

    @foreach ($user->getBranches() as $branch)
        <div class="card" role="main" aria-labelledby="signinTitle"
            style="width: 200px; justify-content: center; align-items: center; display: flex; flex-direction: column; padding: 20px; margin: 20px; cursor: pointer;"
            onclick="document.getElementById('form-{{ $branch->id }}').submit();">
            <form id="form-{{ $branch->id }}" method="POST" action="{{ route('select.branch.post', ['user_id' => $user->_id, 'branch_id' => $branch->_id]) }}"
                style="display: none;">
                @csrf
            </form>
            <div style="width: 150px; height: 150px;">
                @if (@$branch->image)
                    <img src="{{ $branch->image->url }}" alt="branch"
                        style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    <div
                        style="text-align: center; width: 150px; height: 150px; background-color: #ccc; display: flex; justify-content: center; align-items: center; font-size: 48px; color: #555; border-radius: 50%; font-weight: bold;">
                        {{ initials($branch->name) }}
                    </div>
                @endif
            </div>
            <br>
            {{ $branch->name }}
        </div>
    @endforeach

</body>

</html>
