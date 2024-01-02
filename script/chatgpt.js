const chatInput = document.querySelector("#chat-input");
const sendButton = document.querySelector("#send-btn");
const chatContainer = document.querySelector(".chat-container");
const themeButton = document.querySelector("#theme-btn");
const deleteButton = document.querySelector("#delete-btn");

const loadDataFromLocalstorage = () => {
    const themeColor = localStorage.getItem("themeColor");
    document.body.classList.toggle("light-mode", themeColor === "light_mode");
    themeButton.innerText = document.body.classList.contains("light-mode") ? "dark_mode" : "light_mode";

    const defaultText = `<div class="default-text">
    <img src="../images/background/chat.png" alt="chatbot-img">
        <h1>Chatbot</h1>
        <p>Hello how can I help you today?</p>
        <p>If you have any questions or issues, let me know! You can start a chat, or you can click the button below!</p>
        <div class="button">
            <div class="button1">
                <button type="button" onclick="handleButtonClick(1)">
                    <div class="text">
                        Issue in Card Payment Support Unit
                    </div>
                </button>
                <button type="button" onclick="handleButtonClick(2)">
                    <div class="text">
                        Issue in ATM Network Support Unit
                    </div>
                </button>
            </div>
            <div class="button2">
                <button type="button" onclick="handleButtonClick(3)">
                    <div class="text">
                        Issue in Digital Branch Support Unit
                    </div>
                </button>
                <button type="button" onclick="handleButtonClick(4)">
                    <div class="text">
                        Issue in Terminal Management Unit
                    </div>
                </button>
            </div>
        </div>
    </div>`;

    chatContainer.innerHTML = localStorage.getItem("all-chats") || defaultText;
    chatContainer.scrollTo(0, chatContainer.scrollHeight);

    const questionsShown = localStorage.getItem("questionsShown");

    if (!questionsShown) {
        displayQuestions();
    }
};

const displayQuestions = (buttonNumber) => {
    const questionContainer = document.createElement("div");
    questionContainer.classList.add("question-container");
    let questions;

    switch (buttonNumber) {
        case 1:
            questions = ["Card Info", "Response Code", "Issue in Card Payment Support Unit", "contact to team Card"];
            // questions = ["Issue in Card Payment Support Unit", "response code", "contact to team Card"];
            break;
        case 2:
            questions = ["Issue in ATM Network Support Unit", "contact to team ATM Network"];
            break;
        case 3:
            questions = ["Issue in Digital Branch Support Unit", "contact to team Digital Branch"];
            break;
        case 4:
            questions = ["Issue in Terminal Management Unit", "contact to team Terminal Management"];
            break;
        default:
            questions = [];
    }

    questions.forEach((question, index) => {
        const questionElement = document.createElement("button");
        questionElement.textContent = question;
        questionElement.addEventListener("click", () => handleQuestionSelection(question));
        questionContainer.appendChild(questionElement);
    });

    chatContainer.appendChild(questionContainer);
};
// Add cases for new button numbers
const handleButtonClick = (buttonNumber) => {
    let buttonText;
    switch (buttonNumber) {
        case 1:
            buttonText = "Issue in Card Payment Support Unit";
            break;
        case 2:
            buttonText = "Issue in ATM Network Support Unit";
            break;
        case 3:
            buttonText = "Issue in Digital Branch Support Unit";
            break;
        case 4:
            buttonText = "Issue in Terminal Management Unit";
            break;
        default:
            buttonText = "";
    }

    const outgoingChatDiv = createChatElement(`<div class="chat-content">
        <div class="chat-details">
        <img src="../images/logo/user.png" alt="user-img">
            <p>${buttonText}</p>
        </div>
    </div>`, "outgoing");
    chatContainer.querySelector(".default-text")?.remove();
    chatContainer.appendChild(outgoingChatDiv);
    chatContainer.scrollTo(0, chatContainer.scrollHeight);

    displayQuestions(buttonNumber);
};

