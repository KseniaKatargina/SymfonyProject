$(document).ready(function() {
    $(".update-quantity").click(function() {
        var orderItemId = $(this).data("order-item-id");
        var action = $(this).data("action");

        // Отправить AJAX-запрос для обновления количества
        $.ajax({
            url: '/update-quantity', // Замените на реальный путь для обновления количества
            method: 'POST',
            data: { orderItemId: orderItemId, action: action },
            success: function(response) {
                // Обновить отображение количества
                $("#quantity-" + orderItemId).text(response.quantity);
                $(".totalPrice").text("Итого: " + response.totalPrice + " рублей");
            },
            error: function(error) {
                console.error('Ошибка при обновлении количества:', error);
            }
        });
    });
});