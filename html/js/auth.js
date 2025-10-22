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

    toggleLogin();