export const route = (name, params) => {
    let url = `http://localhost/${name.replace(/\./g, '/')}`;
    if (params) {
        const queryParams = new URLSearchParams(params);
        if (queryParams.toString()) {
            url += `?${queryParams.toString()}`;
        }
    }
    
    return url;
};