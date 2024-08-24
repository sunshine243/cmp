function generateResponse() {
    var text = document.getElementById("text");
    var chatbox = document.getElementById("chatbox");
    var loading = document.getElementById("loading");

    chatbox.innerHTML += `<div class="message user-message">You: ${text.value}</div>`;
    chatbox.scrollTop = chatbox.scrollHeight;

    // Show the "Bot is typing..." message inside the chatbox
    loading.style.display = 'block';

    fetch("response.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ text: text.value })
    })
    .then(res => res.text())
    .then(res => {
        // Hide the "Bot is typing..." message
        loading.style.display = 'none';

        chatbox.innerHTML += `<div class="message bot-message">Bot: ${res}</div>`;
        chatbox.scrollTop = chatbox.scrollHeight;
        text.value = "";
    })
    .catch(() => {
        // Hide the "Bot is typing..." message
        loading.style.display = 'none';
        chatbox.innerHTML += `<div class="message bot-message">Bot: Sorry, something went wrong.</div>`;
        chatbox.scrollTop = chatbox.scrollHeight;
    });
}

function checkEnter(event) {
    if (event.key === 'Enter') {
        event.preventDefault(); // Prevent default form submission
        generateResponse();
    }
}
