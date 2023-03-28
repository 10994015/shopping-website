<header x-data="{
    cartItemsCount:{{\App\Http\Helpers\Cart::getCartItemsCount()}},
    cartTotalPrice:{{json_encode(\App\Http\Helpers\Cart::getCartItems())}}.reduce((accum, next) => accum + next.price * next.quantity, 0),
    updateCartItemsCount: function(data) {
        this.cartItemsCount = data.count.count
        
        this.cartTotalPrice = data.count.cartItems.reduce((accum, next) => accum + next.price * next.quantity, 0)
    },
    init(){
        const cartItems = {{json_encode(\App\Http\Helpers\Cart::getCartItems())}};
        cartTotalPrice =  cartItems.reduce((accum, next) => accum + next.price * next.quantity, 0)
    }
}"
x-ref="header" x-on:cart-change.window="updateCartItemsCount($event.detail)">
    <a href="/" class='logo'>
        <img src="/images/logo.png" alt="" />
        <span>房子ROW</span>
    </a>
    <nav>
        <a href="/">首頁</a>
        <a href="/store">線上商店</a>
        <a href="">關於我們</a>
        <a href="">聯絡我們</a>
        @if(strpos(Route::currentRouteName(), 'cart') === false)
        <a href="javascript:;" id="cart-btn">
            <p x-text=`$${cartTotalPrice}`></p>
            <div class="icon" x-cloak>
                <span class="cart-number" x-show="cartItemsCount" x-text="cartItemsCount" ></span>
                <i class="fa-solid fa-bag-shopping"></i>
            </div>
        </a>
        @else
        <a href="/cart">
            <p x-text=`$${cartTotalPrice}`></p>
            <div class="icon" x-cloak>
                <span class="cart-number" x-show="cartItemsCount" x-text="cartItemsCount" ></span>
                <i class="fa-solid fa-bag-shopping"></i>
            </div>
        </a>
        
        @endif
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