// Add cases for new questions in handleQuestionSelection
const handleQuestionSelection = async (selectedQuestion) => {
    const outgoingChatDiv = createChatElement(`<div class="chat-content">
        <div class="chat-details">
        <img src="../images/logo/user.png" alt="user-img">
            <p>${selectedQuestion}</p>
        </div>
    </div>`, "outgoing");
    chatContainer.querySelector(".default-text")?.remove();
    chatContainer.appendChild(outgoingChatDiv);
    chatContainer.scrollTo(0, chatContainer.scrollHeight);

    switch (selectedQuestion) {
        case "Card Info":
            displayCardInfoButtons();
            break;
        case "Response Code":
            displayCardResponseButtons();
            break;
        case "Issue in Card Payment Support Unit":
            displayIssueButtons();
            break;
        case "Issue in ATM Network Support Unit":
            // Add logic for ATM Network Support Unit
            displayATMIssueButtons();
            break;
        case "Issue in Digital Branch Support Unit":
            // Add logic for Digital Branch Support Unit
            displayDigitalBranchIssueButtons();
            break;
        case "Issue in Terminal Management Unit":
            // Add logic for Digital Terminal Management Unit
            displayTerminalIssueButtons();
            break;
        case "Issue in Card Payment Support Unit":
            displayIssueButtons();
            break;
        // case "response code":
        //     displayResponseCodeButtons();
        //     break;
        case "contact to team Card":
            displayContactCardLink();
            break;
        case "contact to team Digital Branch":
            displayContactDigitalLink();
            break;
        case "contact to team ATM Network":
            displayContactATMLink();
            break;
        case "contact to team Terminal Management":
            displayContactTerminalLink();
            break;
        case "Error CVV2":
            handleCVV2Error();
            break;
        case "error conection":
            handleErrorConnection();
            break;
        case "Terminal Issue 1":
            handleErrorTerminal1();
            break;
        case "Terminal Issue 2":
            handleErrorTerminal2();
            break;
        case "ATM Issue 1":
            handleErrorATM1();
            break;
        case "ATM Issue 2":
            handleErrorATM2();
            break;
        case "Digital Issue 1":
            handleErrorDigital1();
            break;
        case "Digital Issue 2":
            handleErrorDigital2();
            break;
        // Card Info
        case "1. Visa CashCard Gold":
            handleInfoCard1();
            break;
        case "2. Visa CashCard Blue":
            handleInfoCard2();
            break;
        case "3. Visa CashCard Silver":
            handleInfoCard3();
            break;
        case "4. Virtual Visa CashCard":
            handleInfoCard4();
            break;
        case "5. FTB ATM Debit Card":
            handleInfoCard5();
            break;
        case "6. FTB VIP Visa Debit":
            handleInfoCard6();
            break;
        case "7. Visa Debit Classic":
            handleInfoCard7();
            break;
        default:
            displayDefaultResponse();

    }
};

// Add functions for new cases
const displayATMIssueButtons = () => {
    displayButtons(["ATM Issue 1", "ATM Issue 2"]);
};
const displayDigitalBranchIssueButtons = () => {
    displayButtons(["Digital Issue 1", "Digital Issue 2"]);
};
const displayTerminalIssueButtons = () => {
    displayButtons(["Terminal Issue 1", "Terminal Issue 2"]);
};
// bot reply when select issue 
const displayIssueButtons = () => {
    displayButtons(["Error CVV2", "error conection"]);
};

const displayCardInfoButtons = () => {
    displayButtons(["1. Visa CashCard Gold", "2. Visa CashCard Blue", "3. Visa CashCard Silver", "4. Virtual Visa CashCard", "5. FTB ATM Debit Card", "6. FTB VIP Visa Debit", "7. Visa Debit Classic"]);
};




