async function fetchData(action, user_id) {
    const response = await fetch('api.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            action: action,
            user_id: user_id
        }),
    });

    const data = await response.json();
    if (data.success) {
        return data.data;
    } else {
        throw new Error(data.message);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const chatbotFrame = document.querySelector('iframe');
    chatbotFrame.onload = async () => {
        const user_id = 5; // Ganti dengan logika untuk mendapatkan user_id yang sesuai
        try {
            const userData = await fetchData('getUserData', user_id);
            chatbotFrame.contentWindow.postMessage({ type: 'user_data', data: userData }, '*');
            
            const records = await fetchData('getDailyRecords', user_id);
            chatbotFrame.contentWindow.postMessage({ type: 'daily_records', data: records }, '*');
        } catch (error) {
            console.error('Error fetching data:', error.message);
        }
    };
});

window.addEventListener('message', (event) => {
    if (event.origin !== 'http://localhost' && event.origin !== 'http://127.0.0.1') { // Ganti dengan localhost
        return;
    }

    const message = event.data;
    if (message.type === 'user_data') {
        const userData = message.data;
        // Tampilkan pesan sapaan dengan nama pengguna
        bot.reply(`Hello, ${userData.nama}! How can I assist you today?`);
    } else if (message.type === 'daily_records') {
        const records = message.data;
        // Tampilkan data records kepada pengguna
        bot.reply(`Here are your daily records for the past 7 days: ${JSON.stringify(records)}`);
    }
});
