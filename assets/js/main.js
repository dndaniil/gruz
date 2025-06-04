// Валидация форм
const validateForm = (formId) => {
    const form = document.getElementById(formId);
    if (!form) return false;

    const inputs = form.querySelectorAll('input, select, textarea');
    let isValid = true;

    inputs.forEach(input => {
        const error = input.nextElementSibling;
        if (error && error.classList.contains('error')) {
            error.remove();
        }

        if (input.hasAttribute('required') && !input.value.trim()) {
            showError(input, 'Это поле обязательно для заполнения');
            isValid = false;
            return;
        }

        if (input.name === 'login' && input.value.length < 6) {
            showError(input, 'Логин должен содержать минимум 6 символов');
            isValid = false;
            return;
        }

        if (input.name === 'password' && input.value.length < 6) {
            showError(input, 'Пароль должен содержать минимум 6 символов');
            isValid = false;
            return;
        }

        if (input.name === 'phone') {
            const phoneRegex = /^\+7\(\d{3}\)-\d{3}-\d{2}-\d{2}$/;
            if (!phoneRegex.test(input.value)) {
                showError(input, 'Телефон должен быть в формате +7(XXX)-XXX-XX-XX');
                isValid = false;
                return;
            }
        }

        if (input.name === 'email') {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(input.value)) {
                showError(input, 'Введите корректный email адрес');
                isValid = false;
                return;
            }
        }
    });

    return isValid;
};

// Показ ошибки под полем ввода
const showError = (input, message) => {
    const error = document.createElement('div');
    error.className = 'error';
    error.textContent = message;
    input.parentNode.insertBefore(error, input.nextSibling);
};

// Форматирование телефона
const formatPhoneNumber = (input) => {
    input.addEventListener('input', (e) => {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 0) {
            if (value[0] !== '7') {
                value = '7' + value;
            }
            let formattedValue = '+7';
            if (value.length > 1) formattedValue += '(' + value.substring(1, 4);
            if (value.length > 4) formattedValue += ')-' + value.substring(4, 7);
            if (value.length > 7) formattedValue += '-' + value.substring(7, 9);
            if (value.length > 9) formattedValue += '-' + value.substring(9, 11);
            e.target.value = formattedValue;
        }
    });
};

// Инициализация форматирования телефона
document.addEventListener('DOMContentLoaded', () => {
    const phoneInputs = document.querySelectorAll('input[name="phone"]');
    phoneInputs.forEach(input => formatPhoneNumber(input));
});

// Обработка отправки формы
const handleFormSubmit = async (formId, url) => {
    const form = document.getElementById(formId);
    if (!form) return;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        if (!validateForm(formId)) return;

        const formData = new FormData(form);
        try {
            const response = await fetch(url, {
                method: 'POST',
                body: formData
            });
            const data = await response.json();
            
            if (data.success) {
                if (data.redirect) {
                    window.location.href = data.redirect;
                }
            } else {
                showError(form.querySelector('input'), data.message);
            }
        } catch (error) {
            console.error('Error:', error);
            showError(form.querySelector('input'), 'Произошла ошибка при отправке формы');
        }
    });
}; 