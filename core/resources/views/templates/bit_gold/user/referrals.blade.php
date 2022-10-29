@extends($activeTemplate.'layouts.master')
@section('content')
<div class="cmn-section">
    <div class="container">
        <div class="card">
            <div class="card-body">
                @if(auth()->user()->referrer)
                    <h4 class="mb-2">@lang('You are referred by') {{ auth()->user()->referrer->fullname }}</h4>
                @endif
                <div class="col-md-12 mb-4">
                    <label>@lang('Referral Link')</label>
                    <div class="input-group">
                        <input type="text" name="text" class="form-control form--control referralURL"
                            value="{{ route('home') }}?reference={{ auth()->user()->username }}" readonly>
                            <span class="input-group-text copytext copyBoard" id="copyBoard"> <i class="fa fa-copy"></i> </span>
                    </div>
                </div>
                @if($user->allReferrals->count() > 0 && $maxLevel > 0)
                <div class="treeview-container">
                    <ul class="treeview">
                      <li class="items-expanded"> {{ $user->fullname }} ( {{ $user->username }} )
                            @include($activeTemplate.'partials.under_tree',['user'=>$user,'layer'=>0,'isFirst'=>true])
                        </li>
                    </ul>
                </div>
                @endif
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                @if(auth()->user()->placement)
                    <h4 class="mb-2">@lang('You are placed under') {{ auth()->user()->placement->fullname }}</h4>
                @endif
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label>@lang('Placement Link (Left)')</label>
                        <div class="input-group">
                            <input type="text" name="text" class="form-control form--control referralURL"
                                   value="{{ route('home') }}?placement={{ auth()->user()->username }}&direction=left" readonly>
                            <span class="input-group-text copytext copyBoard" id="copyBoard"> <i class="fa fa-copy"></i> </span>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label>@lang('Placement Link (Right)')</label>
                        <div class="input-group">
                            <input type="text" name="text" class="form-control form--control referralURL"
                                   value="{{ route('home') }}?placement={{ auth()->user()->username }}&direction=right" readonly>
                            <span class="input-group-text copytext copyBoard" id="copyBoard"> <i class="fa fa-copy"></i> </span>
                        </div>
                    </div>
                </div>
                @if($user->allChildren->count() > 0 && $maxLevel > 0)
                    <div class="treeview-container">
                        <ul class="treeview">
                            <li class="items-expanded"> {{ $user->fullname }} ( {{ $user->username }} )
                                @include($activeTemplate.'partials.under_tree_placement',['user'=>$user,'layer'=>0,'isFirst'=>true])
                            </li>
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('style-lib')
    <link href="{{ asset('assets/global/css/jquery.treeView.css') }}" rel="stylesheet" type="text/css">
@endpush

@push('script')
<script src="{{ asset('assets/global/js/jquery.treeView.js') }}"></script>
<script>
    (function($){
    "use strict"
        $('.treeview').treeView();
        $('.copyBoard').click(function(event){
            var parent = event.target.parentElement;
            while (parent.tagName !== 'DIV') {
                parent = parent.parentElement
            }
            var copyText = parent.querySelector(".referralURL");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            document.execCommand("copy");
            copyText.blur();
            this.classList.add('copied');
            setTimeout(() => this.classList.remove('copied'), 1500);
        });
    })(jQuery);
</script>
@endpush
