@extends('layouts.app')

@section('content')
<div class="container">

    <div class="row">
        <div class="col-md-6 d-flex justify-content-between">
            <h2>Leaderboard</h2>
        </div>
        <div class="col-md-6 d-flex justify-content-end">
            <form action="{{ route('leaderboard.recalculate') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-warning">Recalculate</button>
            </form>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-6 d-flex justify-content-between">
            <form action="{{ route('leaderboard.index') }}" method="GET" class="d-flex">
                <input @if(isset($search)) value="{{$search}}"  @endif type="text" name="search" placeholder="Search by User ID" class="form-control" value="{{ request('search') }}">
                <button type="submit" class="btn btn-success ml-2">Search</button>
            </form>
        </div>
        <div class="col-md-6 d-flex justify-content-end">
            <a href="{{ route('leaderboard.index', ['filter' => 'all']) }}" class="btn btn-primary mx-1">All Time</a>
            <a href="{{ route('leaderboard.index', ['filter' => 'day']) }}" class="btn btn-primary mx-1">Today</a>
            <a href="{{ route('leaderboard.index', ['filter' => 'month']) }}" class="btn btn-primary mx-1">This Month</a>
            <a href="{{ route('leaderboard.index', ['filter' => 'year']) }}" class="btn btn-primary mx-1">This Year</a>
        </div>
    </div>

    <table class="table mt-4">
        <thead>
            <tr>
                <th>Rank</th>
                <th>User ID</th>
                <th>Full Name</th>
                <th>Total Points</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($leaderboard as $index => $user)
                <tr>
                    <td>{{ $leaderboard->firstItem() + $index }}</td>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->full_name }}</td>
                    <td>{{ $user->total_points ?? 0 }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            @if ($leaderboard->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link">Previous</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $leaderboard->previousPageUrl() }}" rel="prev">Previous</a>
                </li>
            @endif

            @foreach ($leaderboard->getUrlRange(1, $leaderboard->lastPage()) as $page => $url)
                <li class="page-item {{ $leaderboard->currentPage() == $page ? 'active' : '' }}">
                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                </li>
            @endforeach

            @if ($leaderboard->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $leaderboard->nextPageUrl() }}" rel="next">Next</a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link">Next</span>
                </li>
            @endif
        </ul>
    </nav>
</div>
</div>
@endsection