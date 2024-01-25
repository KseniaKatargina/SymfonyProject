
    // Ваш остальной код здесь

    function updateOrderStatus(orderId, status) {
        fetch(`/update-order-status/${orderId}/${status}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Ошибка при обновлении статуса заказа');
                }
                return response.json();
            })
            .then(data => {
                // Обновить интерфейс после успешного обновления
                console.log('Статус заказа обновлен:', data);

                // Скрыть соответствующую кнопку
                const deliveredButton = document.querySelector(`.status-${orderId}.btn-success`);
                const cancelledButton = document.querySelector(`.status-${orderId}.btn-danger`);
                if (deliveredButton) deliveredButton.classList.add('hide-button');
                if (cancelledButton) cancelledButton.classList.add('hide-button');

                // Найти элемент, отображающий статус заказа, и обновить его текст
                const statusElement = document.querySelector(`.statusT-${orderId}`);
                    statusElement.textContent = data.status;
            })
            .catch(error => {
                console.error('Ошибка при обновлении статуса заказа:', error);
            });
}