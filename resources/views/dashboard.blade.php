<x-app-layout>
    <div class="index">
        <section class="banner">
            <div class="text">
                <h1>最優質的植物，只在<span>FZR</span></h1>
                <p>種類繁多的植物只需 200元起</p>
                <span>Amazing Variety Of Plants Starting Just $ 200</span>
                <a href="/">SHOP NOW</a>
            </div>
        </section>
        <section class="intro">
            <div class="item">
                <i class="fa-solid fa-spa"></i>
                <div class="intro-text">
                    <h3>植物收藏</h3>
                    <p>適合您房間及花園的任何植物</p>
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
                    <p>如果該產品不合適</p>
                </div>
            </div>
        </section>
        <section class="featured">
            <h2>精選植物</h2>
            <p>精挑細選、萬中選一，以及最新的植物產品搶先看</p>
            <div class="featured-list">
                @for($n=0;$n<=12;$n++)
                <div class="item">
                    <div class="add-cart">
                        <i class="fa-solid fa-bag-shopping"></i>
                        <div class="loading"></div>
                        <input type="hidden" value="1" class="productId" />
                    </div>
                    <div class="sale-tag">Sale!</div>
                    <div class="toolbox">Add to cart</div>
                    <img src="/images/plant3-free-img.jpg" alt="" />
                    <small>植物</small>
                    <h3>薄荷</h3>
                    <span><i class="fa-solid fa-star"></i>4.7</span>
                    <div class="price-row"><span class="price">$300</span><span class="sale-price">$200</span></div>
                </div>
                @endfor
            </div>
        </section>
    </div>
@push('scripts')
<script type="text/javascript" >
    
const addCart = document.getElementsByClassName('add-cart');
const featured = document.querySelector('.featured');

const featuredItem  = featured.querySelectorAll('.item');

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
    console.log(23);
    window.location.href = "/dashboard";
}
for(let i=0;i<addCart.length;i++){
    addCart[i].addEventListener('click', addCartFn)
}
for(let i=0;i<featuredItem.length;i++){
    featuredItem[i].addEventListener('click', pushProductPageFn)
}
</script>
@endpush
</x-app-layout>

