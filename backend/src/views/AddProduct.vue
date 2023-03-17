<script setup>

</script>

<template>
<div class="addProduct">
    <h1>Add New Product</h1>
    <div class="card">
        <div class="card-title">
            <h2>Basic Information</h2>
            <p>Fill all information below</p>
        </div>
        <form action="">
            <div class="form-group">
                <label for="">產品名稱</label>
                <input type="text" />
            </div>
            <div class="form-group">
                <label for="">產品價格</label>
                <input type="number" />
            </div>
            <div class="form-group">
                <label for="">產品描述</label>
                <textarea></textarea>
            </div>
            <div class="form-group">
                <label for="">產品圖片</label>
                <label for="imagefile" class="imagefileFor">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mb-2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                    </svg>
                    <span>將文件拖放到此處或單擊以上傳。</span>
                </label>
                <input type="file" id="imagefile" hidden>
            </div>
            <div class="form-group">
                <button type="submit">保存更改</button>
            </div>
        </form>
    </div>
</div>
</template>

<style lang="scss" scoped>
.addProduct{
    display: flex;
    flex-direction: column;
    >h1{
        font-weight: 900;
        color:#fff;
    }
    >.card{
        background-color: #242A30;
        border-radius:12px;
        padding: 1.5rem 2.5rem;
        margin-top: 25px;
        >.card-title{
            border-bottom: 1px #2d343c solid;
            padding-bottom: 15px;
            h2{
                color:#fff;
                font-size: 17px;
                font-weight: 800;
            }
            p{
                margin-top: 5px;
            }
        }
        >form{
            width: 100%;
            display: grid;
            grid-template-columns: repeat(2, 50%);
            grid-column-gap: 30px;
            >.form-group{
                display: flex;
                flex-direction: column;
                padding: 12px 0;
                >label{
                    margin-bottom: 10px;
                    font-weight: 900;
                    font-size: 13px;
                    color:#CED4DA;
                }
                >.imagefileFor{
                    cursor: pointer;
                    width:100%;
                    height: 114px;
                    background-color: #282F36;
                    border:2px #30373f dashed;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    flex-direction: column;
                    font-size: 15px;
                }
                >input[type='text'], input[type='number']{
                    border:none;
                    outline: none;
                    border-radius: 5px;
                    padding: 0 12px;
                    background-color: #282F36;
                    color:#adb5bd;
                    border:1px #30373f solid;
                    height: 36px;
                    font-size: 14px;
                }
                >textarea{
                    border:none;
                    outline: none;
                    border-radius: 5px;
                    padding:8px 12px;
                    background-color: #282F36;
                    color:#adb5bd;
                    border:1px #30373f solid;
                    height: 114px;
                    font-size: 14px;
                    resize: none;
                    resize: vertical;
                }
                >button{
                    color:#f6f6f6;
                    background-color: #1c84ee;
                    border-color: #1c84ee;
                    border-radius: 5px;
                    height: 38px;
                    font-size: 13px;
                    font-weight: 500;
                    width: 78px;
                    transition: .3s;
                    &:hover{
                        background-color: #1870ca;
                        border-color: #1870ca;
                    }
                }
            }
        }
    }
}

input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

input[type="number"] {
  -moz-appearance: textfield; /* Firefox */
}
</style>