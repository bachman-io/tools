@extends('layout.default')
@section('content')
    <br/>
    <h2 class="text-center">WaniKani Progress</h2>
    <br/>
    <h3>Level Progression</h3>
    <p>Wanikani has 60 levels of kanji, covering most of the kanji found up to the N1 Japanese Proficiency Test. This represents the current level I'm on.</p>
    <div class="progress" style="height: 40px;">
        <div class="progress-bar" role="progressbar" style="background: #9300dd; width: {{ (100 / $user['max_level_granted_by_subscription']) * $user['level'] }}%;" aria-valuenow="{{ (100 / $user['max_level_granted_by_subscription']) * $user['level'] }}" aria-valuemin="0" aria-valuemax="100">{{ $user['level'] }}/{{ $user['max_level_granted_by_subscription'] }}</div>
    </div>
    <br/>
    <h3>Study Queue</h3>
    <p>Below are my queued up lessons and reviews. My schedule is as follows:</p>
    <dl>
        <dt>Monday - Friday</dt>
        <dd>Reviews at 6:00AM, 12:00PM, 6:00PM &mdash; Lessons at 6:00PM</dd>
        <dt>Saturday and Sunday</dt>
        <dd>Reviews and Lessons after 9:00AM</dd>
    </dl>
    <table class="table">
        <thead class="thead-light">
        <tr>
            <th scope="col" class="text-center">Next Review</th>
            <th scope="col" class="text-center">Lessons Pending</th>
            <th scope="col" class="text-center">Reviews Pending</th>
            <th scope="col" class="text-center">Reviews Within 1 Hour</th>
            <th scope="col" class="text-center">Reviews Within 1 Day</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th scope="row" class="text-center">{{ $review_date }}</th>
            <td><p class="text-center">{{ $study_queue['lessons_available'] }}</p></td>
            <td><p class="text-center">{{ $study_queue['reviews_available'] }}</p></td>
            <td><p class="text-center">{{ $study_queue['reviews_available_next_hour'] }}</p></td>
            <td><p class="text-center">{{ $study_queue['reviews_available_next_day'] }}</p></td>
        </tr>
        </tbody>
    </table>
    <br/>
    <h3>SRS Distribution</h3>
    <p>The Spacial Repetition System (SRS) queues up reviews for radicals, kanji, and vocabulary at predetermined times, increasing farther apart as you show the ability to memorize them. New or unlearned items start at Apprentice and increase in intervals until they are Burned into your mind. If you get items wrong during reviews, they can be moved down an SRS level to help improve your learning them.</p>
    <table class="table">
        <thead class="thead-light">
        <tr>
            <th scope="col" class="text-center">SRS Stage</th>
            <th scope="col" class="text-center">Review Interval</th>
            <th scope="col" class="text-center">Total</th>
            <th scope="col" class="text-center">Radicals</th>
            <th scope="col" class="text-center">Kanji</th>
            <th scope="col" class="text-center">Vocabulary</th>
        </tr>
        </thead>
        <tbody>
        @foreach($srs_distribution as $srs)
        @if($srs['total'] > 0)
        <tr>
            <th scope="row" class="text-center">{{ $srs['name'] }}</th>
            <td><p class="text-center">{{ $loop->iteration > 1 && $loop->iteration < 9 ? $srs['interval'] : '-' }}</p></td>
            <td><p class="text-center">{{ $srs['total'] }}</p></td>
            <td><p class="text-center">{{ $srs['radicals'] }}</p></td>
            <td><p class="text-center">{{ $srs['kanji'] }}</p></td>
            <td><p class="text-center">{{ $srs['vocabulary'] }}</p></td>
        </tr>
        @endif
        @endforeach
        </tbody>
    </table>
    <br/>
    {{--<h3>Recent Unlocks</h3>
    <p>The 5 most recent items unlocked are shown below. <span style="background: #0093dd;">Radicals are blue,</span> <span style="background: #dd0093;">kanji are magenta,</span> <span style="background: #9300dd;">and vocabulary are purple.</span></p>
    <table class="table">
        <tbody>
        <tr>
            @foreach($recent_unlocks as $unlock)
                @switch($unlock['type'])
                    @case('radical')
                    <td width="20%" style="background: #0093dd;">
                        @break
                    @case('kanji')
                    <td width="20%" style="background: #dd0093;">
                        @break
                    @case('vocabulary')
                    <td width="20%" style="background: #9300dd;">
                        @break
                @endswitch
                    <p class="text-center">@if($unlock['character'] === null)
                            <img class="mx-auto" src="{{ $unlock['image'] }}" width="21px" alt="{{ $unlock['meaning'] }}"
                        @else
                            <strong>{{ $unlock['character'] }}</strong>
                        @endif</p>
                    <p class="text-center">{{ $unlock['meaning'] }}</p>
                </td>
            @endforeach
        </tr>
        </tbody>
    </table>
    <br/>
    <h3>Critical Items</h3>
    <p>Any items that I seriously need to review will show up here. An item will leave this table once it's been properly memorized, and I'm no longer getting it wrong a lot. <span style="background: #0093dd;">Radicals are blue,</span> <span style="background: #dd0093;">kanji are magenta,</span> <span style="background: #9300dd;">and vocabulary are purple.</span></p>
    <table class="table">
        <tbody>
        <tr>
            @forelse($critical_items as $ci)
                @switch($ci['type'])
                    @case('radical')
                    <td width="20%" style="background: #0093dd;">
                    @break
                    @case('kanji')
                    <td width="20%" style="background: #dd0093;">
                    @break
                    @case('vocabulary')
                    <td width="20%" style="background: #9300dd;">
                        @break
                        @endswitch
                        <p class="text-center">@if($ci['character'] === null)
                                <img class="mx-auto" src="{{ $ci['image'] }}" width="21px" alt="{{ $ci['meaning'] }}"
                            @else
                                <strong>{{ $ci['character'] }}</strong>
                            @endif</p>
                        <p class="text-center">{{ $ci['meaning'] }}</p>
                        <p class="text-center">{{ $ci['percentage'] }}</p>
                    </td>
                @empty
                    <td>There are no Critical Items!</td>
                    @endforelse
        </tr>
        </tbody>
    </table>
    <br/>
    <h3>Levels</h3>
    <p>The current level is expanded at the bottom.</p>
    <div class="accordion" id="levelAccordion">
        @foreach($levels as $level)
            <div class="card">
                <div class="card-header" id="header-l{{ $loop->iteration }}">
                    <h2 class="mb-0">
                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse-l{{ $loop->iteration }}" aria-expanded="true" aria-controls="collapse-l{{ $loop->iteration }}">
                            Level {{ $loop->iteration }}
                        </button>
                    </h2>
                </div>
                <div id="collapse-l{{ $loop->iteration }}" class="collapse @if($loop->iteration === $user['level']) show @endif" aria-labelledby="header-l{{ $loop->iteration }}" data-parent="#levelAccordion">
                    <div class="card-body">
                        @if(isset($levels[$loop->iteration]['radicals']))
                            @if($loop->iteration === $user['level'])
                                <h4>Radicals Learned</h4>
                                <div class="progress" style="background: #333333; height: 40px;">
                                    <div class="progress-bar" role="progressbar" style=" background: #0093dd; width: {{ (100 / $level_progression['radicals_total']) * $level_progression['radicals_progress'] }}%;" aria-valuenow="{{ (100 / $level_progression['radicals_total']) * $level_progression['radicals_progress'] }}" aria-valuemin="0" aria-valuemax="100">{{ $level_progression['radicals_progress'] }}/{{ $level_progression['radicals_total'] }}</div>
                                </div>
                                <br/>
                            @else
                                <h4>Radicals</h4>
                            @endif
                            <p>Learned radicals are <span style="background: #0093dd">blue,</span> and unlearned radicals are <span style="background: #666666">grey.</span></p>
                            <table class="table" style="background: #666666">
                                <tbody>
                                <tr>
                                    @foreach($levels[$loop->iteration]['radicals'] as $r)
                                        @if(!is_null($r['user_specific']) && $r['user_specific']['srs'] !== 'apprentice')
                                            <td width="20%" style="background: #0093dd;">
                                        @else
                                            <td width="20%" style="background: #666666;">
                                                @endif
                                                <p class="text-center">@if($r['character'] === null)
                                                        <img class="mx-auto" src="{{ $r['image'] }}" width="21px" alt="{{ $r['meaning'] }}"
                                                    @else
                                                        <strong>{{ $r['character'] }}</strong>
                                                    @endif</p>
                                                <p class="text-center">{{ $r['meaning'] }}</p>
                                            </td>
                                            @if($loop->iteration % 5 === 0)
                                </tr>
                                <tr>
                                    @endif
                                    @endforeach
                                </tr>
                                </tbody>
                            </table>
                        @endif
                            @if($loop->iteration === $user['level'])
                                <h4>Kanji Leaned</h4>
                                <div class="progress" style="background: #333333; height: 40px;">
                                    <div class="progress-bar" role="progressbar" style="background: #dd0093; width: {{ (100 / $level_progression['kanji_total']) * $level_progression['kanji_progress'] }}%;" aria-valuenow="{{ (100 / $level_progression['kanji_total']) * $level_progression['kanji_progress'] }}" aria-valuemin="0" aria-valuemax="100">{{ $level_progression['kanji_progress'] }}/{{ $level_progression['kanji_total'] }}</div>
                                </div>
                                <br/>
                            @else
                        <h4>Kanji</h4>
                            @endif
                        <p>Learned kanji are <span style="background: #dd0093">magenta,</span> and unlearned kanji are <span style="background: #666666">grey.</span></p>
                        <table class="table" style="background: #666666">
                            <tbody>
                            <tr>
                                @foreach($levels[$loop->iteration]['kanji'] as $k)
                                    @if(!is_null($k['user_specific']) && $k['user_specific']['srs'] !== 'apprentice')
                                        <td width="20%" style="background: #dd0093;">
                                    @else
                                        <td width="20%" style="background: #666666;">
                                            @endif
                                            <p class="text-center">@if($k['character'] === null)
                                                    <img class="mx-auto" src="{{ $k['image'] }}" width="21px" alt="{{ $k['meaning'] }}"
                                                @else
                                                    <strong>{{ $k['character'] }}</strong>
                                                @endif</p>
                                            <p class="text-center">{{ $k['meaning'] }}</p>
                                        </td>
                                        @if($loop->iteration % 5 === 0)
                            </tr>
                            <tr>
                                @endif
                                @endforeach
                            </tr>
                            </tbody>
                        </table>
                        <h4>Vocabulary</h4>
                        <p>Learned vocabulary are <span style="background: #9300dd">purple,</span> and unlearned vocabulary are <span style="background: #666666">grey.</span></p>
                        <table class="table" style="background: #666666">
                            <tbody>
                            <tr>
                                @foreach($levels[$loop->iteration]['vocabulary'] as $v)
                                    @if(!is_null($v['user_specific']) && $v['user_specific']['srs'] !== 'apprentice')
                                        <td width="20%" style="background: #9300dd;">
                                    @else
                                        <td width="20%" style="background: #666666;">
                                            @endif
                                            <p class="text-center">@if($v['character'] === null)
                                                    <img class="mx-auto" src="{{ $v['image'] }}" width="21px" alt="{{ $v['meaning'] }}"
                                                @else
                                                    <strong>{{ $v['character'] }}</strong>
                                                @endif</p>
                                            <p class="text-center">{{ $v['meaning'] }}</p>
                                        </td>
                                        @if($loop->iteration % 5 === 0)
                            </tr>
                            <tr>
                                @endif
                                @endforeach
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    </div>--}}
@endsection