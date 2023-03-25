import axiosClient from "../axios";

export function getUser({commit}){
    return axiosClient.get('/user').then(res=>{
        commit('setUser', res.data)
        return res;
    })
}

export function login({commit}, data){
    return axiosClient.post('login', data).then(res=>{
        commit('setUser', res.data.user);
        commit('setToken', res.data.token);
        return data;
    })
}

export function logout({commit}){
    return axiosClient.post('logout').then(res=>{
        commit('setToken', null);
        return res;
    })
}

export function getProducts({commit}, {url = null, search = '', perPage = 10, sort_field, sort_direction}){
    commit('setProducts', [true]);
    url = url || '/products';
    return axiosClient.get(url, {params:{search, per_page:perPage, sort_field, sort_direction}}).then(res=>{
        commit('setProducts', [false, res.data]);
    }).catch(err=>{
        commit('setProducts', [false]);
    })
}

export function createProduct({commit}, product){
    const hidden = (product.hidden) ? 1 :0;
    const featured = (product.featured) ? 1 :0;
    if(product.image instanceof File){
        const form = new FormData();
        form.append('title', product.title);
        form.append('image', product.image);
        form.append('category_id', product.category_id);
        form.append('price', product.price);
        form.append('sale_price', product.sale_price);
        form.append('description', product.description);
        form.append('short_description', product.short_description);
        form.append('manufacturer_name', product.manufacturer_name);
        form.append('hidden', hidden);
        form.append('featured', featured);
        product = form;
    }

    return axiosClient.post('/products', product);
}

export function updateProduct({commit}, product){
    const id = product.id;
    const hidden = (product.hidden) ? 1 :0;
    const featured = (product.featured) ? 1 :0;
    if(product.image instanceof File){
        const form = new FormData();
        form.append('id', product.id);
        form.append('title', product.title);
        form.append('image', product.image);
        form.append('category_id', product.category_id);
        form.append('price', product.price);
        form.append('sale_price', product.sale_price);
        form.append('description', product.description);
        form.append('short_description', product.short_description);
        form.append('manufacturer_name', product.manufacturer_name);
        form.append('hidden', hidden);
        form.append('featured', featured);
        form.append('_method', 'PUT');
        product = form;
    }else{
        product._method = 'PUT'
    }
    return axiosClient.post(`/products/${id}`, product);
}

export function deleteProduct({commit}, id){
    return axiosClient.delete(`/products/${id}`);
}

export function getProduct({commit}, id){
    return axiosClient.get(`/products/${id}`);
}

export function isExistProduct({commit}, id){
    return axiosClient.post(`/isExistProduct`, {id: id}).then(res=>{
        return res;
    });
}

export function createCategory({commit}, category){
    return axiosClient.post('/category', {category:category}).then(res=>{
        return res;
    })
}
export function editCategory({commit}, category){
    return axiosClient.put('/category', {category:category}).then(res=>{
        return res;
    })
}
export function getCategories({commit}){
    return axiosClient.get('/categories').then(res=>{
        commit('setCategories', res.data);
        return res;
    }).catch(err=>{
        console.error(err);
    })
}
export function deleteCategory({commit}, id){
    return axiosClient.delete(`/category/${id}`);
}