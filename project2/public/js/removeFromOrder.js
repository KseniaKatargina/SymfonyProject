document.addEventListener('DOMContentLoaded', function () {
    // Обработчик события клика по кнопке "Удалить из заказа"
    document.querySelectorAll('.remove-from-order').forEach(function (button) {
        button.addEventListener('click', function () {
            // Получаем id элемента заказа
            var orderItemId = button.dataset.orderItemId;

            // Отправляем запрос на сервер для удаления элемента из заказа
            fetch('/remove-from-order', {
                method: 'POST', // Используйте POST для изменения данных на сервере
                headers: {
                    'Content-Type': 'application/json', // Указываем тип контента в заголовке
                },
                body: JSON.stringify({ orderItemId: orderItemId }), // Преобразуем данные в формат JSON
            })
                .then(function (response) {
                    if (!response.ok) {
                        throw new Error('Ошибка при удалении из заказа');
                    }
                    return response.json();
                })
                .then(function (data) {
                    // Обновляем интерфейс после успешного удаления
                    document.getElementById("quantity-" + orderItemId).closest('.item').remove();
                    document.querySelector(".totalPrice").innerText = "Итого: " + data.totalPrice + " рублей";
                })
                .catch(function (error) {
                    console.error('Ошибка при удалении из заказа:', error);
                });
        });
    });
});
