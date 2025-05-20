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

   if (loginBtn) {
    loginBtn.addEventListener('click', function () {
        openModal(loginForm);
    });
}

if (registerBtn) {
    registerBtn.addEventListener('click', function () {
        openModal(registerForm);
    });
}

    overlay.addEventListener('click', closeModal);

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });

    // Корзина
    const cartPhoneInput = document.querySelector('#cart-phone');
    const cartOrderBtn = document.querySelector('.cart-modal__order-btn');
    const cartModal = document.querySelector('.cart-modal');
    const cartCloseBtn = document.querySelector('.cart-modal__close-btn');
    const cartList = document.querySelector('.cart-list');
    let cart = JSON.parse(localStorage.getItem('cart') || '[]');

    // Проверяем, существует ли поле ввода номера телефона
    if (cartPhoneInput) {
        cartPhoneInput.addEventListener('input', function () {
            cartOrderBtn.disabled = !cartPhoneInput.value.trim(); // Активируем кнопку, если поле заполнено
        });
    }

    // Проверяем, существует ли иконка корзины
    const cartIcon = document.querySelector('.cart-icon');
    if (cartIcon) {
        cartIcon.addEventListener('click', function () {
            updateCartUI();
            cartModal.classList.add('active');
        });
    }

    // Проверяем, существует ли кнопка закрытия корзины
    if (cartCloseBtn) {
        cartCloseBtn.addEventListener('click', function () {
            cartModal.classList.remove('active');
        });
    }

    // Проверяем, существует ли кнопка оформления заказа
    if (cartOrderBtn) {
        cartOrderBtn.addEventListener('click', function () {
            const phone = cartPhoneInput.value.trim();
            fetch('order.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ cart, phone }),
            })
                .then((res) => res.json())
                .then((data) => {
                    if (data.success) {
                        showMessage('Заказ успешно оформлен!', 'success');
                        cart = [];
                        localStorage.setItem('cart', JSON.stringify(cart));
                        updateCartUI();
                        cartModal.classList.remove('active');
                        cartPhoneInput.value = ''; // Очищаем поле ввода номера телефона
                    } else {
                        showMessage(data.message, 'error');
                    }
                })
                .catch((error) => console.error('Ошибка:', error));
        });
    }

    // Функция обновления интерфейса корзины
    function updateCartUI() {
        if (!cartList) return;
        cartList.innerHTML = '';
        if (cart.length === 0) {
            cartList.innerHTML = '<li>Корзина пуста</li>';
            cartOrderBtn.disabled = true;
        } else {
            cart.forEach((item) => {
                const li = document.createElement('li');
                li.textContent = item;
                const removeBtn = document.createElement('button');
                removeBtn.textContent = 'Удалить';
                removeBtn.className = 'btn';
                removeBtn.style.background = '#f44336';
                removeBtn.style.marginLeft = '10px';
                removeBtn.onclick = () => {
                    cart = cart.filter((i) => i !== item);
                    localStorage.setItem('cart', JSON.stringify(cart));
                    updateCartUI();
                };
                li.appendChild(removeBtn);
                cartList.appendChild(li);
            });
            cartOrderBtn.disabled = !cartPhoneInput.value.trim(); // Проверяем поле телефона при обновлении корзины
        }
    }

    // Добавление товаров в корзину
    document.querySelectorAll('.tariff-card__btn').forEach((btn, idx) => {
        btn.addEventListener('click', function () {
            const product = idx === 0 ? 'Отчетность' : 'Комплексный сервис';
            if (!cart.includes(product)) {
                cart.push(product);
                localStorage.setItem('cart', JSON.stringify(cart));
                showMessage('Товар добавлен в корзину', 'success');
            } else {
                showMessage('Товар уже в корзине', 'error');
            }
        });
    });

    const orderModal = document.querySelector('.order-modal');
    const orderForm = document.querySelector('#order-form');
    const orderCloseBtn = document.querySelector('.order-modal__close-btn');

    // Показать форму завершения заказа
    if (cartOrderBtn) {
        cartOrderBtn.addEventListener('click', function () {
            cartModal.classList.remove('active');
            orderModal.classList.add('active');
        });
    }

    // Закрыть форму завершения заказа
    if (orderCloseBtn) {
        orderCloseBtn.addEventListener('click', function () {
            orderModal.classList.remove('active');
        });
    }

    // Отправка данных заказа
    if (orderForm) {
        orderForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const phone = document.querySelector('#phone').value;

            fetch('order.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ cart, phone }),
            })
                .then((res) => res.json())
                .then((data) => {
                    if (data.success) {
                        showMessage('Заказ успешно оформлен!', 'success');
                        cart = [];
                        localStorage.setItem('cart', JSON.stringify(cart));
                        updateCartUI();
                        orderModal.classList.remove('active');
                    } else {
                        showMessage(data.message, 'error');
                    }
                })
                .catch((error) => console.error('Ошибка:', error));
        });
    }
});