const chatInput = document.querySelector("#chat-input");
const sendButton = document.querySelector("#send-btn");
const chatContainer = document.querySelector(".chat-container");
const themeButton = document.querySelector("#theme-btn");
const deleteButton = document.querySelector("#delete-btn");

// link skype
const Link = 'https://join.skype.com/shWclka7rGQk';     // Contact Team Card
const LinkA = 'https://join.skype.com/shWclka7rGQk';    // Contact Team Digital
const LinkB = 'https://join.skype.com/shWclka7rGQk';    // Contact Team ATM
const LinkC = 'https://join.skype.com/shWclka7rGQk';    // Contact Team Terminal


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
            questions = ["Card Info", "Response Code", "contact to team Card"];
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
        <button class="submit" onclick="submitUserInput()">Send</button>
    `;
    const userInputInput = inputDiv.querySelector("#userInput");

    // Add event listener for 'keydown' on the input field
    userInputInput.addEventListener("keydown", (event) => {
        if (event.key === 'Enter' && !event.shiftKey) {
            event.preventDefault();
            submitUserInput();
        }

    });

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
                    chatContainer.scrollTo(0, chatContainer.scrollHeight);
                } else {
                    clearInterval(interval);

                    // Add a newline and the additional message
                    // responseDiv.querySelector("p").innerHTML += "<br>Please contact us if you have any issue that need team to support <<a href='https://join.skype.com/tnP3accFexk3' target='_blank'>Link Shy</a>>";

                    // Scroll after adding the additional message
                    chatContainer.scrollTo(0, chatContainer.scrollHeight);
                }
            }, 10);
        })
        .catch(error => {
            console.error('Error fetching response:', error);
        });
};

// end 

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

    const responseText = "Click <a href='" + Link + "' target='_blank' class='contact-link'>here</a> to contact Team Support.";
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

    const responseText = "Click <a href='" + LinkA + "'  target='_blank' class='contact-link'>here</a> to contact Team Support.";
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

    const responseText = "Click <a href='" + LinkB + "' target='_blank' class='contact-link'>here</a> to contact Team Support.";
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

    const responseText = "Click <a href='" + LinkC + "' target='_blank' class='contact-link'>here</a> to contact Team Support.";
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
    displayBotResponse(
        `Visa CashCard Gold
    --> Bin 
    436496
    00000 00000 -> 99999 99999

    --> Feature
    1. Storage amount is more than USD5,000.00
    2. 5 years of card validity
    3. USD currency only
    4. Able to register with FTB Mobile App
    5. Secure payment with ACS 3Ds
    6. No plastic card fee
    7. No annual fee

    --> Limit
    Transaction Type			        \t\tLimit (USD)	Frequency
    Daily ATM Cash Withdrawal		    \t\t5,000.00	7
    Daily POS Cash Advance			    \t5,000.00	7
    Daily Purchase (POS & eCommerce)	10,000.00	7
    Daily Top-Up				        \t\t100,000.00	7
    Daily Quasi Cash			        \t\t10,000.00	7
    Daily Fund Transfer			        \t100,000.00	7
    `);
};

const handleInfoCard2 = () => {
    displayBotResponse(`Visa CashCard Blue
    --> Bin 
    436495
    00000 00000 -> 99999 99999

    --> Feature
    1. Storage amount is up to USD5,000.00
    2. 2 years of card validity
    3. USD currency only
    4. Able to register with FTB Mobile App
    5. Peace of mind with smart chip security
    6. No plastic card fee
    7. No annual fee

    --> Limit
    Transaction Type			       \t\t\tLimit (USD)	Frequency
    Daily ATM Cash Withdrawal		   \t\t2,000.00	    \t7
    Daily POS Cash Advance			   \t2,000.00	    \t7
    Daily Purchase (POS & eCommerce)   \t5,000.00	7
    Daily Top-Up				       \t\t\t100,000.00	7
    Daily Quasi Cash			       \t\t\t5,000.00        7
    Daily Fund Transfer			       \t\t5,000.00	7
    `);
};

const handleInfoCard3 = () => {
    displayBotResponse(`Visa CashCard Silver
    --> Bin	
    437500		
    00000 00000 -> 99999 99999

    --> Feature
    1. Perfect for gift
    2. Able to register with FTB Mobile App
    3. Secure payment with ACS 3Ds
    4. Peace of mind with smart chip security
    5. Storage amount is up to USD5,000.00
    6. 2 years of card validity
    7. USD currency only
    8. No plastic card fee
    9. No annual fee

    --> Limit 
    Transaction Type			    \t\t\tLimit(USD)	Frequency
    Daily ATM Cash Withdrawal		\t\t500.00		7
    Daily POS Cash Advance			\t500.00		7
    Daily Purchase(POS & eCommerce)	\t500.00		7
    Daily Top - Up				    \t\t\t500.00		7
    Daily Quasi Cash			    \t\t\t500.00		7
    Daily Fund Transfer			    \t\t500.00		7
    `);
};


const handleInfoCard4 = () => {
    displayBotResponse(`Virtual Visa CashCard
    --> Bin 
    437500
    00000 00000 -> 99999 99999

    --> Feature
    1. Storage amount is up to USD300.00
    2. 2 years of card validity
    3. USD currency only
    4. Card can be requested immediately in FTB Mobile App
    5. Able to register with FTB Mobile App
    6. Secure payment with ACS 3Ds
    7. Peace of mind with smart chip security
    8. Be able to top up from Debit Account or Cash Card in FTB Mobile App
    9. No annual fee

    --> Limit
    Transaction Type			        \t\t\tLimit (USD)	Frequency
    Daily Purchase (POS & eCommerce)	\t300.00		7
    Daily Top-Up				        \t\t\t300.00		7
    Daily Quasi Cash			        \t\t\t300.00		7
    Daily Fund Transfer			        \t\t300.00		7
    `);
};

const handleInfoCard5 = () => {
    displayBotResponse(`FTB ATM Debit Card
    --> Bin 
    50201410

    --> Feature
    1. Linked to FTB Bank account
    2. 5 years of card validity
    3. USD and KHR currencies
    4. Able to register with FTB Mobile app
    5. Peace of mind with smart chip security
    6. No plastic card fee
    7. No annual fee

    --> Limit 
    Transaction Type		    \t\t\tLimit (USD)	Frequency
    Daily ATM Cash Withdrawal	\t\t5,000.00	7
    Daily POS Cash Advance		\tUnlimited	Unlimited
    Daily Purchase (POS)		\t\t1,000.00	\t7
    Daily Top-Up			    \t\t\tUnlimited	Unlimited
    Daily Fund Transfer		    \t\t10,000.00	7
