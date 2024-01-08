const chatContainer = document.querySelector('.chat-container');
const chatInput = document.getElementById('chat-input');
const sendBtn = document.getElementById('send-btn');

const createChatElement = (html, type) => {
    const chatElement = document.createElement('div');
    chatElement.classList.add('chat-item', type);
    chatElement.innerHTML = html;
    return chatElement;
};

const makeAjaxRequest = async (url, method, data) => {
    try {
        const response = await fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        });

        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        return await response.json();
    } catch (error) {
        console.error('Error fetching response:', error);
        throw error;
    }
};

const displayBotResponse = async (userInput) => {
    const userChatDiv = createChatElement(`<div class="user-chat">${userInput}</div>`, 'outgoing');
    chatContainer.appendChild(userChatDiv);

    chatInput.value = ''; // Clear the input field

    try {
        const data = await makeAjaxRequest('../script/chatreply.php', 'POST', { msg: userInput });
        const responseText = data.response || "Default response from the server";

        const botChatDiv = createChatElement(`<div class="bot-chat">${responseText}</div>`, 'incoming');
        chatContainer.appendChild(botChatDiv);

        chatContainer.scrollTo(0, chatContainer.scrollHeight);
    } catch (error) {
        console.error('Error processing user input:', error);
    }
};

sendBtn.addEventListener('click', () => {
    const userInputValue = chatInput.value.trim();

    if (userInputValue !== '') {
        displayBotResponse(userInputValue);
    }
});
