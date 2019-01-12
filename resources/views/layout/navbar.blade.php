<ul class="nav nav-tabs">
    <li class="nav-item">
        @if(request()->is('/'))
        <a class="nav-link active" href="/">Home</a>
        @else
            <a class="nav-link" href="/">Home</a>
        @endif
    </li>
    <li class="nav-item">
        @if(request()->is('kanji'))
            <a class="nav-link active" href="/kanji">Kanji</a>
        @else
            <a class="nav-link" href="/kanji">Kanji</a>
        @endif
    </li>
   {{-- <li class="nav-item">
        @if(request()->is('kanji_v2'))
            <a class="nav-link active" href="/kanji_v2">Kanji V2</a>
        @else
            <a class="nav-link" href="/kanji_v2">Kanji V2</a>
        @endif
    </li>--}}
    <li class="nav-item">
        <a class="nav-link disabled" href="#">Anime</a>
    </li>
</ul>