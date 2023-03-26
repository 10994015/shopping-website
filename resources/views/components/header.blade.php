<header x-data="{
    cartItemsCount:{{\App\Http\Helpers\Cart::getCartItemsCount()}},
    updateCartItemsCount: function(count) {
        this.cartItemsCount = {{\App\Http\Helpers\Cart::getCartItemsCount()}};
    }
}"
x-ref="header" x-on:cart-change.window="cartItemsCount = $event.detail.count">
    <a href="/" class='logo'>
        <img src="/images/logo.png" alt="" />
        <span>房子ROW</span>
    </a>
    <nav>
        <a href="/">首頁</a>
        <a href="/store">線上商店</a>
        <a href="">關於我們</a>
        <a href="">聯絡我們</a>
        <a href="javascript:;" id="cart-btn">
            <p>$ 0</p>
            <div class="icon">
                <span class="cart-number" x-show="cartItemsCount" x-text="cartItemsCount" x-cloak></span>
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