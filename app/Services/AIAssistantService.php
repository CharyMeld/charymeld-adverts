<?php

namespace App\Services;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use Illuminate\Support\Str;

class AIAssistantService
{
    protected $personalities = [
        'helpful' => [
            'tone' => 'friendly and informative',
            'greeting' => 'Hi! 👋 I\'m your CharyMeld Adverts assistant. How can I help you today?',
        ],
        'professional' => [
            'tone' => 'formal and detailed',
            'greeting' => 'Welcome to CharyMeld Adverts. I\'m here to assist you with any inquiries.',
        ],
        'friendly' => [
            'tone' => 'warm and casual',
            'greeting' => 'Hey there! 😊 Welcome to CharyMeld! What can I do for you?',
        ],
        'casual' => [
            'tone' => 'relaxed and conversational',
            'greeting' => 'Hey! What\'s up? Need some help with CharyMeld?',
        ],
    ];

    protected $knowledgeBase = [
        'about' => 'CharyMeld Adverts is your trusted marketplace for buying and selling across Nigeria. We connect buyers and sellers with ease.',
        'posting' => 'To post an advert: 1) Sign up/Login, 2) Click "Post Ad", 3) Fill in details, 4) Upload images, 5) Choose a plan and pay.',
        'plans' => 'We offer 3 plans: Regular (₦1,000 - 30 days), Featured (₦3,000 - 30 days, top search results), Premium (₦5,000 - 60 days, homepage featured).',
        'payment' => 'We accept payments via Paystack and Flutterwave. All major cards and bank transfers are supported.',
        'safety' => 'Safety tips: Meet in public places, verify items before payment, don\'t share sensitive info, report suspicious activity.',
        'categories' => 'Browse categories: Vehicles, Real Estate, Electronics, Fashion, Services, and more!',
        'contact' => 'Contact us: Email - support@charymeld.com, Phone - +234 (0) 123 456 789',
    ];

    public function generateResponse(ChatConversation $conversation, string $userMessage): string
    {
        $personality = $this->personalities[$conversation->ai_personality] ?? $this->personalities['helpful'];

        // Check if user wants to connect to support
        if ($this->detectSupportRequest($userMessage)) {
            return $this->handleSupportRequest($conversation);
        }

        // If already connected to support, let them know
        if ($conversation->support_status === 'requested') {
            return "🔔 Your request for support has been received! A team member will join this conversation shortly. In the meantime, feel free to describe your issue in detail.";
        }

        if ($conversation->support_status === 'connected') {
            return "👤 You're currently connected with our support team. They'll respond to you shortly!";
        }

        // Simple keyword matching for demo - In production, integrate with OpenAI, Anthropic Claude, or similar
        $response = $this->matchKeywords($userMessage);

        if (!$response) {
            $response = $this->generateContextualResponse($conversation, $userMessage, $personality);
        }

        return $response;
    }

    protected function detectSupportRequest(string $message): bool
    {
        $message = strtolower($message);

        return preg_match('/\b(connect|talk to|speak to|reach|contact|get|need)\b.*\b(human|person|agent|support|team|representative|staff|someone|help)\b/i', $message)
            || preg_match('/\b(live chat|live support|real person|actual person)\b/i', $message)
            || preg_match('/\b(yes|yeah|sure|ok|okay|please)\b.*\b(connect|support)\b/i', $message);
    }

    protected function handleSupportRequest(ChatConversation $conversation): string
    {
        // Only process if not already requested or connected
        if ($conversation->support_status === 'ai_only') {
            // Update conversation status
            $conversation->update([
                'support_status' => 'requested',
                'support_requested_at' => now(),
            ]);

            // Create admin notification - handle both authenticated and guest users
            $userId = $conversation->user_id;
            $userName = $conversation->user ? $conversation->user->name : 'Guest User';

            \App\Models\AdminNotification::createSupportRequest(
                $conversation->id,
                $userId,
                $userName
            );

            return "✅ **Support Request Received!**\n\n" .
                   "I've notified our support team about your request. A team member will join this conversation shortly to assist you personally.\n\n" .
                   "⏱️ **Average wait time:** 2-5 minutes\n\n" .
                   "While you wait, feel free to describe your issue in detail so our team can help you faster!";
        } elseif ($conversation->support_status === 'requested') {
            return "⏳ **Your support request is pending...**\n\n" .
                   "Our team has been notified and will join shortly. Please hold tight!\n\n" .
                   "In the meantime, feel free to provide more details about your issue.";
        } elseif ($conversation->support_status === 'connected') {
            return "✅ **You're already connected to our support team!**\n\n" .
                   "A support agent is in this conversation. Please continue your conversation with them.";
        }

        return "Our support team will assist you shortly.";
    }

