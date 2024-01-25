document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.add-to-cart-button').forEach(function(button) {
        button.addEventListener('click', function() {
            var dishId = button.dataset.dishId;

            // Отправить fetch-запрос для добавления блюда в заказ
            fetch('/add-to-cart', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    // Добавьте любые другие заголовки, если необходимо
                },
                body: JSON.stringify({ dishId: dishId })
            })
                .then(function(response) {
                    if (!response.ok) {
                        if (response.status === 401) {
                            window.location.href = '/login';
                        } else {
                            throw new Error('Ошибка при добавлении в заказ');
                        }
                    }
                    return response.json();
                })
                .then(function(data) {
                    // Обработать успешный ответ, если нужно
                    console.log('Блюдо добавлено в заказ:', data);
                    var quantityElement = document.querySelector(`#quantity-${dishId}`);
                    if (quantityElement) {
                        quantityElement.innerText = 'В заказе: ' + data.quantity;
                    }
                })
                .catch(function(error) {
                    if (error instanceof TypeError && error.message === 'Failed to fetch') {
                        console.error('Ошибка при отправке запроса. Убедитесь, что сервер доступен.');
                    } else {
                        console.error('Ошибка при добавлении в заказ:', error);
                    }
                });
        });
    });
});