// response code 
const displayCardResponseButtons = () => {
    const incomingChatDiv = createChatElement(`<div class="chat-content">
        <div class="chat-details">
            <img src="../images/background/chat.png" alt="chatbot-img">
        </div>
    </div>`, "incoming");

    chatContainer.appendChild(incomingChatDiv);
    chatContainer.scrollTo(0, chatContainer.scrollHeight);

    // Display input field and submit button
    const inputDiv = document.createElement("div");
    inputDiv.innerHTML = `
        <input class="input_text" type="number" id="userInput" placeholder="Enter a number">
        <button class="submit" onclick="submitUserInput()">Submit</button>
    `;
    incomingChatDiv.querySelector(".chat-details").appendChild(inputDiv);
    chatContainer.scrollTo(0, chatContainer.scrollHeight);
};

// Function to handle user input submission
const submitUserInput = () => {
    const userInputValue = document.getElementById("userInput").value;
    const responseText = generateResponseText(userInputValue);

    const responseDiv = document.createElement("div");
    responseDiv.innerHTML = `<p></p>`;
    chatContainer.appendChild(responseDiv);
    chatContainer.scrollTo(0, chatContainer.scrollHeight);

    let i = 0;

    const interval = setInterval(() => {
        if (i <= responseText.length) {
            responseDiv.querySelector("p").innerHTML = responseText.slice(0, i);
            i++;
        } else {
            clearInterval(interval);
            // Optionally, you can remove the typing-animation or input elements here
        }
        chatContainer.scrollTo(0, chatContainer.scrollHeight);
    }, 10);
};

// Function to generate a response based on user input
// const generateResponseText = (userInput) => {
//     const incomingChatDiv = createChatElement(`<div class="chat-content">
//         <div class="chat-details">
//             <img src="../images/background/chat.png" alt="chatbot-img">
//         </div>
//     </div>`, "incoming");

//     const responseText = `You entered: ${userInput}. Thank you for your input!`;

//     // Display the bot's response
//     const responseDiv = document.createElement("div");
//     responseDiv.innerHTML = `<p></p>`;
//     incomingChatDiv.querySelector(".chat-details").appendChild(responseDiv);
//     chatContainer.appendChild(incomingChatDiv);
//     chatContainer.scrollTo(0, chatContainer.scrollHeight);

//     let i = 0;

//     const interval = setInterval(() => {
//         if (i <= responseText.length) {
//             responseDiv.querySelector("p").innerHTML = responseText.slice(0, i);
//             i++;
//         } else {
//             clearInterval(interval);
//         }
//         chatContainer.scrollTo(0, chatContainer.scrollHeight);
//     }, 50);
// };
const generateResponseText = (userInput) => {
    const incomingChatDiv = createChatElement(`<div class="chat-content">
        <div class="chat-details">
            <img src="../images/background/chat.png" alt="chatbot-img">
        </div>
    </div>`, "incoming");

    const responseDiv = document.createElement("div");
    responseDiv.innerHTML = `<p></p>`;
    incomingChatDiv.querySelector(".chat-details").appendChild(responseDiv);
    chatContainer.appendChild(incomingChatDiv);
    chatContainer.scrollTo(0, chatContainer.scrollHeight);

    // Simulate loading response from the server
    makeAjaxRequest('../script/chatreply.php', 'POST', { msg: userInput })
        .then(data => {
            const responseText = data || "Default response from the server";

            let i = 0;
            const interval = setInterval(() => {
                if (i <= responseText.length) {
                    responseDiv.querySelector("p").innerHTML = responseText.slice(0, i);
                    i++;
                } else {
                    clearInterval(interval);
                }
                chatContainer.scrollTo(0, chatContainer.scrollHeight);
            }, 10);
        })
        .catch(error => {
            console.error('Error fetching response:', error);
        });
};

