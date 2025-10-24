const API_BASE_URL = 'http://localhost:24789/api';

function toggleLogin() {
  document.getElementById('article-login').hidden = false;
  document.getElementById('article-signup').hidden = true;
  document.getElementById('btn-login').setAttribute('aria-expanded', 'true');
  document.getElementById('btn-signup').setAttribute('aria-expanded', 'false');
}

function toggleSignup() {
  document.getElementById('article-login').hidden = true;
  document.getElementById('article-signup').hidden = false;
  document.getElementById('btn-login').setAttribute('aria-expanded', 'false');
  document.getElementById('btn-signup').setAttribute('aria-expanded', 'true');
}

function toggleLoader(show = true) {
  let loader = document.getElementById('api-loader');
  
  if (!loader) {
    loader = document.createElement('div');
    loader.id = 'api-loader';
    loader.style.position = 'fixed';
    loader.style.top = '50%';
    loader.style.left = '50%';
    loader.style.transform = 'translate(-50%, -50%)';
    loader.style.width = '50px';
    loader.style.height = '50px';
    loader.style.borderRadius = '50%';
    loader.style.border = '5px solid rgba(255, 165, 0, 0.3)';
    loader.style.borderTopColor = '#FF9800'; 
    loader.style.animation = 'spin 1s linear infinite';
    loader.style.zIndex = '1001';
    loader.style.display = 'none';
    
    const style = document.createElement('style');
    style.textContent = '@keyframes spin { 0% { transform: translate(-50%, -50%) rotate(0deg); } 100% { transform: translate(-50%, -50%) rotate(360deg); } }';
    document.head.appendChild(style);
    
    const overlay = document.createElement('div');
    overlay.id = 'loader-overlay';
    overlay.style.position = 'fixed';
    overlay.style.top = '0';
    overlay.style.left = '0';
    overlay.style.width = '100%';
    overlay.style.height = '100%';
    overlay.style.backgroundColor = 'rgba(0, 0, 0, 0.3)';
    overlay.style.zIndex = '1000';
    overlay.style.display = 'none';
    
    document.body.appendChild(overlay);
    document.body.appendChild(loader);
  }
  
  const overlay = document.getElementById('loader-overlay');
  
  if (show) {
    loader.style.display = 'block';
    overlay.style.display = 'block';
  } else {
    loader.style.display = 'none';
    overlay.style.display = 'none';
  }
}

function showMessage(message, type = 'info') {
  let messageContainer = document.getElementById('message-container');
  
  if (!messageContainer) {
    messageContainer = document.createElement('div');
    messageContainer.id = 'message-container';
    messageContainer.style.position = 'fixed';
    messageContainer.style.top = '20px';
    messageContainer.style.left = '50%';
    messageContainer.style.transform = 'translateX(-50%)';
    messageContainer.style.padding = '10px 20px';
    messageContainer.style.borderRadius = '4px';
    messageContainer.style.fontWeight = '500';
    messageContainer.style.zIndex = '1000';
    document.body.appendChild(messageContainer);
  }
  
  switch (type) {
    case 'success':
      messageContainer.style.backgroundColor = '#4CAF50';
      messageContainer.style.color = 'white';
      break;
    case 'error':
      messageContainer.style.backgroundColor = '#F44336';
      messageContainer.style.color = 'white';
      break;
    default:
      messageContainer.style.backgroundColor = '#2196F3';
      messageContainer.style.color = 'white';
  }
  
  messageContainer.textContent = message;
  messageContainer.style.display = 'block';
  
  setTimeout(() => {
    messageContainer.style.display = 'none';
  }, 3000);
}

document.getElementById('form-login').addEventListener('submit', async function(e) {
  e.preventDefault();
  
  const email = document.getElementById('email-login').value;
  const password = document.getElementById('password-login').value;
  
  if (!email || !password) {
    showMessage('Veuillez remplir tous les champs', 'error');
    return;
  }
  
  try {
    toggleLoader(true); 
    
    const response = await fetch(`${API_BASE_URL}/signin`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ email, password })
    });
    
    const data = await response.json();
    
    toggleLoader(false); 
    
    if (data.type === 'success') {
      localStorage.setItem('access_token', data.access_token);
      localStorage.setItem('refresh_token', data.refresh_token);
      localStorage.setItem('user', JSON.stringify(data.user));
      
      showMessage('Connexion réussie! Redirection...', 'success');
      
      setTimeout(() => {
        window.location.href = 'catalogue.html';
      }, 1000);
    } else {
      showMessage(data.message || 'Erreur lors de la connexion', 'error');
    }
  } catch (error) {
    toggleLoader(false);
    console.error('Erreur de connexion:', error);
    showMessage('Problème de connexion au serveur', 'error');
  }
});

