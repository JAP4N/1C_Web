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

    const reportsBtn = document.querySelector('.header__btn-reports');
    const reportsModal = document.querySelector('.reports-modal');
    const reportsOrdersBtn = document.querySelector('.reports-modal__orders-btn');
    const reportsTable = document.querySelector('.reports-modal__table');
    const reportsCloseBtn = document.querySelector('.reports-modal__close-btn');
    const reportsRequestsBtn = document.querySelector('.reports-modal__requests-btn');
    const reportsRequestsTable = document.querySelector('.reports-modal__requests-table');
    const reportsOverdueBtn = document.querySelector('.reports-modal__overdue-btn');
    const reportsOverdueTable = document.querySelector('.reports-modal__overdue-table');

    if (reportsBtn && reportsModal) {
        reportsBtn.addEventListener('click', function () {
            reportsModal.classList.add('active');
            reportsTable.classList.add('visually-hidden');
            if (reportsRequestsTable) reportsRequestsTable.classList.add('visually-hidden');
            if (reportsOverdueTable) reportsOverdueTable.classList.add('visually-hidden');
        });
    }
    if (reportsCloseBtn && reportsModal) {
        reportsCloseBtn.addEventListener('click', function () {
            reportsModal.classList.remove('active');
        });
    }
    if (reportsOrdersBtn && reportsTable) {
        reportsOrdersBtn.addEventListener('click', function () {
            fetch('get_orders.php')
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        let html = `<table>
                            <tr>
                                <th>ID</th>
                                <th>User ID</th>
                                <th>Имя</th>
                                <th>Услуга</th>
                                <th>Цена</th>
                                <th>Телефон</th>
                                <th>Дата</th>
                            </tr>`;
                        data.orders.forEach(order => {
                            html += `<tr>
                                <td>${order.id}</td>
                                <td>${order.user_id}</td>
                                <td>${order.username}</td>
                                <td>${order.service}</td>
                                <td>${order.price}</td>
                                <td>${order.phone}</td>
                                <td>${order.created_at}</td>
                            </tr>`;
                        });
                        html += `</table>`;
                        reportsTable.innerHTML = html;
                        reportsTable.classList.remove('visually-hidden');
                    } else {
                        reportsTable.innerHTML = '<div style="color:red;">Ошибка загрузки данных</div>';
                        reportsTable.classList.remove('visually-hidden');
                    }
                });
        });
    }
    if (reportsRequestsBtn && reportsRequestsTable) {
        reportsRequestsBtn.addEventListener('click', function () {
            fetch('get_advantages_requests.php')
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        let html = `<table>
                            <tr>
                                <th>ID</th>
                                <th>User ID</th>
                                <th>Логин</th>
                                <th>Имя из формы</th>
                                <th>Телефон</th>
                                <th>Вопрос</th>
                                <th>Дата</th>
                            </tr>`;
                        data.requests.forEach(req => {
                            html += `<tr>
                                <td>${req.id}</td>
                                <td>${req.user_id ?? ''}</td>
                                <td>${req.username}</td>
                                <td>${req.name}</td>
                                <td>${req.telephone}</td>
                                <td>${req.question}</td>
                                <td>${req.created_at}</td>
                            </tr>`;
                        });
                        html += `</table>`;
                        reportsRequestsTable.innerHTML = html;
                        reportsRequestsTable.classList.remove('visually-hidden');
                        reportsTable.classList.add('visually-hidden');
                    } else {
                        reportsRequestsTable.innerHTML = '<div style="color:red;">Ошибка загрузки данных</div>';
                        reportsRequestsTable.classList.remove('visually-hidden');
                        reportsTable.classList.add('visually-hidden');
                    }
                });
        });
    }
    if (reportsOverdueBtn && reportsOverdueTable) {
        reportsOverdueBtn.addEventListener('click', function () {
            fetch('get_overdue_orders.php')
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        let html = `<table>
                            <tr>
                                <th>ID</th>
                                <th>User ID</th>
                                <th>Имя</th>
                                <th>Услуга</th>
                                <th>Цена</th>
                                <th>Телефон</th>
                                <th>Дата заказа</th>
                                <th>Срок выполнения</th>
                            </tr>`;
                        data.orders.forEach(order => {
                            html += `<tr>
                                <td>${order.id}</td>
                                <td>${order.user_id}</td>
                                <td>${order.username}</td>
                                <td>${order.service}</td>
                                <td>${order.price}</td>
                                <td>${order.phone}</td>
                                <td>${order.created_at}</td>
                                <td>${order.deadline}</td>
                            </tr>`;
                        });
                        html += `</table>`;
                        reportsOverdueTable.innerHTML = html;
                        reportsOverdueTable.classList.remove('visually-hidden');
                        reportsTable.classList.add('visually-hidden');
                        reportsRequestsTable.classList.add('visually-hidden');
                    } else {
                        reportsOverdueTable.innerHTML = '<div style="color:red;">Ошибка загрузки данных</div>';
                        reportsOverdueTable.classList.remove('visually-hidden');
                        reportsTable.classList.add('visually-hidden');
                        reportsRequestsTable.classList.add('visually-hidden');
                    }
                }) // Закрываем вызов then
                .catch(error => console.error('Ошибка:', error)); // Добавляем обработку ошибок
    }); // Закрываем addEventListener
}

    // Для advantages-form: блокировка кнопки без согласия
    const advForm = document.querySelector('.advantages-form');
    if (advForm) {
        const acceptCheckbox = advForm.querySelector('.advantages-form__input--check');
        const submitBtn = advForm.querySelector('.advantages-form__btn');
        submitBtn.disabled = !acceptCheckbox.checked; // Всегда блокируем, если галочка не стоит
        acceptCheckbox.addEventListener('change', function () {
            submitBtn.disabled = !acceptCheckbox.checked;
        });

        // Отправка формы через AJAX
        advForm.addEventListener('submit', function (e) {
            e.preventDefault();
            if (!acceptCheckbox.checked) return; // Не отправлять, если галочка не стоит
            const formData = new FormData(advForm);
            fetch('save_advantages_form.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showMessage('Ваша заявка отправлена!', 'success');
                    advForm.reset();
                    submitBtn.disabled = true;
                } else {
                    showMessage(data.message || 'Ошибка отправки', 'error');
                }
            })
            .catch(() => showMessage('Ошибка отправки', 'error'));
        });
    }
});