// Function to make AJAX request (using Fetch API for illustration)
const makeAjaxRequest = (url, method, data) => {
    return fetch(url, {
        method,
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: Object.keys(data).map(key => encodeURIComponent(key) + '=' + encodeURIComponent(data[key])).join('&'),
    })
        .then(response => response.text())
        .catch(error => Promise.reject(error));
};

// end response code 

// link to 
const displayContactCardLink = () => {
    const incomingChatDiv = createChatElement(`<div class="chat-content">
        <div class="chat-details">
        <img src="../images/background/chat.png" alt="chatbot-img">
        </div>
    </div>`, "incoming");

    chatContainer.appendChild(incomingChatDiv);
    chatContainer.scrollTo(0, chatContainer.scrollHeight);

    // Simulate typing effect
    const responseDiv = document.createElement("div");
    responseDiv.innerHTML = `<p></p>`;
    incomingChatDiv.querySelector(".chat-details").appendChild(responseDiv);
    chatContainer.scrollTo(0, chatContainer.scrollHeight);

    const responseText = "Click <a href='https://t.me/+VBs6umCCQvAyNjBl' target='_blank'>here</a> to contact us on Telegram.";
    let i = 0;

    const interval = setInterval(() => {
        if (i <= responseText.length) {
            responseDiv.querySelector("p").innerHTML = responseText.slice(0, i);
            i++;
        } else {
            clearInterval(interval);
            incomingChatDiv.querySelector(".typing-animation")?.remove();
        }
        chatContainer.scrollTo(0, chatContainer.scrollHeight);
    }, 10); // Adjust the duration as needed
};

// link to C
const displayContactDigitalLink = () => {
    const incomingChatDiv = createChatElement(`<div class="chat-content">
        <div class="chat-details">
            <img src="../images/background/chat.png" alt="chatbot-img">
        </div>
    </div>`, "incoming");

    chatContainer.appendChild(incomingChatDiv);
    chatContainer.scrollTo(0, chatContainer.scrollHeight);

    // Simulate typing effect
    const responseDiv = document.createElement("div");
    responseDiv.innerHTML = `<p></p>`;
    incomingChatDiv.querySelector(".chat-details").appendChild(responseDiv);
    chatContainer.scrollTo(0, chatContainer.scrollHeight);

    const responseText = "Click <a href='https://t.me/+VBs6umCCQvAyNjBl' target='_blank'>here</a> to contact us on Telegram.";
    let i = 0;

    const interval = setInterval(() => {
        if (i <= responseText.length) {
            responseDiv.querySelector("p").innerHTML = responseText.slice(0, i);
            i++;
        } else {
            clearInterval(interval);
            incomingChatDiv.querySelector(".typing-animation")?.remove();
        }
        chatContainer.scrollTo(0, chatContainer.scrollHeight);
    }, 10);
};
// link to 
const displayContactATMLink = () => {
    const incomingChatDiv = createChatElement(`<div class="chat-content">
        <div class="chat-details">
            <img src="../images/background/chat.png" alt="chatbot-img">
        </div>
    </div>`, "incoming");

    chatContainer.appendChild(incomingChatDiv);
    chatContainer.scrollTo(0, chatContainer.scrollHeight);

    // Simulate typing effect
    const responseDiv = document.createElement("div");
    responseDiv.innerHTML = `<p></p>`;
    incomingChatDiv.querySelector(".chat-details").appendChild(responseDiv);
    chatContainer.scrollTo(0, chatContainer.scrollHeight);

    const responseText = "Click <a href='https://t.me/+VBs6umCCQvAyNjBl' target='_blank'>here</a> to contact us on Telegram.";
    let i = 0;

    const interval = setInterval(() => {
        if (i <= responseText.length) {
            responseDiv.querySelector("p").innerHTML = responseText.slice(0, i);
            i++;
        } else {
            clearInterval(interval);
            incomingChatDiv.querySelector(".typing-animation")?.remove();
        }
        chatContainer.scrollTo(0, chatContainer.scrollHeight);
    }, 10);
};
// link to 
const displayContactTerminalLink = () => {
    const incomingChatDiv = createChatElement(`<div class="chat-content">
        <div class="chat-details">
            <img src="../images/background/chat.png" alt="chatbot-img">
        </div>
    </div>`, "incoming");

    chatContainer.appendChild(incomingChatDiv);
    chatContainer.scrollTo(0, chatContainer.scrollHeight);

    // Simulate typing effect
    const responseDiv = document.createElement("div");
    responseDiv.innerHTML = `<p></p>`;
    incomingChatDiv.querySelector(".chat-details").appendChild(responseDiv);
    chatContainer.scrollTo(0, chatContainer.scrollHeight);

    const responseText = "Click <a href='https://t.me/+VBs6umCCQvAyNjBl' target='_blank'>here</a> to contact us on Telegram.";
    let i = 0;

    const interval = setInterval(() => {
        if (i <= responseText.length) {
            responseDiv.querySelector("p").innerHTML = responseText.slice(0, i);
            i++;
        } else {
            clearInterval(interval);
            incomingChatDiv.querySelector(".typing-animation")?.remove();
        }
        chatContainer.scrollTo(0, chatContainer.scrollHeight);
    }, 10);
};


