function get() {
    const result = axios.get('api/users');
    return result;
}

export default {
    get,
};