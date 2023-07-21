<header x-data="{
    cartItemsCount:{{\App\Http\Helpers\Cart::getCartItemsCount()}},
    cartTotalPrice:{{json_encode(\App\Http\Helpers\Cart::getCartItems())}}.reduce((accum, next) => accum + next.price * next.quantity, 0),
    updateCartItemsCount: function(data) {
        this.cartItemsCount = data.count
        this.cartTotalPrice = data.cartItems.reduce((accum, next) => accum + next.price * next.quantity, 0)
    },
    init(){
        const cartItems = {{json_encode(\App\Http\Helpers\Cart::getCartItems())}};
        this.cartTotalPrice =  cartItems.reduce((accum, next) => accum + next.price * next.quantity, 0)
    }
}"
x-ref="header" x-on:cart-change.window="updateCartItemsCount($event.detail)">
    <a href="/" class='logo'>
        <img src="/images/logo.png" alt="" />
        <span>房子ROW</span>
    </a>
    <nav>
        <a href="/" class="md-hidn">首頁</a>
        <a href="/store" class="md-hidn">線上商店</a>
        <a href="/about" class="md-hidn">關於我們</a>
        <a href="/contact" class="md-hidn">聯絡我們</a>
       
        <a @if(strpos(Route::currentRouteName(), 'cart') === false)  @click="$dispatch('cart-open')" href="javascript:;" id="cart-btn" @else href="/cart" @endif > 
            <p x-text=`$${cartTotalPrice}`></p>
            <div class="icon" x-cloak>
                <span class="cart-number" x-show="cartItemsCount" x-text="cartItemsCount" ></span>
                <i class="fa-solid fa-bag-shopping"></i>
            </div>
        </a>

        @if(Auth::check())
        <a href="javascript:;" class="myaccount">
            <i class="fa-solid fa-user mr-1"></i>{{Auth::user()->name}}
            <ol>
                <p x-on:click="window.location.href='/profile'"><i class="fa-solid fa-user"></i>我的帳號</p>
                <p x-on:click="window.location.href='/orders'"><i class="fa-solid fa-file"></i>訂單查詢</p>
                <form method="post" action="{{route('logout')}}" id="logoutForm" onclick="logoutForm.submit()"><p>@csrf<i class="fa-solid fa-right-from-bracket"></i>登出</p></form>
            </ol>
        </a>
        @else
        <a href="/login" ><i class="fa-solid fa-user"></i></a>
        @endif
        <a href="javascript:;" class="menu">
            <i class="fas fa-bars"></i>
        </a>
    </nav>
</header>

@push('scripts')

<script>
</script>
@endpush