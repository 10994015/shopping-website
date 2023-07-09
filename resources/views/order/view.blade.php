<x-app-layout>
    <div class="myOrder">
        <div class="order">
            <p>訂單編號:#123</p>
            <p>2023/10/10</p>
            <p>目前狀態: <span class="{{($order->isPaid() ? 'paid' : 'unpaid')}}">{{$order->isPaid() ? '已付款' : '未付款'}}</span></p>
            <h2>訂單詳細資料</h2>
            <div class="column">
                <div class="title">商品</div>
                <div class="content">詳細資料</div>
            </div>
            @foreach($order->items as $item)
            <div class="column">
                <div class="title">{{$item->product->title}}</div>
                <div class="content">$ {{(int)$item->unit_price}}</div>
            </div>
            @endforeach
            <div class="column">
                <div class="title">總計</div>
                <div class="content">$ {{(int)$order->total_price}}</div>
            </div>
            <div class="column">
                <div class="title">運送方式</div>
                <div class="content">宅配</div>
            </div>
            <div class="column">
                <div class="title">付款方式</div>
                <div class="content">信用卡</div>
            </div>
            <div class="column">
                <div class="title">帳單地址</div>
                <div class="content">123</div>
            </div>
            <div class="column">
                <div class="title">收件地址</div>
                <div class="content">123</div>
            </div>
            <div class="column">
                <div class="title">姓名</div>
                <div class="content">{{$user->name}}</div>
            </div>
            <div class="column">
                <div class="title">Email</div>
                <div class="content">{{$user->email}}</div>
            </div>
            <div class="column">
                <div class="title">手機</div>
                <div class="content">{{$user->customer->phone}}</div>
            </div>
            <a href="/orders">回訂單列表</a>
            @if(!$order->isPaid())
            <form action="{{route('checkout.checkout-order', $order)}}" method="post" >
                @csrf
                <button class="gotopay">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-white mr-1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                    </svg>
                    前往付款
                </button>
            </form>
        @endif
        </div>
    </div>


    @push('scripts')
    <script>
        document.querySelector('header').classList.add('isActive');
    </script>
    @endpush
</x-app-layout>