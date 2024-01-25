document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.cuisine-link').forEach(function(link) {
        link.addEventListener('click', function() {
            var cuisine = link.dataset.cuisine;

            // Отправить fetch-запрос для загрузки ресторанов
            fetch('/load-restaurants?cuisine=' + cuisine, {
                method: 'GET',
            })
                .then(function(response) {
                    if (!response.ok) {
                        throw new Error('Error loading restaurants');
                    }
                    return response.json();
                })
                .then(function(data) {
                    var html = '';

                    for (var i = 0; i < data.length; i++) {
                        html += '<div class="col-md-4">';
                        html += '<div class="card">';
                        html += '<a href="' + data[i].url + '">';
                        html += '<img src="' + data[i].imagePath + '" class="card-img-top" alt="' + data[i].name + '">';
                        html += '<div class="card-body">';
                        html += '<h5 class="card-title">' + data[i].name + '</h5>';
                        html += '<p class="card-text">' + data[i].description + '</p>';
                        html += '<p class="card-text">Rating: ' + data[i].rating + '</p>';
                        html += '</div>';
                        html += '</a>';
                        html += '</div>';
                        html += '</div>';
                    }

                    // document.querySelector('.row').innerHTML = '';
                    // document.querySelector('.row').innerHTML = html;

                    // // Очистить .row перед добавлением нового контента

                    document.querySelector('#restaurants-container').innerHTML = html;
                    //
                    // // Показать .row после обновления контента
                    // document.querySelector('.row').style.display = 'block';

                })
                .catch(function(error) {
                    console.error('Error loading restaurants:', error);
                });
        });
    });
});
