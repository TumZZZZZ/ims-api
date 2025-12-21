<!-- Floating Chat Widget -->
<div id="chat-widget">
    <!-- Circular button with image -->
    <div id="chat-button" onclick="toggleChat()">
        <img src="https://images.vexels.com/media/users/3/139959/isolated/preview/d9bc539ecb4092e391863126207c3b6a-cloud-chat-round-icon.png"
            alt="Chat" />
    </div>

    <!-- Chat box hidden by default -->
    <div id="chat-box">
        <!-- Chat header -->
        <div id="chat-header">Chat with AI</div>

        <!-- Chat body -->
        <div id="chat-body">
            <div id="chat-messages"></div>
            <input type="text" id="chat-input" placeholder="Type a message..." autocomplete="off" />
        </div>
    </div>
</div>

<style>
    /* Container */
    #chat-widget {
        position: fixed;
        bottom: 20px;
        right: 20px;
        font-family: 'Khmer OS Siemreap Regular', Arial;
        z-index: 9999;
    }

    /* Circular chat button */
    #chat-button {
        width: 65px;
        height: 65px;
        border-radius: 50%;
        cursor: pointer;
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.25);
        overflow: hidden;
        transition: transform 0.3s ease;
    }

    #chat-button img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    #chat-button:hover {
        transform: scale(1.1);
    }

    /* Chat Box - hidden by default */
    #chat-box {
        display: none;
        width: 360px;
        max-height: 500px;
        background: #f9f9f9;
        border-radius: 20px;
        box-shadow: 0 4px 25px rgba(0, 0, 0, 0.3);
        flex-direction: column;
        overflow: hidden;
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.4s ease, transform 0.4s ease;
    }

    #chat-box.show {
        display: flex;
        opacity: 1;
        transform: translateY(0);
    }

    /* Chat Header */
    #chat-header {
        background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
        color: white;
        padding: 15px;
        font-weight: bold;
        text-align: center;
    }

    /* Chat Body */
    #chat-body {
        display: flex;
        flex-direction: column;
        padding: 10px;
        flex: 1;
        height: 400px;
    }

    /* Messages scrollable */
    #chat-messages {
        flex: 1;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        margin-bottom: 10px;
        padding-right: 5px;
    }

    /* Messages */
    .message {
        padding: 10px 15px;
        border-radius: 20px;
        margin-bottom: 8px;
        max-width: 70%;
        word-wrap: break-word;
        font-size: 14px;
        line-height: 1.4;
    }

    .message.user {
        background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
        color: #fff;
        align-self: flex-end;
        border-bottom-right-radius: 0;
        font-weight: bold;
    }

    .message.bot {
        background: linear-gradient(135deg, #e0e0e0 0%, #cfcfcf 100%);
        color: #333;
        align-self: flex-start;
        border-bottom-left-radius: 0;
    }

    #chat-input {
        padding: 10px;
        border-radius: 20px;
        border: 1px solid #ccc;
        width: 100%;
        outline: none;
        font-size: 14px;
    }

    #chat-messages::-webkit-scrollbar {
        width: 6px;
    }

    #chat-messages::-webkit-scrollbar-thumb {
        background-color: rgba(0, 0, 0, 0.2);
        border-radius: 3px;
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Toggle chat visibility and load history
    async function toggleChat() {
        const chatBox = $('#chat-box');
        chatBox.toggleClass('show');
        if (chatBox.hasClass('show')) {
            chatBox.show();
            const res = await fetch("{{ route('chat.history') }}");
            const messages = await res.json();
            const chatMessages = $('#chat-messages');
            chatMessages.empty();
            messages.forEach(m => {
                chatMessages.append(`<div class="message user"><b></b> ${m.user}</div>`);
                chatMessages.append(`<div class="message bot"><b></b> ${m.ai}</div>`);
            });
            chatMessages.scrollTop(chatMessages[0].scrollHeight);
        }
    }

    // Append AI message with smooth typing
    function appendBotMessageSmoothly(message) {
        const chatMessages = $('#chat-messages');
        const botDiv = $('<div class="message bot"></div>');
        chatMessages.append(botDiv);
        chatMessages.scrollTop(chatMessages[0].scrollHeight);

        let i = 0;
        const speed = 30;

        function typeChar() {
            if (i < message.length) {
                botDiv.append(message.charAt(i));
                i++;
                chatMessages.scrollTop(chatMessages[0].scrollHeight);
                setTimeout(typeChar, speed);
            }
        }
        typeChar();
    }

    // Send message
    function sendMessage() {
        let message = $('#chat-input').val().trim();
        if (!message) return;

        $('#chat-messages').append('<div class="message user">' + message + '</div>');
        $('#chat-input').val('');
        $('#chat-messages').scrollTop($('#chat-messages')[0].scrollHeight);

        $.post("{{ route('chat.send') }}", {
            message: message,
            _token: '{{ csrf_token() }}'
        }, function(data) {
            appendBotMessageSmoothly(data.reply);
        });
    }

    // Enter key triggers send
    $('#chat-input').on('keypress', function(e) {
        if (e.which === 13) {
            sendMessage();
            e.preventDefault();
        }
    });

    // Click outside to close chat
    $(document).on('click', function(e) {
        const target = $(e.target);
        if (!target.closest('#chat-box, #chat-button').length) {
            $('#chat-box').removeClass('show').fadeOut(400);
        }
    });

    $(document).ready(function() {
        $('#chat-box').hide();
        $('#chat-button').show();
    });
</script>
