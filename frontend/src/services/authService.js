import api from './api';

export const authService = {
  // 1. Ambil CSRF Cookie sebelum login
  async getCsrfToken() {
    return await api.get('/sanctum/csrf-cookie');
  },

  // 2. Login User
  async login(credentials) {
    await this.getCsrfToken(); // Selalu panggil csrf-cookie dulu
    const response = await api.post('/login', credentials);
    return response.data;
  },

  // 3. Ambil data user yang sedang login
  async getMe() {
    const response = await api.get('/api/v1/me');
    return response.data;
  },

  // 4. Logout User
  async logout() {
    const response = await api.post('/logout');
    return response.data;
  }
};