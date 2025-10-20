<!-- Floating AI Assistant Widget -->
<div id="aiAssistantWidget" x-data="aiAssistant()" x-init="init()" class="fixed bottom-6 right-6 z-50">
    <!-- Chat Button -->
    <button @click="toggleChat()"
            x-show="!isOpen"
            class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-full p-4 shadow-2xl transform hover:scale-110 transition-all duration-300 relative group">
        <!-- Pulsing notification dot for new users -->
        <span x-show="showNotification"
              class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full animate-ping"></span>
        <span x-show="showNotification"
              class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full"></span>

        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
        </svg>

        <!-- Tooltip -->
        <div class="absolute bottom-full right-0 mb-2 px-3 py-2 bg-gray-900 text-white text-sm rounded-lg whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
            Need help? Chat with AI Assistant! 🤖
        </div>
    </button>

    <!-- Chat Window -->
    <div x-show="isOpen"
         x-transition:enter="transform transition ease-out duration-300"
         x-transition:enter-start="translate-y-full opacity-0"
         x-transition:enter-end="translate-y-0 opacity-100"
         x-transition:leave="transform transition ease-in duration-200"
         x-transition:leave-start="translate-y-0 opacity-100"
         x-transition:leave-end="translate-y-full opacity-0"
         class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-96 h-[600px] flex flex-col overflow-hidden border-2 border-blue-500/20">

        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center animate-pulse">
                    <span class="text-2xl">🤖</span>
                </div>
                <div>
                    <h3 class="font-bold text-sm">AI Assistant</h3>
                    <p class="text-xs text-blue-100">Always here to help!</p>
                </div>
            </div>
            <button @click="closeChat()" class="hover:bg-white/20 rounded-full p-1 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Messages -->
        <div class="flex-1 overflow-y-auto p-4 space-y-4" id="assistantMessages">
            <!-- Welcome Message -->
            <div class="flex gap-2">
                <div class="flex-shrink-0 w-8 h-8 bg-gradient-to-br from-purple-500 to-blue-500 rounded-full flex items-center justify-center text-white">
                    🤖
                </div>
                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg px-4 py-3 max-w-xs">
                    <p class="text-sm text-gray-800 dark:text-gray-200" x-html="welcomeMessage"></p>
                </div>
            </div>

            <!-- Tour Suggestion for New Users -->
            <div x-show="shouldShowTourSuggestion" class="bg-gradient-to-r from-blue-50 to-purple-50 dark:from-blue-900/20 dark:to-purple-900/20 rounded-lg p-4 border border-blue-200 dark:border-blue-700">
                <p class="text-sm text-gray-800 dark:text-gray-200 mb-3" x-html="getTourSuggestionText()"></p>
                <div class="flex gap-2">
                    <button @click="startTour()" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-lg transition font-medium">
                        🎯 Yes, show me around!
                    </button>
                    <button @click="dismissTour()" class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition">
                        Maybe later
                    </button>
                </div>
            </div>

            <!-- Dynamic messages will appear here -->
            <template x-for="(message, index) in messages" :key="index">
                <div class="flex" :class="message.type === 'user' ? 'justify-end' : 'gap-2'">
                    <div x-show="message.type === 'ai'" class="flex-shrink-0 w-8 h-8 bg-gradient-to-br from-purple-500 to-blue-500 rounded-full flex items-center justify-center text-white">
                        🤖
                    </div>
                    <div class="rounded-lg px-4 py-3 max-w-xs"
                         :class="message.type === 'user' ? 'bg-blue-600 text-white' : 'bg-gray-100 dark:bg-gray-700'">
                        <p class="text-sm" :class="message.type === 'user' ? 'text-white' : 'text-gray-800 dark:text-gray-200'" x-html="message.text"></p>
                    </div>
                </div>
            </template>

            <!-- Typing Indicator -->
            <div x-show="isTyping" class="flex gap-2">
                <div class="flex-shrink-0 w-8 h-8 bg-gradient-to-br from-purple-500 to-blue-500 rounded-full flex items-center justify-center text-white">
                    🤖
                </div>
                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg px-4 py-3">
                    <div class="flex gap-1">
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0ms"></div>
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 150ms"></div>
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 300ms"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="px-4 py-2 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
            <div class="flex gap-2 overflow-x-auto pb-2">
                <button @click="askQuestion('How do I post an advert?')" class="flex-shrink-0 text-xs px-3 py-1 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-full hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                    📝 Post Advert
                </button>
                <button @click="askQuestion('What are the pricing plans?')" class="flex-shrink-0 text-xs px-3 py-1 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-full hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                    💰 Pricing
                </button>
                <button @click="askQuestion('How do I contact support?')" class="flex-shrink-0 text-xs px-3 py-1 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-full hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                    💬 Support
                </button>
            </div>
        </div>

        <!-- Input -->
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            <div class="flex gap-2">
                <input type="text"
                       x-model="inputMessage"
                       @keydown.enter="sendMessage()"
                       placeholder="Type your message..."
                       class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white text-sm">
                <button @click="sendMessage()"
                        :disabled="!inputMessage.trim()"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </button>
            </div>
            <a href="{{ route('chatbot.index') }}" class="block text-center text-xs text-gray-500 hover:text-blue-600 mt-2 transition">
                Open full chat window →
            </a>
        </div>
    </div>
