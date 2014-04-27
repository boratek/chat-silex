//funkcja odpowiedzialna za wyświetlanie wiadomości wprowadzonych do bazy danych
$(document).ready(function() {

    if ($('#chat').size()) {
        $('#send').click(function() { //aktywacja, kiedy przycisk "send" został kliknięty
            var mess = $('#mess').val(); //ustawienie zmiennej mess przechowującej zawartość pola o id="mess"
            if ($.trim(mess) == '') {
                return ;
            }
            $.ajax({
                url: '/index.php/chat/post',
                type: 'post',
                data: {message: mess},
                success: function() {
                    $('#chat').attr('src', '/index.php/chat/listing?' + Math.round(Math.random()*1000)); 
                    //nadawanie indywidualnego adresu odświeżanemu fragmentowi strony czatu
                    $('#mess').val('');
                }
            });

        });
        window.setInterval(function() { //ustawienie interwału czasowego, po którym strona ma przeładować zawartość okna czatu
            $('#chat').attr('src', '/index.php/chat/listing?' + Math.round(Math.random()*1000));
        }, 5000);
    }

});