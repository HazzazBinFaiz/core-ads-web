<ul @if($isFirst) class="firstList" @endif>
    @foreach($user->allChildren as $under)
        @if($loop->first)
            @php $layer++ @endphp
        @endif
        <li>{{ $under->fullname }} ( {{ $under->username }} ) - ( {{ $under->place_direction }} )
            @if(($under->allChildren->count()) > 0 && ($layer < $maxLevel))
                @include($activeTemplate.'partials.under_tree_placement',['user'=>$under,'layer'=>$layer,'isFirst'=>false])
            @endif
        </li>
    @endforeach
</ul>