</div>

<script>
function aiAssistant() {
    return {
        isOpen: false,
        showNotification: false,
        shouldShowTourSuggestion: false,
        messages: [],
        inputMessage: '',
        isTyping: false,
        welcomeMessage: '👋 Hi! I\'m your AI assistant. I can help you navigate {{ config("app.name") }}, answer questions, or connect you with support!',
        currentContext: '',

        async init() {
            // Detect context based on URL
            const path = window.location.pathname;
            if (path.startsWith('/advertiser')) {
                this.currentContext = 'advertiser';
            } else if (path.startsWith('/admin')) {
                this.currentContext = 'admin';
            } else {
                this.currentContext = 'public';
            }

            // Debug: Log the detected context
            console.log('AI Assistant - Current URL:', window.location.pathname);
            console.log('AI Assistant - Detected Context:', this.currentContext);

            // Check if user has dismissed the tour recently
            const dismissedTime = localStorage.getItem('ai_assistant_dismissed_time_' + this.currentContext);
            const currentTime = new Date().getTime();
            const cooldownPeriod = 24 * 60 * 60 * 1000; // 24 hours in milliseconds

            // If dismissed within cooldown period, don't show
            if (dismissedTime && (currentTime - parseInt(dismissedTime)) < cooldownPeriod) {
                console.log('AI Assistant - Tour dismissed recently, skipping');
                this.shouldShowTourSuggestion = false;
                return;
            }

            // Check if user should see tour based on context
            await this.checkTourStatus();

            // Check if already opened in this session
            const openedInSession = sessionStorage.getItem('ai_assistant_opened_session_' + this.currentContext);

            // Auto-open for first-time visitors after 3 seconds (only once per session)
            if (this.shouldShowTourSuggestion && !openedInSession) {
                setTimeout(() => {
                    this.isOpen = true;
                    sessionStorage.setItem('ai_assistant_opened_session_' + this.currentContext, 'true');
                }, 3000);
            }
        },

        async checkTourStatus() {
            @auth
            // Check specific tour completion based on context
            const tourType = this.currentContext === 'advertiser' ? 'advertiser' : (this.currentContext === 'admin' ? 'admin' : 'initial');
            console.log('AI Assistant - Tour Type:', tourType);

            try {
                const response = await fetch(`/onboarding/check/${tourType}`);
                const data = await response.json();
                console.log('AI Assistant - Tour Completed:', data.completed);
                this.shouldShowTourSuggestion = !data.completed;
                this.showNotification = !data.completed;

                // Update welcome message based on context
                if (this.currentContext === 'advertiser' && !data.completed) {
                    this.welcomeMessage = '👋 Welcome to your Advertiser Dashboard! Let me show you around!';
                } else if (this.currentContext === 'admin' && !data.completed) {
                    this.welcomeMessage = '👋 Welcome to the Admin Panel! Let me show you around.';
                } else if (this.currentContext === 'public' && !data.completed) {
                    this.welcomeMessage = '👋 Hi! I\'m your AI assistant. Want a quick tour of {{ config("app.name") }}?';
                }
            } catch (error) {
                console.error('Error checking tour status:', error);
                this.shouldShowTourSuggestion = false;
            }
            @else
            // For guests, only show on public pages
            if (this.currentContext === 'public') {
                // Check if dismissed recently
                const dismissedTime = localStorage.getItem('ai_assistant_dismissed_time_' + this.currentContext);
                const currentTime = new Date().getTime();
                const cooldownPeriod = 24 * 60 * 60 * 1000; // 24 hours

                // Only show if not dismissed or cooldown expired
                const isDismissedRecently = dismissedTime && (currentTime - parseInt(dismissedTime)) < cooldownPeriod;

                this.shouldShowTourSuggestion = !isDismissedRecently;
                this.showNotification = this.shouldShowTourSuggestion;

                if (this.shouldShowTourSuggestion) {
                    this.welcomeMessage = '👋 Hi! I\'m your AI assistant. Want a quick tour of {{ config("app.name") }}?';
                }
            }
            @endauth
        },

        getTourSuggestionText() {
            if (this.currentContext === 'advertiser') {
                return '👋 Welcome! I notice this is your first time in the Advertiser Dashboard. Would you like a quick guided tour? I\'ll show you how to create adverts, manage campaigns, and track performance!';
            } else if (this.currentContext === 'admin') {
                return '👋 Welcome to the Admin Panel! Let me give you a guided tour of all the admin features and how to manage the platform effectively.';
            } else {
                return '👋 Welcome! I notice you\'re new here. Would you like me to give you a quick guided tour of {{ config("app.name") }}? I\'ll show you step-by-step how everything works!';
            }
        },

        toggleChat() {
            this.isOpen = !this.isOpen;
            if (this.isOpen) {
                this.showNotification = false;
                localStorage.setItem('ai_assistant_opened_' + this.currentContext, 'true');
            }
        },

        closeChat() {
            this.isOpen = false;
        },

        async startTour() {
            this.shouldShowTourSuggestion = false;

            // Add message
            this.messages.push({
                type: 'user',
                text: 'Yes, show me around!'
            });

            this.isTyping = true;
            await this.delay(1000);
            this.isTyping = false;

            this.messages.push({
                type: 'ai',
                text: '🎉 Awesome! Let me show you around!<br><br>I\'ll guide you through:<br>✓ How to search for items<br>✓ How to post adverts<br>✓ Understanding pricing plans<br>✓ Safety tips<br><br>Ready to begin? Click the button below!'
            });

            setTimeout(() => {
                this.scrollToBottom();
            }, 100);

            // Start the actual tour based on context
            setTimeout(() => {
                let tourUrl = '{{ route("onboarding.tour") }}';
                if (this.currentContext === 'advertiser') {
                    tourUrl = '{{ route("onboarding.advertiser") }}';
                } else if (this.currentContext === 'admin') {
                    tourUrl = '{{ route("onboarding.admin") }}';
                }
                console.log('AI Assistant - Redirecting to tour URL:', tourUrl);
                window.location.href = tourUrl;
            }, 2000);
        },

        dismissTour() {
            this.shouldShowTourSuggestion = false;

            // Save dismissal timestamp (will prevent showing for 24 hours)
            const currentTime = new Date().getTime();
            localStorage.setItem('ai_assistant_dismissed_time_' + this.currentContext, currentTime.toString());

            // Mark as dismissed
            localStorage.setItem('ai_assistant_dismissed_' + this.currentContext, 'true');

            this.messages.push({
                type: 'user',
                text: 'Maybe later'
            });

            this.isTyping = true;
            setTimeout(() => {
                this.isTyping = false;
                this.messages.push({
                    type: 'ai',
                    text: 'No problem! I\'m always here if you need help. Just click on me anytime! 😊'
                });
                this.scrollToBottom();
            }, 800);
        },

        async askQuestion(question) {
            this.inputMessage = question;
            await this.sendMessage();
        },

        async sendMessage() {
            if (!this.inputMessage.trim()) return;

            const message = this.inputMessage.trim();
            this.inputMessage = '';

            // Add user message
            this.messages.push({
                type: 'user',
                text: message
            });

            this.scrollToBottom();

            // Show typing indicator
            this.isTyping = true;
            await this.delay(1500);
            this.isTyping = false;

            // Simple response (in production, this would call your AI service)
            let response = this.getResponse(message);

            this.messages.push({
                type: 'ai',
                text: response
            });

            this.scrollToBottom();
        },

        getResponse(message) {
            const msg = message.toLowerCase();

            if (msg.includes('post') || msg.includes('advert') || msg.includes('sell')) {
                return '📝 To post an advert:<br>1. Click "Post Ad" in the menu<br>2. Fill in your details<br>3. Upload photos<br>4. Choose a pricing plan<br>5. Submit!<br><br>Need detailed help? Say "connect me to support"';
            }

            if (msg.includes('price') || msg.includes('cost') || msg.includes('plan')) {
                return '💰 We have 3 plans:<br>• Regular (₦1,000) - 30 days<br>• Featured (₦3,000) - 30 days, top results<br>• Premium (₦5,000) - 60 days, homepage featured<br><br>Which one interests you?';
            }

            if (msg.includes('support') || msg.includes('help') || msg.includes('connect')) {
                return '💬 I can connect you with a live support agent!<br><br><a href="{{ route("chatbot.index") }}" class="text-blue-600 underline">Click here to chat with support →</a>';
            }

            return 'I can help you with:<br>• Posting adverts<br>• Pricing plans<br>• Payment methods<br>• Safety tips<br>• Account help<br><br>What would you like to know?';
        },

        scrollToBottom() {
            setTimeout(() => {
                const container = document.getElementById('assistantMessages');
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            }, 100);
        },

        delay(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        }
    }
}
</script>
