function updatePrices() {
    $.ajax({
        url: 'index.php?route=live-prices',
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            const prices = {};
            data.forEach(function (crypto) {
                const newPrice = crypto.current_price.toFixed(2);
                prices[crypto.id] = parseFloat(newPrice);

                const priceElement = document.getElementById('crypto-price-' + crypto.id);
                if (priceElement) {
                    const oldPrice = parseFloat(priceElement.textContent.replace('$', ''));
                    if (!isNaN(oldPrice) && oldPrice !== parseFloat(newPrice)) {
                        animatePriceChange(priceElement, oldPrice, parseFloat(newPrice));
                    }
                    priceElement.textContent = '$' + newPrice;
                }
            });

            updateSelectedPrice(prices, 'buy-crypto-select', 'buy-crypto-price');
            updateSelectedPrice(prices, 'sell-crypto-select', 'sell-crypto-price');
            updateSelectedPrice(prices, 'alert-crypto-select', 'alert-crypto-price');
            updateSelectedPrice(prices, 'stop-loss-crypto-select', 'stop-loss-current-price');

            checkLocalAlerts(prices);
        },
        error: function () {
            console.error('Erreur lors de la récupération des prix.');
        }
    });
}


function updateSelectedPrice(prices, selectId, priceElementId) {
    const select = document.getElementById(selectId);
    if (select) {
        const selectedId = select.value;
        const priceElement = document.getElementById(priceElementId);
        if (prices[selectedId] !== undefined && priceElement) {
            const oldPrice = parseFloat(priceElement.textContent.replace('$', '').replace('Prix non disponible', '0'));
            const newPrice = prices[selectedId];

            if (!isNaN(oldPrice) && oldPrice !== newPrice) {
                animatePriceChange(priceElement, oldPrice, newPrice);
            }

            priceElement.textContent = '$' + newPrice.toFixed(2);
        }
    }
}

function updateStopLossCurrentPrice(prices) {
    const select = document.getElementById('stop-loss-crypto-select');
    const priceElement = document.getElementById('stop-loss-current-price');

    if (select && priceElement) {
        const selectedId = select.value;
        if (prices[selectedId] !== undefined) {
            const newPrice = prices[selectedId];
            const oldPriceText = priceElement.textContent.replace('Prix actuel : $', '');
            const oldPrice = parseFloat(oldPriceText);

            if (!isNaN(oldPrice) && oldPrice !== newPrice) {
                animatePriceChange(priceElement, oldPrice, newPrice);
            }

            priceElement.textContent = 'Prix actuel : $' + newPrice.toFixed(2);
        } else {
            priceElement.textContent = 'Sélectionnez une crypto';
        }
    }
}

function animatePriceChange(element, oldPrice, newPrice) {
    if (oldPrice < newPrice) {
        element.style.setProperty('color', 'green', 'important');
    } else if (oldPrice > newPrice) {
        element.style.setProperty('color', 'red', 'important');
    }

    element.style.transition = 'color 0.5s ease';

    setTimeout(() => {
        element.style.removeProperty('color');
    }, 1000);
}

setInterval(updatePrices, 2000);
updatePrices();

$(document).ready(function () {
    $('#buy-crypto-select, #sell-crypto-select, #stop-loss-crypto-select').on('change', updatePrices);
});


function loadLocalAlerts() {
    const alerts = localStorage.getItem('userAlerts');
    return alerts ? JSON.parse(alerts) : [];
}

function saveLocalAlerts(alerts) {
    localStorage.setItem('userAlerts', JSON.stringify(alerts));
}

function fetchActiveAlerts() {
    $.ajax({
        url: 'index.php?route=fetch-active-alerts',
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            saveLocalAlerts(data);
        }
    });
}

function checkLocalAlerts(prices) {
    const alerts = loadLocalAlerts();
    const alertContainer = $('#ajax-alerts-container');

    alerts.forEach(function (alert) {
        const cryptoPrice = prices[alert.crypto_id];
        if (!cryptoPrice) return;

        const target = parseFloat(alert.target_price);
        let triggered = false;

        if (alert.action === 'buy' && cryptoPrice <= target) triggered = true;
        if (alert.action === 'sell' && cryptoPrice >= target) triggered = true;

        if (triggered) {
            alertContainer.append(`<div class="alert alert-danger">🔔 ${alert.action.toUpperCase()} alert for ${alert.symbol} reached $${target} (Now: $${cryptoPrice})</div>`);

            const updatedAlerts = alerts.filter(a => a.id !== alert.id);
            saveLocalAlerts(updatedAlerts);

            $.post('index.php?route=mark-alert-triggered', { alert_id: alert.id });
        }
    });
}

function updateTotalPortfolio() {
    $.ajax({
        url: 'index.php?route=get-portfolio-total',
        method: 'GET',
        dataType: 'json',
        success: function (response) {
            if (response.total !== undefined) {
                const totalElement = $('#total-portfolio-usd');
                const oldTotal = parseFloat(totalElement.text().replace('$', '')) || 0;
                const newTotal = response.total;

                if (oldTotal < newTotal) {
                    totalElement.css('color', 'green');
                } else if (oldTotal > newTotal) {
                    totalElement.css('color', 'red');
                }

                totalElement.text('$' + newTotal.toFixed(2));

                setTimeout(() => {
                    totalElement.css('color', '');
                }, 1000);
            }
        },
        error: function () {
            console.error('Erreur lors de la récupération du total du portefeuille.');
        }
    });
}

function checkStopLosses() {
    $.ajax({
        url: 'index.php?route=check-stop-losses',
        method: 'GET',
        dataType: 'json',
        success: function (response) {
            if (response.notifications && response.notifications.length > 0) {
                response.notifications.forEach(function (notification) {
                    $('#ajax-alerts-container').append('<div class="alert alert-danger">' + notification + '</div>');
                });
            }
        },
        error: function () {
            console.error('Erreur lors de la vérification des stop-loss.');
        }
    });
}

setInterval(checkStopLosses, 2000);
checkStopLosses();

setInterval(updateTotalPortfolio, 2000);
updateTotalPortfolio();

setInterval(fetchActiveAlerts, 10000);
fetchActiveAlerts();
