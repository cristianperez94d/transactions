function post({ form={} }) {
  const result = axios.post(`api/transactions`, form);
  return result;
}

function get({page = 1, filter='',value=''}) {
  const result = axios.get(`api/transactions?page=${page}&filter=${filter}&value=${value}`);
  return result;
}
  
  export default {
    post,
    get,
  };