`);
};


const handleInfoCard6 = () => {
    displayBotResponse(`FTB VIP Visa Debit
    --> Bin 
    47638887

    --> Feature
    1. Linked to FTB Bank account
    2. 5 years of card validity
    3. USD and KHR currencies
    4. Able to register with FTB Mobile app
    5. No plastic card fee
    6. No annual fee

    --> Limit
    Transaction Type			    \t\tLimit (USD)	Frequency
    Daily ATM Cash Withdrawal		\t5,000.00	7
    Daily POS Cash Advance		    \tUnlimited	Unlimited
    Daily Purchase (POS)			\t1,000.00	        7
    Daily Top-Up		    		\t\tUnlimited	Unlimited
    Daily FT Destination			\t10,000.00	7
    Daily Fund Transfer		    	\t5,000.00	7
    Daily Third-party Source		\t2,000.00	        7
    Daily Third-Party Destination	\t10,000.00	20
    `);
};

const handleInfoCard7 = () => {
    displayBotResponse(`Visa Debit Classic
    --> Bin 
    9116001 (CSS)
    476388

    --> Feature
    1. Linked to FTB Bank account
    2. 5 years of card validity
    3. USD and KHR currencies
    4. Able to register with FTB Mobile app
    5. No plastic card fee
    6. No annual fee

    --> Limit
    Transaction Type		        \t\tLimit (USD)	Frequency
    Daily ATM Cash Withdrawal	    \t\t1,000.00	\t7
    Daily POS Cash Advance		    \tUnlimited	7
    Daily Purchase (POS)		    \t\t1,000.00	\t7
    Daily Top-Up			        \t\t100,000.00	7
    Daily FT Destination		    \t\t10,000.00	7
    Daily Fund Transfer		        \t5,000.00	7
    Daily Third-party Source		\t2,000.00	    \t7
    Daily Third-Party Destination	\t1,500.00	    \t7
`);
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
        <div class="chat-detail">
        <img src="../images/background/chat.png" alt="chatbot-img">
        </div>
    </div>`, "incoming");

    chatContainer.appendChild(incomingChatDiv);
    chatContainer.scrollTo(0, chatContainer.scrollHeight);

    // Simulate typing effect
    const responseDiv = document.createElement("div");
    responseDiv.innerHTML = `<p style="opacity: 0;"></p>`;
    incomingChatDiv.querySelector(".chat-detail").appendChild(responseDiv);
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

chatInput.addEventListener("keydown", (event) => {
    if (event.key === "Enter") {
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
        event.preventDefault(); // Prevents the default behavior of the Enter key in a textarea
    }
});

deleteButton.addEventListener("click", () => {
    chatContainer.innerHTML = "";
    localStorage.removeItem("all-chats");
    displayDefaultMessage();
});


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
    setTimeout(getBotResponse, 500);
};

themeButton.addEventListener("click", () => {
    // Toggle body's class for the theme mode and save the updated theme to the local storage 
    document.body.classList.toggle("light-mode");
    localStorage.setItem("themeColor", themeButton.innerText);
    themeButton.innerText = document.body.classList.contains("light-mode") ? "dark_mode" : "light_mode";
});

const initialInputHeight = chatInput.scrollHeight;



loadDataFromLocalstorage();
sendButton.addEventListener("click", handleOutgoingChat);

deleteButton.addEventListener("click", () => {
    if (confirm("Are you sure you want to delete all the chats?")) {
        localStorage.removeItem("all-chats");
        loadDataFromLocalstorage();
    }
});



const getBotResponse = (userInput) => {
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
                    chatContainer.scrollTo(0, chatContainer.scrollHeight);
                } else {
                    clearInterval(interval);

                    chatContainer.scrollTo(0, chatContainer.scrollHeight);
                }
            }, 10);
        })
        .catch(error => {
            console.error('Error fetching response:', error);
        });
};

const UserInput = () => {
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


chatInput.addEventListener("input", () => {
    // Adjust the height of the input field dynamically based on its content
    chatInput.style.height = `${initialInputHeight}px`;
    chatInput.style.height = `${chatInput.scrollHeight}px`;
});

chatInput.addEventListener("keydown", (e) => {
    if (e.key === "Enter" && !e.shiftKey && window.innerWidth > 800) {
        e.preventDefault();
        handleOutgoingChat();
    }
});

loadDataFromLocalstorage();
sendButton.addEventListener("click", handleOutgoingChat);