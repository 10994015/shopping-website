<x-app-layout>
    <div class="index">
        <section class="banner">
            <div class="text">
                <h1>最優質的傢俱，只在<span>房子ROW</span></h1>
                <p>種類繁多的傢俱只需 200元起</p>
                <span>A wide range of furniture from as little as $200</span>
                <a href="/store">SHOP NOW</a>
            </div>
        </section>
        <section class="intro">
            <div class="item">
                <i class="fa-solid fa-chair"></i>
                <div class="intro-text">
                    <h3>家具擺設</h3>
                    <p>全新現代的傢俱系列</p>
                </div>
            </div>
            <div class="item">
                <i class="fa-solid fa-car-side"></i>
                <div class="intro-text">
                    <h3>免運費</h3>
                    <p>100%下單免運費</p>
                </div>
            </div>
            <div class="item">
                <i class="fa-solid fa-rotate-right"></i>
                <div class="intro-text">
                    <h3>完全退款</h3>
                    <p>如果該產品不適合你家</p>
                </div>
            </div>
        </section>
        <section class="news">
            <h2>最新傢俱</h2>
            <p>搶先購買精挑細選、萬中選一的傢俱，讓我們幫助您提高生活品質</p>
            <div class="news-list">
                @for($n=0;$n<8;$n++)
                <div class="item">
                    <div class="add-cart">
                        <i class="fa-solid fa-bag-shopping"></i>
                        <div class="loading"></div>
                        <input type="hidden" value="1" class="productId" />
                    </div>
                    <div class="sale-tag">Sale!</div>
                    <div class="toolbox">Add to cart</div>
                    <img src="/images/plant3-free-img.jpg" alt="" />
                    <small>椅子</small>
                    <h3>黑色扶手椅</h3>
                    <span><i class="fa-solid fa-star"></i>4.7</span>
                    <div class="price-row"><span class="price">$900</span><span class="sale-price">$700</span></div>
                </div>
                @endfor
            </div>
            <a href="/store" class="readmore">
                查看所有產品
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" />
                </svg>
            </a>
        </section>
        <section class="featured">
            <h2>精選商品</h2>
            <div class="featured-list">
                @for($i=0;$i<3;$i++)
                <div class="item">
                    <div class="imgbox"><img src="/images/office3.png" alt=""></div>
                    <h4>黑色扶手椅</h4>
                </div>
                @endfor
            </div>
        </section>
        <section class="combos">
            <div class="combo" style="background-image: url('/images/f1.jpg')">
                <div class="cover"></div>
                <div class="text-container">
                    <div>
                        <p>精選集 / 01</p>
                        <h4>Neo Futura 辦公家具</h4>
                    </div>
                    <a href="###">
                        店鋪收藏
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" />
                        </svg>
                    </a>
                </div>
            </div>
            <div class="combo" style="background-image: url('/images/f2.jpg')">
                <div class="cover"></div>
                <div class="text-container">
                    <div>
                        <p>精選集 / 02</p>
                        <h4>質樸的家庭辦公家具套裝</h4>
                    </div>
                    <a href="/store">
                        店鋪收藏
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" />
                        </svg>
                    </a>
                </div>
            </div>
        </section>
        <section class="feedback">
            <div class="feedback-list">
                @for($f=0;$f<3;$f++)
                <div class="item">
                    <div class="stars">
                        @for($i=0;$i<5;$i++) <i class="fa-solid fa-star"></i> @endfor
                    </div>
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit。Ut elit tellus, luctus nec ullamcorper mattis。
                    </p>
                    <span>User Name</span>
                </div>
                @endfor
            </div>
        </section>
        <section class="bottom-intro">
            <div class="cover"></div>
            <div class="text-intro">
                <span>房子ROW傢俱專賣</span>
                <h3>讓我們打造您夢想中的工作空間</h3>
                <a href="/store" class="readmore">
                    立即購物
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" />
                    </svg>
                </a>
            </div>
        </section>
    </div>
@push('scripts')
<script type="text/javascript" >
    
const addCart = document.getElementsByClassName('add-cart');
const news = document.querySelector('.news');

const newsItem  = news.querySelectorAll('.item');

const addCartFn = (e)=>{
    e.stopPropagation();
    if (e.target.tagName  == "I") return  e.target.parentNode.click();
    e.target.querySelector('.fa-solid').style.display = "none";
    e.target.querySelector('.loading').style.display = "block";
    setTimeout(() => {
        e.target.querySelector('i').style.display = "block";
        e.target.querySelector('.loading').style.display = "none";
    }, 1000);
}
const pushProductPageFn = ()=>{
    window.location.href = "/product-detail";
}
for(let i=0;i<addCart.length;i++){
    addCart[i].addEventListener('click', addCartFn)
}
for(let i=0;i<newsItem.length;i++){
    newsItem[i].addEventListener('click', pushProductPageFn)
}
</script>
@endpush
</x-app-layout>

