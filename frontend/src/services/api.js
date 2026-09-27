import axios from 'axios';

const api = axios.create({
  baseURL: 'http://localhost:8000', // Origin backend Laravel
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
  withCredentials: true, // WAJIB untuk Sanctum SPA Authentication
});

export default api;