@extends('layout.default')
@section('content')
    <br/>
    <h2 class="text-center">WaniKani Levels {{ $level_range }}</h2>
    <br/>
    <h3>Notes</h3>
    <p><ul>
        <li><span style="background: #666;">Locked items have are dark grey.</span></li>
        <li><span style="background: #888;">Unlocked but Unlearned items are light grey.</span></li>
        <li>Learned items: <ul>
                <li><span style="background: #0093dd">are blue if they are radicals.</span></li>
                <li><span style="background: #dd0093">are magenta if they are kanji.</span></li>
                <li><span style="background: #9300dd">are purple if they are vocabulary.</span></li>
            </ul></li>
    </ul></p>
    <br/>
    <table class="table">
        <thead class="thead-light">
        <tr>
            <th scope="col" class="text-center" width="5%">Level</th>
            <th scope="col" class="text-center" width="10%">Type</th>
            <th scope="col" class="text-center" width="5%">Count</th>
            <th scope="col" class="text-center" width="80%">Items</th>
        </tr>
        </thead>
        <tbody>
        @foreach($levels as $level => $types)
            @foreach($types as $type => $items)
                <tr>
                    @if($type === 0)
                        <th scope="row" rowspan="3">{{ $level }}</th>
                    @endif
                    <td>{{ $type_names[$type] }}</td>
                        <td>{{ count($items) }}</td>
                    <td>
                        @forelse($items as $item)
                            <a href="{{ $item['document_url'] }}" target="_blank" rel="noopener" data-toggle="tooltip" data-placement="top" data-original-title="{{ $item['meanings'] }}">
                                <span lang="ja"  class="no-wrap" style="background: {{ $item['color'] }}">{!! $item['characters'] !!}</span>
                            </a>

                        @empty
                            There are no {{ $type_names[$type] }} on this level
                        @endforelse
                    </td>
                </tr>
            @endforeach
        @endforeach
        </tbody>
    </table>
@endsection