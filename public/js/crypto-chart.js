document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('cryptoChart').getContext('2d');

    let cryptoChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Prix en USDT',
                data: [],
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    labels: {
                        color: '#eaecef'
                    }
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Temps',
                        color: '#eaecef'
                    },
                    ticks: {
                        color: '#eaecef'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Prix (USDT)',
                        color: '#eaecef'
                    },
                    ticks: {
                        color: '#eaecef'
                    }
                }
            }
        }
    });

    function updateChart(newPrice) {
        const now = new Date();
        const timeLabel = now.getHours() + ':' + now.getMinutes() + ':' + now.getSeconds();

        cryptoChart.data.labels.push(timeLabel);
        cryptoChart.data.datasets[0].data.push(newPrice);

        if (cryptoChart.data.labels.length > 15) {
            cryptoChart.data.labels.shift();
            cryptoChart.data.datasets[0].data.shift();
        }

        cryptoChart.update();
    }

    function fetchInitialPrice() {
        const selectedCryptoId = $('#buy-crypto-select').val();
        if (!selectedCryptoId) return;

        $.ajax({
            url: 'index.php?route=live-prices',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                const selectedCrypto = data.find(crypto => crypto.id == selectedCryptoId);
                if (selectedCrypto) {
                    updateChart(selectedCrypto.current_price);
                }
            }
        });
    }

    setInterval(fetchInitialPrice, 5000);

    $('#buy-crypto-select, #sell-crypto-select').on('change', function() {
        cryptoChart.data.labels = [];
        cryptoChart.data.datasets[0].data = [];
        cryptoChart.update();

        fetchInitialPrice();
    });
});
