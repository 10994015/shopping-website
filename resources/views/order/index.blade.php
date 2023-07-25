<x-app-layout>
    <div class="orders">
        <div class="order-list">
            <div class="order-item title">
                <div class="number">訂單編號</div>
                <div>時間</div>
                <div>付款狀態</div>
                <div>總金額</div>
                <div>訂單詳情</div>
            </div>
            @foreach($orders as $order)
            <div class="order-item">
                <div class="number">
                    <small class="md">訂單編號:</small>
                    #{{$order->id}}</div>
                <div><small class="md">日期:</small>{{$order->orderDate()}}</div>
                <div><span class="{{($order->isPaid()) ? 'paid' : 'unpaid'}}">{{($order->isPaid()) ? '已付款' : '未付款'}}</span></div>
                <div><small class="md">總金額:</small>${{(int)$order->total_price}}</div>
                <div>
                    <a href="{{route('order.view', $order)}}">訂單詳情</a>
                    @if(!$order->isPaid())
                        <form action="{{route('checkout.checkout-order', $order)}}" method="post" class="mt-5">
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
            @endforeach
        </div>
    </div>
@push('scripts')
<script>
    document.querySelector('header').classList.add('isActive');
</script>
@endpush
</x-app-layout>
