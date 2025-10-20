function toggleLogin() {
  document.getElementById('login-form').classList.remove('hidden');
  document.getElementById('signup-form').classList.add('hidden');
  document.getElementById('login-toggle').classList.add('active');
  document.getElementById('signup-toggle').classList.remove('active');
}

function toggleSignup() {
  document.getElementById('signup-form').classList.remove('hidden');
  document.getElementById('login-form').classList.add('hidden');
  document.getElementById('signup-toggle').classList.add('active');
  document.getElementById('login-toggle').classList.remove('active');
}

// Affiche Login par défaut
toggleLogin();


document.getElementById("signup-form").addEventListener("submit", async (e) => {
  e.preventDefault();
  const nom = document.getElementById("signup-nom").value;
  const email = document.getElementById("signup-email").value;
  const password = document.getElementById("signup-password").value;

  const response = await fetch("http://localhost:8080/api/utilisateurs/signup", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ nom, email, password })
  });

  const data = await response.json();
  alert(data.message || "Inscription réussie !");
});
