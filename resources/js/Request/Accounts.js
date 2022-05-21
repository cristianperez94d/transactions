function postType({ form={} }) {
  const result = axios.post(`api/accounts/type`, form);
  return result;
}

function post({ form={} }) {
  const result = axios.post(`api/accounts`, form);
  return result;
}

function get() {
  const result = axios.get(`api/accounts`);
  return result;
}

export default {
  postType,
  post,
  get,
};