    protected function matchKeywords(string $message): ?string
    {
        $message = strtolower($message);

        // Greeting patterns
        if (preg_match('/\b(hi|hello|hey|good morning|good afternoon)\b/i', $message)) {
            return 'Hello! 👋 How can I assist you with CharyMeld Adverts today?';
        }

        // About CharyMeld
        if (preg_match('/\b(what is|about|tell me about) (charymeld|this site|website)\b/i', $message)) {
            return $this->knowledgeBase['about'] . ' Would you like to know more about posting adverts or our features?';
        }

        // Posting adverts - detailed step responses
        if (preg_match('/\b(how to|post|create|add|sell|advertise)\b.*\b(ad|advert|listing|item)\b/i', $message)) {
            return $this->knowledgeBase['posting'] . ' Need help with any specific step?';
        }

        // Step-specific help
        if (preg_match('/\b(step\s*[1-5]|step\s*(one|two|three|four|five))\b/i', $message, $matches)) {
            return $this->getStepHelp($message);
        }

        // Short affirmative responses (yes, step 2, etc.)
        if (preg_match('/^(yes|yeah|yep|sure|ok|okay)(\s+step\s*[1-5])?$/i', $message)) {
            return $this->getStepHelp($message);
        }

        // Pricing/Plans
        if (preg_match('/\b(price|cost|plan|pricing|how much|fee|payment plan)\b/i', $message)) {
            return $this->knowledgeBase['plans'] . ' Which plan interests you?';
        }

        // Payment
        if (preg_match('/\b(pay|payment|transaction|card|bank)\b/i', $message)) {
            return $this->knowledgeBase['payment'] . ' Do you have any specific payment questions?';
        }

        // Safety
        if (preg_match('/\b(safe|safety|secure|scam|fraud|trust)\b/i', $message)) {
            return '🛡️ Your safety is important! ' . $this->knowledgeBase['safety'];
        }

        // Categories
        if (preg_match('/\b(category|categories|what can I|browse)\b/i', $message)) {
            return $this->knowledgeBase['categories'] . ' What are you looking for?';
        }

        // Contact - Now offers to connect to support
        if (preg_match('/\b(contact|reach|support|help desk|email|phone)\b/i', $message)) {
            return "I can help you reach our support team! Here are your options:\n\n" .
                   "📞 **Quick Contact:**\n" .
                   "• Email: support@charymeld.com\n" .
                   "• Phone: +234 (0) 123 456 789\n\n" .
                   "💬 **Or I can connect you with a live support agent right now!**\n\n" .
                   "Just say \"connect me to support\" and I'll notify our team immediately.";
        }

        // Account related
        if (preg_match('/\b(account|profile|login|register|sign up)\b/i', $message)) {
            return 'To create an account, click "Sign Up" in the top menu. Already have an account? Click "Login". Need help with account recovery?';
        }

        // Search
        if (preg_match('/\b(search|find|looking for)\b/i', $message)) {
            return 'Use our search bar at the top or browse by category. You can filter by price, location, and more! What are you searching for?';
        }

        return null;
    }

