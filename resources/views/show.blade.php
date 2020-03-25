@extends('layouts.app')

@section('title', 'Product Sites')

@section('content')
    <div class="step">
        <h2> {{$data->count}} Productions</h2>
        <ul>
        @foreach ($data->production as $production)
                <li>{{ $production->title }}
                <ul>
                    <li> Type: {{$production->type}}</li>
                    <li> Sites:
                        <ul>
                        @foreach ($production->sites as $site)
                        <li>{{$site->shoot_date}} - {{$site->name}} </li>
                        @endforeach
                        </ul>
                    </li>
                </ul>
                </li>
            @endforeach
        </ul>
    </div>
@endsection
