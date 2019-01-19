@extends('layout.default')
@section('content')
    <br/>
    <h2 class="text-center">WaniKani Summary</h2>
    <br/>
    <h3>Level Progression</h3>
    <p>Wanikani has 60 levels of kanji, covering most of the kanji found up to the N1 Japanese Proficiency Test. This represents the current level I'm on.</p>
    <div class="progress" style="height: 40px;">
        <div class="progress-bar" role="progressbar" style="background: #DD9300; color: #ffffff; width: {{ (100 / $user['max_level_granted_by_subscription']) * $user['level'] }}%;" aria-valuenow="{{ (100 / $user['max_level_granted_by_subscription']) * $user['level'] }}" aria-valuemin="0" aria-valuemax="100">{{ $user['level'] }}/{{ $user['max_level_granted_by_subscription'] }}</div>
    </div>
    <hr/>
    @if($burned_items['burned'] >= 88)
    <h3>Burned Items</h3>
    <p>These items are “fluent” in my brain. The answers come with little-to-no effort. I will remember these items for a long, long time. Even if I don’t use them and “forget” them sometime in the future, they should come back to me quickly after recalling it. Items that are “burned” no longer show up in reviews.</p>
    <div class="progress" style="height: 40px;">
        <div class="progress-bar" role="progressbar" style="background: #DD9300; color: #ffffff; width: {{ (100 / $burned_items['total']) * $burned_items['burned'] }}%;" aria-valuenow="{{ (100 / $burned_items['total']) * $burned_items['burned'] }}" aria-valuemin="0" aria-valuemax="100">{{ $burned_items['burned'] }}/{{ $burned_items['total'] }}</div>
    </div>
    <hr/>
    @endif
    <h3>Recent Unlocks</h3>
    <p>The 30 most recent items unlocked are shown below. <span style="background: #0093dd;">Radicals are blue,</span> <span style="background: #dd0093;">kanji are magenta,</span> <span style="background: #9300dd;">and vocabulary are purple.</span></p>
    <table class="table">
        <thead class="thead-light">
        <tr>
            <th scope="col" class="text-center" width="90%">Items</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                @forelse($recent_unlocks as $item)
                    <a href="{{ $item->document_url }}" target="_blank" rel="noopener" data-toggle="tooltip" data-placement="top" data-original-title="{{ $item->meanings }}">
                            <span lang="ja" class="no-wrap"
                                  @switch($item->object)
                                  @case('radical')
                                  style="background: #0093dd;"
                                  @break
                                  @case('kanji')
                                  style="background: #dd0093;"
                                  @break
                                  @case('vocabulary')
                                  style="background: #9300dd;"
                        @break
                                    @endswitch
                                >
                        @if(!is_null($item->character_image))
                                    {!! $item->character_image !!}
                                @else
                                    {{ $item->characters }}
                                @endif
                    </span>
                    </a>
                    &nbsp;
                @empty
                There are no recent unlocks to display.
                @endforelse
            </td>
        </tr>
        </tbody>
    </table>
    <hr/>
    <h3>Critical Items</h3>
    <p>Any items that I seriously need to review will show up here. An item will leave this table once it's been properly memorized, and I'm no longer getting it wrong a lot. <span style="background: #0093dd;">Radicals are blue,</span> <span style="background: #dd0093;">kanji are magenta,</span> <span style="background: #9300dd;">and vocabulary are purple.</span></p>
    <table class="table">
        <thead class="thead-light">
        <tr>
            <th scope="col" class="text-center" width="90%">Items</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                @forelse($critical_items as $item)
                    <a href="{{ $item->document_url }}" target="_blank" rel="noopener" data-toggle="tooltip" data-placement="top" data-original-title="{{ $item->meanings }}">
                            <span lang="ja" class="no-wrap"
                                  @switch($item->object)
                                  @case('radical')
                                  style="background: #0093dd;"
                                  @break
                                  @case('kanji')
                                  style="background: #dd0093;"
                                  @break
                                  @case('vocabulary')
                                  style="background: #9300dd;"
                        @break
                                    @endswitch
                                >
                        @if(!is_null($item->character_image))
                                    {!! $item->character_image !!}
                                @else
                                    {{ $item->characters }}
                                @endif
                    </span>
                    </a>
                    &nbsp;
                @empty
                    There are no Critical Items!
                @endforelse
            </td>
        </tr>
        </tbody>
    </table>
    <hr/>
    <h3>SRS Distribution</h3>
    <p>The Spacial Repetition System (SRS) queues up reviews for radicals, kanji, and vocabulary at predetermined times, increasing farther apart as you show the ability to memorize them. New or unlearned items start at Initiate, and move to Apprentice I once you learn their lessons; they then increase in intervals until they are Burned into your mind. If you get items wrong during reviews, they can be moved down an SRS stage to help improve your learning them.</p>
    <table class="table">
        <thead class="thead-light">
        <tr>
            <th scope="col" class="text-center">SRS Stage</th>
            @if($user['level'] <= 3)
                <th scope="col" class="text-center">Accelerated Interval <a data-toggle="tooltip" data-placement="top" data-original-title="The accelerated interval is used for the first two levels of assignments.">[?]</a></th>
            @endif
            @if($user['level'] >= 3)
                <th scope="col" class="text-center">Interval</th>
            @endif
            <th scope="col" class="text-center">Total</th>
            <th scope="col" class="text-center">Radicals</th>
            <th scope="col" class="text-center">Kanji</th>
            <th scope="col" class="text-center">Vocabulary</th>
        </tr>
        </thead>
        <tbody>
        @foreach($srs_distribution as $stage)
            @if($stage['total'] > 0)
                <tr>
                    <th scope="row" class="text-center">{{ $stage['name'] }}</th>
                    @if($user['level'] <= 3)
                    <td class="text-center">{{ $stage['acc_interval'] }}</td>
                    @endif
                    @if($user['level'] >= 3)
                        <td class="text-center">{{ $stage['interval'] }}</td>
                    @endif
                    <td class="text-center">{{ number_format($stage['total']) }}</td>
                    <td class="text-center">{{ number_format($stage['radicals']) }}</td>
                    <td class="text-center">{{ number_format($stage['kanji']) }}</td>
                    <td class="text-center">{{ number_format($stage['vocabulary']) }}</td>
                </tr>
            @endif
        @endforeach
        </tbody>
    </table>
    <hr/>
    <h3>Study Queue</h3>
    <p>Below are my queued up lessons and reviews. My schedule is as follows:</p>
    <dl>
        <dt>Monday - Friday</dt>
        <dd>Reviews at 6:00AM, 12:00PM, 6:00PM &mdash; Lessons at 6:00PM</dd>
        <dt>Saturday and Sunday</dt>
        <dd>Reviews after 9:00AM &mdash; Lessons at 12:00PM </dd>
    </dl>
    <p><span style="background: #0093dd;">Radicals are blue,</span> <span style="background: #dd0093;">kanji are magenta,</span> <span style="background: #9300dd;">and vocabulary are purple.</span></p>
    <h4>Lessons</h4>
    <table class="table">
        <thead class="thead-light">
        <tr>
            <th scope="col" class="text-center" width="5%">Level</th>
            <th scope="col" class="text-center" width="5%">Lessons Pending</th>
            <th scope="col" class="text-center" width="90%">Items</th>
        </tr>
        </thead>
        <tbody>
        @forelse($study_queue['lessons']['subjects'] as $level => $types)
        <tr>
            <th scope="row" class="text-center">{{ $level }}</th>
            <td class="text-center">{{ number_format($study_queue['lessons']['totals'][$level]) }}</td>
            <td class="text-center">
                @foreach($types as $items => $objects)
                    @foreach($objects as $item)
                        <a href="{{ $item->document_url }}" target="_blank" rel="noopener" data-toggle="tooltip" data-placement="top" data-original-title="{{ $item->meanings }}">
                            <span lang="ja" class="no-wrap"
                    @switch($item->object)
                        @case('radical')
                                style="background: #0093dd;"
                        @break
                        @case('kanji')
                                  style="background: #dd0093;"
                        @break
                        @case('vocabulary')
                                  style="background: #9300dd;"
                        @break
                    @endswitch
                                >
                        @if(!is_null($item->character_image))
                            {!! $item->character_image !!}
                        @else
                            {{ $item->characters }}
                        @endif
                    </span>
                        </a>
                                &nbsp;
                    @endforeach
                @endforeach
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="4" class="text-center">There are no lessons in the queue.</td>
        </tr>
        @endforelse
        </tbody>
    </table>
    @forelse($study_queue['reviews'] as $review)
        <h4>Reviews Available {{ $review['available_at'] }}</h4>
        <table class="table">
            <thead class="thead-light">
            <tr>
                <th scope="col" class="text-center" width="5%">Level</th>
                <th scope="col" class="text-center" width="5%">Reviews Pending</th>
                <th scope="col" class="text-center" width="90%">Items</th>
            </tr>
            </thead>
            <tbody>
            @forelse($review['subjects'] as $level => $types)
                <tr>
                    <th scope="row" class="text-center">{{ $level }}</th>
                    <td class="text-center">{{ $review['totals'][$level] }}</td>
                    <td class="text-center">
                        @foreach($types as $items => $objects)
                            @foreach($objects as $item)
                                <a href="{{ $item->document_url }}" target="_blank" rel="noopener" data-toggle="tooltip" data-placement="top" data-original-title="{{ $item->meanings }}">
                                    @switch($item->object)
                                        @case('radical')
                                        <span lang="ja"  class="no-wrap" style="background: #0093dd;">
                        @break
                                            @case('kanji')
                            <span lang="ja"  class="no-wrap" style="background: #dd0093;">
                        @break
                                @case('vocabulary')
                                <span lang="ja"  class="no-wrap" style="background: #9300dd;">
                        @break
                                    @endswitch
                                    @if(!is_null($item->character_image))
                                        {!! $item->character_image !!}
                                    @else
                                        {{ $item->characters }}
                                    @endif
                    </span>
                                </a>
                                &nbsp;
                            @endforeach
                        @endforeach
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">There are no lessons in the queue.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    @empty
        <h4>Reviews</h4>
        <table class="table">
            <thead class="thead-light">
            <tr>
                <th scope="col" class="text-center" width="5%">Level</th>
                <th scope="col" class="text-center" width="5%">Reviews Pending</th>
                <th scope="col" class="text-center" width="90%">Items</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td colspan="3" class="text-center">There are no reviews in the queue.</td>
            </tr>
            </tbody>
        </table>
    @endforelse
    <br/>
@endsection