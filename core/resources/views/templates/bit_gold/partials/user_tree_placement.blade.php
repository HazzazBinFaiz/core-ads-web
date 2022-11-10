<div class="w-100 d-flex justify-content-center">
    <div @if($user)
             style="cursor: pointer;" onclick="location.href = '{{ route('user.referrals', ['placement' => $user->username]) }}'" data-toggle="tree-tooltip"
         title='<div>
         <div class="d-flex justify-content-between"><span>Refer UserID : </span> <span>{{ optional($user->referrer)->username ?? 'N/A' }}</span></div>
         <div class="d-flex justify-content-between"><span>Invest Rank : </span><span>{{ \App\Lib\Rank::getRankName($user->rank) }}</span></div>
         <div class="d-flex justify-content-between"><span>Joining Rank : </span><span>{{ \App\Lib\JoiningRank::getRankName($user->joining_rank) }}</span></div>
         <div class="d-flex justify-content-between"><span>Total : </span><span>{{ $user->left_investment }} | {{ $user->right_investment }}</span></div>
         <div class="d-flex justify-content-between"><span>Actives : </span><span>{{ $user->left_active }} | {{ $user->right_active }}</span></div>
         <div class="d-flex justify-content-between"><span>Inactive : </span><span>{{ $user->left_count - $user->left_active }} | {{ $user->right_count - $user->right_active }}</span></div>
         <div class="d-flex justify-content-between"><span>Matched : </span><span>{{ $user->matched }}</span></div>
         </div>'
        @endif>
        <div class="border d-flex justify-content-center">
            <img @if(optional($user)->activated_at == null) class="inverse" @endif style="width: 4rem; height: 4rem;" src="/assets/images/globe.png"/>
        </div>
        <div>{{ optional($user)->username ?? 'N/A' }}</div>
    </div>
</div>
