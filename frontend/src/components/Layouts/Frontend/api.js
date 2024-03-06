// api.js
import axios from 'axios';

const api = axios.create({
  //baseURL: 'http://localhost:8000/api/v1', // Update with your Laravel API URL
  baseURL: 'https://dhroobo-admin.dhakaitsolutions.com/api/v1', // Update with your Laravel API URL
});

api.interceptors.response.use(
  (response) => response,
  (error) => {
    console.error('Error submitting data:', error);
    return Promise.reject(error);
  }
);

export default api;
