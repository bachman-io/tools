@extends('layout.default')
@section('content')
    <br/>
    <h2 class="text-center">Anime Summary</h2>
    <br/>
    <h3>Currently Watching</h3>
    <p>Anime in my Watching list with 1 or more episodes completed.</p>
    <table class="table">
        <thead class="thead-light">
        <tr>
            <th scope="col" class="text-center" width="10%"></th>
            <th scope="col" class="text-center" width="50%">Title</th>
            <th scope="col" class="text-center" width="10%">Format</th>
            <th scope="col" class="text-center" width="10%">Length</th>
            <th scope="col" class="text-center" width="10%">Progress</th>
            <th scope="col" class="text-center" width="10%">Avg. Score</th>
        </tr>
        </thead>
        <tbody>
        @forelse($currently_watching as $anime)
            <tr>
                <td rowspan="2"><img src="{{ $anime->cover_thumbnail }}" alt="{{ $anime->title_native }}"/></td>
                <td><a href="{{ $anime->site_url }}" target="_blank" rel="noopener" data-toggle="tooltip" data-placement="top" data-original-title="{{ !is_null($anime->title_english) ? $anime->title_english : $anime->title_romaji }}"><span lang="ja">{{ $anime->title_native }}</span></a></td>
                <td class="text-center">{{ $anime->format }}</td>
                <td class="text-center">{{ $anime->duration }} min.</td>
                <td class="text-center">{{ $anime->progress }} / {{ !is_null($anime->episodes) ? $anime->episodes : '??' }}</td>
                <td class="text-center">{{ $anime->average_score }}</td>
            </tr>
            <tr>
                <td colspan="5">
                    <p>{!! $anime->description  !!}</p>
                    <p><strong>Studio(s):</strong> {!! $anime->studios !!}</p>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">I'm not watching any anime right now.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
    <br/>
    <h3>Watch Pool</h3>
    <p>The Watch Pool holds anime that is "on deck" to watch in the near future. Every time I finish an anime, I roll a six-sided die, and the anime in that row number in the Watch Pool becomes the next anime to watch. I then use my <a href="{{ route('anime_ctw') }}">Commit to Watch</a> tool to pull a random anime from my Planning list and move it to the Watch Pool.</p>
    <p>If there are 7 anime in the pool and none under Currently Watching, it means I'm taking a break on my anime for some reason. I have picked one of six items in the pool to watch next, and added a seventh item</p>
    <table class="table">
        <thead class="thead-light">
        <tr>
            <th scope="col" class="text-center" width="10%"></th>
            <th scope="col" class="text-center" width="60%">Title</th>
            <th scope="col" class="text-center" width="10%">Format</th>
            <th scope="col" class="text-center" width="10%">Length</th>
            <th scope="col" class="text-center" width="10%">Avg. Score</th>
        </tr>
        </thead>
        <tbody>@forelse($watch_pool as $anime)
            <tr>
                <td rowspan="2"><img src="{{ $anime->cover_thumbnail }}" alt="{{ $anime->title_native }}"/></td>
                <td><a href="{{ $anime->site_url }}" target="_blank" rel="noopener" data-toggle="tooltip" data-placement="top" data-original-title="{{ !is_null($anime->title_english) ? $anime->title_english : $anime->title_romaji }}"><span lang="ja">{{ $anime->title_native }}</span></a></td>
                <td class="text-center">{{ $anime->format }}</td>
                <td class="text-center">{{ $anime->duration }} min.</td>
                <td class="text-center">{{ $anime->average_score }}</td>
            </tr>
            <tr>
                <td colspan="4">
                    <p>{!! $anime->description  !!}</p>
                    <p><strong>Studio(s):</strong> {!! $anime->studios !!}</p>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center">My Watch Pool is empty.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
    <br/>
    <h3>Top 10 Best</h3>
    <p>These anime are the best anime that I've watched, calculated by adding together my personal score and AniLists' Average (weighed) Score.</p>
    <table class="table">
        <thead class="thead-light">
        <tr>
            <th scope="col" class="text-center" width="10%"></th>
            <th scope="col" class="text-center" width="40%">Title</th>
            <th scope="col" class="text-center" width="10%">Format</th>
            <th scope="col" class="text-center" width="10%">Length</th>
            <th scope="col" class="text-center" width="10%">Avg. Score</th>
            <th scope="col" class="text-center" width="10%">My Score</th>
            <th scope="col" class="text-center" width="10%">Score Factor</th>
        </tr>
        </thead>
        <tbody>
        @forelse($top_ten_best as $anime)
            <tr>
                <td rowspan="2"><img src="{{ $anime->cover_thumbnail }}" alt="{{ $anime->title_native }}"/></td>
                <td><a href="{{ $anime->site_url }}" target="_blank" rel="noopener" data-toggle="tooltip" data-placement="top" data-original-title="{{ !is_null($anime->title_english) ? $anime->title_english : $anime->title_romaji }}"><span lang="ja">{{ $anime->title_native }}</span></a></td>
                <td class="text-center">{{ $anime->format }}</td>
                <td class="text-center">{{ $anime->duration }} min.</td>
                <td class="text-center">{{ $anime->average_score }}</td>
                <td class="text-center">{{ $anime->my_score }}</td>
                <td class="text-center">{{ $anime->score_factor }}</td>
            </tr>
            <tr>
                <td colspan="6">
                    <p>{!! $anime->description  !!}</p>
                    <p><strong>Studio(s):</strong> {!! $anime->studios !!}</p>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center">The Top Ten Best can't be displayed right now.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
@endsection