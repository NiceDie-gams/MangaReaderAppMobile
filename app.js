document.addEventListener('DOMContentLoaded', () => {

    const loginTab = document.getElementById('login-tab');
    const registerTab = document.getElementById('register-tab');
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');
    const loginMessage = document.getElementById('login-message');
    const registerMessage = document.getElementById('register-message');

    loginTab.addEventListener('click', () => {
        loginTab.classList.add('active');
        registerTab.classList.remove('active');
        loginForm.classList.add('active');
        registerForm.classList.remove('active');
        clearMessages();
    });

    registerTab.addEventListener('click', () => {
        registerTab.classList.add('active');
        loginTab.classList.remove('active');
        registerForm.classList.add('active');
        loginForm.classList.remove('active');
        clearMessages();
    });

    function clearMessages() {
        loginMessage.textContent = '';
        loginMessage.className = 'message';
        registerMessage.textContent = '';
        registerMessage.className = 'message';
    }

    loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const username = document.getElementById('login-username').value.trim();
        const password = document.getElementById('login-password').value;

        if (!username || !password) {
            showMessage(loginMessage, 'Заполните все поля', 'error');
            return;
        }

        try {
            // реальная реализация здесь, не забыть!!!
            await fakeAuth({ username, password });
            showMessage(loginMessage, 'Успешный вход! (демо)', 'success');
            loginForm.reset();
        } catch (error) {
            showMessage(loginMessage, error.message, 'error');
        }
    });

    registerForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const username = document.getElementById('reg-username').value.trim();
        const email = document.getElementById('reg-email').value.trim();
        const password = document.getElementById('reg-password').value;
        const confirm = document.getElementById('reg-confirm').value;

        if (!username || !email || !password || !confirm) {
            showMessage(registerMessage, 'Заполните все поля', 'error');
            return;
        }

        if (password !== confirm) {
            showMessage(registerMessage, 'Пароли не совпадают', 'error');
            return;
        }

        try {
            await fakeRegister({ username, email, password });
            showMessage(registerMessage, 'Регистрация успешна! (демо)', 'success');
            registerForm.reset();
        } catch (error) {
            showMessage(registerMessage, error.message, 'error');
        }
    });

    function showMessage(element, text, type) {
        element.textContent = text;
        element.className = `message ${type}`;
    }

    function fakeAuth(data) {
        return new Promise((resolve, reject) => {
            setTimeout(() => {
                if (data.username && data.password) {
                    resolve({ success: true });
                } else {
                    reject(new Error('Неверный логин или пароль'));
                }
            }, 500);
        });
    }

    function fakeRegister(data) {
        return new Promise((resolve, reject) => {
            setTimeout(() => {
                if (data.username && data.email && data.password) {
                    resolve({ success: true });
                } else {
                    reject(new Error('Ошибка регистрации'));
                }
            }, 500);
        });
    }

    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('sw.js')
            .then(reg => console.log('Service Worker registered', reg))
            .catch(err => console.log('Service Worker registration failed', err));
    }
});