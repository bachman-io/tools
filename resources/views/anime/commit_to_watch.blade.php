@extends('layout.default')
@section('content')
    <br xmlns="http://www.w3.org/1999/html"/>
    <h2 class="text-center">Commit to Watch</h2>
    <br/>
    <table class="table">
        <tbody>
            <tr>
                <td rowspan="3"><img src="{{ $anime->cover_image }}" alt="{{ $anime->title_native }}"/></td>
                <td><a href="{{ $anime->site_url }}" target="_blank" rel="noopener" data-toggle="tooltip" data-placement="top" data-original-title="{{ !is_null($anime->title_english) ? $anime->title_english : $anime->title_romaji }}"><span lang="ja">{{ $anime->title_native }}</span></a></td>
            </tr>
            <tr>
                <td>
                    <ul class="list-unstyled">
                        <li><strong>Format:</strong> {{ $anime->format }}</li>
                        <li><strong>Duration:</strong> {{ $anime->duration }} min.</li>
                        <li><strong>Average Score:</strong> {{ $anime->average_score }}</li>
                    </ul>
                </td>
                <td class="text-center"></td>
            </tr>
            <tr>
                <td colspan="5">
                    <p>{!! $anime->description  !!}</p>
                    <p><strong>Studio(s):</strong> {!! $anime->studios !!}</p>
                </td>
            </tr>
        </tbody>
    </table>
@endsection