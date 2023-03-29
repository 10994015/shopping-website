<x-app-layout>
    <div class="profile">
        <form action="{{route('profile.update')}}" method="post" 
        x-data="{
            billingAddress: {{ json_encode([
                'address1' => old('billing_address1', $billingAddress['address1']),
                'city' => old('billing_city', $billingAddress['city']),
                'state' => old('billing_state', $billingAddress['state']),
                'country_code' => old('billing_country_code', $billingAddress['country_code']),
            ]) }},
            shippingAddress: {{ json_encode([
                'address1' => old('shipping_address1', $shippingAddress['address1']),
                'city' => old('shipping_city', $shippingAddress['city']),
                'state' => old('shipping_state', $shippingAddress['state']),
                'country_code' => old('shipping_country_code', $shippingAddress['country_code']),
            ]) }},
            sameBilling:false,
            sameBillingAddress:function(e){
                console.log(this.sameBilling)
                if(this.sameBilling){
                    this.shippingAddress.address1 = this.billingAddress.address1;
                    this.shippingAddress.city = this.billingAddress.city;
                    this.shippingAddress.state = this.billingAddress.state;
                    this.shippingAddress.country_code = this.billingAddress.country_code;
                }
            }
        }">
            @csrf
            @if(session()->has('flash_message'))
            <p class="text-green-500 mb-5">{{session('flash_message')}}</p>
            @endif
            <h2>基本資料設定</h2>
            <div class="label-group">
                <label for="">
                    <p>姓</p>
                    <x-profile-input type="text" name="first_name" value="{{old('first_name', $customer->first_name)}}" />
                </label>
                <label for="">
                    <p>名</p>
                    <x-profile-input type="text" name="last_name" value="{{old('last_name', $customer->last_name)}}" />
                </label>
            </div>
            <label for="">
                <p>電子郵件</p>
                <x-profile-input type="email" name="email" value="{{old('email', $user->email)}}" />
            </label>
            <label for="">
                <p>手機號碼</p>
                <x-profile-input type="text" name="phone" value="{{old('phone', $customer->phone)}}" />
            </label>
            <label for="">
                <p>密碼</p>
                <a href="/profile/reset-password">設定新的密碼</a>
            </label>
            <div class="title-group">
                <h2>帳單地址</h2>
            </div>
            <label for="">
                <p>送貨地點</p>
                <select name="shipping_country_code" id="" x-model="billingAddress.counrty_code">
                    <option value="tai">台灣</option>
                </select>
            </label>
            <div class="label-group">
                <label for="billing_city">
                    <p>城市/縣</p>
                    <select type="select" name="billing_city" id="billing_city" x-model="billingAddress.city">
                        <option value="">請選擇</option>
                        <option value="桃園市">桃園市</option>
                        <option value="台北市">台北市</option>
                    </select>
                </label>
                <label for="billing_state">
                    <p>地區</p>
                    <select name="billing_state" id="billing_area" x-model="billingAddress.state">
                        <option value="">請選擇</option>
                        <option value="桃園區">桃園區</option>
                        <option value="中壢區">中壢區</option>
                    </select>
                </label>
            </div>
            <label for="billing_address1">
                <p>地址</p>
                <x-profile-input type="text" placeholder="" name="billing_address1" x-model="billingAddress.address1" />
            </label>
            <div class="title-group">
                <h2>收件地址</h2>
                <label for="sameBilling">
                    <input type="checkbox" id="sameBilling" x-on:change="sameBillingAddress($event.target)" x-model="sameBilling" />
                    同帳單地址
                </label>
            </div>
            <label for="billing_country_code">
                <p>送貨地點</p>
                <select name="billing_country_code" id="" x-model="shippingAddress.counrty_code">
                    <option value="tai">台灣</option>
                </select>
            </label>
            <div class="label-group">
                <label for="shipping_city">
                    <p>城市/縣</p>
                    <select name="shipping_city" id="" x-model="shippingAddress.city">
                        <option value="">請選擇</option>
                        <option value="桃園市">桃園市</option>
                        <option value="台北市">台北市</option>
                    </select>
                </label>
                <label for="shipping_state">
                    <p>地區</p>
                    <select name="shipping_state" id="" x-model="shippingAddress.state">
                        <option value="">請選擇</option>
                        <option value="桃園區">桃園區</option>
                        <option value="中壢區">中壢區</option>
                    </select>
                </label>
            </div>
            <label for="">
                <p>地址</p>
                <x-profile-input type="text" placeholder="" name="shipping_address1" x-model="shippingAddress.address1" />
            </label>
            <x-auth-validation-errors class="mb-4" :errors="$errors" />
            <div class="btn-group" class="mt-10">
                <button class="cancel">取消</button>
                <button class="">儲存變更</button>
            </div>
        </form>
    </div>
    


@push('scripts')
<script>
    document.querySelector('header').classList.add('isActive');
</script>
@endpush
</x-app-layout>