// chatbot reply
const handleCVV2Error = () => {
    displayBotResponse("error cvv2 because customer input wrong cvv");
};

const handleErrorConnection = () => {
    displayBotResponse("error conection because network error");
};

const handleErrorATM1 = () => {
    displayBotResponse("Answer ATM1");
};

const handleErrorATM2 = () => {
    displayBotResponse("Answer ATM2");
};
const handleErrorDigital1 = () => {
    displayBotResponse("Answer digital1");
};

const handleErrorDigital2 = () => {
    displayBotResponse("Answer digital2");
};
const handleErrorTerminal1 = () => {
    displayBotResponse("Answer terminal1");
};

const handleErrorTerminal2 = () => {
    displayBotResponse("Answer terminal2");
};
const displayDefaultResponse = () => {
    displayBotResponse("Sorry, I couldn't understand that. Please try again.");
};

// handle card info
const handleInfoCard1 = () => {
    displayBotResponse("Feature \n1. Storage amount is up to USD25,000.00 \n2. 5 years of card validity\n3. USD currency only\n4. Able to register with FTB Mobile App\n5. Secure payment with ACS 3Ds\n6. No plastic card fee\n7. No annual fee \n\nLegibility \n1. Legal age (18 years or more above)\n2. Have identity card, passport, family book, or any valid documents;\n3. Have a permanent address in Cambodia\n4. Have a mobile number and e-mail\n5. Have proper work and sufficient income.");
};

const handleInfoCard2 = () => {
    displayBotResponse("Feature\n1. Storage amount is up to USD5,000.00\n2. 2 years of card validity\n3. USD currency only\n4. Able to register with FTB Mobile App\n5. Secure payment with ACS 3Ds\n6. Peace of mind with smart chip security\n7. USD5.00 plastic card fee\n8. No annual fee\n\nRequirement\nLegal age (18 years or above);\n1. Have identity card, passport, family book, or any valid documents;\n2. Have a permanent address in Cambodia\n3. Have a mobile number and e-mail\n4. Have proper work and sufficient income");
};
const handleInfoCard3 = () => {
    displayBotResponse("Feature\n1. Perfect for gift\n2. Storage amount is up to USD500.00\n3. 2 years of card validity\n4. USD currency only\n5. Able to register with FTB Mobile App\n6. Secure payment with ACS 3Ds\n7. Peace of mind with smart chip security\n8. USD3.00 plastic card fee\n9. No annual fee\n\nRequirement\n1. Have identity card, passport, family book, or any valid documents;\n2. Have a mobile number and e - mail; ");
};

