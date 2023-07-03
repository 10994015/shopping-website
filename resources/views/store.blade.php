<x-app-layout>
    <div class="store" x-data="{
        filterOpen:false,
    }">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                <a href="#" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                    首頁
                </a>
                </li>
                <li aria-current="page">
                <div class="flex items-center">
                \<span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">線上商店</span>
                </div>
                </li>
            </ol>
        </nav>
        <h3>SHOP</h3>
        <div class="filter-list">
            <div class="flex items-center cursor-pointer" id="filterBtn" x-on:click="filterOpen = true">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-600">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" />
                </svg>
                篩選
            </div>
            <div>
                <select name="" id="">
                    <option value="">預設排列</option>
                    <option value="">依時間排列 由近至遠</option>
                    <option value="">依時間排列 由遠至近</option>
                    <option value="">依價格排列 由低至高</option>
                    <option value="">依價格排列 由高至低</option>
                </select>
            </div>
        </div>
        <div class="products-list">
            @foreach($products as $product)
            <div class="item" 
                x-data="{
                    isLoading:false,
                    productItem:{{json_encode($product)}},
                    addToCart:function(slug){
                        if(!this.isLoading){
                            this.isLoading = true;
                            console.log(this.productItem)
                            setTimeout(()=>{
                                axios.post('/cart/add/' + slug ,{product:this.productItem, quantity:1}).then(res=>{
                                    console.log(res.data)
                                    this.$dispatch('cart-change', res.data)
                                    this.$dispatch('shop-add-change', res.data)
                                });
                                this.isLoading = false;
                            },500)
                        }
                    }
                }"
            >
                <div class="add-cart" x-on:click="addToCart('{{$product->slug}}')">
                    <i  x-show="!isLoading" class="fa-solid fa-bag-shopping"></i>
                    <div x-show="isLoading" class="loading"></div>
                    <input type="hidden" value="{{$product->id}}" class="productId" />
                </div>
                <div class="sale-tag">Sale!</div>
                <div class="toolbox">加入購物車</div>
                <img src="{{$product->image}}" alt="{{$product->title}}"             onclick="window.location.href=`/product-detail/{{$product->slug}}`"
                 />
                <small>{{$product->category->name}}</small>
                <h3>{{$product->title}}</h3>
                <span><i class="fa-solid fa-star"></i>4.7</span>
                @if($product->sale_price)
                <div class="price-row"><span class="price">${{$product->price}}</span><span class="sale-price">${{$product->sale_price}}</span></div>
                @else
                <div class="price-row"><span class="sale-price">${{$product->price}}</span></div>
                @endif
            </div>
            @endforeach
        </div>
        <div class="filter-component" x-show.transition.duration.500ms="filterOpen"  >
            <div class="back"></div>
            <div x-bind:class="['filter']" x-show="filterOpen"    x-transition:enter-start="left-[-100%]" x-transition:enter-end="left-0"  x-transition:leave-start="left-0" x-transition:leave-end="left-[-100%]" x-on:click.outside="filterOpen = false" >
                <i class="fas fa-times close-filter" x-on:click="filterOpen = false" ></i>
                <div class="search-product">
                    <input type="text" class="search" placeholder="Search products..." />
                    <button type="button">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </button>
                </div>
                <div class="filter-price">
                    <h3>篩選價格</h3>
                    <div class="price-input">
                        <div class="field">
                            $<input type="number" class="input-min" value="0">
                        </div>
                        <div class="field">
                            $<input type="number" class="input-max" value="10000">
                        </div>
                    </div>
                    <div class="slider">
                        <div class="progress"></div>
                    </div>
                    <div class="range-input">
                        <input type="range" class="range-min" min="0" max="10000" value="0" step="100">
                        <input type="range" class="range-max" min="0" max="10000" value="10000" step="100">
                    </div>
                </div>
                <div class="products-categories">
                    <h3>商品分類</h3>
                    <div class="categories">
                        @foreach ($categories as $category)
                        <span>{{$category->name}} ({{DB::table('products')->where([['category_id', $category->id], ['hidden', 0]])->count()}})</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>



@push('scripts')


<script>
    document.querySelector('header').classList.add('isActive');
    const rangeInput = document.querySelectorAll(".range-input input"),
    priceInput = document.querySelectorAll(".price-input input"),
    range = document.querySelector(".slider .progress");
    let priceGap = 1000;

    priceInput.forEach(input =>{
        input.addEventListener("input", e =>{
            let minPrice = parseInt(priceInput[0].value),
            maxPrice = parseInt(priceInput[1].value);
            
            if((maxPrice - minPrice >= priceGap) && maxPrice <= rangeInput[1].max){
                if(e.target.className === "input-min"){
                    rangeInput[0].value = minPrice;
                    range.style.left = ((minPrice / rangeInput[0].max) * 100) + "%";
                }else{
                    rangeInput[1].value = maxPrice;
                    range.style.right = 100 - (maxPrice / rangeInput[1].max) * 100 + "%";
                }
            }
        });
    });

    rangeInput.forEach(input =>{
        input.addEventListener("input", e =>{
            let minVal = parseInt(rangeInput[0].value),
            maxVal = parseInt(rangeInput[1].value);

            if((maxVal - minVal) < priceGap){
                if(e.target.className === "range-min"){
                    rangeInput[0].value = maxVal - priceGap
                }else{
                    rangeInput[1].value = minVal + priceGap;
                }
            }else{
                priceInput[0].value = minVal;
                priceInput[1].value = maxVal;
                range.style.left = ((minVal / rangeInput[0].max) * 100) + "%";
                range.style.right = 100 - (maxVal / rangeInput[1].max) * 100 + "%";
            }
        });
    });
</script>
{{-- @include('components.add-cart') --}}

@endpush
</x-app-layout>