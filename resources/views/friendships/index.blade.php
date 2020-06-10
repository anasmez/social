@extends('layouts.app')

@section('content')
    <div class="container">
        @foreach($friendshipRequests as $friendshipRequest)
            {{$friendshipRequest->sender->name}}
            <accept-friendship-btn
                    dusk="accept-friendship"
                    :sender="{{$friendshipRequest->sender}}"
                    friendship-status="{{$friendshipRequest->status}}"
            ></accept-friendship-btn>
        @endforeach
    </div>
@endsection
