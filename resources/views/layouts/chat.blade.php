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
        /* Important: hidden initially */
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

    /* Show class for animation */
    #chat-box.show {
        display: flex;
        /* flex layout */
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
        /* scrollable height */
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

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Toggle chat when circle button clicked
    function toggleChat() {
        const box = $('#chat-box');
        if (box.hasClass('show')) {
            box.removeClass('show');
            setTimeout(() => box.hide(), 400); // hide after transition
            $('#chat-button').show();
        } else {
            box.show();
            setTimeout(() => box.addClass('show'), 10); // trigger fade-in
            $('#chat-button').hide();
            $('#chat-input').focus();
        }
    }

    // Smoothly close chat if clicking outside
    $(document).on('click', function(event) {
        const target = $(event.target);
        const box = $('#chat-box');
        const button = $('#chat-button');

        if (box.hasClass('show') && !target.closest('#chat-box').length && !target.closest('#chat-button').length) {
            box.removeClass('show'); // trigger fade-out
            setTimeout(() => box.hide(), 400); // wait for transition to finish
            button.fadeIn(200); // smooth fade-in for circle button
        }
    });

    // Send message and scroll
    function scrollToBottom() {
        const chat = $('#chat-messages');
        chat.scrollTop(chat[0].scrollHeight);
    }

    function appendBotMessageSmoothly(message) {
        const chat = $('#chat-messages');
        const botDiv = $('<div class="message bot"></div>');
        chat.append(botDiv);
        scrollToBottom();

        let i = 0;
        const speed = 30; // milliseconds per character

        function typeChar() {
            if (i < message.length) {
                botDiv.append(message.charAt(i));
                i++;
                scrollToBottom();
                setTimeout(typeChar, speed);
            }
        }

        typeChar();
    }

    function sendMessage() {
        let message = $('#chat-input').val().trim();
        if (!message) return;

        $('#chat-messages').append('<div class="message user">' + message + '</div>');
        scrollToBottom();
        $('#chat-input').val('');

        $.ajax({
            url: '/chat/send',
            method: 'POST',
            data: {
                message: message,
                _token: '{{ csrf_token() }}'
            },
            success: function(data) {
                // Instead of appending instantly, use smooth typing
                appendBotMessageSmoothly(data.reply);
            }
        });
    }

    // Enter key sends message
    $('#chat-input').on('keypress', function(e) {
        if (e.which === 13) {
            sendMessage();
            e.preventDefault();
        }
    });

    // Initial load: only circle visible
    $(document).ready(function() {
        $('#chat-box').hide();
        $('#chat-button').show();
    });
</script>
