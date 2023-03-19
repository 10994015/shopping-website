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
    if(product.image instanceof File){
        const form = new FormData();
        form.append('title', product.title);
        form.append('image', product.image);
        form.append('price', product.price);
        form.append('description', product.description);
        product = form;
    }

    return axiosClient.post('/products', product);
}

export function updateProduct({commit}, product){
    const id = product.id;
    if(product.image instanceof File){
        const form = new FormData();
        form.append('id', product.id);
        form.append('title', product.title);
        form.append('image', product.image);
        form.append('price', product.price);
        form.append('description', product.description);
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