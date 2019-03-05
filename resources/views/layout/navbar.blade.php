<ul class="nav nav-tabs">
    <li class="nav-item">
        @if(request()->is('/'))
            <a class="nav-link active" href="/">Home</a>
        @else
            <a class="nav-link" href="/">Home</a>
        @endif
    </li>
    <li class="nav-item">
        @if(request()->is('wanikani*'))
        <a class="nav-link active dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">WaniKani</a>
        @else
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">WaniKani</a>
        @endif
        <div class="dropdown-menu">
            <a class="dropdown-item" href="{{ route('wk_summary') }}">Summary</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="{{ route('wk_levels', ["1-10"]) }}">Levels 1-10</a>
            <a class="dropdown-item" href="{{ route('wk_levels', ["11-20"]) }}">Levels 11-20</a>
            <a class="dropdown-item" href="{{ route('wk_levels', ["21-30"]) }}">Levels 21-30</a>
            <a class="dropdown-item" href="{{ route('wk_levels', ["31-40"]) }}">Levels 31-40</a>
            <a class="dropdown-item" href="{{ route('wk_levels', ["41-50"]) }}">Levels 41-50</a>
            <a class="dropdown-item" href="{{ route('wk_levels', ["51-60"]) }}">Levels 51-60</a>
        </div>
    </li>
    <li class="nav-item">
        @if(request()->is('anime*'))
            <a class="nav-link active dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Anime</a>
        @else
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Anime</a>
        @endif
            <div class="dropdown-menu">
                <a class="dropdown-item" href="{{ route('anime_summary') }}">Summary</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('anime_ctw') }}">Commit to Watch</a>
            </div>
    </li>
    <li class="nav-item">
        <a class="nav-link disabled" href="#">PrefRev</a>
    </li>
</ul>