const handleInfoCard4 = () => {
    displayBotResponse("Feature\n1. Card can be requested immediately in FTB Mobile App\n2. Storage amount is up to USD300.00\n3. 2 years of card validity\n4. USD currency only\n5. Able to register with FTB Mobile App\n6. Secure payment with ACS 3Ds\n7. Peace of mind with smart chip security\n8. Be able to top up from Debit Account or CashCard in FTB Mobile App\n9. No annual fee\n\nRequirement\n1. Must be a Mobile App User   \n\nHOW TO CREATE VIRTUAL VISA CASHCARD\n1. Log into FTB Mobile App\n2. Tap on MENU button and tap CARDS button\n3. Select virtual card and then tap on add(+) button\n4. Select Continue button and enter your Transaction PIN\n5. Your new VISA Virtual Card is Ready, please topup card balance and enjoy your payment");
};
const handleInfoCard5 = () => {
    displayBotResponse("Feature\n1. Linked to FTB Bank account\n2. 5 years of card validity\n3. USD and KHR currencies\n4. Able to register with FTB Mobile App\n5. Peace of mind with smart chip security\n6. No plastic card fee\n7. No annual fee\n\nBenefits\n1. Making withdrawals with FTB ATM and other bank’s ATM where Visa is accepted.\n2. Paying for goods and services safely and conveniently through the FTB POS terminal.\n3. Making funds transfers to own accounts, other accounts within FTB Bank and FTB Visa CashCard.\n4. Peace of mind with smart chip security\n5. Saving cost and time.\n\nRequirement\n1. Having an account with FTB;\n2. Have identity card, passport, family book, or any valid documents;\n3. Have a permanent address in CambodiaHave a mobile number and e - mail\n4. Have a proper work, salary or any sufficient income, and a good reputation");
};

const handleInfoCard6 = () => {
    displayBotResponse("Feature\n1. 5 years of card validity\n2. USD and KHR currenciesAble to register with FTB Mobile App\n3. Secure payment with ACS 3Ds\n4. Peace of mind with smart chip security\n5. No plastic card fee\n6. No annual fee\n\nBenefits\n1. Making withdrawals with FTB ATM and other bank’s ATM where Visa is accepted.\n2. Paying for goods and services safely and conveniently through the FTB POS terminal.\n3. Making funds transfers to own accounts, other accounts within FTB Bank and FTB Visa CashCard.\n3. Peace of mind with smart chip security\n4.Saving cost and time.\n5. Free access to Airport Lounge – Plaza Premium Lounge\n\nRequirement\n1.Own a VIP Account with FTB, or\n2.Own a Gold Account with FTB");
};
const handleInfoCard7 = () => {
    displayBotResponse("Feature\n1. Linked to FTB Bank account\n2. 5 years of card validity\n3. USD and KHR currencies\n4. Able to register with FTB Mobile App\n5. Secure payment with ACS 3Ds\n6. Peace of mind with smart chip security\n7. No plastic card fee\n8. USD5.00 annual fee\n\nBenefits\n1. Making withdrawals with FTB ATM and other bank’s ATM where Visa is accepted.\n2. Worldwide purchase at both POS terminals and eCommerce merchants.\n3. Making funds transfers to own accounts, other accounts within FTB Bank, FTB Visa CashCard and other banks Visa card.\n4. Peace of mind with smart chip securitySaving cost and time.\n\nRequirement\n1. Having an account with FTB\n2. Have identity card, passport, family book, or any valid documents\n3. Have a permanent address in Cambodia\n4. Have a mobile number and e - mail\n5. Have proper work and sufficient income");
};


// prepare by khout kimhean ###########################################################################################################################


const displayButtons = (buttonLabels) => {
    const questionContainer = document.createElement("div");
    questionContainer.classList.add("question-container");

    buttonLabels.forEach((label) => {
        const questionElement = document.createElement("button");
        questionElement.textContent = label;
        questionElement.addEventListener("click", () => handleQuestionSelection(label));
        questionContainer.appendChild(questionElement);
    });

    chatContainer.appendChild(questionContainer);
};


