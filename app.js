document.addEventListener('DOMContentLoaded', () => {

    const loginTab = document.getElementById('login-tab');
    const registerTab = document.getElementById('register-tab');
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');
    const loginMessage = document.getElementById('login-message');
    const registerMessage = document.getElementById('register-message');

    const STORAGE_KEY = 'app_users';

    function getUsers() {
        const users = localStorage.getItem(STORAGE_KEY);
        return users ? JSON.parse(users) : [];
    }

    function saveUsers(users) {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(users));
    }

    async function registerUser(username, email, password) {
        return new Promise((resolve, reject) => {
            const users = getUsers();
            if (users.some(user => user.username === username)) {
                reject(new Error('Пользователь с таким именем уже существует'));
                return;
            }
            users.push({ username, email, password });
            saveUsers(users);
            resolve({ success: true });
        });
    }

    async function loginUser(username, password) {
        return new Promise((resolve, reject) => {
            const users = getUsers();
            const user = users.find(user => user.username === username);
            if (!user) {
                reject(new Error('Пользователь не найден'));
                return;
            }
            if (user.password !== password) {
                reject(new Error(`Неверный пароль возможно вы имели ввиду ${user.password}`));
                return;
            }
            resolve({ success: true, username });
        });
    }

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
            await loginUser(username, password);
            showMessage(loginMessage, 'Успешный вход!', 'success');
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
            await registerUser(username, email, password);
            showMessage(registerMessage, 'Регистрация успешна! Теперь можно войти.', 'success');
            registerForm.reset();
            setTimeout(() => {
                registerTab.click();
            }, 1000);
        } catch (error) {
            showMessage(registerMessage, error.message, 'error');
        }
    });

    function showMessage(element, text, type) {
        element.textContent = text;
        element.className = `message ${type}`;
    }

    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('sw.js')
            .then(reg => console.log('Service Worker registered', reg))
            .catch(err => console.log('Service Worker registration failed', err));
    }
});