import axios from 'axios';

const authApi= axios.create({
    baseURL:`${import.meta.env.VITE_API_URL}/`,   
    headers: {
      'X-Requested-With': 'XMLHttpRequest',
      'Accept': 'application/json'},
    withCredentials: true,
    withXSRFToken: true
});

/*authApi.interceptors.request.use((config) => 
{
  const token = getCsrfToken();    
  
  if (token) 
  {    
    console.log(token);
    config.headers["Authorization"]=`Bearer ${token}`;    
  }
  return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

function getCsrfToken(): string | null 
{
  const cookies = document.cookie.split('; ');
  for (let i = 0; i < cookies.length; i++) {
    const cookie = cookies[i].split('=');
    const cookieName = cookie[0];
    const cookieValue = cookie[1];
    if (cookieName === 'XSRF-TOKEN') {
      return cookieValue; //encodeURIComponent(cookieValue);
    }
  }
  return null;
}*/
  
export default authApi;