// response writing bot
let botResponseCounter = 0;

const displayBotResponse = (responseText) => {
    const incomingChatDiv = createChatElement(`<div class="chat-content">
        <div class="chat-details">
        <img src="../images/background/chat.png" alt="chatbot-img">
        </div>
    </div>`, "incoming");

    chatContainer.appendChild(incomingChatDiv);
    chatContainer.scrollTo(0, chatContainer.scrollHeight);

    // Simulate typing effect
    const responseDiv = document.createElement("div");
    responseDiv.innerHTML = `<p style="opacity: 0;"></p>`;
    incomingChatDiv.querySelector(".chat-details").appendChild(responseDiv);
    chatContainer.scrollTo(0, chatContainer.scrollHeight);

    let opacity = 0;
    let i = 0;

    const interval = setInterval(() => {
        if (i < responseText.length) {
            opacity += 0.1;
            responseDiv.querySelector("p").style.opacity = opacity.toFixed(1);
            responseDiv.querySelector("p").innerHTML += responseText.charAt(i);
            i++;
            chatContainer.scrollTo(0, chatContainer.scrollHeight);
        } else {
            clearInterval(interval);
            incomingChatDiv.querySelector(".typing-animation")?.remove();
            chatContainer.scrollTo(0, chatContainer.scrollHeight);

            // Increment and display the bot response counter
            botResponseCounter++;
            console.log(`Bot Response ${String.fromCharCode(65 + botResponseCounter - 1)}:`);
        }
    }, 10);
};

// end response writing bot 

sendButton.addEventListener("click", () => {
    userText = chatInput.value;
    if (userText.trim() !== "") {
        const outgoingChatDiv = createChatElement(`<div class="chat-content">
            <div class="chat-details">
            <img src="../images/logo/user.png" alt="user-img">
                <p>${userText}</p>
            </div>
        </div>`, "outgoing");
        chatContainer.querySelector(".default-text")?.remove();
        chatContainer.appendChild(outgoingChatDiv);
        chatContainer.scrollTo(0, chatContainer.scrollHeight);

        chatInput.value = "";
        getBotResponse(userText);
    }
});

deleteButton.addEventListener("click", () => {
    chatContainer.innerHTML = "";
    localStorage.removeItem("all-chats");
    displayDefaultMessage();
});

const displayDefaultMessage = () => {
    const defaultText = `<div class="default-text">
    <img src="../images/background/chat.png" alt="chatbot-img">
        <h1>Chatbot</h1>
        <p>Hello how can I help you today?</p>
        <p>If you have any questions or issues, let me know! You can start a chat, or you can click the button below!</p>
        <div class="button">
            <div class="button1">
                <button type="button" onclick="handleButtonClick(1)">
                    <div class="text">
                        Issue in Card Payment Support Unit
                    </div>
                </button>
                <button type="button" onclick="handleButtonClick(2)">
                    <div class="text">
                        Issue in ATM Network Support Unit
                    </div>
                </button>
            </div>
            <div class="button2">
                <button type="button" onclick="handleButtonClick(3)">
                    <div class="text">
                        Issue in Digital Branch Support Unit
                    </div>
                </button>
                <button type="button" onclick="handleButtonClick(4)">
                    <div class="text">
                        Issue in Digital Terminal Management Unit
                    </div>
                </button>
            </div>
        </div>
    </div>`;
    chatContainer.innerHTML = defaultText;
};


const createChatElement = (content, className) => {
    // Create new div and apply chat, specified class and set html content of div
    const chatDiv = document.createElement("div");
    chatDiv.classList.add("chat", className);
    chatDiv.innerHTML = content;
    return chatDiv; // Return the created chat div
}

