<header class="">
    <a href="/" class='logo'>
        <img src="/images/logo.png" alt="" />
        <span>房子ROW</span>
    </a>
    <nav>
        <a href="">首頁</a>
        <a href="/store">線上商店</a>
        <a href="">關於我們</a>
        <a href="">聯絡我們</a>
        <a href="javascript:;" id="cart-btn">
            <p>$ 0</p>
            <div class="icon">
                <span class="cart-number">0</span>
                <i class="fa-solid fa-bag-shopping"></i>
            </div>
        </a>
        @if(Auth::check())
        <a href="javascript:;" class="myaccount">
            <i class="fa-solid fa-user mr-1"></i>{{Auth::user()->name}}
            <ol>
                <p><i class="fa-solid fa-user"></i>我的帳號</p>
                <p><i class="fa-solid fa-file"></i>訂單查詢</p>
                <form method="post" action="{{route('logout')}}" id="logoutForm" onclick="logoutForm.submit()"><p>@csrf<i class="fa-solid fa-right-from-bracket"></i>登出</p></form>
            </ol>
        </a>
        @else
        <a href="/login" ><i class="fa-solid fa-user"></i></a>
        @endif
    </nav>
</header>

@push('scripts')

<script>
</script>
@endpush