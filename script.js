document.addEventListener('DOMContentLoaded', function () {
    const loginBtn = document.querySelector('.header__btn-login');
    const registerBtn = document.querySelector('.header__btn-register');
    const loginForm = document.querySelector('.login-form');
    const registerForm = document.querySelector('.register-form');
    const overlay = document.createElement('div');
    overlay.className = 'modal__overlay';

    document.body.appendChild(overlay);

    // Функция для открытия модального окна
    function openModal(form) {
        overlay.classList.add('active');
        form.classList.add('active');
    }

    // Функция для закрытия модального окна
    function closeModal() {
        overlay.classList.remove('active');
        loginForm.classList.remove('active');
        registerForm.classList.remove('active');
    }

    // Функция для отображения всплывающего сообщения
    function showMessage(message, type = 'success') {
        const messageBox = document.createElement('div');
        messageBox.className = `message-box message-box--${type}`;
        messageBox.textContent = message;

        document.body.appendChild(messageBox);

        setTimeout(() => {
            messageBox.classList.add('visible');
        }, 100); // Небольшая задержка для анимации

        setTimeout(() => {
            messageBox.classList.remove('visible');
            setTimeout(() => messageBox.remove(), 300); // Удаление после скрытия
        }, 3000); // Сообщение исчезает через 3 секунды
    }

    // Обработка формы авторизации
    loginForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(loginForm);
        fetch('login.php', {
            method: 'POST',
            body: formData,
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeModal();
                    showMessage(data.message, 'success');
                    setTimeout(() => location.reload(), 1000); // Перезагрузка страницы
                } else {
                    showMessage(data.message, 'error');
                }
            })
            .catch(error => console.error('Ошибка:', error));
    });

    // Обработка формы регистрации
    registerForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(registerForm);
        fetch('register.php', {
            method: 'POST',
            body: formData,
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeModal();
                    showMessage(data.message, 'success');
                    setTimeout(() => location.reload(), 1000); // Перезагрузка страницы
                } else {
                    showMessage(data.message, 'error');
                }
            })
            .catch(error => console.error('Ошибка:', error));
    });

    loginBtn.addEventListener('click', function () {
        openModal(loginForm);
    });

    registerBtn.addEventListener('click', function () {
        openModal(registerForm);
    });

    overlay.addEventListener('click', closeModal);

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });
});