const copyResponse = (copyBtn) => {
    // Copy the text content of the response to the clipboard
    const reponseTextElement = copyBtn.parentElement.querySelector("p");
    navigator.clipboard.writeText(reponseTextElement.textContent);
    copyBtn.textContent = "done";
    setTimeout(() => copyBtn.textContent = "content_copy", 1000);
}
const showTypingAnimation = () => {
    // Display the typing animation and call the getChatResponse function
    const html = `<div class="chat-content">
                    <div class="chat-details">
                    <img src="../images/background/chat.png" alt="chatbot-img">
                        <div class="typing-animation">
                            <div class="typing-dot" style="--delay: 0.2s"></div>
                            <div class="typing-dot" style="--delay: 0.3s"></div>
                            <div class="typing-dot" style="--delay: 0.4s"></div>
                        </div>
                    </div>
                    <span onclick="copyResponse(this)" class="material-symbols-rounded">content_copy</span>
                </div>`;
    // Create an incoming chat div with typing animation and append it to chat container
    const incomingChatDiv = createChatElement(html, "incoming");
    chatContainer.appendChild(incomingChatDiv);
    chatContainer.scrollTo(0, chatContainer.scrollHeight);
    getChatResponse(incomingChatDiv);
}

const handleOutgoingChat = () => {
    userText = chatInput.value.trim(); // Get chatInput value and remove extra spaces
    if (!userText) return; // If chatInput is empty return from here

    // Clear the input field and reset its height
    chatInput.value = "";
    chatInput.style.height = "auto";

    const html = `<div class="chat-content">
        <div class="chat-details">
        <img src="../images/logo/user.png" alt="user-img">
                <p>${userText}</p>
        </div>
    </div>`;

    // Create an outgoing chat div with user's message and append it to chat container
    const outgoingChatDiv = createChatElement(html, "outgoing");
    chatContainer.querySelector(".default-text")?.remove();
    chatContainer.appendChild(outgoingChatDiv);
    chatContainer.scrollTo(0, chatContainer.scrollHeight);
    setTimeout(showTypingAnimation, 500);
};

themeButton.addEventListener("click", () => {
    // Toggle body's class for the theme mode and save the updated theme to the local storage 
    document.body.classList.toggle("light-mode");
    localStorage.setItem("themeColor", themeButton.innerText);
    themeButton.innerText = document.body.classList.contains("light-mode") ? "dark_mode" : "light_mode";
});

const initialInputHeight = chatInput.scrollHeight;

chatInput.addEventListener("input", () => {
    // Adjust the height of the input field dynamically based on its content
    chatInput.style.height = `${initialInputHeight}px`;
    chatInput.style.height = `${chatInput.scrollHeight}px`;
});

chatInput.addEventListener("keydown", (e) => {
    // If the Enter key is pressed without Shift and the window width is larger
    // than 800 pixels, handle the outgoing chat
    if (e.key === "Enter" && !e.shiftKey && window.innerWidth > 800) {
        e.preventDefault();
        handleOutgoingChat();
    }
});

loadDataFromLocalstorage();
sendButton.addEventListener("click", handleOutgoingChat);

deleteButton.addEventListener("click", () => {
    // Remove the chats from local storage and call loadDataFromLocalstorage function
    if (confirm("Are you sure you want to delete all the chats?")) {
        localStorage.removeItem("all-chats");
        loadDataFromLocalstorage();
    }
});



chatInput.addEventListener("input", () => {
    // Adjust the height of the input field dynamically based on its content
    chatInput.style.height = `${initialInputHeight}px`;
    chatInput.style.height = `${chatInput.scrollHeight}px`;
});

chatInput.addEventListener("keydown", (e) => {
    // If the Enter key is pressed without Shift and the window width is larger 
    // than 800 pixels, handle the outgoing chat
    if (e.key === "Enter" && !e.shiftKey && window.innerWidth > 800) {
        e.preventDefault();
        handleOutgoingChat();
    }
});

loadDataFromLocalstorage();
sendButton.addEventListener("click", handleOutgoingChat);