document.getElementById('form-signup').addEventListener('submit', async function(e) {
  e.preventDefault();
  
  const nom = document.getElementById('nom-signup').value;
  const email = document.getElementById('email-signup').value;
  const password = document.getElementById('password-signup').value;
  
  if (!nom || !email || !password) {
    showMessage('Veuillez remplir tous les champs', 'error');
    return;
  }
  
  const nomParts = nom.split(' ');
  const prenom = nomParts[0] || '';
  const nomFamille = nomParts.slice(1).join(' ') || '';
  
  try {
    toggleLoader(true); 
    
    const response = await fetch(`${API_BASE_URL}/inscription`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ 
        nom: nomFamille,
        prenom: prenom,
        email: email,
        motDePasse: password
      })
    });
    
    const data = await response.json();
    
    toggleLoader(false); 
    
    if (response.ok) {
      showMessage('Inscription réussie! Vous pouvez maintenant vous connecter.', 'success');
      setTimeout(() => {
        toggleLogin();
      }, 1500);
    } else {
      showMessage(data.message || 'Erreur lors de l\'inscription', 'error');
    }
  } catch (error) {
    toggleLoader(false); 
    console.error('Erreur d\'inscription:', error);
    showMessage('Problème de connexion au serveur', 'error');
  }
});

function checkAuth() {
  const token = localStorage.getItem('access_token');
  if (token) {
    window.location.href = 'catalogue.html';
  }
}

function logout() {
  localStorage.removeItem('access_token');
  localStorage.removeItem('refresh_token');
  localStorage.removeItem('user');
  window.location.href = 'auth.html';
}

function getCurrentUser() {
  const userStr = localStorage.getItem('user');
  return userStr ? JSON.parse(userStr) : null;
}

async function refreshTokenIfNeeded() {
  const refreshToken = localStorage.getItem('refresh_token');
  if (!refreshToken) return false;
  
  try {
    toggleLoader(true); 
    
    const response = await fetch(`${API_BASE_URL}/refresh-token`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ refresh_token: refreshToken })
    });
    
    const data = await response.json();
    
    toggleLoader(false); 
    
    if (data.type === 'success') {
      localStorage.setItem('access_token', data.access_token);
      localStorage.setItem('refresh_token', data.refresh_token);
      localStorage.setItem('user', JSON.stringify(data.user));
      return true;
    }
    
    return false;
  } catch (error) {
    toggleLoader(false);
    console.error('Erreur lors du rafraîchissement du token:', error);
    return false;
  }
}

async function fetchWithAuth(url, options = {}, showLoaderFlag = true) {
  const token = localStorage.getItem('access_token');
  if (!token) {
    window.location.href = 'auth.html';
    return null;
  }
  
  const headers = {
    ...options.headers,
    'Authorization': `Bearer ${token}`
  };
  
  try {
    if (showLoaderFlag) toggleLoader(true); 
    
    const response = await fetch(url, { ...options, headers });
    
    if (response.status === 401) {
      const refreshed = await refreshTokenIfNeeded();
      if (refreshed) {
        const newToken = localStorage.getItem('access_token');
        headers.Authorization = `Bearer ${newToken}`;
        return fetch(url, { ...options, headers });
      } else {
        if (showLoaderFlag) toggleLoader(false);
        logout();
        return null;
      }
    }
    
    if (showLoaderFlag) toggleLoader(false); 
    
    return response;
  } catch (error) {
    if (showLoaderFlag) toggleLoader(false); 
    console.error('Erreur lors de la requête:', error);
    return null;
  }
}

document.addEventListener('DOMContentLoaded', function() {
  checkAuth();
  toggleLogin();
});