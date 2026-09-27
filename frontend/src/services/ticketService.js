import api from './api';

export const ticketService = {
  // Fetch daftar tiket dengan pagination
  async getTickets(page = 1, perPage = 5) {
    const response = await api.get(`/api/v1/tickets?page=${page}&per_page=${perPage}`);
    return response.data; // Mengembalikan { data: [...], links: {...}, meta: {...} }
  },

  // Detail tiket berdasarkan ID
  async getTicketById(id) {
    const response = await api.get(`/api/v1/tickets/${id}`);
    return response.data;
  },

  // Fetch daftar kategori untuk dropdown form
  async getCategories() {
    const response = await api.get('/api/v1/categories');
    return response.data; // Mengembalikan { data: [{id, name}] }
  },

  // Buat tiket baru (mengirim payload category_id datar)
  async createTicket(ticketData) {
    // ticketData contoh: { subject, description, category_id, is_urgent, note }
    const response = await api.post('/api/v1/tickets', ticketData);
    return response.data;
  }
};