    protected function getStepHelp(string $message): string
    {
        $message = strtolower($message);

        // Extract step number
        $stepNumber = null;
        if (preg_match('/step\s*([1-5])/i', $message, $matches)) {
            $stepNumber = (int) $matches[1];
        } elseif (preg_match('/step\s*(one|two|three|four|five)/i', $message, $matches)) {
            $stepMap = ['one' => 1, 'two' => 2, 'three' => 3, 'four' => 4, 'five' => 5];
            $stepNumber = $stepMap[strtolower($matches[1])];
        } elseif (preg_match('/([1-5])/', $message, $matches)) {
            $stepNumber = (int) $matches[1];
        }

        // Step-specific detailed help
        $steps = [
            1 => "📝 **Step 1: Sign up/Login**\n\n" .
                 "• Click \"Sign Up\" in the top menu\n" .
                 "• Fill in your name, email, and password\n" .
                 "• Verify your email address\n" .
                 "• Or login if you already have an account\n\n" .
                 "Already have an account? Just click \"Login\" instead!",

            2 => "➕ **Step 2: Click \"Post Ad\"**\n\n" .
                 "• After logging in, look for the \"Post Ad\" button in the top navigation menu\n" .
                 "• You'll be taken to the advert creation form\n" .
                 "• Choose your advert type (classified ad or campaign)\n" .
                 "• Select the category that best fits your item\n\n" .
                 "**Tip:** Make sure you're logged in first! The \"Post Ad\" button appears after login.",

            3 => "✍️ **Step 3: Fill in details**\n\n" .
                 "• **Title:** Write a clear, catchy title (e.g., \"iPhone 13 Pro - Brand New\")\n" .
                 "• **Description:** Provide detailed information about your item\n" .
                 "• **Price:** Enter your asking price in Naira (₦)\n" .
                 "• **Location:** Add your city/area for local buyers\n" .
                 "• **Contact Info:** Add your phone number and email\n\n" .
                 "**Pro Tip:** More details = more interested buyers!",

            4 => "📸 **Step 4: Upload images**\n\n" .
                 "• Click \"Choose Files\" or drag & drop images\n" .
                 "• You can upload up to 5 images per advert\n" .
                 "• Accepted formats: JPG, PNG, WEBP\n" .
                 "• Maximum size: 5MB per image\n" .
                 "• First image becomes your main display image\n\n" .
                 "**Pro Tip:** Use clear, well-lit photos showing different angles!",

            5 => "💳 **Step 5: Choose a plan and pay**\n\n" .
                 "• **Regular (₦1,000):** Standard listing, 30 days active\n" .
                 "• **Featured (₦3,000):** Top of search results, featured badge, 30 days\n" .
                 "• **Premium (₦5,000):** Homepage featured, premium badge, 60 days\n\n" .
                 "• Select your preferred payment gateway (Paystack or Flutterwave)\n" .
                 "• Complete secure payment\n" .
                 "• Your advert goes live after admin approval!\n\n" .
                 "Need help with another step?",
        ];

        if ($stepNumber && isset($steps[$stepNumber])) {
            return $steps[$stepNumber];
        }

        // Default if no specific step found
        return $this->knowledgeBase['posting'] . ' Which step would you like help with? (1-5)';
    }

    protected function generateContextualResponse(ChatConversation $conversation, string $message, array $personality): string
    {
        // Get recent conversation history
        $recentMessages = $conversation->messages()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->reverse();

        $message = strtolower($message);

        // Check if previous message was about posting adverts
        $previousAiMessage = $recentMessages->where('sender_type', 'ai')->last();
        if ($previousAiMessage && str_contains(strtolower($previousAiMessage->message), 'need help with any specific step')) {
            // User is likely responding to step question
            if (preg_match('/\b(yes|yeah|yep|sure|step)\b/i', $message)) {
                return $this->getStepHelp($message);
            }
        }

        // Check for step numbers in isolation
        if (preg_match('/^[1-5]$/', trim($message))) {
            return $this->getStepHelp("step " . trim($message));
        }

        // Generic contextual responses
        $responses = [
            "I understand you're asking about: \"{$message}\". Let me help you with that! 🤔\n\nAre you looking for information about:\n• Posting adverts?\n• Pricing plans?\n• Payment methods?\n• Safety tips?",
            "That's a great question! I can help you with:\n• **Posting adverts** - Step by step guide\n• **Pricing** - Our plans and costs\n• **Payments** - How to pay safely\n• **Categories** - What you can sell\n\nWhat would you like to know more about?",
            "I'm here to help! 😊\n\nI can assist you with:\n✓ How to post an advert\n✓ Pricing plans and costs\n✓ Payment and safety\n✓ Account and profile help\n\nWhat can I help you with today?",
        ];

        return $responses[array_rand($responses)];
    }

    public function getGreeting(string $personality = 'helpful'): string
    {
        return $this->personalities[$personality]['greeting'] ?? $this->personalities['helpful']['greeting'];
    }

    public function suggestQuestions(): array
    {
        return [
            'How do I post an advert?',
            'What are your pricing plans?',
            'How do payments work?',
            'Is CharyMeld safe to use?',
            'How do I contact support?',
            'What categories are available?',
        ];
    }
}
