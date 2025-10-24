// token.js
const API_BASE_URL = 'http://localhost:24789/api';

window.TokenService = {
  async refreshToken() {
    try {
      const refreshToken = localStorage.getItem('refresh_token');
      if (!refreshToken) {
        return false;
      }

      const response = await fetch(`${API_BASE_URL}/refresh-token`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ refresh_token: refreshToken })
      });

      if (!response.ok) {
        return false;
      }

      const data = await response.json();
      if (data.type === 'success') {
        localStorage.setItem('access_token', data.access_token);
        localStorage.setItem('refresh_token', data.refresh_token);
        return true;
      }
      return false;
    } catch (error) {
      console.error('Erreur lors du rafraîchissement du token:', error);
      return false;
    }
  },

  async fetchWithAuth(url, options = {}) {
    const token = localStorage.getItem('access_token');
    const headers = {
      ...(options.headers || {}),
      'Authorization': `Bearer ${token}`
    };

    let response = await fetch(url, {
      ...options,
      headers
    });

    if (response.status === 401) {
      const refreshed = await this.refreshToken();
      
      if (refreshed) {
        const newToken = localStorage.getItem('access_token');
        headers.Authorization = `Bearer ${newToken}`;
        
        response = await fetch(url, {
          ...options,
          headers
        });
      } else {
        localStorage.removeItem('access_token');
        localStorage.removeItem('refresh_token');
        localStorage.removeItem('user');
        window.location.href = 'auth.html';
      }
    }

    return response;
  }
};