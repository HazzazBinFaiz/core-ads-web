@extends($activeTemplate.'layouts.master')
@section('content')
<style>
    .inverse {
        -webkit-filter: grayscale(100%); /* Safari 6.0 - 9.0 */
        filter: grayscale(100%);
    }
</style>
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
                                   value="{{ route('home') }}?reference={{ auth()->user()->username }}&placement={{ auth()->user()->username }}&direction=left" readonly>
                            <span class="input-group-text copytext copyBoard" id="copyBoard"> <i class="fa fa-copy"></i> </span>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label>@lang('Placement Link (Right)')</label>
                        <div class="input-group">
                            <input type="text" name="text" class="form-control form--control referralURL"
                                   value="{{ route('home') }}?reference={{ auth()->user()->username }}&placement={{ auth()->user()->username }}&direction=right" readonly>
                            <span class="input-group-text copytext copyBoard" id="copyBoard"> <i class="fa fa-copy"></i> </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                    <div class="col-sm-12">
                        <div class="w-100 p-4 d-flex justify-content-center">
                            @if($placement->id != auth()->id() && $placement->placement)
                                <a href="{{ route('user.referrals', ['placement' => $placement->placement->username]) }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width: 2rem; height: 2rem; color: yellow;">
                                        <path fill-rule="evenodd" d="M11.47 2.47a.75.75 0 011.06 0l7.5 7.5a.75.75 0 11-1.06 1.06l-6.22-6.22V21a.75.75 0 01-1.5 0V4.81l-6.22 6.22a.75.75 0 11-1.06-1.06l7.5-7.5z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            @endif
                        </div>
                        <table class="w-100">
                            <tr>
                                <td colspan="4">
                                    @include($activeTemplate.'.partials.under_tree_placement', ['user' => $placement])
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    @include($activeTemplate.'.partials.under_tree_placement', ['user' => $left = optional($placement)->left()])
                                </td>
                                <td colspan="2">
                                    @include($activeTemplate.'.partials.under_tree_placement', ['user' => $right = optional($placement)->right()])
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    @include($activeTemplate.'.partials.under_tree_placement', ['user' => optional($left)->left()])
                                </td>
                                <td>
                                    @include($activeTemplate.'.partials.under_tree_placement', ['user' => optional($left)->right()])
                                </td>
                                <td>
                                    @include($activeTemplate.'.partials.under_tree_placement', ['user' => optional($right)->left()])
                                </td>
                                <td>
                                    @include($activeTemplate.'.partials.under_tree_placement', ['user' => optional($right)->right()])
                                </td>

                            </tr>
                        </table>
                    </div>
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
        $(function () {
            $('[data-toggle="tree-tooltip"]').tooltip({
                html: true
            })
        })
    })(jQuery);
</script>
@endpush
